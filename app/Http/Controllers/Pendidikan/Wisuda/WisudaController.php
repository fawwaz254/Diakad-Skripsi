<?php

namespace App\Http\Controllers\Pendidikan\Wisuda;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Wisuda as Wisuda;
use App\Models\PeriodeWisuda as PeriodeWisuda;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibWisuda;

use Auth;
use DB;
use Session;
use Validator;

class WisudaController extends BaseController{

    public function viewWisuda(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/wisuda/nama-wisuda/view-wisuda',compact('auth_data'));

    }

    public function addWisuda(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_wisuda = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/wisuda/nama-wisuda/add-wisuda',compact('auth_data','id_wisuda'));

    }

    public function editWisuda($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_wisuda = LibWisuda::fetchDataWisuda($auth_data, $id);

        return view('pendidikan/wisuda/nama-wisuda/edit-wisuda',compact('auth_data','data_wisuda'));

    }

    public function datatablesWisuda(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibWisuda::fetchDataWisuda($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_wisuda
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionWisuda(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_wisuda' => 'required',
            'keterangan_wisuda' => 'required'
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

                $wisuda                     = new Wisuda;
                $wisuda->id_wisuda          = $id;
                $wisuda->nm_wisuda          = $input->nm_wisuda;
                $wisuda->keterangan_wisuda  = $input->keterangan_wisuda;
                $wisuda->id_sekolah         = $input->auth_data->pengguna->id_sekolah;
                $wisuda->created_by         = $input->auth_data->pengguna->id_pengguna;
                $wisuda->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wisuda/nama-wisuda',
                    'message' => 'Save Wisuda Successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $wisuda                     = Wisuda::find($id);
                $wisuda->nm_wisuda          = $input->nm_wisuda;
                $wisuda->keterangan_wisuda  = $input->keterangan_wisuda;
                $wisuda->updated_by         = $input->auth_data->pengguna->id_pengguna;
                $wisuda->updated_at         = $now;
                $wisuda->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wisuda/nama-wisuda',
                    'message' => 'Update Wisuda Successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($periodeWisuda = PeriodeWisuda::where('id_wisuda',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Wisuda'
                    ]; 
                }
                else{
                    // make object to find id
                    $wisuda               = Wisuda::find($id);
                    $wisuda->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $wisuda->save();

                    $wisuda->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Wisuda Successfully'
                    ];
                }
            }
        }
    }

}