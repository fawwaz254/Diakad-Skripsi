<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Pengguna as Pengguna;
use App\Models\Agama as Agama;
use App\Models\KebutuhanKhusus as KebutuhanKhusus;
use App\Models\JenisTinggal as JenisTinggal;
use App\Models\JenisTransportasi as JenisTransportasi;
use App\Models\JenisLayakPip as JenisLayakPip;
use App\Models\Siswa as Siswa;
use App\Models\CalonSiswaBaru;
use App\Models\Kelas as Kelas;
use App\Models\Jalur as Jalur;
use App\Models\Semester as Semester;
use App\Models\Sekolah as Sekolah;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Penerimaan as Penerimaan;
use App\Models\JenisPendidikan as JenisPendidikan;
use App\Models\JenisPekerjaan as JenisPekerjaan;
use App\Models\JenisPenghasilan as JenisPenghasilan;
use App\Models\TingkatPrestasiSiswa as TingkatPrestasiSiswa;
use App\Models\Kota as Kota;
use App\Models\Provinsi as Provinsi;

use App\Libraries\Pendidikan\LibSiswa;


use Auth;
use DB;
use Session;
use Validator;

class InsertUpdateSiswaController extends BaseController
{
	public function viewInsertUpdateSiswa(Request $request){
        # code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;
		$status_pengguna = StatusPengguna::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
		->where('status_join_table','=',3)
		->get();
		$kelas = Kelas::join('jurusan','jurusan.id_jurusan','=','kelas.id_jurusan')->where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->orderBy('kelas.tingkat','asc')->get();
		$thn_masuk_siswa = Siswa::select('thn_masuk_siswa')->distinct()->orderBy('thn_masuk_siswa', 'ASC')->get();
		$semester = Semester::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->orderBy('thn_akademik_semester', 'desc')->orderBy('nm_semester', 'asc')->get();
		$jalur = Jalur::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->get();

		$sekolah = Sekolah::where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)->first();
		return view('pendidikan/siswa/insert-update-siswa/view-insert-update-siswa',compact('auth_data','status_pengguna','kelas','thn_masuk_siswa','semester','jalur','sekolah'));

	}
	public function actionViewUpdateSiswa(Request $request){
		# code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		$validator = Validator::make($request->all(), [
			'nis_nama_siswa' =>'required'
		]);

		if($validator->fails()) {
			return [
				'status' => 300, // FAILED
				'message' => $validator->errors()->first()
			];
		}
		else {
			if ($siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $input->nis_nama_siswa)) {
				return [
					'status' => 204, // SUCCESS AND LOAD CONTENT
					'path' => 'siswa/insert-update-siswa/view-detail/'.$input->nis_nama_siswa
				];
			} else {
				return [
					'status' => 300, // FAILED
					'message' => 'NIS tidak ditemukan'
				];
			}
		}
	}
	public function viewDetailUpdateSiswa(Request $request, $nis_nama_siswa){
        # code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		if($siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $nis_nama_siswa)){

		}else{
			return [
				'status' => 300, // FAILED
				'message' => 'NIS tidak ditemukan'
			];
		}
		$agama = Agama::get();
		$kebutuhanKhusus = KebutuhanKhusus::get();
		$jenisTinggal = JenisTinggal::get();
		$jenisTransportasi = JenisTransportasi::get();
		$jenisPip = JenisLayakPip::get();
		$jenisPendidikan = JenisPendidikan::get();
		$jenisPenghasilan = JenisPenghasilan::get();
		$jenisPekerjaan = JenisPekerjaan::get();
		$tingkatPrestasi = TingkatPrestasiSiswa::get();
		$kota = Kota::get();
		$kotaTinggal = Kota::get();
		$provinsi = Provinsi::get();
		
		$kotaLahir = Kota::where('id_kota','=',$siswa->id_kota_lahir)->first();
		// dd($siswa);

		return view('pendidikan/siswa/insert-update-siswa/view-update-siswa',compact('auth_data','nis_nama_siswa','siswa','agama','kebutuhanKhusus','jenisTinggal','jenisTransportasi','jenisPip','jenisPendidikan','jenisPenghasilan','jenisPekerjaan','tingkatPrestasi','kotaLahir','kota','provinsi','kotaTinggal'));
	}
	public function actionInsertUpdateSiswa(Request $request, $mode, $id = null){
		$input = (object) $request->input();
		$auth_data = $input->auth_data;
		$now = Carbon::now(env('APP_TIMEZONE', ''));

		if($mode == "insert"){
			$validator = Validator::make($request->all(), [
				'nis_siswa' =>'required',
				'nm_pengguna' => 'required',
				'jenis_kelamin' => 'required',
				'id_status_pengguna' => 'required',
				'id_kelas' => 'required',
				'thn_masuk_siswa' => 'required',
				'id_semester'	=> 'required',
				'id_jalur' => 'required'

			]);
			if($validator->fails()) {
					return [
		            'status' => 300, // FAILED
		            'message' => $validator->errors()->first()
		        ];
	    	}
	    	//jika validasi benar
	    	else{
	    		$siswa = Siswa::where('nis_siswa','=',$input->nis_siswa)->orWhere('nisn_siswa','=',$input->nisn_siswa)->first();
		    	$id_penerimaan 		= Penerimaan::where('jenis_penerimaan','=','2')->where('tahun_penerimaan','=',$input->thn_masuk_siswa)->first();

		    	//jika tidak ada siswa 
		    	if($siswa == null){
		    		DB::beginTransaction();
		    		$id_siswa 			= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
		    		$id_pengguna 		= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
		    		$id_c_siswa 		= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
		    		$id_admisi 			= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
		    		$id_jalur_siswa 	= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

		    		try{
		    			DB::table('calon_siswa_baru')->insert(
		    				[
		    					'id_c_siswa' 	=> $id_c_siswa, 
		    					'id_penerimaan' => $id_penerimaan->id_penerimaan,
		    					'nm_c_siswa' 	=> $input->nm_pengguna,
		    					'jenis_kelamin' => $input->jenis_kelamin,
		    					'nisn_siswa' 	=> $input->nisn_siswa,
		    					'nis_siswa' 	=> $input->nis_siswa,
		    					'created_at'	=> $now,
		    					'created_by' 	=> $input->auth_data->pengguna->id_pengguna
		    				]
		    			);
		    			DB::table('calon_siswa_fisik')->insert(
		    				[
								'id_c_siswa' 	=> $id_c_siswa,
								'created_at'	=> $now,
		    					'created_by' 	=> $input->auth_data->pengguna->id_pengguna						    
		    				]
		    			);

		    			DB::table('calon_siswa_ortu')->insert(
		    				[
								'id_c_siswa' 	=> $id_c_siswa,
								'created_at'	=> $now,
		    					'created_by' 	=> $input->auth_data->pengguna->id_pengguna					    	
		    				]
		    			);

		    			DB::table('calon_siswa_sekolah')->insert(
		    				[
								'id_c_siswa' 	=> $id_c_siswa,
								'created_at'	=> $now,
		    					'created_by' 	=> $input->auth_data->pengguna->id_pengguna
		    				]
		    			);
		    			DB::table('pengguna')->insert(
		    				[
		    					'id_pengguna' 			=> $id_pengguna,
		    					'id_status_pengguna' 	=> $input->id_status_pengguna,
		    					'id_sekolah' 			=> $input->auth_data->pengguna->id_sekolah,
		    					'nm_pengguna'			=> $input->nm_pengguna,
		    					'username' 				=> $input->nis_siswa,
		    					'password'				=> Hash::make($input->nis_siswa),
		    					'must_change_password' 	=> 1,
								'status_join_table' 	=> 3,
								'created_at'			=> $now,
		    					'created_by' 			=> $input->auth_data->pengguna->id_pengguna
		    				]
		    			);

		    			DB::table('siswa')->insert(
		    				[	
		    					'id_siswa' 	 			=> $id_siswa,
		    					'id_pengguna' 			=> $id_pengguna,
		    					'id_c_siswa' 			=> $id_c_siswa,
		    					'id_kelompok_biaya'		=> 0,
		    					'id_kelas' 	 			=> $input->id_kelas,
		    					'nis_siswa'	 			=> $input->nis_siswa,
		    					'nisn_siswa'			=> $input->nisn_siswa,
								'thn_masuk_siswa'		=> $input->thn_masuk_siswa,
								'created_at'			=> $now,
		    					'created_by' 			=> $input->auth_data->pengguna->id_pengguna
		    				]
		    			);

		    			DB::table('admisi')->insert(
		    				[
		    					'id_admisi' 			=> $id_admisi,
		    					'id_siswa' 	 			=> $id_siswa,
		    					'id_semester' 			=> $input->id_semester,
		    					'id_status_pengguna' 	=> $input->id_status_pengguna,
								'id_jalur' 				=> $input->id_jalur,
								'created_at'			=> $now,
		    					'created_by' 			=> $input->auth_data->pengguna->id_pengguna					    	
		    				]
		    			);

		    			DB::table('jalur_siswa')->insert(
		    				[
		    					'id_jalur_siswa' 		=> $id_jalur_siswa,
		    					'id_siswa' 	 			=> $id_siswa,
		    					'id_semester' 			=> $input->id_semester,
		    					'id_jalur' 				=> $input->id_jalur,
		    					'id_admisi' 			=> $id_admisi,
								'is_jalur_aktif' 		=> 1,
								'created_at'			=> $now,
		    					'created_by' 			=> $input->auth_data->pengguna->id_pengguna					    	
		    				]
		    			);

		    			DB::table('role_pengguna')->insert(
		    				[	
		    					'id_pengguna' 				=> $id_pengguna,
		    					'id_role' 					=> 3,
		    					'keterangan_role_pengguna' 	=> "Input Pendidikan",
								'is_aktif'					=> 1,
								'created_at'			=> $now,
		    					'created_by' 				=> $input->auth_data->pengguna->id_pengguna
		    				]
		    			);
		    			DB::commit();
		    			return [
			                    'status' => 202, // SUCCESS AND LOAD PAGE
								'message' => 'Insert Data Siswa Berhasil!',
								'path' => 'siswa/cari-siswa/view-detail-siswa/'.$input->nis_siswa.'/'.$input->nis_siswa
			            ];
		    		}
		    		catch (\Exception $e) {
	                    DB::rollback();
	                    // something went wrong
	                    return [
	                                'status' 	=> 200, // GAGAL
	                                'message'	=> 'Insert Data Siswa Gagal'
	                            ];
	                } 

		    	}	
	    	}
		}
		elseif($mode == "update"){
	    	$validator = Validator::make($request->all(), [

			]);
			if($validator->fails()) {
					return [
		            'status' => 300, // FAILED
		            'message' => $validator->errors()->first()
		        ];
	    	}
	    	//jika validasi benar
	    	else{
	    		$siswa = Siswa::where('nis_siswa','=',$input->nis_siswa)->orWhere('nisn_siswa','=',$input->nisn_siswa)->first();
	    		$calonSiswa = CalonSiswaBaru::where('id_c_siswa','=',$input->id_c_siswa)->first();
		    	$id_c_siswa_prestasi 		= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
		    	$id_c_siswa_beasiswa		= $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

	    		if($siswa != null || $calonSiswa != null){
	    			DB::beginTransaction();
	    			try{
	    				DB::table('pengguna')->where('id_pengguna', $input->id_pengguna)->update([
							'nm_pengguna' 			=> $input->nm_pengguna,
							'username' 				=> $input->nis_siswa,
							'password' 				=> Hash::make($input->nis_siswa),
							'id_sekolah' 			=> $input->auth_data->pengguna->id_sekolah,
							'id_status_pengguna'	=> $input->id_status_pengguna,
							'must_change_password' 	=> 1,
							'status_join_table' 	=> 3,
							'updated_at' 			=> $now,
							'updated_by' 			=> $input->auth_data->pengguna->id_pengguna
						]);

	    				DB::table('calon_siswa_baru')->where('id_c_siswa', $input->id_c_siswa)->update([
							'id_penerimaan' 		=> $calonSiswa->id_penerimaan,
							'kode_voucher' 			=> $calonSiswa->kode_voucher,
							'password' 				=> $calonSiswa->password,
							'nm_c_siswa' 			=> $input->nm_pengguna,
							'nik_siswa' 			=> $input->nik_siswa,
							'jenis_kelamin' 		=> $input->jenis_kelamin,
							'nisn_siswa'			=> $input->nisn_siswa,
							'id_agama'				=> $input->id_agama,
							'id_kota_lahir'			=> $input->id_kota_lahir,
							'tgl_lahir'				=> $input->tgl_lahir,
							'nomor_identitas'		=> $input->nik_siswa,
							'nomor_akta_lahir'		=> $input->nomor_akta_lahir,
							'kewarganegaraan'		=> $input->kewarganegaraan,
							'nm_kewarganegaraan'	=> $input->nm_kewarganegaraan,
							'id_kebutuhan_khusus'	=> $input->id_kebutuhan_khusus,
							'alamat_jalan'			=> $input->alamat_jalan,
							'alamat_dusun'			=> $input->alamat_dusun,
							'alamat_kelurahan'		=> $input->alamat_kelurahan,
							'alamat_rt'				=> $input->alamat_rt,
							'alamat_rw'				=> $input->alamat_rw,
							'alamat_kecamatan'		=> $input->alamat_kecamatan,
							'alamat_kodepos'		=> $input->alamat_kodepos,
							'alamat_kota'			=> $input->alamat_kota,
							'alamat_provinsi'		=> $input->alamat_provinsi,
							'alamat_longitude'		=> $input->alamat_longitude,
							'alamat_latitude'		=> $input->alamat_latitude,
							'nomor_hp'				=> $input->nomor_hp_ortu,
							'id_jenis_tinggal'		=> $input->id_jenis_tinggal,
							'anak_ke'				=> $input->anak_ke,
							'dari_x_bersaudara'		=> $input->dari_x_bersaudara,
							'jarak_rumah_sekolah'	=> $input->jarak_rumah_sekolah,
							'waktu_tempuh_sekolah_jam'	=> $input->waktu_tempuh_sekolah_jam,
							'waktu_tempuh_sekolah_menit' => $input->waktu_tempuh_sekolah_menit,
							'id_jenis_transportasi'	=> $input->id_jenis_transportasi,
							'nomor_kks'				=> $input->nomor_kks,
							'is_penerima_kps'		=> $input->is_penerima_kps,
							'nomor_kps'				=> $input->nomor_kps,
							'is_punya_kip'			=> $input->is_punya_kip,
							'nomor_kip'				=> $input->nomor_kip,
							'nm_tertera_kip'		=> $input->nm_tertera_kip,
							'is_layak_pip'			=> $input->is_layak_pip,
							'id_jenis_layak_pip'	=> $input->id_jenis_layak_pip,
							'updated_at' 			=> $now,
							'updated_by' 			=> $input->auth_data->pengguna->id_pengguna
						]);

	    				DB::table('calon_siswa_ortu')->where('id_c_siswa', $input->id_c_siswa)->update([
							'nm_ayah'					=> $input->nm_ayah,
							'nik_ayah'					=> $input->nik_ayah,
							'tgl_lahir_ayah'			=> $input->tgl_lahir_ayah,
							'id_jenis_pendidikan_ayah'	=> $input->id_jenis_pendidikan_ayah,
							'id_jenis_pekerjaan_ayah'	=> $input->id_jenis_pekerjaan_ayah,
							'id_jenis_penghasilan_ayah'	=> $input->id_jenis_penghasilan_ayah,
							'id_kebutuhan_khusus_ayah'	=> $input->id_kebutuhan_khusus_ayah,
							'nm_ibu'					=> $input->nm_ibu,
							'nik_ibu'					=> $input->nik_ibu,
							'tgl_lahir_ibu'			    => $input->tgl_lahir_ibu,
							'id_jenis_pendidikan_ibu'	=> $input->id_jenis_pendidikan_ibu,
							'id_jenis_pekerjaan_ibu'	=> $input->id_jenis_pekerjaan_ibu,
							'id_jenis_penghasilan_ibu'	=> $input->id_jenis_penghasilan_ibu,
							'id_kebutuhan_khusus_ibu'	=> $input->id_kebutuhan_khusus_ibu,
							'nm_wali'					=> $input->nm_wali,
							'nik_wali'					=> $input->nik_wali,
							'tgl_lahir_wali'			=> $input->tgl_lahir_wali,
							'id_jenis_pendidikan_wali'	=> $input->id_jenis_pendidikan_wali,
							'id_jenis_pekerjaan_wali'	=> $input->id_jenis_pekerjaan_wali,
							'id_jenis_penghasilan_wali'	=> $input->id_jenis_penghasilan_wali,
							'id_kebutuhan_khusus_wali'	=> $input->id_kebutuhan_khusus_wali,
							'email_ortu'				=> $input->email_ortu,
							'nomor_telp_ortu'			=> $input->nomor_telp_ortu,
							'nomor_hp_ortu'				=> $input->nomor_hp_ortu,
							'updated_at' 				=> $now,
							'updated_by' 				=> $input->auth_data->pengguna->id_pengguna
						]);

	    				DB::table('calon_siswa_fisik')->where('id_c_siswa', $input->id_c_siswa)->update([
							'tinggi_badan'				=> $input->tinggi_badan,
							'berat_badan'				=> $input->berat_badan,
							'updated_at' 				=> $now,
							'updated_by' 				=> $input->auth_data->pengguna->id_pengguna
						]);

	    				DB::table('calon_siswa_prestasi')->where('id_c_siswa', $input->id_c_siswa)->update([	
							'updated_at' 				=> $now,
							'updated_by' 				=> $input->auth_data->pengguna->id_pengguna
						]);

	    				DB::table('calon_siswa_prestasi')->insert([
							'id_c_siswa_prestasi'		=> $id_c_siswa_prestasi,
							'id_c_siswa'				=> $input->id_c_siswa,
							'id_tingkat_prestasi_siswa'	=> $input->id_tingkat_prestasi_siswa,
							'jenis_prestasi_c_siswa'	=> $input->jenis_prestasi,
							'nm_prestasi_c_siswa'		=> $input->nm_prestasi_c_siswa,
							'tgl_prestasi_c_siswa'		=> $input->tgl_prestasi_c_siswa,
							'penyelenggara_prestasi_c_siswa'	=> $input->penyelenggara_prestasi_c_siswa,
							'peringkat_prestasi_c_siswa'	=> $input->peringkat_prestasi_c_siswa,
							'created_at'		=> $now,
							'created_by' 			=> $input->auth_data->pengguna->id_pengguna					    	
						]);

		    			DB::table('calon_siswa_beasiswa')->insert([
							'id_c_siswa_beasiswa'		=> $id_c_siswa_beasiswa,
							'id_c_siswa'				=> $input->id_c_siswa,
							'jenis_beasiswa_c_siswa'	=> $input->jenis_beasiswa_c_siswa,
							'keterangan_beasiswa_c_siswa'	=> $input->keterangan_beasiswa_c_siswa,
							'tahun_mulai_beasiswa_c_siswa'	=> $input->tahun_mulai_beasiswa_c_siswa,
							'tahun_selesai_beasiswa_c_siswa' => $input->tahun_selesai_beasiswa_c_siswa,
							'created_at'				=> $now,
							'created_by' 				=> $input->auth_data->pengguna->id_pengguna					    	
						]);
	    				DB::commit();
		    			return [
							'status' => 200, // SUCCESS AND LOAD TABLE
							'message' => 'Update Data Siswa Berhasil!'
			            ];
	    			}
	    			catch (\Exception $e) {
						DB::rollback();
						
	                    return [
							'status' 	=> 200, // GAGAL
							'message' => (env('APP_DEBUG', 'true') == 'true')? $e->getMessage() : 'Operation error. Error '.$e->getLine()
	                    ];
	    			}
	    		}
	    		else{
	    			return [
			                'status' => 200, // SUCCESS AND LOAD TABLE
			               	'message' => 'Siswa Tidak Ditemukan!'
			        ];
	    		}
	    	}
		}
	}
}
