<?php

namespace App\Http\Controllers\Keuangan\PengeluaranSekolah;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PengeluaranBiaya as PengeluaranBiaya;
use App\Models\Staff as Staff;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class InputPengeluaranController extends BaseController{

    public function viewInputPengeluaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('keuangan/pengeluaran-sekolah/input-pengeluaran/view-input-pengeluaran',compact('auth_data'));

    }

    public function addInputPengeluaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_subkategori_pengeluaran = LibDataKeuangan::fetchDataSubkategoriPengeluaran($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $id_pengeluaran_biaya = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/pengeluaran-sekolah/input-pengeluaran/add-input-pengeluaran',compact('auth_data','data_subkategori_pengeluaran','data_semester','id_pengeluaran_biaya'));

    }

    public function editInputPengeluaran($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_subkategori_pengeluaran = LibDataKeuangan::fetchDataSubkategoriPengeluaran($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_pengeluaran = LibDataKeuangan::fetchDataPengeluaran($auth_data, $id);

        // convert format date
        $tgl_pengeluaran_biaya = strftime( "%A, %d %B %Y", strtotime($data_pengeluaran->tgl_pengeluaran_biaya));

        return view('keuangan/pengeluaran-sekolah/input-pengeluaran/edit-input-pengeluaran',compact('auth_data','data_subkategori_pengeluaran','data_semester','data_pengeluaran', 'tgl_pengeluaran_biaya'));

    }

    public function datatablesInputPengeluaran(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataKeuangan::fetchDataPengeluaran($auth_data, null, "1");

        return Datatables::of($list_data)
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('nm_pengeluaran_biaya_subkategori', function($item){
                    return $item->nm_pengeluaran_biaya_subkategori." - ".$item->nm_pengeluaran_biaya_kategori;
                })
                ->addColumn('nm_pengguna', function($item){
                    if( ! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    }
                    elseif( ! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;   
                    }
                    elseif( ! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;   
                    }
                    else {
                        return $item->nm_pengguna; 
                    }
                })
                ->addColumn('tgl_pengeluaran_biaya', function($item){
                    return strftime( "%A, %d %B %Y", strtotime($item->tgl_pengeluaran_biaya));
                })
                ->addColumn('besar_pengeluaran_biaya', function($item){
                    return "Rp".number_format($item->besar_pengeluaran_biaya);
                })
                ->addColumn('is_upload_file', function($item){
                    if($item->is_upload_file == 1) {
                        return "Ya";
                    }
                    else {
                        return "Tidak";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_pengeluaran_biaya
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionInputPengeluaran(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_semester'                       => 'required',
            'id_pengeluaran_biaya_subkategori'  => 'required',
            'tgl_pengeluaran_biaya'             => 'required',
            'besar_pengeluaran_biaya'           => 'required',
            'keterangan_pengeluaran_biaya'      => 'required',
            'is_upload_file'                    => 'required'
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
                if ($input->auth_data->pengguna->status_join_table == 1) {
                    // get id_guru
                    $staff = Staff::select('id_staff')
                        ->where('id_pengguna','=',$input->auth_data->pengguna->id_pengguna)
                        ->first();

                    $id_staff = $staff->id_staff;
                }
                else {
                    $id_staff = null;
                }

                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $pengeluaran                                    = new PengeluaranBiaya;
                $pengeluaran->id_pengeluaran_biaya              = $id;
                $pengeluaran->id_pengeluaran_biaya_subkategori  = $input->id_pengeluaran_biaya_subkategori;
                $pengeluaran->id_semester                       = $input->id_semester;
                $pengeluaran->id_staff                          = $id_staff;
                // convert format date
                $pengeluaran->tgl_pengeluaran_biaya             = date_format(date_create($input->tgl_pengeluaran_biaya),"Y-m-d H:i:s");
                $pengeluaran->besar_pengeluaran_biaya           = $input->besar_pengeluaran_biaya;
                $pengeluaran->keterangan_pengeluaran_biaya      = $input->keterangan_pengeluaran_biaya;
                $pengeluaran->is_upload_file                    = $input->is_upload_file;
                $pengeluaran->created_by                        = $input->auth_data->pengguna->id_pengguna;
                $pengeluaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pengeluaran-sekolah/input-pengeluaran',
                    'message' => 'Save Pengeluaran Successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $pengeluaran                                    = PengeluaranBiaya::find($id);
                $pengeluaran->id_pengeluaran_biaya_subkategori  = $input->id_pengeluaran_biaya_subkategori;
                $pengeluaran->id_semester                       = $input->id_semester;
                // convert format date
                $pengeluaran->tgl_pengeluaran_biaya             = date_format(date_create($input->tgl_pengeluaran_biaya),"Y-m-d H:i:s");
                $pengeluaran->besar_pengeluaran_biaya           = $input->besar_pengeluaran_biaya;
                $pengeluaran->keterangan_pengeluaran_biaya      = $input->keterangan_pengeluaran_biaya;
                $pengeluaran->is_upload_file                    = $input->is_upload_file;                
                $pengeluaran->updated_by                        = $input->auth_data->pengguna->id_pengguna;
                $pengeluaran->updated_at                        = $now;
                $pengeluaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pengeluaran-sekolah/input-pengeluaran',
                    'message' => 'Update Pengeluaran Successfully'
                ];
            }
            elseif($mode == 'delete'){
                // make object to find id
                $pengeluaran               = PengeluaranBiaya::find($id);
                $pengeluaran->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $pengeluaran->save();

                $pengeluaran->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Pengeluaran Successfully'
                ];
            }
        }
    }


}