<?php

namespace App\Http\Controllers\Pendidikan\SettingKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\WaliKelas as WaliKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class WaliKelasController extends BaseController{

    public function viewWaliKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('pendidikan/setting-kelas/wali-kelas/view-wali-kelas',compact('auth_data','data_kelas'));

    }

    public function actionViewWaliKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required'
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
                        'path' => 'setting-kelas/wali-kelas/view-kelas/'.$input->id_kelas
                    ];   
        }
    }

    public function viewKelasWaliKelas(Request $request, $id_kelas){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($id_kelas);
        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

    	return view('pendidikan/setting-kelas/wali-kelas/view-kelas-wali-kelas',compact('auth_data', 'data_kelas'));

    }

    public function addWaliKelas(Request $request, $id_kelas){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        // ambil data guru melalui role sumber daya
        $data_guru = LibGuru::fetchDataAllGuru($auth_data);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_wali_kelas = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/setting-kelas/wali-kelas/add-wali-kelas',compact('auth_data','data_kelas','data_semester','data_guru','id_wali_kelas'));

    }

    public function editWaliKelas(Request $request, $id_kelas, $id_semester, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);
        // dd($id_kelas);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        // dd($data_semester);

        // ambil data guru melalui role sumber daya
        $data_guru = LibGuru::fetchDataAllGuru($auth_data);

        $data_wali_kelas = LibGuru::fetchDataWaliKelas($auth_data, $id_kelas, $id);

        return view('pendidikan/setting-kelas/wali-kelas/edit-wali-kelas',compact('auth_data','data_kelas','data_semester','data_guru','data_wali_kelas'));

    }

    public function datatablesWaliKelas(Request $request, $id_kelas){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibGuru::fetchDataWaliKelas($auth_data, $id_kelas);

        return Datatables::of($list_data)
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('status_aktif', function($item){
                    if($item->is_aktif == 0){
                        return "Non-Aktif";
                    }
                    else{
                        return "Aktif";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id'            => $item->id_wali_kelas,
                        'id_kelas'      => $item->id_kelas,
                        'id_semester'   => $item->id_semester
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionWaliKelas(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'id_semester' => 'required',
            'id_guru' => 'required',
            'is_aktif' => 'required'
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

            // ACTION ADD
            if($mode == 'add') {
                // cek apabila ada record kelas dan semester yg sama
                $waliKelas = WaliKelas::join('semester','semester.id_semester','=','wali_kelas.id_semester')
                                ->where('wali_kelas.id_kelas','=',$input->id_kelas)
                                ->where('wali_kelas.id_semester','=',$input->id_semester)
                                ->where('semester.id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                                ->first();

                $waliKelasGuru = WaliKelas::join('semester','semester.id_semester','=','wali_kelas.id_semester')
                                ->where('wali_kelas.id_guru','=',$input->id_guru)
                                ->where('wali_kelas.id_semester','=',$input->id_semester)
                                ->where('semester.id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                                ->first();

                if($waliKelas || $waliKelasGuru){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Wali Kelas!'
                    ];
                }
                else {
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $waliKelas                       = new WaliKelas;
                    $waliKelas->id_wali_kelas        = $id;
                    $waliKelas->id_kelas             = $input->id_kelas;
                    $waliKelas->id_semester          = $input->id_semester;
                    $waliKelas->id_guru              = $input->id_guru;
                    $waliKelas->is_aktif             = $input->is_aktif;
                    $waliKelas->created_by           = $input->auth_data->pengguna->id_pengguna;
                    $waliKelas->save();

                    // cek jika update status aktif = 1, maka yg lain status aktif = 0
                    if($input->is_aktif == 1) {
                        $data_wali_kelas  = WaliKelas::where('id_wali_kelas', "<>", $id)->where('id_kelas', $input->id_kelas)->get();

                        foreach ($data_wali_kelas as $waliKelas) {
                            $waliKelas->is_aktif    = 0;
                            $waliKelas->updated_by  = $input->auth_data->pengguna->id_pengguna;
                            $waliKelas->updated_at  = $now;
                            $waliKelas->save();
                        }
                    }

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'setting-kelas/wali-kelas/view-kelas/'.$input->id_kelas,
                        'message' => 'Save Wali Kelas successfully'
                    ];
                }
            }
            elseif($mode == 'edit'){
                // cek apabila ada record kelas dan semester yg sama
                $waliKelas = WaliKelas::join('semester','semester.id_semester','=','wali_kelas.id_semester')
                                ->where('wali_kelas.id_kelas','=',$input->id_kelas)
                                ->where('wali_kelas.id_semester','=',$input->id_semester)
                                ->where('semester.id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                                ->first();

                $waliKelasGuru = WaliKelas::join('semester','semester.id_semester','=','wali_kelas.id_semester')
                                ->where('wali_kelas.id_guru','=',$input->id_guru)
                                ->where('wali_kelas.id_semester','=',$input->id_semester)
                                ->where('semester.id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                                ->first();

                if($waliKelas || $waliKelasGuru){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Wali Kelas!'
                    ];
                }
                else {
                    // make object to find id
                    $waliKelas                   = WaliKelas::find($id);
                    $waliKelas->id_kelas         = $input->id_kelas;
                    $waliKelas->id_semester      = $input->id_semester;
                    $waliKelas->id_guru          = $input->id_guru;
                    $waliKelas->is_aktif         = $input->is_aktif;
                    $waliKelas->updated_by       = $input->auth_data->pengguna->id_pengguna;
                    $waliKelas->updated_at       = $now;
                    $waliKelas->save();

                    // cek jika update status aktif = 1, maka yg lain status aktif = 0
                    if($input->is_aktif == 1) {
                        $data_wali_kelas  = WaliKelas::where('id_wali_kelas', "<>", $id)->where('id_kelas', $input->id_kelas)->get();

                        foreach ($data_wali_kelas as $waliKelas) {
                            $waliKelas->is_aktif    = 0;
                            $waliKelas->updated_by  = $input->auth_data->pengguna->id_pengguna;
                            $waliKelas->updated_at  = $now;
                            $waliKelas->save();
                        }
                    }

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'setting-kelas/wali-kelas/view-kelas/'.$input->id_kelas,
                        'message' => 'Update Wali Kelas successfully'
                    ];
                }
            }
            elseif($mode == 'delete'){
                // make object to find id
                $waliKelas               = WaliKelas::find($id);
                $waliKelas->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $waliKelas->save();

                $waliKelas->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Wali Kelas successfully'
                ];
            }
        }
    }

}