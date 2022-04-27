<?php

namespace App\Http\Controllers\Guru\LaporanKerjaHarianMGMP;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use DB;
use Illuminate\Validation\Rule;
use Session;
use Validator;

class LaporanKerjaHarianController extends Controller
{
    public function viewLaporanHarianMGMP(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view(' guru/mgmp/laporan-harian-mgmp/add-data-laporan-harian-mgmp',compact('auth_data'));

    }

}
