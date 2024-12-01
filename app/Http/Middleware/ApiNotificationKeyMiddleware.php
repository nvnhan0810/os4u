<?php

namespace App\Http\Middleware;

use App\Models\ClientNotificationKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiNotificationKeyMiddleware
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

        $notification = ClientNotificationKey::where('notification_api_key', $request->header('X-Authorization'))->first();

        if (!$notification) {
            Log::info(__METHOD__, [
                'message' => 'Not Have Notification Key',
            ]);

            return response()->json([
                'message' => 'UnAuthorization',
            ], 403);
        }

        Log::info(__METHOD__, [
            'message' => 'Pass Middleware',
        ]);

        return $next($request);
    }
}
