<?php

namespace App\Http\Controllers\BK\PenangananSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\JenisTindakan as JenisTindakan;
use App\Models\TindakanPelanggaran as TindakanPelanggaran;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\BimbinganKonseling\LibDataPelanggaran;

use Auth;
use DB;
use Session;
use Validator;

class JenisTindakanController extends BaseController{

    public function viewJenisTindakan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('bk/penanganan-siswa/jenis-tindakan/view-jenis-tindakan',compact('auth_data'));

    }

    public function addJenisTindakan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_jenis_tindakan = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('bk/penanganan-siswa/jenis-tindakan/add-jenis-tindakan',compact('auth_data','id_jenis_tindakan'));

    }

    public function editJenisTindakan($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_tindakan = LibDataPelanggaran::fetchDataJenisTindakan($auth_data, $id);

        return view('bk/penanganan-siswa/jenis-tindakan/edit-jenis-tindakan',compact('auth_data','data_jenis_tindakan'));

    }

    public function datatablesJenisTindakan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataPelanggaran::fetchDataJenisTindakan($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_jenis_tindakan
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionJenisTindakan(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_jenis_tindakan' => 'required',
            'keterangan_jenis_tindakan' => 'required'
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

                $jenisTindakan                              = new JenisTindakan;
                $jenisTindakan->id_jenis_tindakan           = $id;
                $jenisTindakan->nm_jenis_tindakan           = $input->nm_jenis_tindakan;
                $jenisTindakan->keterangan_jenis_tindakan   = $input->keterangan_jenis_tindakan;
                $jenisTindakan->id_sekolah                  = $input->auth_data->pengguna->id_sekolah;
                $jenisTindakan->created_by                  = $input->auth_data->pengguna->id_pengguna;
                $jenisTindakan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penanganan-siswa/jenis-tindakan',
                    'message' => 'Save Jenis Tindakan successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $jenisTindakan                              = JenisTindakan::find($id);
                $jenisTindakan->nm_jenis_tindakan           = $input->nm_jenis_tindakan;
                $jenisTindakan->keterangan_jenis_tindakan   = $input->keterangan_jenis_tindakan;
                $jenisTindakan->updated_by                  = $input->auth_data->pengguna->id_pengguna;
                $jenisTindakan->updated_at                  = $now;
                $jenisTindakan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penanganan-siswa/jenis-tindakan',
                    'message' => 'Update Jenis Tindakan successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($tindakanPelanggaran = TindakanPelanggaran::where('id_jenis_tindakan',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Jenis Tindakan'
                    ]; 
                }
                else{
                    // make object to find id
                    $jenisTindakan               = JenisTindakan::find($id);
                    $jenisTindakan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $jenisTindakan->save();

                    $jenisTindakan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jenis Tindakan successfully'
                    ];
                }
            }
        }
    }


}