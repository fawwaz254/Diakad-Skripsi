<?php

namespace App\Http\Controllers\Humas\KegiatanHarian;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KegiatanHarianPertanyaan;
use App\Models\KegiatanHarianJawaban;
use App\Models\KegiatanHarianKategori;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class RekapKesehatanController extends BaseController{

    public function viewRekapKesehatan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('humas/kegiatan-harian/rekap-kesehatan/view-rekap-kesehatan',compact('auth_data'));
    }

}