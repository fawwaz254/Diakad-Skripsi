<?php

namespace App\Http\Controllers\Pendidikan\SettingKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kelas as Kelas;
use App\Models\Siswa as Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class KelasController extends BaseController{

    public function viewKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/setting-kelas/kelas/view-kelas',compact('auth_data'));

    }

    public function addKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = LibDataAkademik::fetchDataJurusan($auth_data);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_kelas = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/setting-kelas/kelas/add-kelas',compact('auth_data','data_jurusan','id_kelas'));

    }

    public function editKelas($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = LibDataAkademik::fetchDataJurusan($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id);

        return view('pendidikan/setting-kelas/kelas/edit-kelas',compact('auth_data','data_jurusan','data_kelas'));

    }

    public function datatablesKelas(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibKelas::fetchDataKelas($auth_data);

        return Datatables::of($list_data)
                ->addColumn('nm_sekretaris', function($item){
                    if(empty($item->nm_sekretaris)){
                        $data = array(
                            'nm_sekretaris' => 0,
                            'id' => $item->id_kelas
                        );
                    }
                    else{
                        $data = array(
                            'nm_sekretaris' => $item->nm_sekretaris
                        );
                    }
                    return $data;
                })
                ->addColumn('nm_ruangan', function($item){
                    if(empty($item->nm_ruangan)){
                        $data = array(
                            'nm_ruangan' => 0,
                            'id' => $item->id_kelas
                        );
                    }
                    else{
                        $data = array(
                            'nm_ruangan' => $item->nm_ruangan
                        );
                    }
                    return $data;
                })
                ->addColumn('nm_wali_kelas', function($item){
                    if(empty($item->nm_wali_kelas)){
                        $data = array(
                            'nm_wali_kelas' => 0,
                            'id' => $item->id_kelas
                        );
                    }
                    else{
                        $data = array(
                            'nm_wali_kelas' => $item->nm_wali_kelas
                        );
                    }
                    return $data;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kelas
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionKelas(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jurusan' => 'required',
            'nm_kelas' => 'required',
            'tingkat' => 'required',
            'keterangan_kelas' => 'required'
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
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $kelas                     = new Kelas;
                    $kelas->id_kelas           = $id;
                    $kelas->id_jurusan         = $input->id_jurusan;
                    $kelas->nm_kelas           = $input->nm_kelas;
                    $kelas->tingkat            = $input->tingkat;
                    $kelas->keterangan_kelas   = $input->keterangan_kelas;
                    $kelas->created_by         = $input->auth_data->pengguna->id_pengguna;
                    $kelas->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'setting-kelas/kelas',
                        'message' => 'Save Kelas successfully'
                    ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $kelas                          = Kelas::find($id);
                $kelas->id_jurusan              = $input->id_jurusan;
                $kelas->nm_kelas                = $input->nm_kelas;
                $kelas->tingkat                 = $input->tingkat;
                $kelas->keterangan_kelas        = $input->keterangan_kelas;
                $kelas->updated_by              = $input->auth_data->pengguna->id_pengguna;
                $kelas->updated_at              = $now;
                $kelas->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'setting-kelas/kelas',
                    'message' => 'Update Kelas successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($siswa = Siswa::where('id_kelas',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Kelas'
                    ]; 
                }
                else{
                    // make object to find id
                    $kelas               = Kelas::find($id);
                    $kelas->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $kelas->save();

                    $kelas->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Kelas successfully'
                    ];
                }
            }
        }
    }

}