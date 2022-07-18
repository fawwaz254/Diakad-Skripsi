<?php

namespace App\Http\Controllers\Keuangan\PengeluaranSekolah;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PengeluaranBiayaSubkategori as PengeluaranBiayaSubkategori;
use App\Models\PengeluaranBiaya as PengeluaranBiaya;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class SubkategoriPengeluaranController extends BaseController{

    public function viewSubkategoriPengeluaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('keuangan/pengeluaran-sekolah/subkategori-pengeluaran/view-subkategori-pengeluaran',compact('auth_data'));

    }

    public function addSubkategoriPengeluaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_kategori_pengeluaran = LibDataKeuangan::fetchDataKategoriPengeluaran($auth_data);

        $id_pengeluaran_biaya_subkategori = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/pengeluaran-sekolah/subkategori-pengeluaran/add-subkategori-pengeluaran',compact('auth_data','data_kategori_pengeluaran','id_pengeluaran_biaya_subkategori'));

    }

    public function editSubkategoriPengeluaran($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori_pengeluaran = LibDataKeuangan::fetchDataKategoriPengeluaran($auth_data);

        $data_subkategori_pengeluaran = LibDataKeuangan::fetchDataSubkategoriPengeluaran($auth_data, $id);

        return view('keuangan/pengeluaran-sekolah/subkategori-pengeluaran/edit-subkategori-pengeluaran',compact('auth_data','data_kategori_pengeluaran','data_subkategori_pengeluaran'));

    }

    public function datatablesSubkategoriPengeluaran(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataSubkategoriPengeluaran($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_pengeluaran_biaya_subkategori
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionSubkategoriPengeluaran(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_pengeluaran_biaya_kategori' => 'required',
            'nm_pengeluaran_biaya_subkategori' => 'required',
            'keterangan_pengeluaran_biaya_subkategori' => 'required'
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

                $subKategoriPengeluaran                                             = new PengeluaranBiayaSubkategori;
                $subKategoriPengeluaran->id_pengeluaran_biaya_subkategori           = $id;
                $subKategoriPengeluaran->id_pengeluaran_biaya_kategori              = $input->id_pengeluaran_biaya_kategori;
                $subKategoriPengeluaran->nm_pengeluaran_biaya_subkategori           = $input->nm_pengeluaran_biaya_subkategori;
                $subKategoriPengeluaran->keterangan_pengeluaran_biaya_subkategori   = $input->keterangan_pengeluaran_biaya_subkategori;
                $subKategoriPengeluaran->created_by                                 = $input->auth_data->pengguna->id_pengguna;
                $subKategoriPengeluaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pengeluaran-sekolah/subkategori-pengeluaran',
                    'message' => 'Save Sub-Kategori Pengeluaran successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $subKategoriPengeluaran                                             = PengeluaranBiayaSubkategori::find($id);
                $subKategoriPengeluaran->id_pengeluaran_biaya_kategori              = $input->id_pengeluaran_biaya_kategori;
                $subKategoriPengeluaran->nm_pengeluaran_biaya_subkategori           = $input->nm_pengeluaran_biaya_subkategori;
                $subKategoriPengeluaran->keterangan_pengeluaran_biaya_subkategori   = $input->keterangan_pengeluaran_biaya_subkategori;
                $subKategoriPengeluaran->updated_by                                 = $input->auth_data->pengguna->id_pengguna;
                $subKategoriPengeluaran->updated_at                                 = $now;
                $subKategoriPengeluaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pengeluaran-sekolah/subkategori-pengeluaran',
                    'message' => 'Update Sub-Kategori Pengeluaran successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($pengeluaranBiaya = PengeluaranBiaya::where('id_pengeluaran_biaya_subkategori',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Sub-Kategori Pengeluaran'
                    ]; 
                }
                else{
                    // make object to find id
                    $subKategoriPengeluaran               = PengeluaranBiayaSubkategori::find($id);
                    $subKategoriPengeluaran->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $subKategoriPengeluaran->save();

                    $subKategoriPengeluaran->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Sub-Kategori Pengeluaran successfully'
                    ];
                }
            }
        }
    }


}