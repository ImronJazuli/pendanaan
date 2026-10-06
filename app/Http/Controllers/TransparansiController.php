<?php

namespace App\Http\Controllers;

use App\Models\LaporanDana;
use App\Models\KategoriKausa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransparansiController extends Controller
{
    public function index(Request $request): View
    {
        $query = LaporanDana::with(['kausa.kategori', 'rincian'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('kausa', function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('kausa', function ($q) use ($request) {
                $q->where('kategori_kausa_id', $request->input('kategori'));
            });
        }

        $laporan = $query->paginate(10);
        $kategoris = KategoriKausa::where('aktif', true)->get();

        return view('transparansi.index', compact('laporan', 'kategoris'));
    }
}
