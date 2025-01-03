<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModuleApp;
use Illuminate\Http\Request;

class AppPermissionController extends Controller
{
    public function checkPermissions(Request $request) {
        $modules = $request->get('modules', []);

        $moduleApps = ModuleApp::with(['features'])->get();

        $result = [];

        foreach($moduleApps as $app) {
            if (in_array($app->name, $modules)) {
                $permissions = [];

                foreach($app->features as $feature) {
                    $permissions[$feature->name] = true;
                }

                $result[$app->name] = $permissions;
                continue;
            }

            $result[$app->name] = false;
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
