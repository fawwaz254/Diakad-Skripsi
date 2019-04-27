<?php

namespace App\Http\Controllers\Keuangan\DataKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\BiayaSekolah as BiayaSekolah;
use App\Models\DetailBiaya as DetailBiaya;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class BiayaSekolahController extends BaseController{

    public function viewBiayaSekolah(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('keuangan/data-keuangan/biaya-sekolah/view-biaya-sekolah',compact('auth_data'));

    }

    public function addBiayaSekolah(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);

        $id_biaya_sekolah = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/data-keuangan/biaya-sekolah/add-biaya-sekolah',compact('auth_data','data_kelompok_biaya','data_semester','data_jalur','id_biaya_sekolah'));

    }

    public function editBiayaSekolah($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);

        $data_biaya_sekolah = LibDataKeuangan::fetchDataBiayaSekolah($auth_data, null, $id);

        return view('keuangan/data-keuangan/biaya-sekolah/edit-biaya-sekolah',compact('auth_data','data_kelompok_biaya','data_semester','data_jalur','data_biaya_sekolah'));

    }

    public function datatablesBiayaSekolah(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataBiayaSekolah($auth_data);

        return Datatables::of($list_data)
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('jalur', function($item){
                    if( ! empty($item->nm_jalur)){
                        return $item->nm_jalur;
                    }
                    else{
                        return "-";
                    }
                })
                ->addColumn('besar_biaya_sekolah', function($item){
                    return "Rp".number_format($item->besar_biaya_sekolah);
                })
                ->addColumn('validasi_biaya_sekolah', function($item){
                    if($item->validasi_biaya_sekolah == 0){
                        return "Belum";
                    }
                    else{
                        return "Sudah";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_biaya_sekolah
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionBiayaSekolah(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelompok_biaya' => 'required',
            'id_semester' => 'required',
            //'id_jalur' => 'required',
            'besar_biaya_sekolah' => 'required',
            'validasi_biaya_sekolah' => 'required',
            'keterangan_biaya_sekolah' => 'required',
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

                $biayaSekolah                               = new BiayaSekolah;
                $biayaSekolah->id_biaya_sekolah             = $id;
                $biayaSekolah->id_kelompok_biaya            = $input->id_kelompok_biaya;
                $biayaSekolah->id_semester                  = $input->id_semester;
                if( ! empty($input->id_jalur)) {
                    $biayaSekolah->id_jalur                 = $input->id_jalur;
                }
                $biayaSekolah->besar_biaya_sekolah          = $input->besar_biaya_sekolah;
                $biayaSekolah->validasi_biaya_sekolah       = $input->validasi_biaya_sekolah;
                $biayaSekolah->keterangan_biaya_sekolah     = $input->keterangan_biaya_sekolah;
                $biayaSekolah->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $biayaSekolah->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/biaya-sekolah',
                    'message' => 'Save Biaya Sekolah successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $biayaSekolah                               = BiayaSekolah::find($id);
                $biayaSekolah->id_kelompok_biaya            = $input->id_kelompok_biaya;
                $biayaSekolah->id_semester                  = $input->id_semester;
                if( ! empty($input->id_jalur)) {
                    $biayaSekolah->id_jalur                 = $input->id_jalur;
                }
                $biayaSekolah->besar_biaya_sekolah          = $input->besar_biaya_sekolah;
                $biayaSekolah->validasi_biaya_sekolah       = $input->validasi_biaya_sekolah;
                $biayaSekolah->keterangan_biaya_sekolah     = $input->keterangan_biaya_sekolah;
                $biayaSekolah->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $biayaSekolah->updated_at                   = $now;
                $biayaSekolah->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/biaya-sekolah',
                    'message' => 'Update Biaya Sekolah successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($detailBiaya = DetailBiaya::where('id_biaya_sekolah',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Biaya Sekolah'
                    ]; 
                }
                else{
                    // make object to find id
                    $biayaSekolah               = BiayaSekolah::find($id);
                    $biayaSekolah->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $biayaSekolah->save();

                    $biayaSekolah->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Biaya Sekolah successfully'
                    ];
                }
            }
        }
    }


}