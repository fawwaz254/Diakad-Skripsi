<?php

namespace App\Http\Controllers\Siswa\Akademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;

use App\Models\Siswa;
use App\Models\Bulan;
use App\Models\PresensiHarian;
use App\Models\PresensiHarianSiswa;


use Auth;
use DB;
use Session;
use Validator;

class LihatAbsensiController extends BaseController{

    public function viewLihatAbsensi(Request $request, $id_bulan = null, $tahun = null){
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $now = Carbon::today();
        if(empty($id_bulan)){
            $id_bulan = $now->month;
        }

        if(empty($tahun)){
            $tahun = $now->year;
        }
        
        $start_month = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();

    	return view('siswa/akademik/lihat-absensi/view-lihat-absensi',compact('auth_data','data_bulan','bulan','tahun','dates','siswa'));

    }

}