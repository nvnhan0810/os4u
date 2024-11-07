<?php

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
