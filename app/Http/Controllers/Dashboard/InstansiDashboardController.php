<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Kausa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return view('dashboard.instansi.profil');
    }

    public function updateProfil(Request $request): RedirectResponse
    {
        // TODO: Implement update logic
        return redirect()->route('instansi.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}