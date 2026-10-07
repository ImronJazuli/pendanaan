<?php

use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\DonatarDashboardController;
use App\Http\Controllers\Dashboard\InstansiDashboardController;
use App\Http\Controllers\Dashboard\InstansiLaporanDanaController;
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

        // Support untuk kolom role (baru) dan peran (lama)
        $userRole = $user->role ?? $user->peran ?? 'donatur';

        if ($userRole === 'institution_user' || $userRole === 'instansi') {
            return redirect()->route('instansi.dashboard');
        } elseif ($userRole === 'donatur') {
            return redirect()->route('donatur.dashboard');
        } elseif ($userRole === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Fallback jika role tidak dikenali
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk Instansi (support middleware baru dan lama)
    Route::middleware('peran:institution_user')->group(function () {
        Route::get('/dashboard/instansi', [InstansiDashboardController::class, 'index'])->name('dashboard.instansi');
        Route::get('/instansi/dashboard', [InstansiDashboardController::class, 'index'])->name('instansi.dashboard');
        Route::get('/dashboard/instansi/profil', [InstansiDashboardController::class, 'profil'])->name('instansi.profil');
        Route::match(['put', 'post'], '/dashboard/instansi/profil', [InstansiDashboardController::class, 'updateProfil'])->name('instansi.profil.update');

        // Alur Kausa Edit & Resubmit
        Route::get('/dashboard/instansi/{kausa}/edit', [InstansiDashboardController::class, 'edit'])->whereNumber('kausa')->name('dashboard.instansi.edit');
        Route::match(['put', 'patch'], '/dashboard/instansi/{kausa}', [InstansiDashboardController::class, 'update'])->whereNumber('kausa')->name('dashboard.instansi.update');

        // Modul Laporan Penggunaan Dana (LPJ)
        Route::get('/dashboard/instansi/laporan', [InstansiLaporanDanaController::class, 'index'])->name('instansi.laporan');
        Route::get('/dashboard/instansi/laporan/buat', [InstansiLaporanDanaController::class, 'create'])->name('instansi.laporan.create');
        Route::post('/dashboard/instansi/laporan', [InstansiLaporanDanaController::class, 'store'])->name('instansi.laporan.store');
        Route::get('/dashboard/instansi/laporan/{laporan}', [InstansiLaporanDanaController::class, 'show'])->whereNumber('laporan')->name('instansi.laporan.show');

        // Panduan SPJ & Kuitansi
        Route::get('/dashboard/instansi/panduan', [InstansiDashboardController::class, 'panduan'])->name('instansi.panduan');

        // Detail Kausa
        Route::get('/dashboard/instansi/{kausa}', [InstansiDashboardController::class, 'detail'])->name('dashboard.instansi.detail');
    });

    // Route untuk Donatur (support middleware baru dan lama)
    Route::middleware('peran:donatur')->group(function () {
        Route::get('/dashboard/donatur', [DonatarDashboardController::class, 'index'])->name('donatur.dashboard');
    });

    // Route untuk Admin guard web dengan peran admin
    Route::middleware('auth.admin')->group(function () {
        Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
        Route::get('/dashboard/admin/kausa-aktif', [AdminDashboardController::class, 'kausaAktif'])->name('admin.kausa.aktif');
        Route::post('/dashboard/admin/kausa/{kausa}/selesai', [AdminDashboardController::class, 'selesaikanKausa'])->name('admin.kausa.selesai');
        Route::get('/dashboard/admin/donasi', [AdminDashboardController::class, 'donasi'])->name('admin.donasi');
        Route::get('/dashboard/admin/laporan', [AdminDashboardController::class, 'laporan'])->name('admin.laporan');
        Route::post('/dashboard/admin/laporan/{laporan}/verify', [AdminDashboardController::class, 'verifyLaporan'])->name('admin.laporan.verify');
        Route::post('/dashboard/admin/laporan/{laporan}/revise', [AdminDashboardController::class, 'reviseLaporan'])->name('admin.laporan.revise');
        Route::post('/dashboard/admin/laporan/{laporan}/reject', [AdminDashboardController::class, 'rejectLaporan'])->name('admin.laporan.reject');
        Route::get('/dashboard/admin/legalitas', [AdminDashboardController::class, 'legalitas'])->name('admin.legalitas');
        Route::post('/dashboard/admin/legalitas/{instansi}/verify', [AdminDashboardController::class, 'verifyInstansi'])->name('admin.legalitas.verify');
        Route::post('/dashboard/admin/legalitas/{instansi}/reject', [AdminDashboardController::class, 'rejectInstansi'])->name('admin.legalitas.reject');
        Route::get('/dashboard/admin/kausa/{kausa}', [AdminDashboardController::class, 'detail'])->name('dashboard.admin.detail');
        Route::post('/dashboard/admin/kausa/{kausa}/verify', [AdminDashboardController::class, 'verify'])->name('dashboard.admin.verify');
        Route::post('/dashboard/admin/kausa/{kausa}/reject', [AdminDashboardController::class, 'reject'])->name('dashboard.admin.reject');
        Route::post('/dashboard/admin/kausa/{kausa}/revise', [AdminDashboardController::class, 'revise'])->name('dashboard.admin.revise');
    });
});

// Route untuk Admin guard 'admin' (tabel admins terpisah)
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
