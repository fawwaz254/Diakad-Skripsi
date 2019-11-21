<?php

namespace App\Http\Controllers\PPDB\Penetapan;;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Penetapan as Penetapan;
use App\Models\PenetapanPenerimaan as PenetapanPenerimaan;

use App\Models\CalonSiswaBaru as CalonSiswaBaru;
use App\Models\Semester as Semester;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;

use Auth;
use DB;
use Session;
use Validator;

class PenetapanController extends BaseController {

    public function viewPenetapan(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('ppdb/penetapan/data-penetapan/view-penetapan',compact('auth_data'));
    }

    public function viewPenetapanPenerimaan($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_penetapan_penerimaan = DB::table('penetapan')->where('id_penetapan',$id)->first();
        return view('ppdb/penetapan/data-penetapan/view-penetapan-penerimaan',compact('auth_data','data_penetapan_penerimaan'));
    }

    public function addPenetapan(Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $id_penetapan = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
        return view('ppdb/penetapan/data-penetapan/add-penetapan',compact('auth_data','id_penetapan'));
    }

    public function addPenetapanPenerimaan($id, Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        }); 
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $penetapan = Penetapan::find($id);
        return view('ppdb/penetapan/data-penetapan/add-penetapan-penerimaan',compact('auth_data','penetapan','penerimaan','grup_penerimaan_tahun'));
    }

    public function editPenetapan($id, Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_penetapan = DB::table('penetapan')->where('id_penetapan',$id)->first();
        return view('ppdb/penetapan/data-penetapan/edit-penetapan',compact('auth_data','data_penetapan'));
    }

    public function editPenetapanPenerimaan($id, Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_penetapan = DB::table('penetapan')->where('id_penetapan',$id)->first();
        return view('ppdb/penetapan/data-penetapan/edit-penetapan-penerimaan',compact('auth_data','data_penetapan'));
    }

    public function datatablesPenetapan(Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Penetapan::orderBy('id_penetapan','desc')->get();

        return Datatables::of($list_data)
                ->addColumn('nm_penetapan', function($item) {
                    if( ! empty($item->nm_penetapan)){
                        return $item->nm_penetapan;
                    }
                    else{
                        return "-";
                    }
                })
                ->addColumn('periode', function($item) {
                    if( ! empty($item->periode)){
                        return $item->periode;
                    }
                    else{
                        return "-";
                    }
                })                
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_penetapan
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesPenetapanPenerimaan($id, Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data  = PenetapanPenerimaan::select('penetapan_penerimaan.id_penetapan_penerimaan', 
            'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan',
            'penerimaan.nm_semester_penerimaan','penerimaan.tahun_penerimaan')
                ->join('penerimaan','penerimaan.id_penerimaan','=','penetapan_penerimaan.id_penerimaan')
                ->join('penetapan','penetapan.id_penetapan','=','penetapan_penerimaan.id_penetapan')
                ->where('penetapan.id_penetapan','=',$id)
                ->orderBy('penerimaan.nm_penerimaan', 'asc')
                ->get();

        return Datatables::of($list_data)
                ->addColumn('tahun_penerimaan', function($item) {
                    if( ! empty($item->tahun_penerimaan)){
                        return $item->tahun_penerimaan;
                    }
                    else{
                        return "-";
                    }
                })
                ->addColumn('nm_penerimaan', function($item) {
                    if( ! empty($item->nm_penerimaan)){
                        return $item->nm_penerimaan;
                    }
                    else{
                        return "-";
                    }
                })      
                ->addColumn('gelombang_penerimaan', function($item) {
                    if( ! empty($item->gelombang_penerimaan)){
                        return $item->gelombang_penerimaan;
                    }
                    else{
                        return "-";
                    }
                }) 
                ->addColumn('nm_semester_penerimaan', function($item) {
                    if( ! empty($item->nm_semester_penerimaan)){
                        return $item->nm_semester_penerimaan;
                    }
                    else{
                        return "-";
                    }
                }) 
                ->addColumn('is_aktif', function($item) {
                    if( ! empty($item->is_aktif)){
                        return $item->is_aktif;
                    }
                    else{
                        return "-";
                    }
                }) 

                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_penetapan
                    );
                    return $data;
                })
                ->make(true);
    }


    // Action POST
    public function actionPenetapan(Request $request, $mode, $id = null){
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'nm_penetapan'          => 'required',
            'nomor_sk_penetapan'    => 'required',
            'tgl_penetapan'         => 'required',
            'periode'               => 'required',
            'is_aktif'              => 'required'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                
                $penetapan                            = new Penetapan;
                $penetapan->id_penetapan              = $id;
                
                $penetapan->nm_penetapan              = $input->nm_penetapan;
                $penetapan->nomor_sk_penetapan       = $input->nomor_sk_penetapan;
                
                $penetapan->tgl_penetapan              = date_format(date_create($input->tgl_penetapan),"Y-m-d");
                $penetapan->periode                     = $input->periode;
                $penetapan->is_aktif                   = $input->is_aktif;
                $penetapan->id_sekolah                 = $input->auth_data->pengguna->id_sekolah;
                $penetapan->created_by                 = $input->auth_data->pengguna->id_pengguna;
                $penetapan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penetapan/data-penetapan',
                    'message' => 'Save Penetapan successfully'
                ];
            }
            elseif($mode == 'edit') {
                // make object to find id
                $penetapan                             = Penetapan::find($id);

                $penetapan->id_penetapan              = $id;
                
                $penetapan->nm_penetapan              = $input->nm_penetapan;
                $penetapan->nomor_sk_penetapan       = $input->nomor_sk_penetapan;
                
                $penetapan->tgl_penetapan              = date_format(date_create($input->tgl_penetapan),"Y-m-d");
                $penetapan->periode                     = $input->periode;
                $penetapan->is_aktif                   = $input->is_aktif;
                $penetapan->id_sekolah                 = $input->auth_data->pengguna->id_sekolah;
                
                $penetapan->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                $penetapan->updated_at                 = $now;
                $penetapan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penetapan/data-penetapan',
                    'message' => 'Update Penetapan successfully'
                ];
            }
            elseif($mode == 'delete') {
                
                    // make object to find id
                    $penetapan               = Penetapan::find($id);
                    $penetapan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $penetapan->save();
                    $penetapan->delete();
                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Penetapan successfully'
                    ];
                
            }
        }
    }

    // action penetapan penerimaan
    public function actionPenetapanPenerimaan(Request $request, $mode, $id = null){
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'id_penerimaan'          => 'required'           
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                
                $penetapan_penerimaan                           = new PenetapanPenerimaan;
                $penetapan_penerimaan->id_penetapan_penerimaan  = $id;
                $penetapan_penerimaan->id_penetapan             = $input->id_penetapan;
                $penetapan_penerimaan->id_penerimaan            = $input->id_penerimaan;

                $penetapan_penerimaan->created_by               = $input->auth_data->pengguna->id_pengguna;
                $penetapan_penerimaan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penetapan/data-penetapan/view-penetapan-penerimaan/'.$input->id_penetapan,
                    'message' => 'Save Penetapan Penerimaan successfully'
                ];
            }
            elseif($mode == 'edit') {
                // make object to find id
                /*$penetapan                             = Penetapan::find($id);

                $penetapan->id_penetapan              = $id;
                
                $penetapan->nm_penetapan              = $input->nm_penetapan;
                $penetapan->nomor_sk_penetapan       = $input->nomor_sk_penetapan;
                
                $penetapan->tgl_penetapan              = date_format(date_create($input->tgl_penetapan),"Y-m-d");

                $penetapan->periode                     = $input->periode;
                $penetapan->is_aktif                   = $input->is_aktif;
                $penetapan->id_sekolah                 = $input->auth_data->pengguna->id_sekolah;
                
                $penetapan->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                $penetapan->updated_at                 = $now;
                $penetapan->save();*/

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penetapan/data-penetapan',
                    'message' => 'Update Penetapan Penerimaan successfully'
                ];
            }
            elseif($mode == 'delete') {
                
                    // make object to find id
                    $penetapan_penerimaan               = PenetapanPenerimaan::find($id);
                    $penetapan_penerimaan->id_penetapan_penerimaan  = $id;
                    $penetapan_penerimaan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $penetapan_penerimaan->save();
                    $penetapan_penerimaan->delete();
                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Penetapan Penerimaan successfully'
                    ];
                
            }
        }
    }

}