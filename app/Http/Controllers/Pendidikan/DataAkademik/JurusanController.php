<?php

namespace App\Http\Controllers\Pendidikan\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Jurusan as Jurusan;
use App\Models\Kelas as Kelas;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class JurusanController extends BaseController{

    public function viewJurusan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/data-akademik/jurusan/view-jurusan',compact('auth_data'));

    }

    public function addJurusan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_jurusan = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/data-akademik/jurusan/add-jurusan',compact('auth_data','id_jurusan'));

    }

    public function editJurusan($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = LibDataAkademik::fetchDataJurusan($auth_data, $id);

        return view('pendidikan/data-akademik/jurusan/edit-jurusan',compact('auth_data','data_jurusan'));

    }

    public function datatablesJurusan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataAkademik::fetchDataJurusan($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_jurusan
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionJurusan(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_jurusan' => 'required',
            'kode_jurusan' => 'required'
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

            if($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $jurusan                  = new Jurusan;
                $jurusan->id_jurusan      = $id;
                $jurusan->nm_jurusan      = $input->nm_jurusan;
                $jurusan->kode_jurusan    = $input->kode_jurusan;
                $jurusan->id_sekolah      = $input->auth_data->pengguna->id_sekolah;
                $jurusan->created_by      = $input->auth_data->pengguna->id_pengguna;
                $jurusan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/jurusan',
                    'message' => 'Save Jurusan Successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $jurusan                  = Jurusan::find($id);
                $jurusan->nm_jurusan      = $input->nm_jurusan;
                $jurusan->kode_jurusan    = $input->kode_jurusan;
                $jurusan->updated_by      = $input->auth_data->pengguna->id_pengguna;
                $jurusan->updated_at      = $now;
                $jurusan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/jurusan',
                    'message' => 'Update Jurusan Successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($kelas = Kelas::where('id_jurusan',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Jurusan'
                    ]; 
                }
                else{
                    // make object to find id
                    $jurusan               = Jurusan::find($id);
                    $jurusan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $jurusan->save();

                    $jurusan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jurusan Successfully'
                    ];
                }
            }
        }
    }

}