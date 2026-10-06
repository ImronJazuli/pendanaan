<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class DonaturAuthController extends Controller
{
    /**
     * Registrasi donatur baru dan kirim verifikasi email.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'donatur',
        ]);

        $user->sendEmailVerificationNotification();

        return redirect('/login?tab=donatur&mode=signin&status=verify-email');
    }

    /**
     * Login donatur dengan verifikasi email.
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'donatur',
        ], $remember)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput($request->only('email', 'remember'));
        }

        $user = Auth::user();

        if ($user->email_verified_at === null) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login?tab=donatur&mode=signin&status=need-verify');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('donatur.dashboard'));
    }

    /**
     * Redirect ke halaman autentikasi Google.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google OAuth, buat atau update akun donatur.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user !== null) {
                if ($user->google_id === null) {
                    $user->google_id = $googleUser->getId();
                }

                if ($user->avatar === null) {
                    $user->avatar = $googleUser->getAvatar();
                }

                if ($user->email_verified_at === null) {
                    $user->email_verified_at = now();
                }

                $user->save();
                Auth::login($user);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'donatur',
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                ]);

                Auth::login($user);
            }

            return redirect(session()->pull('url.intended', route('donatur.dashboard')));
        } catch (\Throwable $e) {
            return redirect('/login?tab=donatur&status=google-failed');
        }
    }

    /**
     * Verifikasi email donatur via signed URL.
     */
    public function verifyEmail(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            abort(403);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/login?tab=donatur&mode=signin&status=already-verified');
        }

        $user->markEmailAsVerified();

        return redirect('/login?tab=donatur&mode=signin&status=verified');
    }

    /**
     * Kirim ulang email verifikasi ke donatur yang belum verifikasi.
     */
    public function resendVerification(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return back();
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'Email verifikasi telah dikirim ulang.');
    }

    /**
     * Logout donatur dan invalidate session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
