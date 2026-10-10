<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Kausa;
use App\Models\Notifikasi;
use App\Models\TransaksiPembayaran;
use App\Services\NotifikasiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            'metode_pembayaran' => ['nullable', 'string', 'in:qris,bank,va,transfer,manual'],
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

        session()->put('donasi_token_'.$donasi->pesanan_pembayaran, true);

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

            $kausaLocked = Kausa::where('id', $donasiLocked->kausa_id)->lockForUpdate()->first();

            $donasiLocked->update([
                'status' => Donasi::STATUS_SUCCESS,
                'dibayar_pada' => now(),
            ]);

            // Update dana terkumpul kausa secara resmi via atomic increment
            $kausaLocked->tambahDanaTerkumpul((float) $donasiLocked->nominal);

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
                NotifikasiService::kirim(
                    $instansiUser->id,
                    'donasi_masuk',
                    'Donasi Baru Diterima',
                    'Donasi sebesar Rp '.number_format($donasiLocked->nominal, 0, ',', '.')." diterima untuk kausa '{$donasiLocked->kausa->judul}'.",
                    route('dashboard.instansi.detail', $donasiLocked->kausa_id)
                );
            }

            // Notifikasi ke Donatur jika terdaftar
            if ($donasiLocked->user_id) {
                NotifikasiService::kirim(
                    $donasiLocked->user_id,
                    'donasi_berhasil',
                    'Pembayaran Donasi Berhasil',
                    'Terima kasih! Donasi Anda sebesar Rp '.number_format($donasiLocked->nominal, 0, ',', '.')." untuk kausa '{$donasiLocked->kausa->judul}' telah tercatat.",
                    route('donatur.dashboard')
                );
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

    /**
     * Unggah bukti transfer manual untuk donasi.
     */
    public function uploadBukti(Request $request, string $kode): RedirectResponse
    {
        $donasi = Donasi::where('pesanan_pembayaran', $kode)->firstOrFail();

        // 1. Validasi transisi status: donasi yang sudah berhasil/final tidak boleh upload ulang
        if ($donasi->status === Donasi::STATUS_SUCCESS || $donasi->status === 'berhasil') {
            abort(422, 'Donasi sudah berstatus berhasil dan tidak dapat mengunggah bukti pembayaran lagi.');
        }

        if (in_array($donasi->status, [Donasi::STATUS_EXPIRED, 'kadaluarsa', 'dibatalkan'])) {
            abort(422, 'Donasi sudah tidak aktif.');
        }

        $request->validate([
            'bukti_transfer' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'bukti_transfer.required' => 'File bukti transfer wajib diunggah.',
            'bukti_transfer.mimes' => 'Format file bukti harus berupa JPG, PNG, atau PDF.',
            'bukti_transfer.max' => 'Ukuran file bukti maksimal 2MB.',
        ]);

        $path = $request->file('bukti_transfer')->store('bukti-manual', 'local');

        $donasi->update([
            'path_bukti_manual' => $path,
            'status' => Donasi::STATUS_MENUNGGU_VERIFIKASI_MANUAL,
        ]);

        session()->put('donasi_token_'.$kode, true);

        return redirect()->route('donasi.bayar', $kode)->with('status', 'Bukti transfer berhasil diunggah. Menunggu verifikasi manual dari Admin Pemkab Tulungagung.');
    }

    /**
     * Tampilkan/stream berkas bukti transfer manual untuk donatur pemilik donasi.
     */
    public function lihatBukti(Request $request, string $kode)
    {
        $donasi = Donasi::where('pesanan_pembayaran', $kode)->firstOrFail();
        abort_unless($donasi->path_bukti_manual, 404, 'Bukti transfer tidak ditemukan.');

        $user = $request->user();
        $isAuthorized = false;

        if ($user && $user->hasRole('admin')) {
            $isAuthorized = true;
        } elseif ($donasi->user_id && $user && $user->id === $donasi->user_id) {
            $isAuthorized = true;
        } elseif (! $donasi->user_id && session('donasi_token_'.$kode)) {
            $isAuthorized = true;
        }

        abort_unless($isAuthorized, 403, 'Anda tidak memiliki hak akses untuk melihat bukti transfer ini.');

        $disk = Storage::disk('local')->exists($donasi->path_bukti_manual) ? 'local' : 'public';
        abort_unless(Storage::disk($disk)->exists($donasi->path_bukti_manual), 404, 'File bukti transfer tidak ditemukan di penyimpanan.');

        return Storage::disk($disk)->response($donasi->path_bukti_manual);
    }

    public function receipt(string $kode): View
    {
        return $this->kuitansi($kode);
    }
}
