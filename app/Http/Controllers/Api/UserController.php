<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientUser;
use App\Models\O4uClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function updateDeviceInfo(Request $request) {
        $clientKey = $request->header('X-Authorization');

        $request->validate([
            'device_id' => 'required|string',
            'device_info' => 'required|string',
            'username' => 'required|string',
            'domain' => 'required|string',
            'db' => 'required|string',
        ]);

        $user = ClientUser::with(['devices'])
            ->where('username', $request->username)
            ->where('domain', $request->domain)
            ->where('db', $request->db)
            ->first();
        $client = O4uClient::firstWhere('api_key', $clientKey);

        if (!$client) {
            $client = O4uClient::where('is_public', true)->first();
        }

        if (!$client) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid Client',
            ]);
        }

        $device = null;

        Log::info(__METHOD__, [
            'user' => $user,
        ]);

        if ($user) {
            $device = $user->devices->where('device_id', $request->device_id)->first();
        } else {
            $user = ClientUser::create([
                'username' => $request->username,
                'db' => $request->db,
                'domain' => $request->domain,
                'client_id' => $client->id,
            ]);
        }

        if ($device) {
            $device->device_info = $request->device_info();
            $device->status = true;
            $device->save();
        } else {
            $user->devices()->create([
                'device_info' => $request->device_info,
                'device_id' => $request->device_id,
                'status' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Save User info done',
        ]);
    }
}
