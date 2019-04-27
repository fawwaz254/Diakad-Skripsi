<?php

namespace App\Http\Controllers\WaliMurid\Akademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class JadwalKBMController extends BaseController{

    public function viewSiswaJadwalKBM(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_siswa = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna);

        return view('wali-murid/akademik/jadwal-kbm/view-siswa-jadwal-kbm',compact('auth_data','data_siswa'));

    }

    public function actionViewSiswaJadwalKBM(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_pengguna' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            return [
                        'status' => 204, // SUCCESS AND LOAD CONTENT
                        'path' => 'akademik/jadwal-kbm/'.$input->id_pengguna
                    ];   
        }
    }

    public function viewJadwalKBM(Request $request, $id_pengguna){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $id_pengguna);

    	return view('wali-murid/akademik/jadwal-kbm/view-jadwal-kbm',compact('auth_data', 'semester_aktif', 'data_siswa'));

    }

    public function datatablesJadwalKBM(Request $request, $id_pengguna){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = LibSiswa::fetchDataJadwalKBM($auth_data, $id_pengguna, $semester_aktif->id_semester);

        return Datatables::of($list_data)
                ->addColumn('mata_pelajaran', function($item){
                    return $item->kd_mata_pelajaran." - ".$item->nm_mata_pelajaran;
                })
                ->addColumn('jadwal_jam', function($item){
                    return $item->jam_mulai.":".$item->menit_mulai." - ".$item->jam_selesai.":".$item->menit_selesai;
                })
                ->addColumn('nm_pengguna', function($item){
                    if( ! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    }
                    elseif( ! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;   
                    }
                    elseif( ! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;   
                    }
                    else {
                        return $item->nm_pengguna; 
                    }
                })
                ->make(true);
    }

}