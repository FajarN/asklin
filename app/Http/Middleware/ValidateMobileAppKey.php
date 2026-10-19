<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateMobileAppKey
{
    /**
     * Handle an incoming request.
     * Validates that the request has the expected mobile app secret key.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = config('app.mobile_app_key');

        // Jika MOBILE_APP_KEY belum dikonfigurasi di .env, loloskan untuk mencegah lockout saat setup awal
        if (empty($expectedKey)) {
            return $next($request);
        }

        // Ambil header X-App-Key (atau query param ?app_key= sebagai fallback unduh dokumen/browser)
        $providedKey = $request->header('X-App-Key') 
            ?: $request->header('x-app-key') 
            ?: $request->query('app_key');

        if (!$providedKey || !hash_equals((string) $expectedKey, (string) $providedKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses Ditolak: Kunci otorisasi aplikasi ASKLIN tidak sah atau belum disertakan.',
            ], 403);
        }

        return $next($request);
    }
}
