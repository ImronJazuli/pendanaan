<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Kausa;
use App\Models\Notifikasi;
use App\Models\TransaksiPembayaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DonasiController extends Controller
{
    /**
     * Simpan inisiasi donasi baru untuk kausa tertentu.
     */
    public function store(Request $request, string $slug): RedirectResponse
    {
        $kausa = Kausa::where('slug', $slug)
            ->where('status', 'disetujui')
            ->firstOrFail();

        $request->validate([
            'nominal' => ['required', 'numeric', 'min:10000', 'max:500000000'],
            'nama_donatur' => ['nullable', 'string', 'max:255'],
            'email_donatur' => ['nullable', 'email', 'max:255'],
            'telepon_donatur' => ['nullable', 'string', 'max:30'],
            'anonim' => ['nullable', 'boolean'],
            'doa_dukungan' => ['nullable', 'string', 'max:1000'],
            'metode_pembayaran' => ['nullable', 'string', 'in:qris,bank,va,transfer'],
        ], [
            'nominal.required' => 'Nominal donasi wajib diisi.',
            'nominal.min' => 'Nominal donasi minimal Rp 10.000.',
            'nominal.max' => 'Nominal donasi maksimal Rp 500.000.000 per transaksi.',
            'email_donatur.email' => 'Format email donatur tidak valid.',
        ]);

        $user = auth()->user();
        $isAnonim = $request->boolean('anonim');
        $namaDonatur = $isAnonim ? 'Hamba Allah' : ($request->input('nama_donatur') ?? $user?->name ?? 'Donatur Peduli');
        $email = $request->input('email_donatur') ?? $user?->email;
        $telepon = $request->input('telepon_donatur') ?? $user?->phone_number;
        $metode = $request->input('metode_pembayaran', 'qris');

        // Format kode donasi unik: INV-YYYYMM-XXXX
        $kodeDonasi = 'INV-'.date('Ym').'-'.strtoupper(Str::random(5));

        $donasi = DB::transaction(function () use ($kausa, $user, $kodeDonasi, $request, $namaDonatur, $isAnonim, $email, $telepon, $metode) {
            $donasi = Donasi::create([
                'kausa_id' => $kausa->id,
                'user_id' => $user?->id,
                'pesanan_pembayaran' => $kodeDonasi,
                'nominal' => $request->input('nominal'),
                'nama_donatur' => $namaDonatur,
                'anonim' => $isAnonim,
                'email_donatur' => $email,
                'telepon_donatur' => $telepon,
                'doa_dukungan' => $request->input('doa_dukungan'),
                'metode_pembayaran' => $metode,
                'status' => Donasi::STATUS_PENDING,
            ]);

            TransaksiPembayaran::create([
                'donasi_id' => $donasi->id,
                'penyedia' => 'simulasi',
                'referensi_penyedia' => 'SIM-'.Str::upper(Str::random(10)),
                'token_pembayaran' => Str::random(32),
                'status' => 'menunggu',
                'kedaluwarsa_pada' => now()->addHours(24),
            ]);

            return $donasi;
        });

        return redirect()->route('donasi.bayar', $donasi->pesanan_pembayaran);
    }

    /**
     * Tampilkan halaman instruksi pembayaran & QRIS simulasi.
     */
    public function payment(string $kode): View|RedirectResponse
    {
        $donasi = Donasi::where('pesanan_pembayaran', $kode)
            ->with(['kausa.instansi', 'transaksi'])
            ->firstOrFail();

        if ($donasi->status === Donasi::STATUS_SUCCESS || $donasi->status === 'berhasil') {
            return redirect()->route('donasi.sukses', $kode);
        }

        return view('donasi.bayar', compact('donasi'));
    }

    /**
     * Eksekusi simulasi pembayaran sukses (Fake Payment Gateway).
     */
    public function simulate(string $kode): RedirectResponse
    {
        $donasi = Donasi::where('pesanan_pembayaran', $kode)
            ->with('kausa')
            ->firstOrFail();

        DB::transaction(function () use ($donasi) {
            // Lock donasi untuk mencegah race condition / duplicate increment
            $donasiLocked = Donasi::where('id', $donasi->id)->lockForUpdate()->first();

            if ($donasiLocked->status === Donasi::STATUS_SUCCESS || $donasiLocked->status === 'berhasil') {
                return;
            }

            $donasiLocked->update([
                'status' => Donasi::STATUS_SUCCESS,
                'dibayar_pada' => now(),
            ]);

            // Update dana terkumpul kausa secara resmi via atomic increment
            $donasiLocked->kausa->tambahDanaTerkumpul((float) $donasiLocked->nominal);

            // Update TransaksiPembayaran
            if ($donasiLocked->transaksiPembayaran) {
                $donasiLocked->transaksiPembayaran->update([
                    'status' => 'success',
                    'dibayar_pada' => now(),
                    'respons_penyedia' => [
                        'status' => 'PAID',
                        'simulated_at' => now()->toIso8601String(),
                        'channel' => $donasiLocked->metode_pembayaran,
                    ],
                ]);
            }

            // Notifikasi ke Instansi pemilik kausa
            $instansiUser = $donasiLocked->kausa->instansi?->user;
            if ($instansiUser) {
                Notifikasi::create([
                    'user_id' => $instansiUser->id,
                    'jenis' => 'donasi_masuk',
                    'judul' => 'Donasi Baru Diterima',
                    'isi' => 'Donasi sebesar Rp '.number_format($donasiLocked->nominal, 0, ',', '.')." diterima untuk kausa '{$donasiLocked->kausa->judul}'.",
                    'tautan' => route('dashboard.instansi.detail', $donasiLocked->kausa_id),
                    'dibaca_pada' => null,
                ]);
            }

            // Notifikasi ke Donatur jika terdaftar
            if ($donasiLocked->user_id) {
                Notifikasi::create([
                    'user_id' => $donasiLocked->user_id,
                    'jenis' => 'donasi_berhasil',
                    'judul' => 'Pembayaran Donasi Berhasil',
                    'isi' => 'Terima kasih! Donasi Anda sebesar Rp '.number_format($donasiLocked->nominal, 0, ',', '.')." untuk kausa '{$donasiLocked->kausa->judul}' telah tercatat.",
                    'tautan' => route('dashboard.donatur'),
                    'dibaca_pada' => null,
                ]);
            }
        });

        return redirect()->route('donasi.sukses', $kode)->with('status', 'Pembayaran donasi berhasil diverifikasi oleh sistem simulasi!');
    }

    /**
     * Tampilkan halaman terima kasih setelah pembayaran sukses.
     */
    public function success(string $kode): View
    {
        $donasi = Donasi::where('pesanan_pembayaran', $kode)
            ->with(['kausa.instansi', 'transaksi'])
            ->firstOrFail();

        return view('donasi.sukses', compact('donasi'));
    }

    /**
     * Tampilkan kuitansi / bukti donasi digital resmi Pemkab Tulungagung (Printable).
     */
    public function kuitansi(string $kode): View
    {
        $donasi = Donasi::where('pesanan_pembayaran', $kode)
            ->whereIn('status', [Donasi::STATUS_SUCCESS, 'berhasil'])
            ->with(['kausa.instansi', 'user', 'transaksi'])
            ->firstOrFail();

        return view('donasi.kuitansi', compact('donasi'));
    }

    public function receipt(string $kode): View
    {
        return $this->kuitansi($kode);
    }
}
