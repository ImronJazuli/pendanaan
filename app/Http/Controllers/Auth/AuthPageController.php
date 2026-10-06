<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthPageController extends Controller
{
    /**
     * Tampilkan halaman login dengan tab dan mode yang sesuai.
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Jika admin sudah login
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        // Jika user biasa sudah login
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'instansi') {
                return redirect()->route('instansi.dashboard');
            }

            // Default: donatur
            return redirect()->route('donatur.dashboard');
        }

        // Ambil parameter tab dan mode dari query string
        $tab = $request->query('tab', 'donatur');   // donatur|instansi|admin
        $mode = $request->query('mode', 'signin');   // signin|register

        return view('auth.login', compact('tab', 'mode'));
    }
}
