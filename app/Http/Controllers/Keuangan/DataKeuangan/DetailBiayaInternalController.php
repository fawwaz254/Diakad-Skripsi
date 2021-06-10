<?php

namespace App\Http\Controllers\Keuangan\DataKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\DetailBiaya;
use App\Models\DetailBiayaInternal as DetailBiayaInternal;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;
use App\Models\KelompokBiaya;
use App\Models\KelompokBiayaInternal;
use Illuminate\Support\Facades\Validator;

class DetailBiayaInternalController extends BaseController{

    public function viewDetailBiayaInternal2(Request $request,$id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('keuangan/data-keuangan/biaya-sekolah/detail-biaya/detail-biaya-internal/view-detail-biaya-internal',compact('auth_data'));

    }

    public function viewDetailBiayaInternal(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelompok_biaya_internal = LibDataKeuangan::fetchDataBiayaInternal($auth_data);
        $data_kelompok_biaya = KelompokBiaya::orderBy('nm_kelompok_biaya', 'asc')->get();

    	return view('keuangan/data-keuangan/detail-biaya-internal/view-detail-biaya-internal',compact('auth_data', 'data_kelompok_biaya_internal', 'data_kelompok_biaya'));

    }

    public function addDetailBiayaInternal2(Request $request,$id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_detail_biaya_internal = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/data-keuangan/biaya-sekolah/detail-biaya/detail-biaya-internal/add-detail-biaya-internal',compact('auth_data','id_detail_biaya_internal'));

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

    public function editDetailBiayaInternal2($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_biaya_internal = LibDataKeuangan::fetchDataBiayaInternal($auth_data);

        $data_detail_biaya_internal = LibDataKeuangan::fetchDataDetailBiayaInternal($auth_data, $id);

        $detail_biaya = DetailBiaya::where('id_kelompok_biaya_internal',$data_detail_biaya_internal->id_kelompok_biaya_internal)->first();

        return view('keuangan/data-keuangan/biaya-sekolah/detail-biaya/detail-biaya-internal/edit-detail-biaya-internal',compact('auth_data','data_biaya_internal','data_detail_biaya_internal','detail_biaya'));

    }

    public function editDetailBiayaInternal($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_biaya_internal = LibDataKeuangan::fetchDataBiayaInternal($auth_data);

        $data_detail_biaya_internal = LibDataKeuangan::fetchDataDetailBiayaInternal($auth_data, $id);

        return view('keuangan/data-keuangan/detail-biaya-internal/edit-detail-biaya-internal',compact('auth_data','data_biaya_internal','data_detail_biaya_internal'));

    }

    public function datatablesDetailBiayaInternal2(Request $request,$id){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataKeuangan::fetchDataDetailBiayaInternal($auth_data, null, "1");

        $detail_biaya = DetailBiaya::find($id);

        if($detail_biaya->id_kelompok_biaya_internal){
            $list_data = $list_data->where('detail_biaya_internal.id_kelompok_biaya_internal',$detail_biaya->id_kelompok_biaya_internal);
        }

        else{
            $list_data = [];
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
    public function actionDetailBiayaInternal2(Request $request, $id_detail_biaya , $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_detail_biaya_internal' => 'required',
            'nm_detail_biaya_internal.*' => 'required',
            'besar_biaya' => 'required',
            'besar_biaya.*' => 'required'
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

                // get data detail biaya

                $detail_biaya = DetailBiaya::find($id_detail_biaya);

                if($detail_biaya->id_kelompok_biaya_internal){
                    $id_kelompok_biaya_internal = $detail_biaya->id_kelompok_biaya_internal;
                }

                else{

                    $kelompok_biaya_internal = new KelompokBiayaInternal;
                    $kelompok_biaya_internal->id_kelompok_biaya_internal = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $kelompok_biaya_internal->id_biaya = $detail_biaya->id_biaya;
                    $kelompok_biaya_internal->save();

                    $id_kelompok_biaya_internal = $kelompok_biaya_internal->id_kelompok_biaya_internal;

                    // update table detail biaya

                    $detail_biaya->id_kelompok_biaya_internal = $id_kelompok_biaya_internal;
                    $detail_biaya->save();

                }

                $nm_detail_biaya_internal = $request->nm_detail_biaya_internal;
                $besar_biaya = $request->besar_biaya;

                $besar_biaya_input = 0;

                foreach ($nm_detail_biaya_internal as $key => $value) {
                        
                    $besar_biaya_input += $besar_biaya[$key];

                }

                // cek besar biaya di detail biaya internal yang sudah ada 

                $detail_biaya_internal = DetailBiayaInternal::where('id_kelompok_biaya_internal',$id_kelompok_biaya_internal)->sum('besar_biaya');

                if($detail_biaya_internal){
                    $besar_biaya_input += $detail_biaya_internal;
                }

                if($besar_biaya_input > $detail_biaya->besar_biaya){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'jumlah besar biaya tidak boleh melebihi '.$detail_biaya->besar_biaya
                    ];  
                }

                foreach ($nm_detail_biaya_internal as $key => $value) {
                        
                    $detailBiayaInternal                                = new DetailBiayaInternal;
                    $detailBiayaInternal->id_detail_biaya_internal      =  $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $detailBiayaInternal->id_kelompok_biaya_internal    = $id_kelompok_biaya_internal;
                    $detailBiayaInternal->nm_detail_biaya_internal      = $value;
                    $detailBiayaInternal->besar_biaya                   = $besar_biaya[$key];
                    $detailBiayaInternal->created_by                    = $input->auth_data->pengguna->id_pengguna;
                    $detailBiayaInternal->save();

                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/biaya-sekolah/detail-biaya/detail-biaya-internal/'.$id_detail_biaya,
                    'message' => 'Save Detail Biaya Internal successfully'
                ];

            }
            elseif($mode == 'edit'){
                
                $detail_biaya = DetailBiaya::find($id_detail_biaya);
                $data_detail_biaya_internal = DetailBiayaInternal::where('id_kelompok_biaya_internal',$detail_biaya->id_kelompok_biaya_internal)
                                                                    ->where('id_detail_biaya_internal','!=',$id)
                                                                    ->sum('besar_biaya');

                if(($data_detail_biaya_internal + $input->besar_biaya) > $detail_biaya->besar_biaya){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'jumlah besar biaya tidak boleh melebihi '.$detail_biaya->besar_biaya
                    ];  
                }

                $detailBiayaInternal                                = DetailBiayaInternal::find($id);
                //$detailBiayaInternal->id_kelompok_biaya_internal    = $input->id_kelompok_biaya_internal;
                $detailBiayaInternal->nm_detail_biaya_internal      = $input->nm_detail_biaya_internal;
                $detailBiayaInternal->besar_biaya                   = $input->besar_biaya;
                $detailBiayaInternal->updated_by                    = $input->auth_data->pengguna->id_pengguna;
                $detailBiayaInternal->updated_at                    = $now;
                $detailBiayaInternal->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/biaya-sekolah/detail-biaya/detail-biaya-internal/'.$id_detail_biaya,
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