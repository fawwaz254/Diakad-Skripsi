<?php

namespace App\Http\Controllers\Administrator\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FingerprintRealtimeController extends Controller
{
    public function viewFingerprintRealtime(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('administrator/device/fingerprint/view-data-fingerprint', compact('auth_data'));
    }
}
