<?php

namespace App\Http\Controllers\Keuangan\PemasukanSekolah;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PemasukanBiayaSubkategori as PemasukanBiayaSubkategori;
use App\Models\PemasukanBiaya as PemasukanBiaya;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class SubkategoriPemasukanController extends BaseController{

    public function viewSubkategoriPemasukan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('keuangan/pemasukan-sekolah/subkategori-pemasukan/view-subkategori-pemasukan',compact('auth_data'));

    }

    public function addSubkategoriPemasukan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_kategori_pemasukan = LibDataKeuangan::fetchDataKategoriPemasukan($auth_data);

        $id_pemasukan_biaya_subkategori = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/pemasukan-sekolah/subkategori-pemasukan/add-subkategori-pemasukan',compact('auth_data','data_kategori_pemasukan','id_pemasukan_biaya_subkategori'));

    }

    public function editSubkategoriPemasukan($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori_pemasukan = LibDataKeuangan::fetchDataKategoriPemasukan($auth_data);

        $data_subkategori_pemasukan = LibDataKeuangan::fetchDataSubkategoriPemasukan($auth_data, $id);

        return view('keuangan/pemasukan-sekolah/subkategori-pemasukan/edit-subkategori-pemasukan',compact('auth_data','data_kategori_pemasukan','data_subkategori_pemasukan'));

    }

    public function datatablesSubkategoriPemasukan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataSubkategoriPemasukan($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_pemasukan_biaya_subkategori
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionSubkategoriPemasukan(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_pemasukan_biaya_kategori' => 'required',
            'nm_pemasukan_biaya_subkategori' => 'required',
            'keterangan_pemasukan_biaya_subkategori' => 'required'
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

                $subKategoriPemasukan                                             = new PemasukanBiayaSubkategori;
                $subKategoriPemasukan->id_pemasukan_biaya_subkategori           = $id;
                $subKategoriPemasukan->id_pemasukan_biaya_kategori              = $input->id_pemasukan_biaya_kategori;
                $subKategoriPemasukan->nm_pemasukan_biaya_subkategori           = $input->nm_pemasukan_biaya_subkategori;
                $subKategoriPemasukan->keterangan_pemasukan_biaya_subkategori   = $input->keterangan_pemasukan_biaya_subkategori;
                $subKategoriPemasukan->created_by                                 = $input->auth_data->pengguna->id_pengguna;
                $subKategoriPemasukan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pemasukan-sekolah/subkategori-pemasukan',
                    'message' => 'Save Sub-Kategori Pemasukan successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $subKategoriPemasukan                                             = PemasukanBiayaSubkategori::find($id);
                $subKategoriPemasukan->id_pemasukan_biaya_kategori              = $input->id_pemasukan_biaya_kategori;
                $subKategoriPemasukan->nm_pemasukan_biaya_subkategori           = $input->nm_pemasukan_biaya_subkategori;
                $subKategoriPemasukan->keterangan_pemasukan_biaya_subkategori   = $input->keterangan_pemasukan_biaya_subkategori;
                $subKategoriPemasukan->updated_by                                 = $input->auth_data->pengguna->id_pengguna;
                $subKategoriPemasukan->updated_at                                 = $now;
                $subKategoriPemasukan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pemasukan-sekolah/subkategori-pemasukan',
                    'message' => 'Update Sub-Kategori Pemasukan successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($pemasukanBiaya = PemasukanBiaya::where('id_pemasukan_biaya_subkategori',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Sub-Kategori Pemasukan'
                    ]; 
                }
                else{
                    // make object to find id
                    $subKategoriPemasukan               = PemasukanBiayaSubkategori::find($id);
                    $subKategoriPemasukan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $subKategoriPemasukan->save();

                    $subKategoriPemasukan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Sub-Kategori Pemasukan successfully'
                    ];
                }
            }
        }
    }


}