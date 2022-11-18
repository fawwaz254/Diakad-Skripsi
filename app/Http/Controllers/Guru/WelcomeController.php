<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\RoleDashboard;
use App\Models\Setting;

use Auth;
use DB;
use Session;

class WelcomeController extends BaseController{
    public function indexWelcome(Request $request){
        if($start_monkes = Setting::where('key_setting', 'start_monkes')->first()){
            $start_monkes = $start_monkes->value;
        }else{
            $start_monkes = '19:00';
        }

        if($end_monkes = Setting::where('key_setting', 'end_monkes')->first()){
            $end_monkes = $end_monkes->value;
        }else{
            $end_monkes = '07:00';
        }

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $role_aktif = $auth_data->role_aktif;

        $role_dashboard = RoleDashboard::where(['id_role' => $role_aktif->id_role, 'is_aktif' => 1])->first();
        return view('guru/welcome', compact('auth_data', 'role_dashboard', 'start_monkes', 'end_monkes')); //folder akademik/nama file welcome.blade
    }

    public function viewBiodata(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;


    }

}