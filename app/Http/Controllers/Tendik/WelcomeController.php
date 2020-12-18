<?php

namespace App\Http\Controllers\Tendik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\RoleDashboard;

use Auth;
use DB;
use Session;

class WelcomeController extends BaseController{
    public function indexWelcome(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $role_aktif = $auth_data->role_aktif;

        $role_dashboard = RoleDashboard::where(['id_role' => $role_aktif->id_role, 'is_aktif' => 1])->first();

        return view('tendik/welcome', compact('auth_data', 'role_dashboard'));
    }

}