<?php

namespace App\Http\Controllers\Keuangan\DataKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\DetailBiaya as DetailBiaya;
use App\Models\TagihanBiaya as TagihanBiaya;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class DetailBiayaController extends BaseController{

    public function viewDetailBiaya(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('keuangan/data-keuangan/detail-biaya/view-detail-biaya',compact('auth_data'));

    }

    public function addDetailBiaya(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_biaya_sekolah = LibDataKeuangan::fetchDataBiayaSekolah($auth_data, 1);

        $data_biaya = LibDataKeuangan::fetchDataNamaBiaya($auth_data);

        $data_biaya_internal = LibDataKeuangan::fetchDataBiayaInternal($auth_data);

        $data_jenis_detail_biaya = LibDataKeuangan::fetchDataJenisDetailBiaya($auth_data);

        $data_bulan = LibDataKeuangan::fetchDataBulan($auth_data);

        $id_detail_biaya = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/data-keuangan/detail-biaya/add-detail-biaya',compact('auth_data','data_biaya_sekolah','data_biaya','data_biaya_internal','data_jenis_detail_biaya','data_bulan','id_detail_biaya'));

    }

    public function editDetailBiaya($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_biaya_sekolah = LibDataKeuangan::fetchDataBiayaSekolah($auth_data, 1);

        $data_biaya = LibDataKeuangan::fetchDataNamaBiaya($auth_data);

        $data_biaya_internal = LibDataKeuangan::fetchDataBiayaInternal($auth_data);

        $data_jenis_detail_biaya = LibDataKeuangan::fetchDataJenisDetailBiaya($auth_data);

        $data_bulan = LibDataKeuangan::fetchDataBulan($auth_data);

        $data_detail_biaya = LibDataKeuangan::fetchDataDetailBiaya($auth_data, $id);

        return view('keuangan/data-keuangan/detail-biaya/edit-detail-biaya',compact('auth_data','data_biaya_sekolah','data_biaya','data_biaya_internal','data_jenis_detail_biaya','data_bulan','data_detail_biaya'));

    }

    public function ajaxGetBulanByJenisBiaya(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if($input->id_jenis_detail_biaya == 4) { 
            // ambil data bulan
            $data_bulan = LibDataKeuangan::fetchDataBulan($auth_data);
        }
        else {
            $data_bulan = null;
        }

        return $data_bulan;
    }

    public function datatablesDetailBiaya(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataDetailBiaya($auth_data, null, "1");

        return Datatables::of($list_data)
                ->addColumn('biaya_sekolah', function($item){
                    return $item->nm_kelompok_biaya." (".$item->tahun_ajaran." ".$item->nm_semester.")";
                })
                ->addColumn('nm_biaya_internal', function($item){
                    if ( ! empty($item->nm_kelompok_biaya_internal)) {
                        return $item->nm_kelompok_biaya_internal." (".$item->nm_biaya_internal.")";
                    }
                    else {
                        return "-";
                    }
                })
                ->addColumn('validasi_biaya', function($item){
                    if($item->validasi_biaya == 0){
                        return "Belum";
                    }
                    else{
                        return "Sudah";
                    }
                })
                ->addColumn('besar_biaya', function($item){
                    return "Rp".number_format($item->besar_biaya);
                })
                ->addColumn('jenis_biaya', function($item){
                    if($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_jenis_detail_biaya." (".$item->nm_bulan.")";
                    }
                    else {
                        return $item->nm_jenis_detail_biaya;
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_detail_biaya
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionDetailBiaya(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_biaya_sekolah' => 'required',
            'id_biaya' => 'required',
            /*'id_kelompok_biaya_internal' => 'required',*/
            'validasi_biaya' => 'required',
            'besar_biaya' => 'required',
            'keterangan_biaya' => 'required',
            'id_jenis_detail_biaya' => 'required'
            //'id_bulan' => 'required'
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

                if (! empty($input->id_bulan)) {
                    foreach($input->id_bulan as $id_bulan){
                        $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
        
                        $detailBiaya                                = new DetailBiaya;
                        $detailBiaya->id_detail_biaya               = $id;
                        $detailBiaya->id_biaya_sekolah              = $input->id_biaya_sekolah;
                        $detailBiaya->id_biaya                      = $input->id_biaya;
                        $detailBiaya->id_kelompok_biaya_internal    = $input->id_kelompok_biaya_internal;
                        $detailBiaya->validasi_biaya                = $input->validasi_biaya;
                        $detailBiaya->besar_biaya                   = $input->besar_biaya;
                        $detailBiaya->keterangan_biaya              = $input->keterangan_biaya;
                        $detailBiaya->id_jenis_detail_biaya         = $input->id_jenis_detail_biaya;
                        $detailBiaya->id_bulan                      = $id_bulan;
                        $detailBiaya->created_by                    = $input->auth_data->pengguna->id_pengguna;
                        $detailBiaya->save();
                    }
                }
                else {
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
        
                    $detailBiaya                                = new DetailBiaya;
                    $detailBiaya->id_detail_biaya               = $id;
                    $detailBiaya->id_biaya_sekolah              = $input->id_biaya_sekolah;
                    $detailBiaya->id_biaya                      = $input->id_biaya;
                    $detailBiaya->id_kelompok_biaya_internal    = $input->id_kelompok_biaya_internal;
                    $detailBiaya->validasi_biaya                = $input->validasi_biaya;
                    $detailBiaya->besar_biaya                   = $input->besar_biaya;
                    $detailBiaya->keterangan_biaya              = $input->keterangan_biaya;
                    $detailBiaya->id_jenis_detail_biaya         = $input->id_jenis_detail_biaya;
                    $detailBiaya->created_by                    = $input->auth_data->pengguna->id_pengguna;
                    $detailBiaya->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/detail-biaya',
                    'message' => 'Save Detail Biaya successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $detailBiaya                                = DetailBiaya::find($id);
                $detailBiaya->id_biaya_sekolah              = $input->id_biaya_sekolah;
                $detailBiaya->id_biaya                      = $input->id_biaya;
                $detailBiaya->id_kelompok_biaya_internal    = $input->id_kelompok_biaya_internal;
                $detailBiaya->validasi_biaya                = $input->validasi_biaya;
                $detailBiaya->besar_biaya                   = $input->besar_biaya;
                $detailBiaya->keterangan_biaya              = $input->keterangan_biaya;
                $detailBiaya->id_jenis_detail_biaya         = $input->id_jenis_detail_biaya;
                if (! empty($input->id_bulan)) {
                    $detailBiaya->id_bulan                      = $input->id_bulan;
                }
                $detailBiaya->updated_by                    = $input->auth_data->pengguna->id_pengguna;
                $detailBiaya->updated_at                    = $now;
                $detailBiaya->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/detail-biaya',
                    'message' => 'Update Detail Biaya successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($tagihanBiaya = TagihanBiaya::where('id_detail_biaya',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Detail Biaya'
                    ]; 
                }
                else{
                    // make object to find id
                    $detailBiaya               = DetailBiaya::find($id);
                    $detailBiaya->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $detailBiaya->save();

                    $detailBiaya->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Detail Biaya successfully'
                    ];
                }
            }
        }
    }


}