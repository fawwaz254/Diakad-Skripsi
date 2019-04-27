<?php

namespace App\Http\Controllers\Kesiswaan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;

class WelcomeController extends BaseController{
    public function indexWelcome(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('kesiswaan/welcome', compact('auth_data'));
    }

}