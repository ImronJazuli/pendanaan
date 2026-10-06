<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Kausa;
use App\Models\Notifikasi;
use App\Models\RiwayatStatusKausa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
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

        $riwayatKeputusan = RiwayatStatusKausa::with(['kausa.instansi', 'user'])
            ->whereIn('status_baru', ['disetujui', 'ditolak', 'perlu_diperbaiki'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin.index', compact('kausa', 'statusCounts', 'riwayatKeputusan'));
    }

    public function detail(Kausa $kausa): View
    {
        $kausa->load(['kategori', 'instansi', 'dokumen', 'riwayatStatus', 'donasi']);

        return view('dashboard.admin.detail', compact('kausa'));
    }

    public function verify(Kausa $kausa, Request $request): RedirectResponse
    {
        abort_unless($kausa->status !== 'disetujui' && $kausa->status !== 'ditolak', 403);

        $kausa->update(['status' => 'disetujui']);

        $catatan = $request->input('catatan') ?: 'Pengajuan disetujui dan dipublikasikan oleh Admin.';

        $kausa->riwayatStatus()->create([
            'user_id' => auth()->id(),
            'status_baru' => 'disetujui',
            'catatan' => $catatan,
        ]);

        $this->createNotifikasi(
            $kausa,
            'kausa_disetujui',
            'Kausa Disetujui',
            "Kausa '{$kausa->judul}' telah DISETUJUI oleh Admin Pemkab"
        );

        return redirect()->route('dashboard.admin')->with('status', 'Kausa berhasil disetujui dan dipublikasikan.');
    }

    public function reject(Kausa $kausa, Request $request): RedirectResponse
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ]);

        abort_unless($kausa->status !== 'disetujui' && $kausa->status !== 'ditolak', 403);

        $catatan = $request->input('alasan_penolakan');

        $kausa->update(['status' => 'ditolak']);

        $kausa->riwayatStatus()->create([
            'user_id' => auth()->id(),
            'status_baru' => 'ditolak',
            'catatan' => $catatan,
        ]);

        $this->createNotifikasi(
            $kausa,
            'kausa_ditolak',
            'Kausa Ditolak',
            "Kausa '{$kausa->judul}' DITOLAK. Alasan: {$catatan}"
        );

        return redirect()->route('dashboard.admin')->with('status', 'Kausa ditolak.');
    }

    public function revise(Kausa $kausa, Request $request): RedirectResponse
    {
        $request->validate([
            'catatan_revisi' => 'required|string|min:10',
        ]);

        abort_unless($kausa->status !== 'disetujui' && $kausa->status !== 'ditolak', 403);

        $catatan = $request->input('catatan_revisi');

        $kausa->update(['status' => 'perlu_diperbaiki']);

        $kausa->riwayatStatus()->create([
            'user_id' => auth()->id(),
            'status_baru' => 'perlu_diperbaiki',
            'catatan' => $catatan,
        ]);

        $this->createNotifikasi(
            $kausa,
            'kausa_perlu_diperbaiki',
            'Kausa Perlu Perbaikan',
            "Kausa '{$kausa->judul}' perlu PERBAIKAN. Catatan: {$catatan}"
        );

        return redirect()->route('dashboard.admin')->with('status', 'Permintaan perbaikan kausa berhasil dikirim ke instansi.');
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
