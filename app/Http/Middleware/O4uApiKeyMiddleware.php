<?php

namespace App\Http\Middleware;

use App\Models\O4uClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class O4uApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info(__METHOD__, [
            'X-Authorization' => $request->header('X-Authorization'),
        ]);

        if (!$request->header('X-Authorization')) {
            Log::info(__METHOD__, [
                'message' => 'Error Not Have Header',
            ]);
            return response()->json([
                'message' => 'UnAuthorization',
            ], 403);
        }

        $client = O4uClient::where('api_key', $request->header('X-Authorization'))->first();

        Log::info(__METHOD__, [
            'client' => $client,
        ]);

        if (!$client) {
            Log::info(__METHOD__, [
                'message' => 'Not Have API Key',
            ]);

            return response()->json([
                'message' => 'UnAuthorization',
            ], 403);
        }

        Log::info(__METHOD__, [
            'message' => 'Pass Middleware',
        ]);
        $request->merge([
            'client' => $client,
        ]);

        return $next($request);
    }
}
