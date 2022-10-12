<?php

namespace App\Http\Controllers\Keuangan\DataKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KelompokBiaya as KelompokBiaya;
use App\Models\BiayaSekolah as BiayaSekolah;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class KelompokBiayaController extends BaseController{

    public function viewKelompokBiaya(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('keuangan/data-keuangan/kelompok-biaya/view-kelompok-biaya',compact('auth_data'));

    }

    public function addKelompokBiaya(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_kelompok_biaya = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/data-keuangan/kelompok-biaya/add-kelompok-biaya',compact('auth_data','id_kelompok_biaya'));

    }

    public function editKelompokBiaya($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data, $id);

        return view('keuangan/data-keuangan/kelompok-biaya/edit-kelompok-biaya',compact('auth_data','data_kelompok_biaya'));

    }

    public function datatablesKelompokBiaya(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        return Datatables::of($list_data)
                ->addColumn('status_kelompok_biaya', function($item){
                    if($item->status_kelompok_biaya == 1){
                        return "Reguler";
                    }
                    else{
                        return "Khusus";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kelompok_biaya
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionKelompokBiaya(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_kelompok_biaya' => 'required',
            'keterangan_kelompok_biaya' => 'required'
            // 'status_kelompok_biaya' => 'required'
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

                $kelompokBiaya                              = new KelompokBiaya;
                $kelompokBiaya->id_kelompok_biaya           = $id;
                $kelompokBiaya->nm_kelompok_biaya           = $input->nm_kelompok_biaya;
                $kelompokBiaya->keterangan_kelompok_biaya   = $input->keterangan_kelompok_biaya;
                // $kelompokBiaya->status_kelompok_biaya       = $input->status_kelompok_biaya;
                $kelompokBiaya->status_kelompok_biaya       = 1;
                $kelompokBiaya->id_sekolah                  = $input->auth_data->pengguna->id_sekolah;
                $kelompokBiaya->created_by                  = $input->auth_data->pengguna->id_pengguna;
                $kelompokBiaya->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/kelompok-biaya',
                    'message' => 'Save Kelompok Biaya Successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $kelompokBiaya                              = KelompokBiaya::find($id);
                $kelompokBiaya->nm_kelompok_biaya           = $input->nm_kelompok_biaya;
                $kelompokBiaya->keterangan_kelompok_biaya   = $input->keterangan_kelompok_biaya;
                // $kelompokBiaya->status_kelompok_biaya       = $input->status_kelompok_biaya;
                $kelompokBiaya->status_kelompok_biaya       = 1;
                $kelompokBiaya->updated_by                  = $input->auth_data->pengguna->id_pengguna;
                $kelompokBiaya->updated_at                  = $now;
                $kelompokBiaya->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/kelompok-biaya',
                    'message' => 'Update Kelompok Biaya Successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($biayaSekolah = BiayaSekolah::where('id_kelompok_biaya',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Kelompok Biaya'
                    ]; 
                }
                else{
                    // make object to find id
                    $kelompokBiaya               = KelompokBiaya::find($id);
                    $kelompokBiaya->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $kelompokBiaya->save();

                    $kelompokBiaya->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Kelompok Biaya Successfully'
                    ];
                }
            }
        }
    }


}