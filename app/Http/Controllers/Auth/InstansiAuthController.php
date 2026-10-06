<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InstansiAuthController extends Controller
{
    /**
     * Handle registration request from institution.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'identity_type' => 'required|in:email,npwp',
            'email' => 'nullable|email|unique:users,email|required_if:identity_type,email',
            'npwp' => 'nullable|regex:/^\d{2}\.\d{3}\.\d{3}\.\d-\d{3}\.\d{3}$/|unique:users,npwp|required_if:identity_type,npwp',
            'name' => 'required|string|max:255',
            'jenis' => 'required|in:opd,lembaga_sosial,ormas,yayasan',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        // Jika mendaftar pakai NPWP, langsung verified
        $emailVerified = ($request->identity_type === 'npwp') ? now() : null;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email ?? null,
            'npwp' => $request->npwp ?? null,
            'phone_number' => $request->phone_number,
            'role' => 'instansi',
            'password' => Hash::make($request->password),
            'email_verified_at' => $emailVerified,
        ]);

        Instansi::create([
            'user_id' => $user->id,
            'jenis' => $request->jenis,
            'nama' => $request->name,
            'nomor_telepon' => $request->phone_number,
            'status_verifikasi' => 'belum_diverifikasi',
        ]);

        if ($request->identity_type === 'email') {
            $user->sendEmailVerificationNotification();

            return redirect('/login?tab=instansi&mode=signin&status=verify-email');
        }

        // Jika daftar pakai NPWP
        return redirect('/login?tab=instansi&mode=signin&status=pending-admin');
    }

    /**
     * Handle login request from institution.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required',
        ]);

        // Deteksi apakah login_id adalah email atau NPWP
        $isEmail = filter_var($request->login_id, FILTER_VALIDATE_EMAIL);
        $field = $isEmail ? 'email' : 'npwp';

        $user = User::where($field, $request->login_id)
            ->where('role', 'instansi')
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'login_id' => 'Email/NPWP atau password salah.',
            ])->withInput();
        }

        // Cek email verification
        if ($user->email_verified_at === null) {
            return redirect('/login?tab=instansi&mode=signin&status=need-verify');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('instansi.dashboard'));
    }

    /**
     * Verify email address.
     */
    public function verifyEmail(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
            abort(403, 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/login?tab=instansi&mode=signin&status=already-verified');
        }

        $user->markEmailAsVerified();

        return redirect('/login?tab=instansi&mode=signin&status=verified');
    }

    /**
     * Resend email verification notification.
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
     * Logout the institution user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login?tab=instansi');
    }
}
