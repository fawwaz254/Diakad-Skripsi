<?php

namespace App\Http\Controllers\SaranaPrasarana\DataSarprasRuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Ruangan;
use App\Models\KerusakanRuangan;
use App\Models\KondisiRuangan as KondisiRuangan;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;
use Excel;

class KondisiRuanganController extends BaseController{

    public function viewKondisiRuangan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('sarana-prasarana/data-sarpras-ruangan/kondisi-ruangan/view-kondisi-ruangan',compact('auth_data'));

    }

    public function addKondisiRuangan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data);

        $data_kerusakan_ruangan = LibDataSarpras::fetchDataKerusakanRuangan($auth_data);

        $id_kondisi_ruangan = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('sarana-prasarana/data-sarpras-ruangan/kondisi-ruangan/add-kondisi-ruangan',compact('auth_data','data_ruangan','data_kerusakan_ruangan','id_kondisi_ruangan'));

    }

    public function editKondisiRuangan($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data);

        $data_kerusakan_ruangan = LibDataSarpras::fetchDataKerusakanRuangan($auth_data);

        $data_kondisi_ruangan = LibDataSarpras::fetchDataKondisiRuangan($auth_data, $id);

        return view('sarana-prasarana/data-sarpras-ruangan/kondisi-ruangan/edit-kondisi-ruangan',compact('auth_data','data_ruangan','data_kerusakan_ruangan','data_kondisi_ruangan'));

    }

    public function datatablesKondisiRuangan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataSarpras::fetchDataKondisiRuangan($auth_data);

        return Datatables::of($list_data)
                ->addColumn('nm_ruangan', function($item){
                    if($item->is_aktif == 1) {
                        return $item->nm_ruangan." - ".$item->nm_gedung." (Aktif)";
                    }
                    else {
                        return $item->nm_ruangan." - ".$item->nm_gedung." (Non-Aktif)";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kondisi_ruangan
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionKondisiRuangan(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_ruangan'                    => 'required',
            'id_kerusakan_ruangan'          => 'required',
            'persentase_kerusakan_ruangan'  => 'required',
            'keterangan_kerusakan_ruangan'  => 'required'
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
                if($kondisiRuangan = KondisiRuangan::where('id_ruangan',$input->id_ruangan)->where('id_kerusakan_ruangan',$input->id_kerusakan_ruangan)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Kerusakan Ruangan Sudah Ada!'
                    ]; 
                }
                else{
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $kondisiRuangan                                 = new KondisiRuangan;
                    $kondisiRuangan->id_kondisi_ruangan             = $id;
                    $kondisiRuangan->id_ruangan                     = $input->id_ruangan;
                    $kondisiRuangan->id_kerusakan_ruangan           = $input->id_kerusakan_ruangan;
                    $kondisiRuangan->persentase_kerusakan_ruangan   = $input->persentase_kerusakan_ruangan;
                    $kondisiRuangan->keterangan_kerusakan_ruangan   = $input->keterangan_kerusakan_ruangan;
                    $kondisiRuangan->created_by                     = $input->auth_data->pengguna->id_pengguna;
                    $kondisiRuangan->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'data-sarpras-ruangan/kondisi-ruangan',
                        'message' => 'Save Kondisi Ruangan successfully'
                    ];
                }
            }
            elseif($mode == 'edit'){
                if($kondisiRuangan = KondisiRuangan::where('id_ruangan',$input->id_ruangan)->where('id_kerusakan_ruangan',$input->id_kerusakan_ruangan)->first()) {
                    if($kondisiRuangan->id_kondisi_ruangan != $id) {
                        return [
                            'status' => 300, // SUCCESS AND LOAD TABLE
                            'message' => 'Kerusakan Ruangan Sudah Ada!'
                        ]; 
                    }
                    else {
                        // make object to find id
                        $kondisiRuangan                                 = KondisiRuangan::find($id);
                        $kondisiRuangan->id_ruangan                     = $input->id_ruangan;
                        $kondisiRuangan->id_kerusakan_ruangan           = $input->id_kerusakan_ruangan;
                        $kondisiRuangan->persentase_kerusakan_ruangan   = $input->persentase_kerusakan_ruangan;
                        $kondisiRuangan->keterangan_kerusakan_ruangan   = $input->keterangan_kerusakan_ruangan;
                        $kondisiRuangan->updated_by                     = $input->auth_data->pengguna->id_pengguna;
                        $kondisiRuangan->updated_at                     = $now;
                        $kondisiRuangan->save();

                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'data-sarpras-ruangan/kondisi-ruangan',
                            'message' => 'Update Kondisi Ruangan successfully'
                        ];
                    }
                }
            }
            elseif($mode == 'delete'){
                // make object to find id
                $kondisiRuangan               = KondisiRuangan::find($id);
                $kondisiRuangan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $kondisiRuangan->save();

                $kondisiRuangan->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Kondisi Ruangan successfully'
                ];
            }
        }
    }

    public function importExcel(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      return view('sarana-prasarana/data-sarpras-ruangan/kondisi-ruangan/import-excel',compact('auth_data'));

    }

    public function importExcelAction(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
                'file-excel' => 'required',
        ]);
        
        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        else{

            if($request->hasFile('file-excel')){

                $path = $request->file('file-excel')->getRealPath();
                $data = Excel::load($path)->get();

                if($data->count()){

                    DB::beginTransaction();
                    
                    try {

                        foreach ($data as $key => $value) {

                            if(empty($value->nama_ruangan)){
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data inventaris gagal, ada nama ruangan yang kosong'
                                ];
                            }

                            $check_ruangan = Ruangan::where('nm_ruangan',ucwords($value->nama_ruangan))->first();

                            if(!$check_ruangan){
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data inventaris gagal, ada nama ruangan yang tidak ditemukan dalam data master ruangan'
                                ];
                            }

                            if(empty($value->nama_kerusakan)){
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data inventaris gagal, ada nama kerusakan yang kosong'
                                ];
                            }

                            $check_kerusakan = KerusakanRuangan::where('nm_kerusakan_ruangan',($value->nama_kerusakan))->first();
                            
                            if(!$check_kerusakan){
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data inventaris gagal, ada nama kerusakan ruangan yang tidak ditemukan dalam data master kerusakan ruangan'
                                ];
                            }

                            if(empty($value->presentase_kerusakan) && ($value->presentase_kerusakan== 0)){
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data inventaris gagal, ada presentase kerusakan yang kosong'
                                ];
                            }

                            if(empty($value->keterangan) && ($value->presentase_kerusakan== 0)){
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data inventaris gagal, ada keterangan yang kosong'
                                ];
                            }

                            $data                                 = new KondisiRuangan;
                            $data->id_kondisi_ruangan             = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $data->id_ruangan                     = $check_ruangan->id_ruangan;
                            $data->id_kerusakan_ruangan           = $check_kerusakan->id_kerusakan_ruangan;
                            $data->persentase_kerusakan_ruangan   = $value->presentase_kerusakan;
                            $data->keterangan_kerusakan_ruangan   = $value->keterangan;
                            $data->created_by                     = $input->auth_data->pengguna->id_pengguna;
                            $data->save();

                        }

                        DB::commit();

                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'data-sarpras-ruangan/kondisi-ruangan',
                            'message' => 'Import Pemilik Sarpras Successfully'
                        ];

                    }

                    catch (\Exception $e) {

                        DB::rollback();
                
                        return [
                            'status'    => 203, // GAGAL
                            'message'       => (env('APP_DEBUG', 'true') == 'true')? $e->getMessage() : 'Operation error'
                        ];
                    } 

                }

                else{

                    return [
                        'status'    => 300, // FAILED
                        'message'   => "File excel anda kosong"
                    ];

                }

            }

            else{
                return [
                    'status'    => 300, // FAILED
                    'message'   => "File Excel tidak ditemukan"
                ];
            }

        }

    }

}