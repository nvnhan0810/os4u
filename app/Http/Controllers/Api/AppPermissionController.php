<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppPermissionController extends Controller
{
    public function checkPermissions(Request $request) {
        $modules = $request->get('modules', []);

        $stockBarcode = in_array('inven_barcode_app', $modules);
        $approve = in_array('approval_process', $modules);

        return [
            'inven_barcode_app' => $stockBarcode ? [

            ] : false,
            'approval_process' => $approve ? [

            ] : false,
        ];
    }
}
