<?php

namespace App\Http\Controllers\Pendidikan\DataSekolah;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Sekolah as Sekolah;
use App\Models\BentukPendidikan as BentukPendidikan;
use App\Models\Provinsi as Provinsi;
use App\Models\Kota as Kota;

use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class InputDataSekolahController extends BaseController
{
    public function viewInputDataSekolah(Request $request){
	    # code..
	    $input = (object) $request->input();
	    $auth_data = $input->auth_data;
	    $sekolah = Sekolah::join('bentuk_pendidikan','sekolah.id_bentuk_pendidikan','=','bentuk_pendidikan.id_bentuk_pendidikan')
	    	->leftJoin('provinsi','sekolah.alamat_provinsi','=','provinsi.id_provinsi')
	    	->where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->first();
	    $bentuk_pendidikan = BentukPendidikan::orderBy('kode_bentuk_pendidikan','asc')->get();
	    $provinsi = Provinsi::where('is_aktif','=','1')->orderBy('nm_provinsi','asc')->get();
	    $kota = Kota::where('id_provinsi','=',$sekolah->alamat_provinsi)->where('is_aktif','=','1')->orderBy('nm_kota','asc')->get();

    	return view('pendidikan/data-sekolah/input-data-sekolah',compact('auth_data','sekolah','bentuk_pendidikan','provinsi','kota'));
  	}

  	public function getKota($id_provinsi)
    {
        $kota = Kota::where('id_provinsi','=',$id_provinsi)->where('is_aktif','=','1')->orderBy('nm_kota','asc')->get();
        return response()->json($kota);
    }

    public function actionInputDataSekolah(Request $request, $mode, $id = null){
		$input = (object) $request->input();
		$auth_data = $input->auth_data;
		$now = Carbon::now(env('APP_TIMEZONE', ''));

		if($mode == "edit"){
			$validator = Validator::make($request->all(), [
				'nm_sekolah' => 'required'

			]);
			if($validator->fails()) {
					return [
		            'status' => 300, // FAILED
		            'message' => $validator->errors()->first()
		        ];
	    	}else{
	    		$sekolah 								= Sekolah::where('id_sekolah','=',$id)->first();
		          $sekolah->nm_sekolah 					= $input->nm_sekolah;
		          $sekolah->npsn_sekolah				= $input->npsn_sekolah;
		          $sekolah->id_bentuk_pendidikan		= $input->id_bentuk_pendidikan;
		          $sekolah->nomor_sk_pendirian_sekolah	= $input->nomor_sk_pendirian_sekolah;
		          $sekolah->tgl_sk_pendirian_sekolah	= $input->tgl_sk_pendirian_sekolah;
		          $sekolah->status_kepemilikan			= $input->status_kepemilikan;
		          $sekolah->nm_yayasan_sekolah			= $input->nm_yayasan_sekolah;
		          $sekolah->nomor_sk_izin_operasional	= $input->nomor_sk_izin_operasional;
		          $sekolah->tgl_sk_izin_operasional		= $input->tgl_sk_izin_operasional;
		          $sekolah->is_mbs						= $input->is_mbs;
		          $sekolah->luas_tanah_milik_sekolah	= $input->luas_tanah_milik_sekolah;
		          $sekolah->luas_tanah_non_milik_sekolah = $input->luas_tanah_non_milik_sekolah;
		          $sekolah->nm_wajib_pajak_sekolah		= $input->nm_wajib_pajak_sekolah;
		          $sekolah->npwp_sekolah				= $input->npwp_sekolah;
		          $sekolah->alamat_jalan				= $input->alamat_jalan;
		          $sekolah->alamat_kelurahan			= $input->alamat_kelurahan;
		          $sekolah->alamat_kecamatan			= $input->alamat_kecamatan;
		          if($input->alamat_provinsi == "0") {
		          	$alamat_provinsi = null;
		          }
		          else {
		          	$alamat_provinsi = $input->alamat_provinsi;
		          }
		          $sekolah->alamat_provinsi				= $alamat_provinsi;

		          if($input->alamat_kota == "0") {
		          	$alamat_kota = null;
		          }
		          else {
		          	$alamat_kota = $input->alamat_kota;
		          }
		          $sekolah->alamat_kota					= $alamat_kota;
		          $sekolah->alamat_dusun				= $input->alamat_dusun;
		          $sekolah->alamat_rt					= $input->alamat_rt;
		          $sekolah->alamat_rw					= $input->alamat_rw;
		          $sekolah->alamat_kodepos				= $input->alamat_kodepos;
		          $sekolah->nomor_telp_sekolah			= $input->nomor_telp_sekolah;
		          $sekolah->nomor_fax_sekolah			= $input->nomor_fax_sekolah;
		          $sekolah->email_sekolah				= $input->email_sekolah;
		          $sekolah->website_sekolah				= $input->website_sekolah;
		          $sekolah->updated_by					= $input->auth_data->pengguna->id_pengguna;
		          $sekolah->updated_at					= $now;
		          $sekolah->save();

		          return [
		                    'status' => 202, // SUCCESS AND LOAD CONTENTid_periode_magang
		                    'path' => 'data-sekolah/input-data-sekolah/',
		                    'message' => 'Data Sekolah Berhasil Di Update'
		                ];
	    	}
	    }
	}
}
