<?php

namespace App\Http\Controllers\WaliMurid;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;

class WelcomeController extends BaseController
{
    public function indexWelcome(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_anak_murid  = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna);
        return view('wali-murid/welcome', compact('auth_data', 'data_anak_murid'));
    }

    public function actionSaveChangeAnakMurid(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
    }
}
