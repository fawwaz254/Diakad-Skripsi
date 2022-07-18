<?php

namespace App\Http\Controllers\Guru\Jadwal;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class JadwalKBMController extends BaseController{

    public function viewJadwalKBM(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

    	return view('guru/jadwal/jadwal-kbm/view-jadwal-kbm',compact('auth_data', 'semester_aktif'));

    }

    public function datatablesJadwalKBM(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        return Datatables::of($list_data)
                ->addColumn('mata_pelajaran', function($item){
                    return $item->kd_mata_pelajaran." - ".$item->nm_mata_pelajaran;
                })
                ->addColumn('jadwal_jam', function($item){
                    return $item->jam_mulai.":".$item->menit_mulai." - ".$item->jam_selesai.":".$item->menit_selesai;
                })
                ->addColumn('status_pjmp', function($item){
                    if($item->pjmp_pengampu_mp == 1) {
                        return "PJMP";
                    }
                    else {
                        return "Anggota";
                    }
                })
                ->make(true);
    }

}