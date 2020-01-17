<?php

namespace App\Http\Controllers\Keuangan\Rapb;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KategoriRapb as KategoriRapb;
use App\Models\SubkategoriRapb as SubkategoriRapb;
use App\Models\KetSubkategoriRapb as KetSubkategoriRapb;
use App\Models\Rapb as Rapb;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class KategoriPenerimaanController extends BaseController{

    public function viewKategoriPenerimaan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('keuangan/rapb/kategori-penerimaan/view-kategori-penerimaan',compact('auth_data'));

    }

    public function addKategoriPenerimaan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_kategori_rapb = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/rapb/kategori-penerimaan/add-kategori-penerimaan',compact('auth_data','id_kategori_rapb'));

    }

    public function editKategoriPenerimaan(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori_rapb = LibDataKeuangan::fetchDataKategoriRapb($auth_data, null, $id);

        return view('keuangan/rapb/kategori-penerimaan/edit-kategori-penerimaan',compact('auth_data','data_kategori_rapb'));

    }

    public function datatablesKategoriPenerimaan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataKategoriRapb($auth_data, 1);

        return Datatables::of($list_data)
                ->addColumn('subkategori', function($item){
                    $data = array(
                        'jml_subkategori_rapb' => $item->jml_subkategori_rapb,
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


    // Sub Kategori Penerimaan
    public function viewSubkategoriPenerimaan(Request $request, $id_kategori_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori_rapb = LibDataKeuangan::fetchDataKategoriRapb($auth_data, null, $id_kategori_rapb);

        return view('keuangan/rapb/kategori-penerimaan/view-subkategori-penerimaan',compact('auth_data', 'data_kategori_rapb'));

    }

    public function addSubkategoriPenerimaan(Request $request, $id_kategori_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_kategori_rapb = LibDataKeuangan::fetchDataKategoriRapb($auth_data, null, $id_kategori_rapb);

        $id_subkategori_rapb = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/rapb/kategori-penerimaan/add-subkategori-penerimaan',compact('auth_data', 'data_kategori_rapb', 'id_subkategori_rapb'));

    }

    public function editSubkategoriPenerimaan(Request $request, $id_kategori_rapb, $id_subkategori_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori_rapb = LibDataKeuangan::fetchDataKategoriRapb($auth_data, null, $id_kategori_rapb);

        $data_subkategori_rapb = LibDataKeuangan::fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb, $id_subkategori_rapb);

        return view('keuangan/rapb/kategori-penerimaan/edit-subkategori-penerimaan',compact('auth_data','data_kategori_rapb','data_subkategori_rapb'));

    }

    public function datatablesSubkategoriPenerimaan(Request $request, $id_kategori_rapb){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataKeuangan::fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb);

        return Datatables::of($list_data)
                ->addColumn('ket_subkategori', function($item){
                    $data = array(
                        'jml_ket_subkategori_rapb' => $item->jml_ket_subkategori_rapb,
                        'id' => $item->id_subkategori_rapb
                    );
                    return $data;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_subkategori_rapb
                    );
                    return $data;
                })
                ->make(true);
    }

    // Keterangan Sub Kategori Penerimaan
    public function viewKetSubkategoriPenerimaan(Request $request, $id_kategori_rapb, $id_subkategori_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_subkategori_rapb = LibDataKeuangan::fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb, $id_subkategori_rapb);

        return view('keuangan/rapb/kategori-penerimaan/view-ket-subkategori-penerimaan',compact('auth_data', 'data_subkategori_rapb'));

    }

    public function addKetSubkategoriPenerimaan(Request $request, $id_kategori_rapb, $id_subkategori_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_subkategori_rapb = LibDataKeuangan::fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb, $id_subkategori_rapb);

        $id_ket_subkategori_rapb = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/rapb/kategori-penerimaan/add-ket-subkategori-penerimaan',compact('auth_data', 'data_subkategori_rapb', 'id_ket_subkategori_rapb'));

    }

    public function editKetSubkategoriPenerimaan(Request $request, $id_kategori_rapb, $id_subkategori_rapb, $id_ket_subkategori_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_subkategori_rapb = LibDataKeuangan::fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb, $id_subkategori_rapb);

        $data_ket_subkategori_rapb = LibDataKeuangan::fetchDataKetSubkategoriRapb($auth_data, $id_kategori_rapb, $id_subkategori_rapb, $id_ket_subkategori_rapb);

        return view('keuangan/rapb/kategori-penerimaan/edit-ket-subkategori-penerimaan',compact('auth_data','data_subkategori_rapb','data_ket_subkategori_rapb'));

    }

    public function datatablesKetSubkategoriPenerimaan(Request $request, $id_kategori_rapb, $id_subkategori_rapb){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataKeuangan::fetchDataKetSubkategoriRapb($auth_data, $id_kategori_rapb, $id_subkategori_rapb);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_ket_subkategori_rapb
                    );
                    return $data;
                })
                ->make(true);
    }


    // Action POST
    public function actionKategoriPenerimaan(Request $request, $mode, $id = null){

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
        elseif(in_array($mode, ['add-ket-subkategori','edit-ket-subkategori'])) {
           $validator = Validator::make($request->all(), [
                'id_subkategori_rapb' => 'required',
                'kode_ket_subkategori_rapb' => 'required',
                'nm_ket_subkategori_rapb' => 'required'
            ]); 
        }
        else {
            $validator = Validator::make($request->all(), [

            ]); 
        }
        
        if($validator->fails() && ! in_array($mode, ['delete-kategori','delete-subkategori','delete-ket-subkategori'])) {
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
                $kategoriRapb->tipe_kategori_rapb       = 1;
                $kategoriRapb->id_sekolah               = $input->auth_data->pengguna->id_sekolah;
                $kategoriRapb->created_by               = $input->auth_data->pengguna->id_pengguna;
                $kategoriRapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/kategori-penerimaan',
                    'message' => 'Save Kategori Penerimaan successfully'
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
                    'path' => 'rapb/kategori-penerimaan',
                    'message' => 'Update Kategori Penerimaan successfully'
                ];
            }
            elseif($mode == 'delete-kategori'){
                if($subkategoriRapb = SubkategoriRapb::where('id_kategori_rapb',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Kategori Penerimaan Sudah Terpakai Pada Sub-Kategori Penerimaan!'
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
                        'message' => 'Delete Kategori Penerimaan successfully'
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
                    'path' => 'rapb/kategori-penerimaan/sub/'.$input->id_kategori_rapb,
                    'message' => 'Save Sub-Kategori Penerimaan successfully'
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
                    'path' => 'rapb/kategori-penerimaan/sub/'.$input->id_kategori_rapb,
                    'message' => 'Update Sub-Kategori Penerimaan successfully'
                ];
            }
            elseif($mode == 'delete-subkategori'){
                if($rapb = Rapb::where('id_subkategori_rapb',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Sub-Kategori Penerimaan Sudah Terpakai Pada RAPB!'
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
                        'message' => 'Delete Sub-Kategori Penerimaan successfully'
                    ];
                }
            }
            elseif($mode == 'add-ket-subkategori') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $ketSubkategoriRapb                             = new KetSubkategoriRapb;
                $ketSubkategoriRapb->id_ket_subkategori_rapb    = $id;
                $ketSubkategoriRapb->id_subkategori_rapb        = $input->id_subkategori_rapb;
                $ketSubkategoriRapb->kode_ket_subkategori_rapb  = $input->kode_ket_subkategori_rapb;
                $ketSubkategoriRapb->nm_ket_subkategori_rapb    = $input->nm_ket_subkategori_rapb;
                $ketSubkategoriRapb->created_by                 = $input->auth_data->pengguna->id_pengguna;
                $ketSubkategoriRapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/kategori-penerimaan/sub/ket/'.$input->id_kategori_rapb.'/'.$input->id_subkategori_rapb,
                    'message' => 'Save Keterangan Sub-Kategori Penerimaan successfully'
                ];
            }
            elseif($mode == 'edit-ket-subkategori'){
                // make object to find id
                $ketSubkategoriRapb                             = KetSubkategoriRapb::find($id);
                $ketSubkategoriRapb->kode_ket_subkategori_rapb  = $input->kode_ket_subkategori_rapb;
                $ketSubkategoriRapb->nm_ket_subkategori_rapb    = $input->nm_ket_subkategori_rapb;
                $ketSubkategoriRapb->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                $ketSubkategoriRapb->updated_at                 = $now;
                $ketSubkategoriRapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/kategori-penerimaan/sub/ket/'.$input->id_kategori_rapb.'/'.$input->id_subkategori_rapb,
                    'message' => 'Update Keterangan Sub-Kategori Penerimaan successfully'
                ];
            }
            elseif($mode == 'delete-ket-subkategori'){
                // make object to find id
                $ketSubkategoriRapb               = KetSubkategoriRapb::find($id);
                $ketSubkategoriRapb->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $ketSubkategoriRapb->save();

                $ketSubkategoriRapb->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Keterangan Sub-Kategori Penerimaan successfully'
                ];
            }
        }
    }


}