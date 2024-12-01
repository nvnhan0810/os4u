<?php

namespace App\Http\Middleware;

use App\Models\ClientNotificationKey;
use Closure;
use Illuminate\Http\Request;
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
        if (!$request->header('X-Authorization')) {
            return response()->json([
                'message' => 'UnAuthorization',
            ], 403);
        }

        $notification = ClientNotificationKey::where('notification_api_key', $request->header('X-Authorization'))->first();

        if (!$notification) {
            return response()->json([
                'message' => 'UnAuthorization',
            ], 403);
        }

        return $next($request);
    }
}
