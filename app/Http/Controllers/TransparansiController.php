<?php

namespace App\Http\Controllers;

use App\Models\KategoriKausa;
use App\Models\LaporanDana;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransparansiController extends Controller
{
    public function index(Request $request): View
    {
        $query = LaporanDana::with(['kausa.kategori', 'kausa.instansi', 'rincian'])
            ->where('status', 'disetujui')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('kausa', function ($q2) use ($search) {
                        $q2->where('judul', 'like', "%{$search}%")
                            ->orWhere('lokasi', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('kausa', function ($q) use ($request) {
                $q->where('kategori_kausa_id', $request->input('kategori'));
            });
        }

        $laporan = $query->paginate(10);
        $kategoris = KategoriKausa::where('aktif', true)->get();

        $totalLaporanCount = LaporanDana::where('status', 'disetujui')->count();
        $totalDanaDisalurkan = (float) LaporanDana::where('status', 'disetujui')->sum('total_digunakan');

        return view('transparansi.index', compact('laporan', 'kategoris', 'totalLaporanCount', 'totalDanaDisalurkan'));
    }
}
