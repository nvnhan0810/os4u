<?php

use App\Http\Controllers\Api\DataCryptoController;
use App\Http\Controllers\Api\FcmController;
use App\Http\Middleware\O4uApiKeyMiddleware;
use App\Http\Middleware\O4uAppMiddleware;
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
    O4uAppMiddleware::class,
])->group(function() {
    Route::post('/data/decrypt', [DataCryptoController::class, 'decrypt']);
});

Route::middleware([
    O4uApiKeyMiddleware::class
])->group(function() {
    Route::post('/data/encrypt', [DataCryptoController::class, 'encrypt']);

    Route::post('fcm/send', [FcmController::class, 'sendNoti']);
});
