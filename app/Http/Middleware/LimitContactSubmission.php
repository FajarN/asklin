<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LimitContactSubmission
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $key = 'contact_submissions:' . $ip;

        $count = Cache::get($key, 0);

        if ($count >= 2) {
            return response()->json([
                'success' => false,
                'message' => 'Batas pengiriman pesan tercapai. Silakan coba lagi besok.'
            ], 429);
        }

        Cache::put($key, $count + 1, now()->addDay());

        return $next($request);
    }
}
