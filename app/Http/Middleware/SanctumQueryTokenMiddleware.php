<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanctumQueryTokenMiddleware
{
    /**
     * Izinkan autentikasi Sanctum via query parameter `token`
     * Berguna untuk pengunduhan dokumen PDF langsung dari browser/webview mobile.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->filled('token') && !$request->bearerToken()) {
            $request->headers->set('Authorization', 'Bearer ' . $request->query('token'));
        }

        return $next($request);
    }
}
