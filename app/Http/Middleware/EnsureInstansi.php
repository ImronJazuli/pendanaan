<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstansi
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            session(['url.intended' => $request->url()]);

            return redirect()->route('login', ['tab' => 'instansi']);
        }

        $user = Auth::user();
        $isInstansi = in_array($user->role ?? '', ['instansi', 'institution_user'], true)
            || in_array($user->peran ?? '', ['instansi', 'institution_user'], true);

        if (! $isInstansi) {
            session(['url.intended' => $request->url()]);

            return redirect()->route('login', ['tab' => 'instansi']);
        }

        return $next($request);
    }
}
