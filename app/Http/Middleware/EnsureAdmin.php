<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check() || (Auth::check() && in_array(Auth::user()->role ?? Auth::user()->peran, ['admin'], true))) {
            return $next($request);
        }

        if (Auth::check()) {
            abort(403, 'Akses khusus Admin Pemkab Tulungagung.');
        }

        return redirect()->route('login', ['tab' => 'admin']);
    }
}
