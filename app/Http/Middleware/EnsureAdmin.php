<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Hanya admin@example.com yang boleh lewat; selain itu 403
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->email !== 'admin@example.com') {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
