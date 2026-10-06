<?php

use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\DonatarDashboardController;
use App\Http\Controllers\Dashboard\InstansiDashboardController;
use App\Http\Controllers\KausaController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransparansiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/kausa', [KausaController::class, 'index'])->name('kausa.index');
Route::get('/transparansi', [TransparansiController::class, 'index'])->name('transparansi.index');

// Halaman statis
Route::get('/tentang', [PagesController::class, 'tentang'])->name('pages.tentang');
Route::get('/faq', [PagesController::class, 'faq'])->name('pages.faq');
Route::get('/kebijakan-privasi', [PagesController::class, 'privasi'])->name('pages.privasi');
Route::get('/syarat-ketentuan', [PagesController::class, 'syarat'])->name('pages.syarat');
Route::get('/kontak', [PagesController::class, 'kontak'])->name('pages.kontak');

Route::middleware(['auth', 'peran:institution_user'])->group(function () {
    Route::get('/kausa/ajukan', [KausaController::class, 'create'])->name('kausa.create');
    Route::post('/kausa', [KausaController::class, 'store'])->name('kausa.store');
});

// Parameterized route MUST be last
Route::get('/kausa/{slug}', [KausaController::class, 'show'])->name('kausa.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->peran === 'institution_user') {
            return redirect()->route('dashboard.instansi');
        } elseif ($user->peran === 'donatur') {
            return redirect()->route('dashboard.donatur');
        } elseif ($user->peran === 'admin') {
            return redirect()->route('dashboard.admin');
        }

        // Fallback jika peran tidak dikenali
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('peran:institution_user')->group(function () {
        Route::get('/dashboard/instansi', [InstansiDashboardController::class, 'index'])->name('dashboard.instansi');
        Route::get('/dashboard/instansi/profil', [InstansiDashboardController::class, 'profil'])->name('instansi.profil');
        Route::post('/dashboard/instansi/profil', [InstansiDashboardController::class, 'updateProfil'])->name('instansi.profil.update');
        Route::get('/dashboard/instansi/laporan', function () {
            return view('dashboard.instansi.laporan');
        })->name('instansi.laporan');
        Route::get('/dashboard/instansi/panduan', function () {
            return view('dashboard.instansi.panduan');
        })->name('instansi.panduan');
        Route::get('/dashboard/instansi/{kausa}', [InstansiDashboardController::class, 'detail'])->name('dashboard.instansi.detail');
    });

    Route::middleware('peran:donatur')->group(function () {
        Route::get('/dashboard/donatur', [DonatarDashboardController::class, 'index'])->name('dashboard.donatur');
    });

    Route::middleware('peran:admin')->group(function () {
        Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
        Route::get('/dashboard/admin/kausa-aktif', function () {
            return view('dashboard.admin.kausa-aktif');
        })->name('admin.kausa.aktif');
        Route::get('/dashboard/admin/donasi', function () {
            return view('dashboard.admin.donasi');
        })->name('admin.donasi');
        Route::get('/dashboard/admin/laporan', function () {
            return view('dashboard.admin.laporan');
        })->name('admin.laporan');
        Route::get('/dashboard/admin/legalitas', function () {
            return view('dashboard.admin.legalitas');
        })->name('admin.legalitas');
        Route::get('/dashboard/admin/kausa/{kausa}', [AdminDashboardController::class, 'detail'])->name('dashboard.admin.detail');
        Route::post('/dashboard/admin/kausa/{kausa}/verify', [AdminDashboardController::class, 'verify'])->name('dashboard.admin.verify');
        Route::post('/dashboard/admin/kausa/{kausa}/reject', [AdminDashboardController::class, 'reject'])->name('dashboard.admin.reject');
        Route::post('/dashboard/admin/kausa/{kausa}/revise', [AdminDashboardController::class, 'revise'])->name('dashboard.admin.revise');
    });
});

require __DIR__.'/auth.php';
