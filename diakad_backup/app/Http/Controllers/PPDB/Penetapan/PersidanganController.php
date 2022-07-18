<?php

namespace App\Http\Controllers\PPDB\Penetapan;;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Penetapan as Penetapan;
use App\Models\Penerimaan as Penerimaan;
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

class PersidanganController extends BaseController {

    public function viewPersidangan(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_semester_tahun = LibDataAkademik::fetchDataTahunSemester($auth_data);

        $data_tahun_penetapan = Penetapan::distinct()->get([DB::raw('YEAR(tgl_penetapan) as tgl_penetapan')]); 
        //dd($data_tahun_penetapan);
    	return view('ppdb/penetapan/persidangan/view-persidangan',compact('auth_data','data_tahun_penetapan'));
    }

    public function addPenetapan(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $id_penetapan = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
        return view('ppdb/penetapan/data-penetapan/add-penetapan',compact('auth_data','id_penetapan'));
    }

    public function editPersidangan($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_penetapan = Penetapan::where('id_penetapan',$id)->first();
       // dd($data_penetapan);
        return view('ppdb/penetapan/persidangan/edit-persidangan',compact('auth_data','data_penetapan'));
    }
    public function viewPersidanganGelombang($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_penetapan = Penetapan::where('id_penetapan',$id)->first();
        $data_penetapan_penerimaan = PenetapanPenerimaan::where('id_penetapan',$id)->first();
        $data_penerimaan = Penerimaan::where('id_penerimaan',$data_penetapan_penerimaan->id_penerimaan)->first();
        //$data_jurusan = LibPenerimaan::fetchDataJurusanDetailPendaftaran($auth_data, $id);
        //dd('data_jurusan');
        
        return view('ppdb/penetapan/persidangan/view-persidangan-gelombang',compact('auth_data','data_penetapan', 'data_penerimaan'));
    }

    public function editPersidangan2($tahun, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('ppdb/penetapan/persidangan/view-persidangan2',compact('auth_data','tahun'));

    }

    public function viewSidangPenetapan($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_penetapan = Penetapan::where('id_penetapan',$id)->first();
        return view('ppdb/penetapan/persidangan/view-sidang-penetapan',compact('auth_data','data_penetapan'));

    }

    public function actionViewPersidangan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator  = Validator::make($request->all(), [
            'tahun_penetapan' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        }
        else{
            return [
                'status'    => 204, // SUCCESS AND LOAD CONTENT
                // mecocokkan dengan route yang namanya tahun 
                // di PersidanganController@editPersidangan2
                'path'      => 'penetapan/persidangan/tahun/'.$input->tahun_penetapan
            ];
        }
    }

    public function datatablesPersidangan(Request $request, $tahun) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Penetapan::orderBy('id_penetapan','desc')->where('tgl_penetapan','like','%'.$tahun.'%')->get();
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
    // datatable View Gelombang
    public function datatablesPersidanganViewGelombang(Request $request, $id) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data  = PenetapanPenerimaan::select('penetapan_penerimaan.id_penetapan_penerimaan', 
            'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan',
            'penerimaan.nm_semester_penerimaan','penerimaan.tahun_penerimaan')
                ->join('penerimaan','penerimaan.id_penerimaan','=','penetapan_penerimaan.id_penerimaan')
                ->join('penetapan','penetapan.id_penetapan','=','penetapan_penerimaan.id_penetapan')
                ->where('penetapan_penerimaan.id_penetapan','=',$id)
                ->orderBy('penerimaan.nm_penerimaan', 'asc')
                ->get();  
        //dd($list_data);      
        return Datatables::of($list_data)
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

            // ACTION ADD
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

}