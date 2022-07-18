<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Semester;

use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;

class WelcomeController extends BaseController{
    public function indexWelcome(Request $request){
        $semester_aktif = Semester::where('is_aktif_semester','=',1)->first();
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('administrator/welcome', compact('auth_data','semester_aktif'));
    }

}