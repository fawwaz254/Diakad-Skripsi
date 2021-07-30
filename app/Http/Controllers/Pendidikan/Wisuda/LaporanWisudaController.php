<?php

namespace App\Http\Controllers\Pendidikan\Wisuda;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PeriodeWisuda;
use App\Models\PengajuanWisuda;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibWisuda;

use Auth;
use DB;
use Session;
use Validator;

class LaporanWisudaController extends BaseController{

    public function viewLaporanWisuda(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode_wisuda = LibWisuda::fetchDataPeriodeWisuda($auth_data);

    	return view('pendidikan/wisuda/laporan-wisuda/view-laporan-wisuda',compact('auth_data','data_periode_wisuda'));

    }

    public function printLaporanWisuda($id_periode){

        $periode_wisuda = PeriodeWisuda::with('semester')->findOrFail($id_periode);
        $siswa_wisuda = PengajuanWisuda::with('siswa.pengguna','kelas')->where('id_periode_wisuda',$id_periode)->get();
        return view('pendidikan/wisuda/laporan-wisuda/print-laporan-wisuda',compact('periode_wisuda','siswa_wisuda'));

    }

}