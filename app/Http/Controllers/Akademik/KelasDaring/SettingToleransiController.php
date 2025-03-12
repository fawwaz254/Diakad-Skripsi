<?php

namespace App\Http\Controllers\Akademik\KelasDaring;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Setting;

use Auth;
use Session;
use Validator;

class SettingToleransiController extends BaseController
{
    public function viewSettingToleransi(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $setting = Setting::where('key_setting', 'setting_keterlambatan_global')->first();

        return view('akademik/kelas-daring/setting-toleransi-keterlambatan/view-setting-toleransi', compact('auth_data', 'setting'));
    }

    public function actionSettingToleransi(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'setting_keterlambatan_global' =>'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        $setting = Setting::where('key_setting', 'setting_keterlambatan_global')->first();

        $setting->value = $input->setting_keterlambatan_global;
        $setting->save();

        return [
            'status' => 300, // SUCCESS
            'message' => 'Setting berhasil disimpan'
        ];
    }
}
