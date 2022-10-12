<?php

namespace App\Http\Controllers\Keuangan\PengeluaranSekolah;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PengeluaranBiayaKategori as PengeluaranBiayaKategori;
use App\Models\PengeluaranBiayaSubkategori as PengeluaranBiayaSubkategori;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class KategoriPengeluaranController extends BaseController{

    public function viewKategoriPengeluaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('keuangan/pengeluaran-sekolah/kategori-pengeluaran/view-kategori-pengeluaran',compact('auth_data'));

    }

    public function addKategoriPengeluaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_pengeluaran_biaya_kategori = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/pengeluaran-sekolah/kategori-pengeluaran/add-kategori-pengeluaran',compact('auth_data','id_pengeluaran_biaya_kategori'));

    }

    public function editKategoriPengeluaran($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori_pengeluaran = LibDataKeuangan::fetchDataKategoriPengeluaran($auth_data, $id);

        return view('keuangan/pengeluaran-sekolah/kategori-pengeluaran/edit-kategori-pengeluaran',compact('auth_data','data_kategori_pengeluaran'));

    }

    public function datatablesKategoriPengeluaran(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataKategoriPengeluaran($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_pengeluaran_biaya_kategori
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionKategoriPengeluaran(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_pengeluaran_biaya_kategori' => 'required',
            'keterangan_pengeluaran_biaya_kategori' => 'required'
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

                $kategoriPengeluaran                                            = new PengeluaranBiayaKategori;
                $kategoriPengeluaran->id_pengeluaran_biaya_kategori             = $id;
                $kategoriPengeluaran->nm_pengeluaran_biaya_kategori             = $input->nm_pengeluaran_biaya_kategori;
                $kategoriPengeluaran->keterangan_pengeluaran_biaya_kategori     = $input->keterangan_pengeluaran_biaya_kategori;
                $kategoriPengeluaran->id_sekolah                                = $input->auth_data->pengguna->id_sekolah;
                $kategoriPengeluaran->created_by                                = $input->auth_data->pengguna->id_pengguna;
                $kategoriPengeluaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pengeluaran-sekolah/kategori-pengeluaran',
                    'message' => 'Save Kategori Pengeluaran Successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $kategoriPengeluaran                                            = PengeluaranBiayaKategori::find($id);
                $kategoriPengeluaran->nm_pengeluaran_biaya_kategori             = $input->nm_pengeluaran_biaya_kategori;
                $kategoriPengeluaran->keterangan_pengeluaran_biaya_kategori     = $input->keterangan_pengeluaran_biaya_kategori;
                $kategoriPengeluaran->updated_by                                = $input->auth_data->pengguna->id_pengguna;
                $kategoriPengeluaran->updated_at                                = $now;
                $kategoriPengeluaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pengeluaran-sekolah/kategori-pengeluaran',
                    'message' => 'Update Kategori Pengeluaran Successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($subkategoriPengeluaran = PengeluaranBiayaSubkategori::where('id_pengeluaran_biaya_kategori',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Kategori Pengeluaran'
                    ]; 
                }
                else{
                    // make object to find id
                    $kategoriPengeluaran               = PengeluaranBiayaKategori::find($id);
                    $kategoriPengeluaran->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $kategoriPengeluaran->save();

                    $kategoriPengeluaran->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Kategori Pengeluaran Successfully'
                    ];
                }
            }
        }
    }


}