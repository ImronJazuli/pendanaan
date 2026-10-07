<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\LaporanDana;
use App\Models\Notifikasi;
use App\Models\RiwayatStatusKausa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Halaman Antrean Kurasi Kausa
     */
    public function index(Request $request): View
    {
        $query = Kausa::with(['kategori', 'instansi', 'dokumen']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        } else {
            $query->whereIn('status', ['menunggu_verifikasi', 'perlu_diperbaiki']);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('instansi', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $kausa = $query->orderBy('created_at', 'asc')->paginate(15);

        $statusCounts = [
            'total' => Kausa::count(),
            'menunggu_verifikasi' => Kausa::where('status', 'menunggu_verifikasi')->count(),
            'perlu_diperbaiki' => Kausa::where('status', 'perlu_diperbaiki')->count(),
            'disetujui' => Kausa::where('status', 'disetujui')->count(),
            'ditolak' => Kausa::where('status', 'ditolak')->count(),
        ];

        $pendingInstansiCount = Instansi::where('status_verifikasi', 'belum_diverifikasi')->count();

        $riwayatKeputusan = RiwayatStatusKausa::with(['kausa.instansi', 'user'])
            ->whereIn('status_baru', ['disetujui', 'ditolak', 'perlu_diperbaiki'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin.index', compact('kausa', 'statusCounts', 'pendingInstansiCount', 'riwayatKeputusan'));
    }

    /**
     * Detail Kausa untuk Kurasi
     */
    public function detail(Kausa $kausa): View
    {
        $kausa->load(['kategori', 'instansi.dokumen', 'dokumen', 'riwayatStatus', 'donasi']);

        return view('dashboard.admin.detail', compact('kausa'));
    }

    /**
     * Aksi Verifikasi Kausa (Setujui)
     */
    public function verify(Kausa $kausa, Request $request): RedirectResponse
    {
        abort_unless($kausa->status !== 'disetujui' && $kausa->status !== 'ditolak', 403);

        $kausa->update([
            'status' => 'disetujui',
            'dipublikasikan_pada' => now(),
        ]);

        $catatan = $request->input('catatan') ?: 'Pengajuan disetujui dan dipublikasikan oleh Admin Pemkab Tulungagung.';

        $kausa->riwayatStatus()->create([
            'user_id' => auth()->id() ?? auth('admin')->id(),
            'status_baru' => 'disetujui',
            'catatan' => $catatan,
        ]);

        $this->createNotifikasi(
            $kausa,
            'kausa_disetujui',
            'Kausa Disetujui',
            "Kausa '{$kausa->judul}' telah DISETUJUI oleh Admin Pemkab dan resmi tayang ke publik."
        );

        return redirect()->route('dashboard.admin')->with('status', 'Kausa berhasil disetujui dan dipublikasikan ke katalog publik.');
    }

    /**
     * Aksi Tolak Kausa
     */
    public function reject(Kausa $kausa, Request $request): RedirectResponse
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ]);

        abort_unless($kausa->status !== 'disetujui' && $kausa->status !== 'ditolak', 403);

        $catatan = $request->input('alasan_penolakan');

        $kausa->update([
            'status' => 'ditolak',
            'catatan_admin' => $catatan,
        ]);

        $kausa->riwayatStatus()->create([
            'user_id' => auth()->id() ?? auth('admin')->id(),
            'status_baru' => 'ditolak',
            'catatan' => $catatan,
        ]);

        $this->createNotifikasi(
            $kausa,
            'kausa_ditolak',
            'Kausa Ditolak',
            "Kausa '{$kausa->judul}' DITOLAK. Alasan: {$catatan}"
        );

        return redirect()->route('dashboard.admin')->with('status', 'Kausa telah ditolak.');
    }

    /**
     * Aksi Minta Revisi Kausa
     */
    public function revise(Kausa $kausa, Request $request): RedirectResponse
    {
        $request->validate([
            'catatan_revisi' => 'required|string|min:10',
        ]);

        abort_unless($kausa->status !== 'disetujui' && $kausa->status !== 'ditolak', 403);

        $catatan = $request->input('catatan_revisi');

        $kausa->update([
            'status' => 'perlu_diperbaiki',
            'catatan_admin' => $catatan,
        ]);

        $kausa->riwayatStatus()->create([
            'user_id' => auth()->id() ?? auth('admin')->id(),
            'status_baru' => 'perlu_diperbaiki',
            'catatan' => $catatan,
        ]);

        $this->createNotifikasi(
            $kausa,
            'kausa_perlu_diperbaiki',
            'Kausa Perlu Perbaikan',
            "Kausa '{$kausa->judul}' memerlukan perbaikan. Catatan: {$catatan}"
        );

        return redirect()->route('dashboard.admin')->with('status', 'Permintaan perbaikan kausa berhasil dikirim ke instansi.');
    }

    /**
     * Halaman Monitoring Kausa Aktif (Tayang Publik)
     */
    public function kausaAktif(Request $request): View
    {
        $query = Kausa::where('status', 'disetujui')->with(['kategori', 'instansi', 'donasi']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhereHas('instansi', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_kausa_id', $request->input('kategori'));
        }

        if ($request->input('sort') === 'tercapai') {
            $query->orderByRaw('(dana_terkumpul / target_dana) desc');
        } elseif ($request->input('sort') === 'sisa_hari') {
            $query->orderBy('tanggal_berakhir', 'asc');
        } else {
            $query->latest();
        }

        $kausas = $query->paginate(12);

        $kategoris = KategoriKausa::where('aktif', true)->get();

        $metrics = [
            'totalAktif' => Kausa::where('status', 'disetujui')->count(),
            'totalTarget' => (float) Kausa::where('status', 'disetujui')->sum('target_dana'),
            'totalTerkumpul' => (float) Kausa::where('status', 'disetujui')->sum('dana_terkumpul'),
            'capaiTarget' => Kausa::where('status', 'disetujui')->whereRaw('dana_terkumpul >= target_dana')->count(),
        ];

        return view('dashboard.admin.kausa-aktif', compact('kausas', 'kategoris', 'metrics'));
    }

    /**
     * Selesaikan atau Tutup Program Kausa Aktif
     */
    public function selesaikanKausa(Kausa $kausa, Request $request): RedirectResponse
    {
        $catatan = $request->input('catatan', 'Program kausa resmi diselesaikan dan ditutup oleh Admin Pemkab Tulungagung.');

        $kausa->update(['status' => 'selesai']);

        $kausa->riwayatStatus()->create([
            'user_id' => auth()->id() ?? auth('admin')->id(),
            'status_baru' => 'selesai',
            'catatan' => $catatan,
        ]);

        $this->createNotifikasi(
            $kausa,
            'kausa_selesai',
            'Kausa Resmi Selesai',
            "Penggalangan dana untuk program '{$kausa->judul}' telah selesai. Silakan persiapkan Laporan Pertanggungjawaban (LPJ)."
        );

        return redirect()->route('admin.kausa.aktif')->with('status', "Program kausa '{$kausa->judul}' berhasil ditandai selesai.");
    }

    /**
     * Halaman Verifikasi Legalitas OPD & Ormas
     */
    public function legalitas(Request $request): View
    {
        $query = Instansi::with(['user', 'dokumen', 'kausa']);

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_registrasi', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%");
                    });
            });
        }

        $instansis = $query->latest()->paginate(15);

        $stats = [
            'total' => Instansi::count(),
            'belum_diverifikasi' => Instansi::where('status_verifikasi', 'belum_diverifikasi')->count(),
            'terverifikasi' => Instansi::where('status_verifikasi', 'terverifikasi')->count(),
            'ditolak' => Instansi::where('status_verifikasi', 'ditolak')->count(),
        ];

        return view('dashboard.admin.legalitas', compact('instansis', 'stats'));
    }

    /**
     * Aksi Verifikasi Legalitas Instansi
     */
    public function verifyInstansi(Instansi $instansi, Request $request): RedirectResponse
    {
        $instansi->update([
            'status_verifikasi' => 'terverifikasi',
            'terverifikasi_pada' => now(),
        ]);

        if ($instansi->user_id) {
            Notifikasi::create([
                'user_id' => $instansi->user_id,
                'jenis' => 'legalitas_disetujui',
                'judul' => 'Legalitas Instansi Terverifikasi',
                'isi' => 'Selamat, akun dan berkas legalitas lembaga Anda telah diverifikasi oleh Admin Pemkab Tulungagung.',
                'tautan' => route('instansi.profil'),
                'dibaca_pada' => null,
            ]);
        }

        return redirect()->route('admin.legalitas')->with('status', "Instansi '{$instansi->nama}' berhasil diverifikasi resmi.");
    }

    /**
     * Aksi Tolak Legalitas Instansi
     */
    public function rejectInstansi(Instansi $instansi, Request $request): RedirectResponse
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:5',
        ]);

        $alasan = $request->input('alasan_penolakan');

        $instansi->update([
            'status_verifikasi' => 'ditolak',
        ]);

        if ($instansi->user_id) {
            Notifikasi::create([
                'user_id' => $instansi->user_id,
                'jenis' => 'legalitas_ditolak',
                'judul' => 'Legalitas Instansi Ditolak',
                'isi' => "Berkas legalitas instansi ditolak oleh Admin Pemkab. Catatan: {$alasan}",
                'tautan' => route('instansi.profil'),
                'dibaca_pada' => null,
            ]);
        }

        return redirect()->route('admin.legalitas')->with('status', "Pendaftaran instansi '{$instansi->nama}' telah ditolak.");
    }

    /**
     * Halaman Verifikasi Laporan Transparansi Dana (LPJ)
     */
    public function laporan(Request $request): View
    {
        $query = LaporanDana::with(['kausa.instansi', 'rincian', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('kausa', function ($q2) use ($search) {
                        $q2->where('judul', 'like', "%{$search}%");
                    });
            });
        }

        $laporans = $query->latest()->paginate(12);

        $stats = [
            'total' => LaporanDana::count(),
            'menunggu_verifikasi' => LaporanDana::where('status', 'menunggu_verifikasi')->count(),
            'disetujui' => LaporanDana::where('status', 'disetujui')->count(),
            'perlu_revisi' => LaporanDana::where('status', 'perlu_revisi')->count(),
            'totalDanaDilaporkan' => (float) LaporanDana::where('status', 'disetujui')->sum('total_digunakan'),
        ];

        return view('dashboard.admin.laporan', compact('laporans', 'stats'));
    }

    /**
     * Aksi Verifikasi LPJ (Setujui & Publikasikan ke /transparansi)
     */
    public function verifyLaporan(LaporanDana $laporan, Request $request): RedirectResponse
    {
        $laporan->update([
            'status' => 'disetujui',
            'disetujui_pada' => now(),
            'dipublikasikan_pada' => now(),
        ]);

        if ($laporan->user_id) {
            Notifikasi::create([
                'user_id' => $laporan->user_id,
                'jenis' => 'lpj_disetujui',
                'judul' => 'Laporan Pertanggungjawaban Disetujui',
                'isi' => "Laporan realisasi penggunaan dana '{$laporan->judul}' telah disetujui dan resmi dipublikasikan ke Portal Transparansi Publik.",
                'tautan' => route('instansi.laporan'),
                'dibaca_pada' => null,
            ]);
        }

        return redirect()->route('admin.laporan')->with('status', "Laporan '{$laporan->judul}' berhasil diverifikasi dan dipublikasikan.");
    }

    /**
     * Aksi Minta Revisi LPJ
     */
    public function reviseLaporan(LaporanDana $laporan, Request $request): RedirectResponse
    {
        $request->validate([
            'catatan_revisi' => 'required|string|min:5',
        ]);

        $catatan = $request->input('catatan_revisi');

        $laporan->update([
            'status' => 'perlu_revisi',
            'catatan_admin' => $catatan,
        ]);

        if ($laporan->user_id) {
            Notifikasi::create([
                'user_id' => $laporan->user_id,
                'jenis' => 'lpj_perlu_revisi',
                'judul' => 'LPJ Memerlukan Revisi',
                'isi' => "Laporan '{$laporan->judul}' memerlukan perbaikan nota/kuitansi. Catatan: {$catatan}",
                'tautan' => route('instansi.laporan'),
                'dibaca_pada' => null,
            ]);
        }

        return redirect()->route('admin.laporan')->with('status', 'Permintaan revisi laporan berhasil dikirim ke instansi.');
    }

    /**
     * Aksi Tolak LPJ
     */
    public function rejectLaporan(LaporanDana $laporan, Request $request): RedirectResponse
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:5',
        ]);

        $catatan = $request->input('alasan_penolakan');

        $laporan->update([
            'status' => 'ditolak',
            'catatan_admin' => $catatan,
        ]);

        if ($laporan->user_id) {
            Notifikasi::create([
                'user_id' => $laporan->user_id,
                'jenis' => 'lpj_ditolak',
                'judul' => 'LPJ Ditolak',
                'isi' => "Laporan '{$laporan->judul}' ditolak oleh Admin. Alasan: {$catatan}",
                'tautan' => route('instansi.laporan'),
                'dibaca_pada' => null,
            ]);
        }

        return redirect()->route('admin.laporan')->with('status', 'Laporan berhasil ditolak.');
    }

    /**
     * Halaman Rekap Donasi & Pembayaran Masuk
     */
    public function donasi(Request $request): View
    {
        $query = Donasi::with(['kausa', 'user', 'transaksiPembayaran'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('kausa_id')) {
            $query->where('kausa_id', $request->input('kausa_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('pesanan_pembayaran', 'like', "%{$search}%")
                    ->orWhere('nama_donatur', 'like', "%{$search}%")
                    ->orWhere('email_donatur', 'like', "%{$search}%");
            });
        }

        $donasis = $query->paginate(20);

        $kausaList = Kausa::select('id', 'judul')->latest()->get();

        $stats = [
            'totalBerhasil' => (float) Donasi::where('status', Donasi::STATUS_SUCCESS)->sum('nominal'),
            'countBerhasil' => Donasi::where('status', Donasi::STATUS_SUCCESS)->count(),
            'countPending' => Donasi::where('status', Donasi::STATUS_PENDING)->count(),
            'donasiHariIni' => (float) Donasi::where('status', Donasi::STATUS_SUCCESS)->whereDate('dibayar_pada', today())->sum('nominal'),
        ];

        return view('dashboard.admin.donasi', compact('donasis', 'kausaList', 'stats'));
    }

    protected function createNotifikasi(Kausa $kausa, string $jenis, string $judul, string $isi): void
    {
        if (! $kausa->relationLoaded('instansi')) {
            $kausa->load('instansi');
        }

        $userId = $kausa->instansi?->user_id;
        if (! $userId) {
            return;
        }

        Notifikasi::create([
            'user_id' => $userId,
            'jenis' => $jenis,
            'judul' => $judul,
            'isi' => $isi,
            'tautan' => route('dashboard.instansi.detail', $kausa->id),
            'dibaca_pada' => null,
        ]);
    }
}
