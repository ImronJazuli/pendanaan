<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AuthPageController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\DonaturAuthController;
use App\Http\Controllers\Auth\InstansiAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Halaman auth tunggal ──────────────────────────────────────────────────
// Route ini menggantikan route login Breeze lama
Route::get('/login', [AuthPageController::class, 'index'])->name('login');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    return back()->withErrors(['email' => 'Email atau password salah.']);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()?->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->name('verification.send');

Route::get('/verify-email', function (Request $request) {
    return $request->user()?->hasVerifiedEmail()
        ? redirect()->intended('/dashboard')
        : view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->intended('/dashboard?verified=1');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::put('/password', [PasswordController::class, 'update'])->middleware('auth')->name('password.update');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->middleware('auth')->name('password.confirm');
Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])->middleware('auth');

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
