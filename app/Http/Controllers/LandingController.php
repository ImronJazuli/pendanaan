<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Kausa;
use App\Models\LaporanDana;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $totalKausa = Kausa::where('status', 'disetujui')->count();

        $totalDanaTerkumpul = Donasi::where('status', Donasi::STATUS_SUCCESS)
            ->sum('nominal');

        $totalDanaTersalur = LaporanDana::where('status', 'dipublikasikan')
            ->sum('total_digunakan');

        $totalDonatur = Donasi::where('status', Donasi::STATUS_SUCCESS)
            ->count();

        $kausaTerbaru = Kausa::where('status', 'disetujui')
            ->with(['kategori', 'donasi'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('landing', [
            'totalKausa' => $totalKausa,
            'totalDanaTerkumpul' => $totalDanaTerkumpul,
            'totalDanaTersalur' => $totalDanaTersalur,
            'totalDonatur' => $totalDonatur,
            'kausaTerbaru' => $kausaTerbaru,
        ]);
    }
}
