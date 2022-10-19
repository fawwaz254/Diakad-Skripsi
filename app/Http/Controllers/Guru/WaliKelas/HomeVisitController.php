<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\HomeVisit as HomeVisit;
use App\Models\Guru as Guru;
use App\Models\Siswa;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\BimbinganKonseling\LibDataPelanggaran;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class HomeVisitController extends BaseController{

    public function viewHomeVisit(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna','=',$auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        return view('guru/wali-kelas/home-visit/view-home-visit',compact('auth_data', 'wali_kelas'));

    }

    public function addHomeVisit(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna','=',$auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        // ambil data all siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $wali_kelas->id_kelas);

        $id_home_visit = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('guru/wali-kelas/home-visit/add-home-visit',compact('auth_data','wali_kelas','data_siswa','id_home_visit'));

    }

    public function editHomeVisit($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna','=',$auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        // ambil data all siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $wali_kelas->id_kelas);

        $data_home_visit = LibGuru::fetchDataHomeVisit($auth_data, $semester_aktif->id_semester, $guru->id_guru, $id);

        return view('guru/wali-kelas/home-visit/edit-home-visit',compact('auth_data','wali_kelas','data_siswa','data_home_visit'));

    }

    public function datatablesHomeVisit(Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna','=',$auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = LibGuru::fetchDataHomeVisit($auth_data, $semester_aktif->id_semester, $guru->id_guru);

        return Datatables::of($list_data)
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('nm_guru', function($item){
                    if( ! empty($item->gelar_depan_guru) && ! empty($item->gelar_belakang_guru)) {
                        return $item->gelar_depan_guru." ".$item->nm_guru.", ".$item->gelar_belakang_guru;
                    }
                    elseif( ! empty($item->gelar_depan_guru)) {
                        return $item->gelar_depan_guru." ".$item->nm_guru;   
                    }
                    elseif( ! empty($item->gelar_belakang_guru)) {
                        return $item->nm_guru.", ".$item->gelar_belakang_guru;   
                    }
                    else {
                        return $item->nm_guru; 
                    }
                })
                ->addColumn('is_berkas_lengkap', function($item){
                    if ($item->is_berkas_lengkap == 1) {
                        return "Sudah Validasi Kesiswaan";
                    }
                    else {
                        return "Belum Validasi Kesiswaan";
                    }
                })
                ->addColumn('nm_guru_kesiswaan', function($item){
                    if ( ! empty($item->nm_guru_kesiswaan)) {
                        if( ! empty($item->gelar_depan_kesiswaan) && ! empty($item->gelar_belakang_kesiswaan)) {
                            return $item->gelar_depan_kesiswaan." ".$item->nm_guru_kesiswaan.", ".$item->gelar_belakang_kesiswaan;
                        }
                        elseif( ! empty($item->gelar_depan_kesiswaan)) {
                            return $item->gelar_depan_kesiswaan." ".$item->nm_guru_kesiswaan;   
                        }
                        elseif( ! empty($item->gelar_belakang_kesiswaan)) {
                            return $item->nm_guru_kesiswaan.", ".$item->gelar_belakang_kesiswaan;   
                        }
                        else {
                            return $item->nm_guru_kesiswaan; 
                        }
                    }
                    elseif ( ! empty($item->nm_tendik_kesiswaan)) {
                        if( ! empty($item->gelar_depan_tendik_kesiswaan) && ! empty($item->gelar_belakang_tendik_kesiswaan)) {
                            return $item->gelar_depan_tendik_kesiswaan." ".$item->nm_tendik_kesiswaan.", ".$item->gelar_belakang_tendik_kesiswaan;
                        }
                        elseif( ! empty($item->gelar_depan_tendik_kesiswaan)) {
                            return $item->gelar_depan_tendik_kesiswaan." ".$item->nm_tendik_kesiswaan;   
                        }
                        elseif( ! empty($item->gelar_belakang_tendik_kesiswaan)) {
                            return $item->nm_tendik_kesiswaan.", ".$item->gelar_belakang_tendik_kesiswaan;   
                        }
                        else {
                            return $item->nm_tendik_kesiswaan; 
                        }
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_home_visit,
                        'is_berkas_lengkap' => $item->is_berkas_lengkap
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionHomeVisit(Request $request, $mode, $id = null) {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_siswa'              => 'required',
            'nomor_hp_wali_murid'   => 'required',
            'alamat_wali_murid'     => 'required',
            'rangkuman_home_visit'  => 'required'
        ]);
        
        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($input->auth_data);

            if($mode == 'add') {
                // get id_guru
                $guru = Guru::select('id_guru')
                    ->where('id_pengguna','=',$input->auth_data->pengguna->id_pengguna)
                    ->first();

                $id_guru_input = $guru->id_guru;

                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                // Dalam satu semester ternyata bisa bebrapa kali home visit
                /*// cek dobel home visit
                $homeVisitCek = HomeVisit::where('id_guru_wali_kelas','=',$id_guru_input)
                                    ->where('id_siswa','=',$input->id_siswa)
                                    ->where('id_semester','=',$semester_aktif->id_semester)
                                    ->first();

                if($homeVisitCek) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Home Visit!'
                    ];
                }   
                else {*/
                    if($siswa = Siswa::find($input->id_siswa)){
                        $homeVisit                                  = new HomeVisit;
                        $homeVisit->id_home_visit                   = $id;
                        $homeVisit->id_semester                     = $semester_aktif->id_semester;
                        $homeVisit->id_guru_wali_kelas              = $id_guru_input;
                        $homeVisit->id_siswa                        = $input->id_siswa;
                        $homeVisit->id_kelas                        = $siswa->id_kelas;
                        $homeVisit->nomor_hp_wali_murid             = $input->nomor_hp_wali_murid;
                        $homeVisit->alamat_wali_murid               = $input->alamat_wali_murid;
                        $homeVisit->rangkuman_home_visit            = $input->rangkuman_home_visit;
                        $homeVisit->is_berkas_lengkap               = 0;
                        $homeVisit->created_by                      = $input->auth_data->pengguna->id_pengguna;
                        $homeVisit->save();
    
                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'wali-kelas/home-visit',
                            'message' => 'Save Home Visit Successfully'
                        ];
                    }else{
                        return [
                            'status' => 300, // SUCCESS AND LOAD TABLE
                            'message' => 'Failed To Update Home Visit'
                        ]; 
                    }
                /*}*/                                 
            }
            elseif($mode == 'edit') {
                if($siswa = Siswa::find($input->id_siswa)){
                    // make object to find id
                    $homeVisit                                  = HomeVisit::find($id);
                    if($input->id_siswa != $homeVisit->id_siswa){
                        $homeVisit->id_siswa                        = $input->id_siswa;
                        $homeVisit->id_kelas                        = $siswa->id_kelas;
                    }
                    $homeVisit->nomor_hp_wali_murid             = $input->nomor_hp_wali_murid;
                    $homeVisit->alamat_wali_murid               = $input->alamat_wali_murid;
                    $homeVisit->rangkuman_home_visit            = $input->rangkuman_home_visit;
                    $homeVisit->updated_by                      = $input->auth_data->pengguna->id_pengguna;
                    $homeVisit->updated_at                      = $now;
                    $homeVisit->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'wali-kelas/home-visit',
                        'message' => 'Update Home Visit Successfully'
                    ];
                }else{
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Update Home Visit'
                    ];
                }
            }
            elseif($mode == 'delete') {
                // cek apabila sudah validasi kesiswaan
                if($homeVisiCek = HomeVisit::where('id_home_visit',$id)->where('is_berkas_lengkap',1)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Home Visit'
                    ]; 
                }
                else{
                    // make object to find id
                    $homeVisit               = HomeVisit::find($id);
                    $homeVisit->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $homeVisit->save();

                    $homeVisit->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Home Visit Successfully'
                    ];
                }
            }
        }
    }


}