<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AuthPageController;
use App\Http\Controllers\Auth\DonaturAuthController;
use App\Http\Controllers\Auth\InstansiAuthController;
use Illuminate\Support\Facades\Route;

// ── Halaman auth tunggal ──────────────────────────────────────────────────
// Route ini menggantikan route login Breeze lama
Route::get('/login', [AuthPageController::class, 'index'])->name('login');

// ── DONATUR ───────────────────────────────────────────────────────────────
Route::prefix('donatur')->name('donatur.')->group(function () {
    Route::post('/register', [DonaturAuthController::class, 'register'])->name('register');
    Route::post('/login', [DonaturAuthController::class, 'login'])->name('login');
    Route::post('/logout', [DonaturAuthController::class, 'logout'])
        ->name('logout')->middleware('auth');

    // Google OAuth
    Route::get('/auth/google', [DonaturAuthController::class, 'redirectToGoogle'])
        ->name('google');
    Route::get('/auth/google/callback', [DonaturAuthController::class, 'handleGoogleCallback'])
        ->name('google.callback');

    // Email verification
    Route::get('/email/verify/{id}/{hash}', [DonaturAuthController::class, 'verifyEmail'])
        ->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/resend', [DonaturAuthController::class, 'resendVerification'])
        ->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
});

// ── INSTANSI ──────────────────────────────────────────────────────────────
Route::prefix('instansi')->name('instansi.')->group(function () {
    Route::post('/register', [InstansiAuthController::class, 'register'])->name('register');
    Route::post('/login', [InstansiAuthController::class, 'login'])->name('login');
    Route::post('/logout', [InstansiAuthController::class, 'logout'])
        ->name('logout')->middleware('auth');

    // Email verification (hanya jika daftar pakai email)
    Route::get('/email/verify/{id}/{hash}', [InstansiAuthController::class, 'verifyEmail'])
        ->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/resend', [InstansiAuthController::class, 'resendVerification'])
        ->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
});

// ── ADMIN ─────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout')->middleware('auth:admin');
});
