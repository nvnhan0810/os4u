<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class O4uAppMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    if (!$request->header('X-App-Key') && $request->header('X-App-Key') != config('o4u.mobile_app_key')) {
            Log::info('=== MISSING APP HEADER ====', [
                'ip' => $request->ip(),
                // IPs includes Proxy
                'ips' => $request->ips(),
                'header' => $request->header(),
            ]);

            return response()->json([
            'message' => 'Forbiden',
            ], JsonResponse::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
