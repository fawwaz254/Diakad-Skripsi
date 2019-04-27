<?php

namespace App\Http\Controllers\Keuangan\DataKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KelompokBiayaInternal as KelompokBiayaInternal;
use App\Models\DetailBiayaInternal as DetailBiayaInternal;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class BiayaInternalController extends BaseController{

    public function viewBiayaInternal(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('keuangan/data-keuangan/biaya-internal/view-biaya-internal',compact('auth_data'));

    }

    public function addBiayaInternal(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_biaya = LibDataKeuangan::fetchDataNamaBiaya($auth_data);

        $id_kelompok_biaya_internal = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/data-keuangan/biaya-internal/add-biaya-internal',compact('auth_data','data_biaya','id_kelompok_biaya_internal'));

    }

    public function editBiayaInternal($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_biaya = LibDataKeuangan::fetchDataNamaBiaya($auth_data);

        $data_biaya_internal = LibDataKeuangan::fetchDataBiayaInternal($auth_data, $id);

        return view('keuangan/data-keuangan/biaya-internal/edit-biaya-internal',compact('auth_data','data_biaya','data_biaya_internal'));

    }

    public function datatablesBiayaInternal(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataBiayaInternal($auth_data, null, "1");

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kelompok_biaya_internal
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionBiayaInternal(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_biaya' => 'required',
            'nm_kelompok_biaya_internal' => 'required'
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

                $biayaInternal                                  = new KelompokBiayaInternal;
                $biayaInternal->id_kelompok_biaya_internal      = $id;
                $biayaInternal->id_biaya                        = $input->id_biaya;
                $biayaInternal->nm_kelompok_biaya_internal      = $input->nm_kelompok_biaya_internal;
                $biayaInternal->created_by                      = $input->auth_data->pengguna->id_pengguna;
                $biayaInternal->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/biaya-internal',
                    'message' => 'Save Biaya Internal successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $biayaInternal                                  = KelompokBiayaInternal::find($id);
                $biayaInternal->id_biaya                        = $input->id_biaya;
                $biayaInternal->nm_kelompok_biaya_internal      = $input->nm_kelompok_biaya_internal;
                $biayaInternal->updated_by                      = $input->auth_data->pengguna->id_pengguna;
                $biayaInternal->updated_at                      = $now;
                $biayaInternal->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/biaya-internal',
                    'message' => 'Update Biaya Internal successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($detailBiayaInternal = DetailBiayaInternal::where('id_kelompok_biaya_internal',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Biaya Internal'
                    ]; 
                }
                else{
                    // make object to find id
                    $biayaInternal               = KelompokBiayaInternal::find($id);
                    $biayaInternal->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $biayaInternal->save();

                    $biayaInternal->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Biaya Internal successfully'
                    ];
                }
            }
        }
    }


}