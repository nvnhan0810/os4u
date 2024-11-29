<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\FCMSendNotiJob;
use Illuminate\Http\Request;
use Throwable;

class FcmController extends Controller {
    public function __construct() { }

    public function sendNoti(Request $request) {
        $request->validate([
            'fcm_token' => 'required|string',
            'data' => 'nullable|array',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            $title = $request->title;
            $description = $request->description;
            $data = $request->data ?? [];
            $token = $request->fcm_token;

            FCMSendNotiJob::dispatch($title, $description, $token, $data);

            return response()->json([
                'success' => true,
            ]);
        } catch(Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
