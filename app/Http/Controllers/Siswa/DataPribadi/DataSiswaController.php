<?php

namespace App\Http\Controllers\Siswa\DataPribadi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibDataAkademik;

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
use App\Models\CalonSiswaBeasiswa;
use App\Models\RolePengguna;
use App\Models\WaliMurid;
use Auth;
use DB;
use Session;
use Validator;

class DataSiswaController extends BaseController
{

	public function viewDataSiswa(Request $request)
	{
		# code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		$data_siswa = Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();

		if ($siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $data_siswa->nis_siswa)) { } else {
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

		$kotaLahir = Kota::where('id_kota', '=', $siswa->id_kota_lahir)->first();

		return view('siswa/data-pribadi/data-siswa/view-data-siswa', compact('auth_data', 'siswa', 'agama', 'kebutuhanKhusus', 'jenisTinggal', 'jenisTransportasi', 'jenisPip', 'jenisPendidikan', 'jenisPenghasilan', 'jenisPekerjaan', 'tingkatPrestasi', 'kotaLahir', 'kota', 'provinsi', 'kotaTinggal'));
	}

	public function viewPrintSiswa(Request $request, $nis_nama_siswa)
	{

		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		$siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $nis_nama_siswa);
		$beasiswa = CalonSiswaBeasiswa::where('id_c_siswa', $siswa->id_c_siswa)->get();

		$semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

		$data_beasiswa[0]['urutan_1'] = '61.';
		$data_beasiswa[0]['urutan_2'] = 'Menerima Beasiswa';
		$data_beasiswa[0]['urutan_3'] = ': ';

		if ($beasiswa) {
			foreach ($beasiswa as $key => $value) {
				if ($key == 0) {
					$data_beasiswa[$key]['urutan_1'] = '61.';
					$data_beasiswa[$key]['urutan_2'] = 'Menerima Beasiswa';
					$data_beasiswa[$key]['urutan_3'] = $value->keterangan_beasiswa_c_siswa . ' Tahun ' . $value->tahun_mulai_beasiswa_c_siswa . ' - ' . $value->tahun_selesai_beasiswa_c_siswa;
				} else {
					$data_beasiswa[$key]['urutan_1'] = '';
					$data_beasiswa[$key]['urutan_2'] = '';
					$data_beasiswa[$key]['urutan_3'] = $value->keterangan_beasiswa_c_siswa . ' Tahun ' . $value->tahun_mulai_beasiswa_c_siswa . ' - ' . $value->tahun_selesai_beasiswa_c_siswa;
				}
			}
		}

		return view('siswa/data-pribadi/data-siswa/view-print-siswa', compact('auth_data', 'siswa', 'data_beasiswa', 'semester_aktif'));
	}

	public function actionUpdateSiswa(Request $request, $id)
	{

		$input = (object) $request->input();
		$auth_data = $input->auth_data;
		$now = Carbon::now(env('APP_TIMEZONE', ''));

		$validator = Validator::make($request->all(), []);

		if ($validator->fails()) {
			return [
				'status' => 300, // FAILED
				'message' => $validator->errors()->first()
			];
		}
		//jika validasi benar
		else {
			$siswa = Siswa::where('nis_siswa', '=', $input->nis_siswa)->orWhere('nisn_siswa', '=', $input->nisn_siswa)->first();
			$calonSiswa = CalonSiswaBaru::where('id_c_siswa', '=', $input->id_c_siswa)->first();
			$wali_murid = WaliMurid::where('id_pengguna', $input->id_pengguna)->first();
			$id_c_siswa_prestasi 		= $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
			$id_c_siswa_beasiswa		= $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();


			if ($wali_murid != null) {
				// if siswa have wali murid
				$wali_murid = WaliMurid::where('id_pengguna', $input->id_pengguna)->first();
				$wali_murid->nm_wali_murid = $input->nm_ayah;
				$wali_murid->nomor_hp_wali_murid = $input->nomor_hp_ortu;
				$wali_murid->updated_at = $now;
				$wali_murid->updated_by = $input->auth_data->pengguna->id_pengguna;

				if ($siswa->id_wali_murid == null) {
					$siswa->id_wali_murid = $wali_murid->id_wali_murid;
					$siswa->save();
				}
			} else {
				// if siswa doesnt have wali murid
				$wali_murid = new WaliMurid;
				$wali_murid->id_wali_murid =	$input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
				$wali_murid->id_pengguna = $input->id_pengguna;
				$wali_murid->nm_wali_murid = $input->nm_ayah;
				$wali_murid->is_aktif = 1;
				$wali_murid->nomor_hp_wali_murid = $input->nomor_hp_ortu;
				$wali_murid->updated_at = $now;
				$wali_murid->save();

				$siswa->id_wali_murid = $wali_murid->id_wali_murid;
				$siswa->save();

				$pengguna = new Pengguna;
				$pengguna->id_pengguna = $wali_murid->id_wali_murid;
				$pengguna->nm_pengguna = $input->nm_ayah;
				$pengguna->id_sekolah = $input->auth_data->sekolah_data->id_sekolah;
				$pengguna->id_status_pengguna = "Fh2L415358554335b8b4b49e1659";
				$pengguna->username = $input->nomor_hp_ortu;
				$pengguna->password = Hash::make($input->nomor_hp_ortu);
				$pengguna->status_join_table = 4;
				$pengguna->save();

				$role = RolePengguna::where('id_pengguna', $wali_murid->id_wali_murid)->first();
				$role->id_role = 4;
				$role->save();
			}

			if ($siswa != null || $calonSiswa != null) {
				DB::beginTransaction();
				try {
					DB::table('pengguna')->where('id_pengguna', $input->id_pengguna)->update([
						'nm_pengguna' 			=> $input->nm_pengguna,
						'username' 				=> $input->nis_siswa,
						'password' 				=> Hash::make($input->nis_siswa),
						'id_sekolah' 			=> $input->auth_data->pengguna->id_sekolah,
						'id_status_pengguna'	=> $input->id_status_pengguna,
						'must_change_password' 	=> 1,
						'status_join_table' 	=> 3,
						'updated_at' 			=> $now,
						'email_pengguna'		=> $input->email_pengguna,
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
						'tgl_lahir'				=> date('Y-m-d', strtotime($input->tgl_lahir)),
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
						'bahasa_sehari_hari'	=> $input->bahasa_sehari_hari,
						'updated_at' 			=> $now,
						'updated_by' 			=> $input->auth_data->pengguna->id_pengguna
					]);

					DB::table('calon_siswa_ortu')->where('id_c_siswa', $input->id_c_siswa)->update([
						'nm_ayah'					=> $input->nm_ayah,
						'status_ayah'				=> $input->status_ayah,
						'nik_ayah'					=> $input->nik_ayah,
						'tgl_lahir_ayah'			=> date('Y-m-d', strtotime($input->tgl_lahir_ayah)),
						'id_jenis_pendidikan_ayah'	=> $input->id_jenis_pendidikan_ayah,
						'id_jenis_pekerjaan_ayah'	=> $input->id_jenis_pekerjaan_ayah,
						'id_jenis_penghasilan_ayah'	=> $input->id_jenis_penghasilan_ayah,
						'id_kebutuhan_khusus_ayah'	=> $input->id_kebutuhan_khusus_ayah,
						'alamat_jalan_ayah'			=> $input->alamat_jalan_ayah,
						'alamat_dusun_ayah'			=> $input->alamat_dusun_ayah,
						'alamat_kelurahan_ayah'		=> $input->alamat_kelurahan_ayah,
						'almat_rt_ayah'				=> $input->alamat_rt_ayah,
						'alamat_rw_ayah'			=> $input->alamat_rw_ayah,
						'alamat_kecamatan_ayah'		=> $input->alamat_kecamatan_ayah,
						'alamat_kodepos_ayah'		=> $input->alamat_kodepos_ayah,
						'alamat_kota_ayah'			=> $input->alamat_kota_ayah,
						'alamat_provinsi_ayah'		=> $input->alamat_provinsi_ayah,
						'nm_ibu'					=> $input->nm_ibu,
						'status_ibu'				=> $input->status_ibu,
						'nik_ibu'					=> $input->nik_ibu,
						'tgl_lahir_ibu'			    => date('Y-m-d', strtotime($input->tgl_lahir_ibu)),
						'id_jenis_pendidikan_ibu'	=> $input->id_jenis_pendidikan_ibu,
						'id_jenis_pekerjaan_ibu'	=> $input->id_jenis_pekerjaan_ibu,
						'id_jenis_penghasilan_ibu'	=> $input->id_jenis_penghasilan_ibu,
						'id_kebutuhan_khusus_ibu'	=> $input->id_kebutuhan_khusus_ibu,
						'alamat_jalan_ibu'			=> $input->alamat_jalan_ibu,
						'alamat_dusun_ibu'			=> $input->alamat_dusun_ibu,
						'alamat_kelurahan_ibu'		=> $input->alamat_kelurahan_ibu,
						'almat_rt_ibu'			    => $input->alamat_rt_ibu,
						'alamat_rw_ibu'			    => $input->alamat_rw_ibu,
						'alamat_kecamatan_ibu'		=> $input->alamat_kecamatan_ibu,
						'alamat_kodepos_ibu'		=> $input->alamat_kodepos_ibu,
						'alamat_kota_ibu'			=> $input->alamat_kota_ibu,
						'alamat_provinsi_ibu'		=> $input->alamat_provinsi_ibu,
						'nm_wali'					=> $input->nm_wali,
						'status_wali'				=> $input->status_wali,
						'nik_wali'					=> $input->nik_wali,
						'tgl_lahir_wali'			=> date('Y-m-d', strtotime($input->tgl_lahir_wali)),
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

					DB::commit();
					return [
						'status' => 200, // SUCCESS AND LOAD TABLE
						'message' => 'Update Data Siswa Berhasil!'
					];
				} catch (\Exception $e) {
					DB::rollback();

					return [
						'status' 	=> 200, // GAGAL
						'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine()
					];
				}
			} else {
				return [
					'status' => 200, // SUCCESS AND LOAD TABLE
					'message' => 'Siswa Tidak Ditemukan!'
				];
			}
		}
	}
}
