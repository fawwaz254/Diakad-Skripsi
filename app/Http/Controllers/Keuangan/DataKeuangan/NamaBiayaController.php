<?php

namespace App\Http\Controllers\Keuangan\DataKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Biaya as Biaya;
use App\Models\DetailBiaya as DetailBiaya;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class NamaBiayaController extends BaseController{

    public function viewNamaBiaya(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('keuangan/data-keuangan/nama-biaya/view-nama-biaya',compact('auth_data'));

    }

    public function addNamaBiaya(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_biaya = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/data-keuangan/nama-biaya/add-nama-biaya',compact('auth_data','id_biaya'));

    }

    public function editNamaBiaya($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_nama_biaya = LibDataKeuangan::fetchDataNamaBiaya($auth_data, $id);

        return view('keuangan/data-keuangan/nama-biaya/edit-nama-biaya',compact('auth_data','data_nama_biaya'));

    }

    public function datatablesNamaBiaya(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataNamaBiaya($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_biaya
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionNamaBiaya(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_biaya' => 'required',
            'keterangan_biaya' => 'required'
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

                $namaBiaya                       = new Biaya;
                $namaBiaya->id_biaya             = $id;
                $namaBiaya->nm_biaya             = $input->nm_biaya;
                $namaBiaya->keterangan_biaya     = $input->keterangan_biaya;
                $namaBiaya->id_sekolah           = $input->auth_data->pengguna->id_sekolah;
                $namaBiaya->created_by           = $input->auth_data->pengguna->id_pengguna;
                $namaBiaya->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/nama-biaya',
                    'message' => 'Save Nama Biaya successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $namaBiaya                       = Biaya::find($id);
                $namaBiaya->nm_biaya             = $input->nm_biaya;
                $namaBiaya->keterangan_biaya     = $input->keterangan_biaya;
                $namaBiaya->updated_by           = $input->auth_data->pengguna->id_pengguna;
                $namaBiaya->updated_at           = $now;
                $namaBiaya->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/nama-biaya',
                    'message' => 'Update Nama Biaya successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($detailBiaya = DetailBiaya::where('id_biaya',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Nama Biaya'
                    ]; 
                }
                else{
                    // make object to find id
                    $namaBiaya               = Biaya::find($id);
                    $namaBiaya->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $namaBiaya->save();

                    $namaBiaya->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Nama Biaya successfully'
                    ];
                }
            }
        }
    }


}