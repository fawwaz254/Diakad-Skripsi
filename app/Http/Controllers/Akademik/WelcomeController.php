<?php

namespace App\Http\Controllers\Akademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\RoleDashboard;

use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;

use App\Models\Setting;

class WelcomeController extends BaseController{

    public function indexWelcome(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;


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
        $role_aktif = $auth_data->role_aktif;

        $role_dashboard = RoleDashboard::where(['id_role' => $role_aktif->id_role, 'is_aktif' => 1])->first();
        return view('akademik/welcome', compact('auth_data','start_monkes','end_monkes','role_dashboard')); //folder akademik/nama file welcome.blade
    
    }

}