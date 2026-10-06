<?php

namespace App\Http\Controllers;

use App\Models\Kausa;
use App\Models\Donasi;
use App\Models\User;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $totalKausa = Kausa::where('status', 'disetujui')->count();
        
        $totalDanaTerkumpul = Donasi::where('status', 'berhasil')
            ->sum('nominal');
        
        $totalDonatur = User::where('peran', 'donatur')
            ->whereHas('donasi', function ($query) {
                $query->where('status', 'berhasil');
            })
            ->distinct('id')
            ->count();
        
        $kausaTerbaru = Kausa::where('status', 'disetujui')
            ->with(['kategori', 'donasi'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('landing', [
            'totalKausa' => $totalKausa,
            'totalDanaTerkumpul' => $totalDanaTerkumpul,
            'totalDonatur' => $totalDonatur,
            'kausaTerbaru' => $kausaTerbaru,
        ]);
    }
}
