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

class JadwalUjianController extends BaseController{

    public function viewJadwalUjian(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

    	return view('guru/jadwal/jadwal-ujian/view-jadwal-ujian',compact('auth_data', 'semester_aktif'));

    }

    public function datatablesJadwalUTS(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = LibGuru::fetchDataJadwalUTS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        return Datatables::of($list_data)
                ->addColumn('mata_pelajaran', function($item){
                    return $item->kd_mata_pelajaran." - ".$item->nm_mata_pelajaran;
                })
                ->addColumn('tgl_ujian', function($item){
                    return strftime( "%A, %d %B %Y", strtotime($item->tgl_ujian_mp));
                })
                ->addColumn('jadwal_jam', function($item){
                    return $item->jam_mulai." - ".$item->jam_selesai;
                })
                ->addColumn('status_pjmp_uts', function($item){
                    if($item->pjmp_uts == 1) {
                        return "PJMP";
                    }
                    else {
                        return "Anggota";
                    }
                })
                ->addColumn('is_online', function($item){
                    if($item->is_online == 1) {
                        return "Ujian Online";
                    }
                    else {
                        return "Manual";
                    }
                })
                ->make(true);
    }

    public function datatablesJadwalUAS(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = LibGuru::fetchDataJadwalUAS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        return Datatables::of($list_data)
                ->addColumn('mata_pelajaran', function($item){
                    return $item->kd_mata_pelajaran." - ".$item->nm_mata_pelajaran;
                })
                ->addColumn('tgl_ujian', function($item){
                    return strftime( "%A, %d %B %Y", strtotime($item->tgl_ujian_mp));
                })
                ->addColumn('jadwal_jam', function($item){
                    return $item->jam_mulai." - ".$item->jam_selesai;
                })
                ->addColumn('status_pjmp_uas', function($item){
                    if($item->pjmp_uas == 1) {
                        return "PJMP";
                    }
                    else {
                        return "Anggota";
                    }
                })
                ->addColumn('is_online', function($item){
                    if($item->is_online == 1) {
                        return "Ujian Online";
                    }
                    else {
                        return "Manual";
                    }
                })
                ->make(true);
    }

}