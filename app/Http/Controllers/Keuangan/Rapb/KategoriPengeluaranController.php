<?php

namespace App\Http\Controllers\Keuangan\Rapb;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KategoriRapb as KategoriRapb;
use App\Models\SubkategoriRapb as SubkategoriRapb;
use App\Models\Rapb as Rapb;
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

    	return view('keuangan/rapb/kategori-pengeluaran/view-kategori-pengeluaran',compact('auth_data'));

    }

    public function addKategoriPengeluaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_kategori_rapb = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/rapb/kategori-pengeluaran/add-kategori-pengeluaran',compact('auth_data','id_kategori_rapb'));

    }

    public function editKategoriPengeluaran(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori_rapb = LibDataKeuangan::fetchDataKategoriRapb($auth_data, null, $id);

        return view('keuangan/rapb/kategori-pengeluaran/edit-kategori-pengeluaran',compact('auth_data','data_kategori_rapb'));

    }

    public function datatablesKategoriPengeluaran(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataKategoriRapb($auth_data, 2);

        return Datatables::of($list_data)
                ->addColumn('subkategori', function($item){
                    $data = array(
                        'id' => $item->id_kategori_rapb
                    );
                    return $data;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kategori_rapb
                    );
                    return $data;
                })
                ->make(true);
    }


    // Sub Kategori Pengeluaran
    public function viewSubkategoriPengeluaran(Request $request, $id_kategori_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori_rapb = LibDataKeuangan::fetchDataKategoriRapb($auth_data, null, $id_kategori_rapb);

        return view('keuangan/rapb/kategori-pengeluaran/view-subkategori-pengeluaran',compact('auth_data', 'data_kategori_rapb'));

    }

    public function addSubkategoriPengeluaran(Request $request, $id_kategori_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_kategori_rapb = LibDataKeuangan::fetchDataKategoriRapb($auth_data, null, $id_kategori_rapb);

        $id_subkategori_rapb = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/rapb/kategori-pengeluaran/add-subkategori-pengeluaran',compact('auth_data', 'data_kategori_rapb', 'id_subkategori_rapb'));

    }

    public function editSubkategoriPengeluaran(Request $request, $id_kategori_rapb, $id_subkategori_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori_rapb = LibDataKeuangan::fetchDataKategoriRapb($auth_data, null, $id_kategori_rapb);

        $data_subkategori_rapb = LibDataKeuangan::fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb, $id_subkategori_rapb);

        return view('keuangan/rapb/kategori-pengeluaran/edit-subkategori-pengeluaran',compact('auth_data','data_kategori_rapb','data_subkategori_rapb'));

    }

    public function datatablesSubkategoriPengeluaran(Request $request, $id_kategori_rapb){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataKeuangan::fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_subkategori_rapb
                    );
                    return $data;
                })
                ->make(true);
    }


    // Action POST
    public function actionKategoriPengeluaran(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        if(in_array($mode, ['add-kategori','edit-kategori'])) {
           $validator = Validator::make($request->all(), [
                'kode_kategori_rapb' => 'required',
                'nm_kategori_rapb' => 'required',
                'deskripsi_kategori_rapb' => 'required'
            ]); 
        }
        elseif(in_array($mode, ['add-subkategori','edit-subkategori'])) {
           $validator = Validator::make($request->all(), [
                'id_kategori_rapb' => 'required',
                'kode_subkategori_rapb' => 'required',
                'nm_subkategori_rapb' => 'required',
                'deskripsi_subkategori_rapb' => 'required'
            ]); 
        }
        else {
            $validator = Validator::make($request->all(), [

            ]); 
        }
        
        if($validator->fails() && ! in_array($mode, ['delete-kategori','delete-subkategori'])) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if($mode == 'add-kategori') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $kategoriRapb                           = new KategoriRapb;
                $kategoriRapb->id_kategori_rapb         = $id;
                $kategoriRapb->kode_kategori_rapb       = $input->kode_kategori_rapb;
                $kategoriRapb->nm_kategori_rapb         = $input->nm_kategori_rapb;
                $kategoriRapb->deskripsi_kategori_rapb  = $input->deskripsi_kategori_rapb;
                $kategoriRapb->tipe_kategori_rapb       = 2;
                $kategoriRapb->id_sekolah               = $input->auth_data->pengguna->id_sekolah;
                $kategoriRapb->created_by               = $input->auth_data->pengguna->id_pengguna;
                $kategoriRapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/kategori-pengeluaran',
                    'message' => 'Save Kategori Pengeluaran successfully'
                ];
            }
            elseif($mode == 'edit-kategori'){
                // make object to find id
                $kategoriRapb                           = KategoriRapb::find($id);
                $kategoriRapb->kode_kategori_rapb       = $input->kode_kategori_rapb;
                $kategoriRapb->nm_kategori_rapb         = $input->nm_kategori_rapb;
                $kategoriRapb->deskripsi_kategori_rapb  = $input->deskripsi_kategori_rapb;
                $kategoriRapb->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $kategoriRapb->updated_at               = $now;
                $kategoriRapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/kategori-pengeluaran',
                    'message' => 'Update Kategori Pengeluaran successfully'
                ];
            }
            elseif($mode == 'delete-kategori'){
                if($subkategoriRapb = SubkategoriRapb::where('id_kategori_rapb',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Kategori Pengeluaran Sudah Terpakai Pada Sub-Kategori Pengeluaran!'
                    ]; 
                }
                else{
                    // make object to find id
                    $kategoriRapb               = KategoriRapb::find($id);
                    $kategoriRapb->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $kategoriRapb->save();

                    $kategoriRapb->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Kategori Pengeluaran successfully'
                    ];
                }
            }
            elseif($mode == 'add-subkategori') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $subkategoriRapb                                = new SubkategoriRapb;
                $subkategoriRapb->id_subkategori_rapb           = $id;
                $subkategoriRapb->id_kategori_rapb              = $input->id_kategori_rapb;
                $subkategoriRapb->kode_subkategori_rapb         = $input->kode_subkategori_rapb;
                $subkategoriRapb->nm_subkategori_rapb           = $input->nm_subkategori_rapb;
                $subkategoriRapb->deskripsi_subkategori_rapb    = $input->deskripsi_subkategori_rapb;
                $subkategoriRapb->created_by                    = $input->auth_data->pengguna->id_pengguna;
                $subkategoriRapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/kategori-pengeluaran/sub/'.$input->id_kategori_rapb,
                    'message' => 'Save Sub-Kategori Pengeluaran successfully'
                ];
            }
            elseif($mode == 'edit-subkategori'){
                // make object to find id
                $subkategoriRapb                                = SubkategoriRapb::find($id);
                $subkategoriRapb->kode_subkategori_rapb         = $input->kode_subkategori_rapb;
                $subkategoriRapb->nm_subkategori_rapb           = $input->nm_subkategori_rapb;
                $subkategoriRapb->deskripsi_subkategori_rapb    = $input->deskripsi_subkategori_rapb;
                $subkategoriRapb->updated_by                    = $input->auth_data->pengguna->id_pengguna;
                $subkategoriRapb->updated_at                    = $now;
                $subkategoriRapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/kategori-pengeluaran/sub/'.$input->id_kategori_rapb,
                    'message' => 'Update Sub-Kategori Pengeluaran successfully'
                ];
            }
            elseif($mode == 'delete-subkategori'){
                if($rapb = Rapb::where('id_subkategori_rapb',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Sub-Kategori Pengeluaran Sudah Terpakai Pada RAPB!'
                    ]; 
                }
                else{
                    // make object to find id
                    $subkategoriRapb               = SubkategoriRapb::find($id);
                    $subkategoriRapb->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $subkategoriRapb->save();

                    $subkategoriRapb->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Sub-Kategori Pengeluaran successfully'
                    ];
                }
            }
        }
    }


}