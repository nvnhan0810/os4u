<?php

use App\Http\Controllers\Api\FcmController;
use App\Http\Middleware\ApiNotificationKeyMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::post('odoo-barcode/log', function(Request $request) {
    $data = $request->all();

    Log::channel('odoo_barcode')->info(json_encode($data));
});

Route::post('odoo-approval/log', function (Request $request) {
    $data = $request->all();

    Log::channel('odoo_approval')->info(json_encode($data));
});

Route::middleware([
    ApiNotificationKeyMiddleware::class
])->group(function() {
    Route::post('fcm/send', [FcmController::class, 'sendNoti']);
});
