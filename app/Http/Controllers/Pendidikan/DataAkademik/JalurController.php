<?php

namespace App\Http\Controllers\Pendidikan\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Jalur as Jalur;
use App\Models\JalurSiswa as JalurSiswa;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class JalurController extends BaseController{

    public function viewJalur(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/data-akademik/jalur/view-jalur',compact('auth_data'));

    }

    public function addJalur(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_jalur = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/data-akademik/jalur/add-jalur',compact('auth_data','id_jalur'));

    }

    public function editJalur($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jalur = LibDataAkademik::fetchDataJalur($auth_data, $id);

        return view('pendidikan/data-akademik/jalur/edit-jalur',compact('auth_data','data_jalur'));

    }

    public function datatablesJalur(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataAkademik::fetchDataJalur($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_jalur
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionJalur(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_jalur' => 'required',
            'kode_jalur' => 'required'
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

                $jalur                  = new Jalur;
                $jalur->id_jalur        = $id;
                $jalur->nm_jalur        = $input->nm_jalur;
                $jalur->kode_jalur      = $input->kode_jalur;
                $jalur->id_sekolah      = $input->auth_data->pengguna->id_sekolah;
                $jalur->created_by      = $input->auth_data->pengguna->id_pengguna;
                $jalur->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/jalur',
                    'message' => 'Save Jalur Successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $jalur                  = Jalur::find($id);
                $jalur->nm_jalur        = $input->nm_jalur;
                $jalur->kode_jalur      = $input->kode_jalur;
                $jalur->updated_by      = $input->auth_data->pengguna->id_pengguna;
                $jalur->updated_at      = $now;
                $jalur->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/jalur',
                    'message' => 'Update Jalur Successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($jalurSiswa = JalurSiswa::where('id_jalur',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Jalur'
                    ]; 
                }
                else{
                    // make object to find id
                    $jalur               = Jalur::find($id);
                    $jalur->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $jalur->save();

                    $jalur->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jalur Successfully'
                    ];
                }
            }
        }
    }

}