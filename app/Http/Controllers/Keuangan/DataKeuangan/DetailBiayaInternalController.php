<?php

namespace App\Http\Controllers\Keuangan\DataKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\DetailBiayaInternal as DetailBiayaInternal;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;
use App\Models\KelompokBiaya;
use Auth;
use DB;
use Session;
use Validator;

class DetailBiayaInternalController extends BaseController{

    public function viewDetailBiayaInternal(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelompok_biaya_internal = LibDataKeuangan::fetchDataBiayaInternal($auth_data);
        $data_kelompok_biaya = KelompokBiaya::orderBy('nm_kelompok_biaya', 'asc')->get();

    	return view('keuangan/data-keuangan/detail-biaya-internal/view-detail-biaya-internal',compact('auth_data', 'data_kelompok_biaya_internal', 'data_kelompok_biaya'));

    }

    public function addDetailBiayaInternal(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_biaya_internal = LibDataKeuangan::fetchDataBiayaInternal($auth_data);

        $id_detail_biaya_internal = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/data-keuangan/detail-biaya-internal/add-detail-biaya-internal',compact('auth_data','data_biaya_internal','id_detail_biaya_internal'));

    }

    public function editDetailBiayaInternal($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_biaya_internal = LibDataKeuangan::fetchDataBiayaInternal($auth_data);

        $data_detail_biaya_internal = LibDataKeuangan::fetchDataDetailBiayaInternal($auth_data, $id);

        return view('keuangan/data-keuangan/detail-biaya-internal/edit-detail-biaya-internal',compact('auth_data','data_biaya_internal','data_detail_biaya_internal'));

    }

    public function datatablesDetailBiayaInternal(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataKeuangan::fetchDataDetailBiayaInternal($auth_data, null, "1");
        
        if(!empty($input->kelompok_biaya_internal)){
            $list_data = $list_data->where('kelompok_biaya_internal.id_kelompok_biaya_internal', $input->kelompok_biaya_internal);
        }

        if(!empty($input->kelompok_biaya)){
            $kelBiaya = KelompokBiaya::select()
                            ->join('biaya_sekolah', function($join){
                                $join->on('biaya_sekolah.id_kelompok_biaya', '=', 'kelompok_biaya.id_kelompok_biaya');
                                $join->whereNull('biaya_sekolah.deleted_at');
                            })->join('detail_biaya', function($join){
                                $join->on('detail_biaya.id_biaya_sekolah', '=', 'biaya_sekolah.id_biaya_sekolah');
                                $join->whereNull('detail_biaya.deleted_at');
                            })
                            ->where('kelompok_biaya.id_kelompok_biaya', $input->kelompok_biaya)
                            ->get()
                            ->pluck('id_kelompok_biaya_internal')->unique()->all();
            
            $list_data = $list_data->get()->whereIn('kelompok_biaya_internal.id_kelompok_biaya_internal', $kelBiaya);
        }
        

        return Datatables::of($list_data)
                ->addColumn('nm_biaya_internal', function($item){
                    return $item->nm_kelompok_biaya_internal." (".$item->nm_biaya.")";
                })
                ->addColumn('besar_biaya', function($item){
                    return "Rp".number_format($item->besar_biaya);
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_detail_biaya_internal
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionDetailBiayaInternal(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelompok_biaya_internal' => 'required',
            'nm_detail_biaya_internal' => 'required',
            'besar_biaya' => 'required'
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

                $detailBiayaInternal                                = new DetailBiayaInternal;
                $detailBiayaInternal->id_detail_biaya_internal      = $id;
                $detailBiayaInternal->id_kelompok_biaya_internal    = $input->id_kelompok_biaya_internal;
                $detailBiayaInternal->nm_detail_biaya_internal      = $input->nm_detail_biaya_internal;
                $detailBiayaInternal->besar_biaya                   = $input->besar_biaya;
                $detailBiayaInternal->created_by                    = $input->auth_data->pengguna->id_pengguna;
                $detailBiayaInternal->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/detail-biaya-internal',
                    'message' => 'Save Detail Biaya Internal successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $detailBiayaInternal                                = DetailBiayaInternal::find($id);
                $detailBiayaInternal->id_kelompok_biaya_internal    = $input->id_kelompok_biaya_internal;
                $detailBiayaInternal->nm_detail_biaya_internal      = $input->nm_detail_biaya_internal;
                $detailBiayaInternal->besar_biaya                   = $input->besar_biaya;
                $detailBiayaInternal->updated_by                    = $input->auth_data->pengguna->id_pengguna;
                $detailBiayaInternal->updated_at                    = $now;
                $detailBiayaInternal->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/detail-biaya-internal',
                    'message' => 'Update Detail Biaya Internal successfully'
                ];
            }
            elseif($mode == 'delete'){
                // make object to find id
                $detailBiayaInternal               = DetailBiayaInternal::find($id);
                $detailBiayaInternal->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $detailBiayaInternal->save();

                $detailBiayaInternal->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Detail Biaya Internal successfully'
                ];
            }
        }
    }


}