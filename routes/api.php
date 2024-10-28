<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::post('h35sdk/log', function(Request $request) {
    $data = $request->all();

    Log::channel('h35')->info(json_encode($data));
});
