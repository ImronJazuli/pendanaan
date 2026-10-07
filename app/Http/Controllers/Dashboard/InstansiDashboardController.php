<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateKausaRequest;
use App\Http\Requests\UpdateProfilInstansiRequest;
use App\Models\DokumenKausa;
use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InstansiDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $instansi = auth()->user()->instansi;
        abort_unless($instansi, 403, 'Profil instansi belum tersedia.');

        $query = Kausa::where('instansi_id', $instansi->id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->input('sort') === 'tercanggih') {
            $query->orderBy('updated_at', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $kausa = $query->paginate(10);

        $statusCounts = [
            'total' => Kausa::where('instansi_id', $instansi->id)->count(),
            'draf' => Kausa::where('instansi_id', $instansi->id)->where('status', 'draf')->count(),
            'menunggu_verifikasi' => Kausa::where('instansi_id', $instansi->id)->where('status', 'menunggu_verifikasi')->count(),
            'disetujui' => Kausa::where('instansi_id', $instansi->id)->where('status', 'disetujui')->count(),
            'ditolak' => Kausa::where('instansi_id', $instansi->id)->where('status', 'ditolak')->count(),
        ];

        return view('dashboard.instansi.index', compact('kausa', 'statusCounts'));
    }

    public function detail(Kausa $kausa): View
    {
        $instansi = auth()->user()->instansi;
        abort_unless($instansi && $kausa->instansi_id === $instansi->id, 403);

        $kausa->load(['kategori', 'dokumen', 'riwayatStatus', 'laporanDana.dokumen', 'donasi']);

        return view('dashboard.instansi.detail', compact('kausa'));
    }

    public function profil(): View
    {
        $user = auth()->user();

        // Auto-provision record instansi jika belum ada
        $instansi = $user->instansi;
        if (! $instansi) {
            $instansi = Instansi::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $user->name,
                    'jenis' => 'Yayasan',
                    'status_verifikasi' => 'belum_diverifikasi',
                    'alamat' => $user->address,
                    'nomor_telepon' => $user->phone_number,
                ]
            );
        }

        $instansi->load('dokumen');

        return view('dashboard.instansi.profil', compact('instansi', 'user'));
    }

    public function updateProfil(UpdateProfilInstansiRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $instansi = $user->instansi;

        if (! $instansi) {
            $instansi = Instansi::create([
                'user_id' => $user->id,
                'nama' => $request->input('nama') ?? $request->input('nama_lembaga') ?? $user->name,
                'jenis' => $request->input('jenis') ?? $request->input('jenis_badan_hukum') ?? 'Yayasan',
                'status_verifikasi' => 'belum_diverifikasi',
            ]);
        }

        $instansi->update([
            'nama' => $request->input('nama') ?? $request->input('nama_lembaga') ?? $instansi->nama,
            'jenis' => $request->input('jenis') ?? $request->input('jenis_badan_hukum') ?? $instansi->jenis,
            'nomor_registrasi' => $request->input('nomor_registrasi') ?? $request->input('no_sk_kemenkumham') ?? $instansi->nomor_registrasi,
            'alamat' => $request->input('alamat') ?? $request->input('alamat_kantor') ?? $instansi->alamat,
            'nomor_telepon' => $request->input('nomor_telepon') ?? $request->input('wa_pj') ?? $instansi->nomor_telepon,
        ]);

        $userUpdates = [];
        if ($request->filled('nama_pj')) {
            $userUpdates['name'] = $request->input('nama_pj');
        }
        if ($request->filled('nik_pj')) {
            $userUpdates['nik'] = $request->input('nik_pj');
        }
        if ($request->filled('wa_pj') || $request->filled('nomor_telepon')) {
            $userUpdates['phone_number'] = $request->input('wa_pj') ?? $request->input('nomor_telepon');
        }
        if ($request->filled('npwp_lembaga')) {
            $userUpdates['npwp'] = $request->input('npwp_lembaga');
        }
        if ($request->filled('alamat') || $request->filled('alamat_kantor')) {
            $userUpdates['address'] = $request->input('alamat') ?? $request->input('alamat_kantor');
        }
        if (! empty($userUpdates)) {
            $user->update($userUpdates);
        }

        return redirect()->route('instansi.profil')->with('success', 'Profil instansi berhasil diperbarui.');
    }

    public function panduan(): View
    {
        return view('dashboard.instansi.panduan');
    }

    public function edit(Kausa $kausa): View
    {
        $instansi = auth()->user()->instansi;
        abort_unless($instansi && $kausa->instansi_id === $instansi->id, 403, 'Akses ditolak.');
        abort_unless(in_array($kausa->status, ['draf', 'perlu_diperbaiki'], true), 403, 'Kausa dengan status ini tidak dapat diedit.');

        $kausa->load(['kategori', 'dokumen', 'riwayatStatus' => function ($query) {
            $query->latest();
        }]);

        $kategoris = KategoriKausa::where('aktif', true)->get();

        // Cari catatan revisi terbaru dari Admin
        $catatanRevisi = $kausa->catatan_admin;
        if (! $catatanRevisi) {
            $catatanRevisi = $kausa->riwayatStatus
                ->where('status_baru', 'perlu_diperbaiki')
                ->first()?->catatan;
        }

        return view('dashboard.instansi.edit', compact('kausa', 'kategoris', 'catatanRevisi'));
    }

    public function update(UpdateKausaRequest $request, Kausa $kausa): RedirectResponse
    {
        $instansi = auth()->user()->instansi;
        abort_unless($instansi && $kausa->instansi_id === $instansi->id, 403, 'Akses ditolak.');
        abort_unless(in_array($kausa->status, ['draf', 'perlu_diperbaiki'], true), 403, 'Kausa dengan status ini tidak dapat diubah.');

        $isSubmit = $request->input('action') === 'submit';
        $statusBaru = $isSubmit ? 'menunggu_verifikasi' : 'draf';

        DB::transaction(function () use ($request, $kausa, $instansi, $isSubmit, $statusBaru) {
            $data = $request->safe()->only([
                'judul', 'kategori_kausa_id', 'lokasi', 'ringkasan',
                'deskripsi', 'target_dana', 'tanggal_mulai', 'tanggal_berakhir',
            ]);

            $data['status'] = $statusBaru;
            $kausa->update($data);

            // 1. Hapus dokumen yang ditandai hapus
            if ($request->filled('hapus_dokumen')) {
                $dokumenToDelete = DokumenKausa::where('kausa_id', $kausa->id)
                    ->whereIn('id', $request->input('hapus_dokumen'))
                    ->get();

                foreach ($dokumenToDelete as $dok) {
                    if ($dok->path_file && Storage::exists($dok->path_file)) {
                        Storage::delete($dok->path_file);
                    }
                    $dok->delete();
                }
            }

            // 2. Tambah dokumen pendukung baru
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen', []) as $file) {
                    $kausa->dokumen()->create([
                        'jenis_dokumen' => 'dokumen_pendukung',
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $file->store('dokumen/kausa'),
                        'mime_type' => $file->getMimeType(),
                        'ukuran_file' => $file->getSize(),
                    ]);
                }
            }

            // 3. Catat riwayat status
            $catatanLog = $isSubmit
                ? ($request->filled('catatan_perbaikan')
                    ? 'Perbaikan diajukan ulang oleh instansi: '.$request->input('catatan_perbaikan')
                    : 'Pengajuan kausa diperbaiki dan dikirim ulang untuk verifikasi Admin.')
                : 'Draf kausa diperbarui oleh instansi.';

            $kausa->riwayatStatus()->create([
                'user_id' => auth()->id(),
                'status_baru' => $statusBaru,
                'catatan' => $catatanLog,
            ]);

            // 4. Notifikasi untuk Admin jika diajukan ulang
            if ($isSubmit) {
                $adminUsers = User::where('peran', 'admin')->orWhere('role', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    Notifikasi::create([
                        'user_id' => $admin->id,
                        'jenis' => 'kausa_diperbaiki',
                        'judul' => 'Pengajuan Kausa Diperbaiki',
                        'isi' => "Instansi {$instansi->nama} telah memperbarui dan mengirimkan ulang kausa '{$kausa->judul}' untuk diverifikasi.",
                        'tautan' => route('dashboard.admin.detail', $kausa->id),
                        'dibaca_pada' => null,
                    ]);
                }
            }
        });

        $message = $isSubmit
            ? 'Pengajuan kausa berhasil diperbaiki dan dikirimkan kembali ke Admin Pemkab untuk verifikasi.'
            : 'Perubahan draf kausa berhasil disimpan.';

        return redirect()->route('dashboard.instansi.detail', $kausa->id)->with('status', $message);
    }
}
