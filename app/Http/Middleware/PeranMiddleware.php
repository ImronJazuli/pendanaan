<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PeranMiddleware
{
    public function handle(Request $request, Closure $next, string ...$peran): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        $userPeran = $user->peran ?? $user->role;
        $userRole = $user->role ?? $user->peran;

        $allowed = [];
        foreach ($peran as $p) {
            $allowed[] = $p;
            if ($p === 'institution_user') {
                $allowed[] = 'instansi';
            } elseif ($p === 'instansi') {
                $allowed[] = 'institution_user';
            }
        }
        $allowed = array_unique($allowed);

        $hasAccess = in_array($userPeran, $allowed, true) || in_array($userRole, $allowed, true);

        abort_unless($hasAccess, 403);

        return $next($request);
    }
}
