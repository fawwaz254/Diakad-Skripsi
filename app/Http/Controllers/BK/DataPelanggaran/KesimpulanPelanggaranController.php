<?php

namespace App\Http\Controllers\BK\DataPelanggaran;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KesimpulanPelanggaran as KesimpulanPelanggaran;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\BimbinganKonseling\LibDataPelanggaran;

use Auth;
use DB;
use Session;
use Validator;

class KesimpulanPelanggaranController extends BaseController{

    public function viewKesimpulanPelanggaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('bk/data-pelanggaran/kesimpulan-pelanggaran/view-kesimpulan-pelanggaran',compact('auth_data'));

    }

    public function addKesimpulanPelanggaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_kesimpulan_pelanggaran = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('bk/data-pelanggaran/kesimpulan-pelanggaran/add-kesimpulan-pelanggaran',compact('auth_data','id_kesimpulan_pelanggaran'));

    }

    public function editKesimpulanPelanggaran($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kesimpulan_pelanggaran = LibDataPelanggaran::fetchDataKesimpulanPelanggaran($auth_data, $id);

        return view('bk/data-pelanggaran/kesimpulan-pelanggaran/edit-kesimpulan-pelanggaran',compact('auth_data','data_kesimpulan_pelanggaran'));

    }

    public function datatablesKesimpulanPelanggaran(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataPelanggaran::fetchDataKesimpulanPelanggaran($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kesimpulan_pelanggaran
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionKesimpulanPelanggaran(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_kesimpulan_pelanggaran' => 'required',
            'poin_bawah_kesimpulan_pelanggaran' => 'required',
            'poin_atas_kesimpulan_pelanggaran' => 'required',
            'deskripsi_kesimpulan_pelanggaran_1' => 'required'
            /*'deskripsi_kesimpulan_pelanggaran_2' => 'required',
            'deskripsi_kesimpulan_pelanggaran_3' => 'required'*/
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

                $kesimpulanPelanggaran                                      = new KesimpulanPelanggaran;
                $kesimpulanPelanggaran->id_kesimpulan_pelanggaran           = $id;
                $kesimpulanPelanggaran->nm_kesimpulan_pelanggaran           = $input->nm_kesimpulan_pelanggaran;
                $kesimpulanPelanggaran->poin_bawah_kesimpulan_pelanggaran   = $input->poin_bawah_kesimpulan_pelanggaran;
                $kesimpulanPelanggaran->poin_atas_kesimpulan_pelanggaran    = $input->poin_atas_kesimpulan_pelanggaran;
                $kesimpulanPelanggaran->deskripsi_kesimpulan_pelanggaran_1  = $input->deskripsi_kesimpulan_pelanggaran_1;
                $kesimpulanPelanggaran->deskripsi_kesimpulan_pelanggaran_2  = $input->deskripsi_kesimpulan_pelanggaran_2;
                $kesimpulanPelanggaran->deskripsi_kesimpulan_pelanggaran_3  = $input->deskripsi_kesimpulan_pelanggaran_3;
                $kesimpulanPelanggaran->id_sekolah                          = $input->auth_data->pengguna->id_sekolah;
                $kesimpulanPelanggaran->created_by                          = $input->auth_data->pengguna->id_pengguna;
                $kesimpulanPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-pelanggaran/kesimpulan-pelanggaran',
                    'message' => 'Save Kesimpulan Pelanggaran successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $kesimpulanPelanggaran                                        = KesimpulanPelanggaran::find($id);
                $kesimpulanPelanggaran->nm_kesimpulan_pelanggaran           = $input->nm_kesimpulan_pelanggaran;
                $kesimpulanPelanggaran->poin_bawah_kesimpulan_pelanggaran   = $input->poin_bawah_kesimpulan_pelanggaran;
                $kesimpulanPelanggaran->poin_atas_kesimpulan_pelanggaran    = $input->poin_atas_kesimpulan_pelanggaran;
                $kesimpulanPelanggaran->deskripsi_kesimpulan_pelanggaran_1  = $input->deskripsi_kesimpulan_pelanggaran_1;
                $kesimpulanPelanggaran->deskripsi_kesimpulan_pelanggaran_2  = $input->deskripsi_kesimpulan_pelanggaran_2;
                $kesimpulanPelanggaran->deskripsi_kesimpulan_pelanggaran_3  = $input->deskripsi_kesimpulan_pelanggaran_3;
                $kesimpulanPelanggaran->updated_by                          = $input->auth_data->pengguna->id_pengguna;
                $kesimpulanPelanggaran->updated_at                          = $now;
                $kesimpulanPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-pelanggaran/kesimpulan-pelanggaran',
                    'message' => 'Save Kesimpulan Pelanggaran successfully'
                ];
            }
            elseif($mode == 'delete'){
                // make object to find id
                $kesimpulanPelanggaran               = KesimpulanPelanggaran::find($id);
                $kesimpulanPelanggaran->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $kesimpulanPelanggaran->save();

                $kesimpulanPelanggaran->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Kesimpulan Pelanggaran successfully'
                ];
            }
        }
    }


}