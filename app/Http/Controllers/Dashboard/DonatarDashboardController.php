<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonatarDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $query = Donasi::where('user_id', $user->id)
            ->with(['kausa.kategori', 'transaksi']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('sort')) {
            if ($request->input('sort') === 'terbaru') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->input('sort') === 'nominal_tinggi') {
                $query->orderBy('nominal', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $donasi = $query->paginate(10);

        $statCounts = [
            'total' => Donasi::where('user_id', $user->id)->count(),
            'success' => Donasi::where('user_id', $user->id)->where('status', Donasi::STATUS_SUCCESS)->count(),
            'pending' => Donasi::where('user_id', $user->id)->where('status', Donasi::STATUS_PENDING)->count(),
            'failed' => Donasi::where('user_id', $user->id)->whereIn('status', [Donasi::STATUS_FAILED, Donasi::STATUS_EXPIRED])->count(),
            'berhasil' => Donasi::where('user_id', $user->id)->where('status', Donasi::STATUS_SUCCESS)->count(),
        ];

        $totalDonasi = Donasi::where('user_id', $user->id)
            ->where('status', Donasi::STATUS_SUCCESS)
            ->sum('nominal');

        $totalKausaDibantu = Donasi::where('user_id', $user->id)
            ->where('status', Donasi::STATUS_SUCCESS)
            ->distinct('kausa_id')
            ->count();

        return view('dashboard.donatur.index', compact('donasi', 'statCounts', 'totalDonasi', 'totalKausaDibantu'));
    }
}
