<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use App\Libraries\LibGlobal;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Pengguna as Pengguna;
use App\Models\Siswa as Siswa;
use App\Models\Kelas as Kelas;
use App\Models\Jalur as Jalur;
use App\Models\Semester as Semester;
use App\Models\CalonSiswaBaru as CalonSiswaBaru;
use App\Models\CalonSiswaSekolah as CalonSiswaSekolah;
use App\Models\CalonSiswaOrtu as CalonSiswaOrtu;
use App\Models\CalonSiswaFisik as CalonSiswaFisik;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Penerimaan as Penerimaan;
use App\Models\LogKelasSiswa;
use App\Models\Voucher;
use App\Models\Agama;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\PengajuanWisuda;
use App\Models\KebutuhanKhusus;
use App\Models\JenisTinggal;
use App\Models\JenisTransportasi;
use App\Models\JenisLayakPip;
use App\Models\JenisPendidikan;
use App\Models\JenisPekerjaan;
use App\Models\JenisPenghasilan;
use App\Imports\SiswaImport;
use App\Imports\UploadToInsertUpdateSiswa;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
// use Excel;
use DB;
use Session;
use Validator;
use App\Imports\DataImportExcel;
use Barryvdh\Debugbar\Facade as Debugbar;


class UploadDataSiswaController extends BaseController
{

	public function cekFileExcel(Request $request)
	{
		session()->forget('data_excel_siswa');
		if ($request->hasFile('file-excel')) {
			$datas = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
			$datas = $datas[0];
			if (count($datas)) {
				session(['data_excel_siswa' => $datas]);

				return [
					'status' => 204, // SUCCESS AND LOAD CONTENT
					'path' => 'siswa/upload-data-siswa/cek-data-siswa'
				];
			}
		}
	}

	public function viewCekFileExcel(Request $request)
	{
		$input = (object) $request->input();
		$auth_data = $input->auth_data;
		$datas = session('data_excel_siswa');
		$data_status_pengguna = StatusPengguna::where('status_join_table', '=', '3')->get();
		$data_kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat')->orderBy('nm_kelas')->get();
		$data_jalur = Jalur::get();
		$data_semester_masuk = Semester::orderBy('kode_semester')->get();
		$data_penerimaan = Penerimaan::orderBy('tahun_penerimaan')->get();
		$data_agama = Agama::get();
		$data_kota = Kota::orderBy('nm_kota')->get();
		$data_kebutuhan_khusus = KebutuhanKhusus::get();
		$data_provinsi = Provinsi::orderBy('nm_provinsi')->get();
		$data_jenis_tinggal = JenisTinggal::get();
		$data_jenis_transportasi = JenisTransportasi::get();
		$data_jenis_layak_pip = JenisLayakPip::get();
		$data_jenis_pendidikan = JenisPendidikan::get();
		$data_jenis_pekerjaan = JenisPekerjaan::get();
		$data_jenis_penghasilan = JenisPenghasilan::get();
		$data_voucher = Voucher::get();

		$jenis['data_status_pengguna'] = $data_status_pengguna;
		$jenis['data_kelas'] = $data_kelas;
		$jenis['data_jalur'] = $data_jalur;
		$jenis['data_semester_masuk'] =  $data_semester_masuk;
		$jenis['data_penerimaan'] = $data_penerimaan;
		$jenis['data_agama'] = $data_agama;
		$jenis['data_kota'] = $data_kota;
		$jenis['data_kebutuhan_khusus'] = $data_kebutuhan_khusus;
		$jenis['data_provinsi'] = $data_provinsi;
		$jenis['data_jenis_tinggal'] = $data_jenis_tinggal;
		$jenis['data_jenis_transportasi'] = $data_jenis_transportasi;
		$jenis['data_jenis_layak_pip'] = $data_jenis_layak_pip;
		$jenis['data_jenis_pendidikan'] = $data_jenis_pendidikan;
		$jenis['data_jenis_pekerjaan'] = $data_jenis_pekerjaan;
		$jenis['data_jenis_penghasilan'] = $data_jenis_penghasilan;
		$jenis['data_voucher'] = $data_voucher;

		return view('pendidikan/siswa/upload-data-siswa/view-file-excel-data-siswa', compact('auth_data', 'datas', 'jenis'));
	}

	public function postCekFileExcel(Request $request)
	{
		$datas = session('data_excel_siswa');
		if (count($datas)) {
			$data_nis = array();
			foreach ($datas as $data) {
				if (!empty($data['nis'])) {
					$data_nis[] = $data['nis'];
				}
			}

			$jenis = array();
			foreach ($data as $key => $i) {
				$jenis[] = $key;
			}


			$data_siswa = Siswa::whereIn('nis_siswa', $data_nis)->get();
			$data_status_pengguna = StatusPengguna::where('status_join_table', '=', '3')->get();
			$data_kelas = Kelas::where('is_aktif', 1)->get();
			$data_jalur = Jalur::get();
			$data_semester_masuk = Semester::get();
			$data_penerimaan = Penerimaan::get();
			$data_agama = Agama::get();
			$data_kota = Kota::get();
			$data_kebutuhan_khusus = KebutuhanKhusus::get();
			$data_provinsi = Provinsi::get();
			$data_jenis_tinggal = JenisTinggal::get();
			$data_jenis_transportasi = JenisTransportasi::get();
			$data_jenis_layak_pip = JenisLayakPip::get();
			$data_jenis_pendidikan = JenisPendidikan::get();
			$data_jenis_pekerjaan = JenisPekerjaan::get();
			$data_jenis_penghasilan = JenisPenghasilan::get();
			$data_voucher = Voucher::get();

			$validasi = [];
			foreach ($datas as $key => $data_row) {
				// $no = 1;
				$value = new \stdClass();
				foreach ($data_row as $key => $temp_item) {
					$value->$key = $temp_item;
				}

				if (empty($value->nis)) {
					continue;
				}

				$check_nis_siswa = $data_siswa->firstWhere('nis_siswa', (string) $value->nis);
				if (empty($check_nis_siswa)) {
					$validasi[$value->nis]['status'] = 'Insert';
				} else {
					$validasi[$value->nis]['status'] = 'Update';
				}

				$status = $data_status_pengguna->firstWhere('nm_status_pengguna', '=', $value->status_siswa);
				if (empty($status)) {
					$validasi[$value->nis]['status_siswa'] = true;
				}

				$kelas = $data_kelas->firstWhere('nm_kelas', '=', $value->kelas);
				if (empty($kelas)) {
					$validasi[$value->nis]['kelas'] = true;
				}

				$jalur = $data_jalur->firstWhere('nm_jalur', '=', $value->jalur);
				if (empty($jalur)) {
					$validasi[$value->nis]['jalur'] = true;
				}


				if (empty($value->jenis_kelamin)) {
				} else {
					if ($value->jenis_kelamin == "L" || $value->jenis_kelamin == "P") {
					} else {
						$validasi[$value->nis]['jenis_kelamin'] = true;
					}
				}


				$semester_masuk = $data_semester_masuk->firstWhere('kode_semester', '=', $value->semester_masuk);
				if (empty($semester_masuk)) {
					$validasi[$value->nis]['semester_masuk'] = true;
				}

				$id_penerimaan = $data_penerimaan->firstWhere('tahun_penerimaan', '=', (int) $value->tahun_masuk);
				if (empty($id_penerimaan)) {
					$validasi[$value->nis]['tahun_masuk'] = true;
				}


				if (empty($value->orang_tua_kandung)) {
				} else {
					if ($value->orang_tua_kandung == 1 || $value->orang_tua_kandung == 0) {
					} else {
						$validasi[$value->nis]['orang_tua_kandung'] = true;
					}
				}

				if (empty($value->kode_voucher)) {
				} else {
					$find_voucher = $data_voucher->firstWhere('kode_voucher', $value->kode_voucher);
					if ($find_voucher) {
					} else {
						$validasi[$value->nis]['kode_voucher'] = true;
					}
				}

				if (empty($value->agama)) {
				} else {
					$find_agama = $data_agama->firstWhere('kode_agama', $value->agama);
					if ($find_agama) {
					} else {
						$validasi[$value->nis]['agama'] = true;
					}
				}

				if (empty($value->kota_lahir)) {
				} else {
					$find_kota = $data_kota->firstWhere('nm_kota', $value->kota_lahir);
					if ($find_kota) {
					} else {
						$validasi[$value->nis]['kota_lahir'] = true;
					}
				}

				if (empty($value->tanggal_lahir)) {
				} else {
					$tanggal_lahir = date('Y-m-d', strtotime($value->tanggal_lahir));
					if ($tanggal_lahir != '1970-01-01') {
					} else {
						$validasi[$value->nis]['tanggal_lahir'] = true;
					}
				}

				if (empty($value->nama_kota_ksk)) {
				} else {
					$find_kota_ksk = $data_kota->firstWhere('nm_kota', $value->nama_kota_ksk);
					if ($find_kota_ksk) {
					} else {
						$validasi[$value->nis]['nama_kota_ksk'] = true;
					}
				}


				// if (empty($value->nomor_ksk)) {
				// 	$nomor_ksk = null;
				// } else {
				// 	$nomor_ksk = $value->nomor_ksk;
				// }

				// nomor identitas

				// if (empty($value->nomor_identitas_ktp_sim_lainya)) {
				// 	$nomor_identitas = null;
				// } else {
				// 	$nomor_identitas = $value->nomor_identitas_ktp_sim_lainya;
				// }

				// nomor akta lahir

				// if (empty($value->nomor_registrasi_akta_lahir)) {
				// 	$nomor_akta_lahir = null;
				// } else {
				// 	$nomor_akta_lahir = $value->nomor_registrasi_akta_lahir;
				// }

				//find kewarganegaraan
				if (empty($value->kewarganegaraan)) {
				} else {
					if ($value->kewarganegaraan == 1 || $value->kewarganegaraan == 0) {
					} else {
						$validasi[$value->nis]['kewarganegaraan'] = true;
					}
				}


				// nm_kewarganegaraan
				// if (empty($value->nama_kewarganegaraan_jika_dari_wna)) {
				// 	$nm_kewarganegaraan = null;
				// } else {
				// 	$nm_kewarganegaraan = $value->nama_kewarganegaraan_jika_dari_wna;
				// }

				// find kebutuhan khusus
				if (empty($value->kebutuhan_khusus)) {
					// $kebutuhan_khusus = null;
				} else {
					$find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus);
					if ($find_kebutuhan_khusus) {
						// $kebutuhan_khusus = $find_kebutuhan_khusus->id_kebutuhan_khusus;
					} else {
						$validasi[$value->nis]['kebutuhan_khusus'] = true;
					}
				}

				// alamat jalan
				// if (empty($value->alamat_jalan)) {
				// 	$alamat_jalan = null;
				// } else {
				// 	$alamat_jalan = $value->alamat_jalan;
				// }

				// alamat dusun
				// if (empty($value->alamat_dusun)) {
				// 	$alamat_dusun = null;
				// } else {
				// 	$alamat_dusun = $value->alamat_dusun;
				// }

				// alamat kelurahan
				// if (empty($value->alamat_kelurahan)) {
				// 	$alamat_kelurahan = null;
				// } else {
				// 	$alamat_kelurahan = $value->alamat_kelurahan;
				// }

				// alamat rt
				// if (empty($value->alamat_rt)) {
				// 	$alamat_rt = null;
				// } else {
				// 	$alamat_rt = $value->alamat_rt;
				// }

				// alamat rw
				// if (empty($value->alamat_rw)) {
				// 	$alamat_rw = null;
				// } else {
				// 	$alamat_rw = $value->alamat_rw;
				// }

				// alamat kecamatan
				// if (empty($value->alamat_kecamatan)) {
				// 	$alamat_kecamatan = null;
				// } else {
				// 	$alamat_kecamatan = $value->alamat_kecamatan;
				// }

				// alamat kodepos
				// if (empty($value->alamat_kodepos)) {
				// 	$alamat_kodepos = null;
				// } else {
				// 	$alamat_kodepos = $value->alamat_kodepos;
				// }

				// find alamat kota
				if (empty($value->alamat_kota)) {
					// $alamat_kota = null;
				} else {
					$find_alamat_kota = $data_kota->firstWhere('nm_kota', $value->alamat_kota);
					if ($find_alamat_kota) {
						// $alamat_kota = $find_alamat_kota->id_kota;
					} else {
						$validasi[$value->nis]['alamat_kota'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota ' . $value->alamat_kota . ' tidak ditemukan di dalam sistem';
						// Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota ' . $value->alamat_kota . ' tidak ditemukan di dalam sistem');
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota ' . $value->alamat_kota . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// find id provinsi
				if (empty($value->alamat_provinsi)) {
					// $alamat_provinsi = null;
				} else {
					$find_provinsi = $data_provinsi->firstWhere('nm_provinsi', $value->alamat_provinsi);
					if ($find_provinsi) {
						// $alamat_provinsi = $find_provinsi->id_provinsi;
					} else {
						$validasi[$value->nis]['alamat_provinsi'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi ' . $value->alamat_provinsi . ' tidak ditemukan di dalam sistem';
						// Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi ' . $value->alamat_provinsi . ' tidak ditemukan di dalam sistem');
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi ' . $value->alamat_provinsi . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// alamat lattitude
				// if (empty($value->alamat_latitude)) {
				// 	$alamat_latitude = null;
				// } else {
				// 	$alamat_latitude = $value->alamat_latitude;
				// }

				// alamat longitude
				// if (empty($value->alamat_longitude)) {
				// 	$alamat_longitude = null;
				// } else {
				// 	$alamat_longitude = $value->alamat_longitude;
				// }

				// nomor hp
				// if (empty($value->nomor_hp)) {
				// 	$nomor_hp = null;
				// } else {
				// 	$nomor_hp = $value->nomor_hp;
				// }

				// find jenis tinggal
				if (empty($value->jenis_tinggal)) {
					// $jenis_tinggal = null;
				} else {
					$find_jenis_tinggal = $data_jenis_tinggal->firstWhere('kode_jenis_tinggal', $value->jenis_tinggal);
					if ($find_jenis_tinggal) {
						// $jenis_tinggal = $find_jenis_tinggal->id_jenis_tinggal;
					} else {
						$validasi[$value->nis]['jenis_tinggal'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis tinggal ' . $value->jenis_tinggal . ' tidak ditemukan di dalam sistem';
						// Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis tinggal ' . $value->jenis_tinggal . ' tidak ditemukan di dalam sistem');
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis tinggal ' . $value->jenis_tinggal . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// anak ke
				// if (empty($value->anak_ke)) {
				// 	$anak_ke = null;
				// } else {
				// 	$anak_ke = (int) $value->anak_ke;
				// }

				// dari x bersaudara
				// if (empty($value->dari_berapa_bersaudara)) {
				// 	$dari_berapa_bersaudara = null;
				// } else {
				// 	$dari_berapa_bersaudara = (int) $value->dari_berapa_bersaudara;
				// }

				// jarak rumah ke sekolah (km)
				// if (empty($value->jarak_rumah_ke_sekolah_km)) {
				// 	$jarak_rumah_ke_sekolah_km = null;
				// } else {
				// 	$jarak_rumah_ke_sekolah_km = (float) $value->jarak_rumah_ke_sekolah_km;
				// }

				// waktu tempuh sekolah (jam)
				// if (empty($value->waktu_tempuh_ke_sekolah_jam)) {
				// 	$waktu_tempuh_ke_sekolah_jam = null;
				// } else {
				// 	$waktu_tempuh_ke_sekolah_jam = (float) $value->waktu_tempuh_ke_sekolah_jam;
				// }

				// waktu tempuh sekkolah (menit)
				// if (empty($value->waktu_tempuh_ke_sekolah_menit)) {
				// 	$waktu_tempuh_ke_sekolah_menit = null;
				// } else {
				// 	$waktu_tempuh_ke_sekolah_menit = (float) $value->waktu_tempuh_ke_sekolah_menit;
				// }

				// find jenis transportasi
				if (empty($value->jenis_transportasi)) {
					// $jenis_transportasi = null;
				} else {
					$find_jenis_transportasi = $data_jenis_transportasi->firstWhere('kode_jenis_transportasi', $value->jenis_transportasi);
					if ($find_jenis_transportasi) {
						// $jenis_transportasi = $find_jenis_transportasi->id_jenis_transportasi;
					} else {
						$validasi[$value->nis]['jenis_transportasi'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis transportasi ' . $value->jenis_transportasi . ' tidak ditemukan di dalam sistem';
						// Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis transportasi ' . $value->jenis_transportasi . ' tidak ditemukan di dalam sistem');
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis transportasi ' . $value->jenis_transportasi . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// nomor kks
				// if (empty($value->nomor_kartu_keluarga_sejahtera)) {
				// 	$nomor_kartu_keluarga_sejahtera = null;
				// } else {
				// 	$nomor_kartu_keluarga_sejahtera = $value->nomor_kartu_keluarga_sejahtera;
				// }

				// is penerima kps
				// if ($value->merupakan_penerima_kartu_perlindungan_sosial == 1 || $value->merupakan_penerima_kartu_perlindungan_sosial == 0) {
				// 	// $merupakan_penerima_kartu_perlindungan_sosial = (int) $value->merupakan_penerima_kartu_perlindungan_sosial;
				// } else {
				// 	$merupakan_penerima_kartu_perlindungan_sosial = null;
				// }

				// nomor kps
				// if (empty($value->nomor_kartu_perlindungan_sosial)) {
				// 	$nomor_kartu_perlindungan_sosial = null;
				// } else {
				// 	$nomor_kartu_perlindungan_sosial = $value->nomor_kartu_perlindungan_sosial;
				// }

				// is punya kip
				// if ($value->merupakan_penerima_kartu_indonesia_pintar == 1 || $value->merupakan_penerima_kartu_indonesia_pintar == 0) {
				// 	$merupakan_penerima_kartu_indonesia_pintar = (int) $value->merupakan_penerima_kartu_indonesia_pintar;
				// } else {
				// 	$merupakan_penerima_kartu_indonesia_pintar = null;
				// }

				// nomor kip
				// if (empty($value->nomor_kartu_indonesia_pintar)) {
				// 	$nomor_kartu_indonesia_pintar = null;
				// } else {
				// 	$nomor_kartu_indonesia_pintar = $value->nomor_kartu_indonesia_pintar;
				// }

				// nama tertera kip
				// if (empty($value->nama_tertera_pada_kip)) {
				// 	$nama_tertera_pada_kip = null;
				// } else {
				// 	$nama_tertera_pada_kip = $value->nama_tertera_pada_kip;
				// }

				// is layak pip
				// if ($value->layak_pip == 1 || $value->layak_pip == 0) {
				// 	$layak_pip = (int) $value->layak_pip;
				// } else {
				// 	$layak_pip = null;
				// }

				// find jenis layak pip
				if (empty($value->jenis_layak_pip)) {
					// $jenis_layak_pip = null;
				} else {
					$find_jenis_layak = $data_jenis_layak_pip->firstWhere('kode_jenis_layak_pip', $value->jenis_layak_pip);
					if ($find_jenis_layak) {
						// $jenis_layak_pip = $find_jenis_layak->id_jenis_layak_pip;
					} else {
						$validasi[$value->nis]['jenis_layak_pip'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis layak pip ' . $value->jenis_layak_pip . ' tidak ditemukan di dalam sistem';
						// Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis layak pip ' . $value->jenis_layak_pip . ' tidak ditemukan di dalam sistem');
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis layak pip ' . $value->jenis_layak_pip . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// asal sekolah
				// if (empty($value->asal_sekolah)) {
				// 	$asal_sekolah = null;
				// } else {
				// 	$asal_sekolah = $value->asal_sekolah;
				// }

				// id kota sekolah asal
				if (empty($value->kota_asal_sekolah_sebelumnya)) {
					// $kota_asal_sekolah_sebelumnya = null;
				} else {
					$find_kota_sekolah_asal = $data_kota->firstWhere('nm_kota', $value->kota_asal_sekolah_sebelumnya);
					if ($find_kota_sekolah_asal) {
						// $kota_asal_sekolah_sebelumnya = $find_alamat_kota->id_kota;
					} else {
						$validasi[$value->nis]['kota_asal_sekolah_sebelumnya'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kota asal sekolah sebelumnya ' . $value->kota_asal_sekolah_sebelumnya . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kota asal sekolah sebelumnya ' . $value->kota_asal_sekolah_sebelumnya . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kota asal sekolah sebelumnya ' . $value->kota_asal_sekolah_sebelumnya . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// nomor ujian sebelumnya
				// if (empty($value->nomor_ujian_sebelumnya)) {
				// 	$nomor_ujian_sebelumnya = null;
				// } else {
				// 	$nomor_ujian_sebelumnya = $value->nomor_ujian_sebelumnya;
				// }

				// // nomor ijasah sebelumnya
				// if (empty($value->nomor_ijasah_sebelumnya)) {
				// 	$nomor_ijasah_sebelumnya = null;
				// } else {
				// 	$nomor_ijasah_sebelumnya = $value->nomor_ijasah_sebelumnya;
				// }

				// // nomor skhus sebelumnya
				// if (empty($value->nomor_skhus_sebelumnya)) {
				// 	$nomor_skhus_sebelumnya = null;
				// } else {
				// 	$nomor_skhus_sebelumnya = $value->nomor_skhus_sebelumnya;
				// }

				// // nomor shun
				// if (empty($value->nomor_shun_sebelumnya)) {
				// 	$nomor_shun_sebelumnya = null;
				// } else {
				// 	$nomor_shun_sebelumnya = $value->nomor_shun_sebelumnya;
				// }

				// // nilai shun
				// if (empty($value->nilai_shun_sebelumnya)) {
				// 	$nilai_shun_sebelumnya = null;
				// } else {
				// 	$nilai_shun_sebelumnya = (float) $value->nilai_shun_sebelumnya;
				// }

				// // tahun lulus
				// if (empty($value->tahun_lulus)) {
				// 	$tahun_lulus = null;
				// } else {
				// 	$tahun_lulus = (int) $value->tahun_lulus;
				// }

				// // no peserta unas
				// if (empty($value->nomor_peserta_unas)) {
				// 	$nomor_peserta_unas = null;
				// } else {
				// 	$nomor_peserta_unas = $value->nomor_peserta_unas;
				// }

				// // nama ayah
				// if (empty($value->nama_ayah)) {
				// 	$nama_ayah = null;
				// } else {
				// 	$nama_ayah = $value->nama_ayah;
				// }

				// // nik ayah
				// if (empty($value->nik_ayah)) {
				// 	$nik_ayah = null;
				// } else {
				// 	$nik_ayah = $value->nik_ayah;
				// }

				// tanggal lahir ayah
				if (empty($value->tanggal_lahir_ayah)) {
					// $tanggal_lahir_ayah = null;
				} else {
					$tanggal_lahir_ayah = date('Y-m-d', strtotime($value->tanggal_lahir_ayah));

					if ($tanggal_lahir_ayah  != '1970-01-01') {
					} else {
						$validasi[$value->nis]['tanggal_lahir_ayah'] = true;
					}
				}

				// jenis pendidikan ayah
				if (empty($value->jenis_pendidikan_ayah)) {
					// $jenis_pendidikan_ayah = null;
				} else {
					$find_jenis_pendidikan = $data_jenis_pendidikan->firstWhere('kode_jenis_pendidikan', $value->jenis_pendidikan_ayah);
					if ($find_jenis_pendidikan) {
						// $jenis_pendidikan_ayah = $find_jenis_pendidikan->id_jenis_pendidikan;
					} else {
						$validasi[$value->nis]['jenis_pendidikan_ayah'] = true;


						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ayah ' . $value->jenis_pendidikan_ayah . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ayah ' . $value->jenis_pendidikan_ayah . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ayah ' . $value->jenis_pendidikan_ayah . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// jenis pekerjaan ayah
				if (empty($value->jenis_pekerjaan_ayah)) {
					// $jenis_pekerjaan_ayah = null;
				} else {
					$find_jenis_pekerjaan = $data_jenis_pekerjaan->firstWhere('kode_jenis_pekerjaan', $value->jenis_pekerjaan_ayah);
					if ($find_jenis_pekerjaan) {
						// $jenis_pekerjaan_ayah = $find_jenis_pekerjaan->id_jenis_pekerjaan;
					} else {
						$validasi[$value->nis]['jenis_pekerjaan_ayah'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ayah ' . $value->jenis_pekerjaan_ayah . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ayah ' . $value->jenis_pekerjaan_ayah . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ayah ' . $value->jenis_pekerjaan_ayah . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// jenis penghasilan ayah
				if (empty($value->jenis_penghasilan_ayah)) {
					// $jenis_penghasilan_ayah = null;
				} else {
					$find_jenis_penghasilan = $data_jenis_penghasilan->firstWhere('kode_jenis_penghasilan', $value->jenis_penghasilan_ayah);
					if ($find_jenis_penghasilan) {
						// $jenis_penghasilan_ayah = $find_jenis_penghasilan->id_jenis_penghasilan;
					} else {
						$validasi[$value->nis]['jenis_penghasilan_ayah'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ayah ' . $value->jenis_penghasilan_ayah . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ayah ' . $value->jenis_penghasilan_ayah . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ayah ' . $value->jenis_penghasilan_ayah . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// kebutuhan khusus ayah
				if (empty($value->kebutuhan_khusus_ayah)) {
					// $kebutuhan_khusus_ayah = null;
				} else {
					$find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus_ayah);
					if ($find_kebutuhan_khusus) {
						// $kebutuhan_khusus_ayah = $find_kebutuhan_khusus->id_kebutuhan_khusus;
					} else {
						$validasi[$value->nis]['kebutuhan_khusus_ayah'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ayah ' . $value->kebutuhan_khusus_ayah . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ayah ' . $value->kebutuhan_khusus_ayah . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ayah ' . $value->kebutuhan_khusus_ayah . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// nama ibu
				// if (empty($value->nama_ibu)) {
				// 	$nama_ibu = null;
				// } else {
				// 	$nama_ibu = $value->nama_ibu;
				// }

				// // nik ibu
				// if (empty($value->nik_ibu)) {
				// 	$nik_ibu = null;
				// } else {
				// 	$nik_ibu = $value->nik_ibu;
				// }

				// tanggal lahir ibu
				if (empty($value->tanggal_lahir_ibu)) {
					// $tanggal_lahir_ibu = null;
				} else {
					$tanggal_lahir_ibu = date('Y-m-d', strtotime($value->tanggal_lahir_ibu));
					if ($tanggal_lahir_ibu  != '1970-01-01') {
					} else {
						$validasi[$value->nis]['tanggal_lahir_ibu'] = true;
					}
				}

				// jenis pendidikan ibu
				if (empty($value->jenis_pendidikan_ibu)) {
					// $jenis_pendidikan_ibu = null;
				} else {
					$find_jenis_pendidikan = $data_jenis_pendidikan->firstWhere('kode_jenis_pendidikan', $value->jenis_pendidikan_ibu);
					if ($find_jenis_pendidikan) {
						// $jenis_pendidikan_ibu = $find_jenis_pendidikan->id_jenis_pendidikan;
					} else {
						$validasi[$value->nis]['jenis_pendidikan_ibu'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ibu ' . $value->jenis_pendidikan_ibu . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ibu ' . $value->jenis_pendidikan_ibu . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ibu ' . $value->jenis_pendidikan_ibu . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// jenis pekerjaan ibu
				if (empty($value->jenis_pekerjaan_ibu)) {
					// $jenis_pekerjaan_ibu = null;
				} else {
					$find_jenis_pekerjaan = $data_jenis_pekerjaan->firstWhere('kode_jenis_pekerjaan', $value->jenis_pekerjaan_ibu);
					if ($find_jenis_pekerjaan) {
						// $jenis_pekerjaan_ibu = $find_jenis_pekerjaan->id_jenis_pekerjaan;
					} else {
						$validasi[$value->nis]['jenis_pekerjaan_ibu'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ibu ' . $value->jenis_pekerjaan_ibu . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ibu ' . $value->jenis_pekerjaan_ibu . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ibu ' . $value->jenis_pekerjaan_ibu . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// jenis penghasilan ibu
				if (empty($value->jenis_penghasilan_ibu)) {
					// $jenis_penghasilan_ibu = null;
				} else {
					$find_jenis_penghasilan = $data_jenis_penghasilan->firstWhere('kode_jenis_penghasilan', $value->jenis_penghasilan_ibu);
					if ($find_jenis_penghasilan) {
						// $jenis_penghasilan_ibu = $find_jenis_penghasilan->id_jenis_penghasilan;
					} else {
						$validasi[$value->nis]['jenis_penghasilan_ibu'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ibu ' . $value->jenis_penghasilan_ibu . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ibu ' . $value->jenis_penghasilan_ibu . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ibu ' . $value->jenis_penghasilan_ibu . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// kebutuhan khusus ibu
				if (empty($value->kebutuhan_khusus_ibu)) {
					// $kebutuhan_khusus_ibu = null;
				} else {
					$find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus_ibu);
					if ($find_kebutuhan_khusus) {
						// $kebutuhan_khusus_ibu = $find_kebutuhan_khusus->id_kebutuhan_khusus;
					} else {
						$validasi[$value->nis]['kebutuhan_khusus_ibu'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ibu ' . $value->kebutuhan_khusus_ibu . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ibu ' . $value->kebutuhan_khusus_ibu . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ibu ' . $value->kebutuhan_khusus_ibu . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// nama wali
				// if (empty($value->nama_wali)) {
				// 	$nama_wali = null;
				// } else {
				// 	$nama_wali = $value->nama_wali;
				// }

				// // nik wali
				// if (empty($value->nik_wali)) {
				// 	$nik_wali = null;
				// } else {
				// 	$nik_wali = $value->nik_wali;
				// }

				// tanggal lahir wali
				if (empty($value->tanggal_lahir_wali)) {
					// $tanggal_lahir_wali = null;
				} else {
					$tanggal_lahir_wali = date('Y-m-d', strtotime($value->tanggal_lahir_wali));
					if ($tanggal_lahir_wali  != '1970-01-01') {
					} else {
						$validasi[$value->nis]['tanggal_lahir'] = true;
					}
				}

				// jenis pendidikan wali
				if (empty($value->jenis_pendidikan_wali)) {
					// $jenis_pendidikan_wali = null;
				} else {
					$find_jenis_pendidikan = $data_jenis_pendidikan->firstWhere('kode_jenis_pendidikan', $value->jenis_pendidikan_wali);
					if ($find_jenis_pendidikan) {
						// $jenis_pendidikan_wali = $find_jenis_pendidikan->id_jenis_pendidikan;
					} else {
						$validasi[$value->nis]['jenis_pendidikan_wali'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan wali ' . $value->jenis_pendidikan_wali . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan wali ' . $value->jenis_pendidikan_wali . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan wali ' . $value->jenis_pendidikan_wali . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// jenis pekerjaan wali
				if (empty($value->jenis_pekerjaan_wali)) {
					// $jenis_pekerjaan_wali = null;
				} else {
					$find_jenis_pekerjaan = $data_jenis_pekerjaan->firstWhere('kode_jenis_pekerjaan', $value->jenis_pekerjaan_wali);
					if ($find_jenis_pekerjaan) {
						// $jenis_pekerjaan_wali = $find_jenis_pekerjaan->id_jenis_pekerjaan;
					} else {
						$validasi[$value->nis]['jenis_pekerjaan_wali'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan wali ' . $value->jenis_pekerjaan_wali . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan wali ' . $value->jenis_pekerjaan_wali . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan wali ' . $value->jenis_pekerjaan_wali . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// jenis penghasilan wali
				if (empty($value->jenis_penghasilan_wali)) {
					// $jenis_penghasilan_wali = null;
				} else {
					$find_jenis_penghasilan = $data_jenis_penghasilan->firstWhere('kode_jenis_penghasilan', $value->jenis_penghasilan_wali);
					if ($find_jenis_penghasilan) {
						// $jenis_penghasilan_wali = $find_jenis_penghasilan->id_jenis_penghasilan;
					} else {
						$validasi[$value->nis]['jenis_penghasilan_wali'] = true;



						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan wali ' . $value->jenis_penghasilan_wali . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan wali ' . $value->jenis_penghasilan_wali . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan wali ' . $value->jenis_penghasilan_wali . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// kebutuhan khusus wali
				if (empty($value->kebutuhan_khusus_wali)) {
					// $kebutuhan_khusus_wali = null;
				} else {
					$find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus_wali);
					if ($find_kebutuhan_khusus) {
						// $kebutuhan_khusus_wali = $find_kebutuhan_khusus->id_kebutuhan_khusus;
					} else {
						$validasi[$value->nis]['kebutuhan_khusus_wali'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus wali ' . $value->kebutuhan_khusus_wali . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus wali ' . $value->kebutuhan_khusus_wali . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus wali ' . $value->kebutuhan_khusus_wali . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// alamat jalan orang tua
				// if (empty($value->alamat_jalan_orang_tua)) {
				// 	$alamat_jalan_orang_tua = null;
				// } else {
				// 	$alamat_jalan_orang_tua = $value->alamat_jalan_orang_tua;
				// }

				// // alamat dusun orang tua
				// if (empty($value->alamat_dusun_orang_tua)) {
				// 	$alamat_dusun_orang_tua = null;
				// } else {
				// 	$alamat_dusun_orang_tua = $value->alamat_dusun_orang_tua;
				// }

				// // alamat kelurahan orang tua
				// if (empty($value->alamat_kelurahan_orang_tua)) {
				// 	$alamat_kelurahan_orang_tua = null;
				// } else {
				// 	$alamat_kelurahan_orang_tua = $value->alamat_kelurahan_orang_tua;
				// }

				// // alamat rt orang tua
				// if (empty($value->alamat_rt_orang_tua)) {
				// 	$alamat_rt_orang_tua = null;
				// } else {
				// 	$alamat_rt_orang_tua = $value->alamat_rt_orang_tua;
				// }

				// // alamat rw orang tua
				// if (empty($value->alamat_rw_orang_tua)) {
				// 	$alamat_rw_orang_tua = null;
				// } else {
				// 	$alamat_rw_orang_tua = $value->alamat_rw_orang_tua;
				// }

				// // alamat kecamatan orang tua
				// if (empty($value->alamat_kecamatan_orang_tua)) {
				// 	$alamat_kecamatan_orang_tua = null;
				// } else {
				// 	$alamat_kecamatan_orang_tua = $value->alamat_kecamatan_orang_tua;
				// }

				// // alamat kodepos orang tua
				// if (empty($value->alamat_kodepos_orang_tua)) {
				// 	$alamat_kodepos_orang_tua = null;
				// } else {
				// 	$alamat_kodepos_orang_tua = $value->alamat_kodepos_orang_tua;
				// }

				// find alamat kota orang tua
				if (empty($value->alamat_kota_orang_tua)) {
					// $alamat_kota_orang_tua = null;
				} else {
					$find_alamat_kota = $data_kota->firstWhere('nm_kota', $value->alamat_kota_orang_tua);
					if ($find_alamat_kota) {
						// $alamat_kota_orang_tua = $find_alamat_kota->id_kota;
					} else {
						$validasi[$value->nis]['alamat_kota_orang_tua'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota orang tua ' . $value->alamat_kota_orang_tua . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota orang tua ' . $value->alamat_kota_orang_tua . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota orang tua ' . $value->alamat_kota_orang_tua . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// find id provinsi orang tua
				if (empty($value->alamat_provinsi_orang_tua)) {
					// $alamat_provinsi_orang_tua = null;
				} else {
					$find_provinsi = $data_provinsi->firstWhere('nm_provinsi', $value->alamat_provinsi_orang_tua);
					if ($find_provinsi) {
						// $alamat_provinsi_orang_tua = $find_provinsi->id_provinsi;
					} else {
						$validasi[$value->nis]['alamat_provinsi_orang_tua'] = true;

						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi orang tua ' . $value->alamat_provinsi_orang_tua . ' tidak ditemukan di dalam sistem';
						// Debugbar::error(
						// 	'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi orang tua ' . $value->alamat_provinsi_orang_tua . ' tidak ditemukan di dalam sistem'
						// );
						// return [
						// 	'status'    => 300, // FAILED
						// 	'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi orang tua ' . $value->alamat_provinsi_orang_tua . ' tidak ditemukan di dalam sistem'
						// ];
					}
				}

				// no telp orang tua
				// if (empty($value->no_telepon_orang_tua)) {
				// 	$no_telepon_orang_tua = null;
				// } else {
				// 	$no_telepon_orang_tua = $value->no_telepon_orang_tua;
				// }

				// no hp orang tua
				// if (empty($value->no_hp_orang_tua)) {
				// 	$no_hp_orang_tua = null;
				// } else {
				// 	$no_hp_orang_tua = $value->no_hp_orang_tua;
				// }

				// email orang tua
				// if (empty($value->email_orang_tua)) {
				// 	$email_orang_tua = null;
				// } else {
				// 	$email_orang_tua = $value->email_orang_tua;
				// }

				// tinggi badan
				// if (empty($value->tinggi_badan_cm)) {
				// 	$tinggi_badan_cm = null;
				// } else {
				// 	$tinggi_badan_cm = (float) $value->tinggi_badan_cm;
				// }

				// // berat badan
				// if (empty($value->berat_badan_kg)) {
				// 	$berat_badan_kg = null;
				// } else {
				// 	$berat_badan_kg = (float) $value->berat_badan_kg;
				// }

				// is berjilbab
				if (empty($value->apakah_berjilbab)) {
				} else {
					if ($value->apakah_berjilbab == 1 || $value->apakah_berjilbab == 0) {
						// $apakah_berjilbab = (int) $value->apakah_berjilbab;
					} else {
						$validasi[$value->nis]['apakah_berjilbab'] = true;

						// $apakah_berjilbab = null;
					}
				}


				// is buta warna
				if (empty($value->apakah_buta_warna)) {
				} else {
					if ($value->apakah_buta_warna == 1 || $value->apakah_buta_warna == 0) {
						// $apakah_buta_warna = (int) $value->apakah_buta_warna;
					} else {
						$validasi[$value->nis]['apakah_buta_warna'] = true;

						// $apakah_buta_warna = null;
					}
				}


				// ukuran baju
				// if (empty($value->ukuran_baju)) {
				// 	$ukuran_baju = null;
				// } else {
				// 	$ukuran_baju = $value->ukuran_baju;
				// }

				// // riwayat penyakit
				// if (empty($value->riwayat_penyakit)) {
				// 	$riwayat_penyakit = null;
				// } else {
				// 	$riwayat_penyakit = $value->riwayat_penyakit;
				// }

				// golongan darah
				if (empty($value->golongan_darah)) {
				} else {
					if ($value->golongan_darah == 'A' || $value->golongan_darah == 'B' || $value->golongan_darah == 'O' || $value->golongan_darah == 'AB') {
						// $golongan_darah = $value->golongan_darah;
					} else {
						$validasi[$value->nis]['golongan_darah'] = true;

						// $golongan_darah = null;
					}
				}


				// riwayat kelainan jasmani
				// if (empty($value->riwayat_kelainan_jasmani)) {
				// $riwayat_kelainan_jasmani = null;
				// } else {
				// 	$riwayat_kelainan_jasmani = $value->riwayat_kelainan_jasmani;
				// }

				// if ($id_penerimaan == null || $semester_masuk == null || $jenis_kelamin == null || $jalur == null || $kelas == null || $status == null) {
				// 	$this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, Data Tidak Valid pada Siswa "' . $value->nama_lengkap . '"';
				// 	Debugbar::error(
				// 		'Upload Data Siswa ' . (string) $value->nis . ' Gagal, Data Tidak Valid pada Siswa "' . $value->nama_lengkap . '"'
				// 	);
				// 	return [
				// 		'status'    => 300, // FAILED
				// 		'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, Data Tidak Valid pada Siswa "' . $value->nama_lengkap . '"'
				// 	];
				// } else {
				//generate id
				// 		$id_siswa = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
				// 		$id_pengguna = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
				// 		$id_c_siswa = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
				// 		$id_admisi = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
				// 		$id_jalur_siswa = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
				// 		$id_log_kelas_siswa = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
				// 		$arr[] = array(
				// 			'id_log_kelas_siswa' => $id_log_kelas_siswa,
				// 			'id_siswa' => $id_siswa,
				// 			'id_pengguna' => $id_pengguna,
				// 			'id_c_siswa' => $id_c_siswa,
				// 			'id_admisi' => $id_admisi,
				// 			'id_jalur_siswa' => $id_jalur_siswa,
				// 			'id_penerimaan' => $id_penerimaan->id_penerimaan,
				// 			'nis' => (string) $value->nis,
				// 			'nisn' => (string) $value->nisn,
				// 			'nama_lengkap' => $value->nama_lengkap,
				// 			'status_siswa' => $status->id_status_pengguna,
				// 			'kelas' => $kelas->id_kelas,
				// 			'tahun_masuk' => (int) $value->tahun_masuk,
				// 			'jalur' => $jalur->id_jalur,
				// 			'jenis_kelamin' => $jenis_kelamin,
				// 			'semester_masuk' => $semester_masuk['id_semester'],
				// 			'id_sekolah' => $this->auth_data->pengguna->id_sekolah,
				// 			'created_by' => $this->auth_data->pengguna->id_pengguna,
				// 			'is_orang_tua' => $is_orang_tua,
				// 			'kode_voucher' => $kode_voucher,
				// 			'nik_siswa' => $nik,
				// 			'id_agama' => $agama,
				// 			'id_kota_lahir' => $kota_lahir,
				// 			'tgl_lahir' => $tanggal_lahir,
				// 			'id_kota_ksk' => $kota_ksk,
				// 			'nomor_ksk' => $nomor_ksk,
				// 			'nomor_identitas' => $nomor_identitas,
				// 			'nomor_akta_lahir' => $nomor_akta_lahir,
				// 			'kewarganegaraan' => $kewarganegaraan,
				// 			'nm_kewarganegaraan' => $nm_kewarganegaraan,
				// 			'id_kebutuhan_khusus' => $kebutuhan_khusus,
				// 			'alamat_jalan' => $alamat_jalan,
				// 			'alamat_dusun' => $alamat_dusun,
				// 			'alamat_kelurahan' => $alamat_kelurahan,
				// 			'alamat_rt' => $alamat_rt,
				// 			'alamat_rw' => $alamat_rw,
				// 			'alamat_kecamatan' => $alamat_kecamatan,
				// 			'alamat_kodepos' => $alamat_kodepos,
				// 			'alamat_kota' => $alamat_kota,
				// 			'alamat_provinsi' => $alamat_provinsi,
				// 			'alamat_latitude' => $alamat_latitude,
				// 			'alamat_longitude' => $alamat_longitude,
				// 			'nomor_hp' => $nomor_hp,
				// 			'id_jenis_tinggal' => $jenis_tinggal,
				// 			'anak_ke' => $anak_ke,
				// 			'dari_x_bersaudara' => $dari_berapa_bersaudara,
				// 			'jarak_rumah_sekolah' => $jarak_rumah_ke_sekolah_km,
				// 			'waktu_tempuh_sekolah_jam' => $waktu_tempuh_ke_sekolah_jam,
				// 			'waktu_tempuh_sekolah_menit' => $waktu_tempuh_ke_sekolah_menit,
				// 			'id_jenis_transportasi' => $jenis_transportasi,
				// 			'nomor_kks' => $nomor_kartu_keluarga_sejahtera,
				// 			'is_penerima_kps' => $merupakan_penerima_kartu_perlindungan_sosial,
				// 			'nomor_kps' => $nomor_kartu_perlindungan_sosial,
				// 			'is_punya_kip' => $merupakan_penerima_kartu_indonesia_pintar,
				// 			'nomor_kip' => $nomor_kartu_indonesia_pintar,
				// 			'nm_tertera_kip' => $nama_tertera_pada_kip,
				// 			'is_layak_pip' => $layak_pip,
				// 			'id_jenis_layak_pip' => $jenis_layak_pip,
				// 			'asal_sekolah' => $asal_sekolah,
				// 			'id_kota_sekolah_asal' => $kota_asal_sekolah_sebelumnya,
				// 			'nomor_ujian_sebelumnya' => $nomor_ujian_sebelumnya,
				// 			'nomor_ijasah_sebelumnya' => $nomor_ijasah_sebelumnya,
				// 			'nomor_skhus_sebelumnya' => $nomor_skhus_sebelumnya,
				// 			'nomor_shun' => $nomor_shun_sebelumnya,
				// 			'nilai_shun' => $nilai_shun_sebelumnya,
				// 			'tahun_lulus' => $tahun_lulus,
				// 			'nomor_peserta_unas' => $nomor_peserta_unas,
				// 			'nm_ayah' => $nama_ayah,
				// 			'nik_ayah' => $nik_ayah,
				// 			'tgl_lahir_ayah' => $tanggal_lahir_ayah,
				// 			'id_jenis_pendidikan_ayah' => $jenis_pendidikan_ayah,
				// 			'id_jenis_pekerjaan_ayah' => $jenis_pekerjaan_ayah,
				// 			'id_jenis_penghasilan_ayah' => $jenis_penghasilan_ayah,
				// 			'id_kebutuhan_khusus_ayah' => $kebutuhan_khusus_ayah,
				// 			'nm_ibu' => $nama_ibu,
				// 			'nik_ibu' => $nik_ibu,
				// 			'tgl_lahir_ibu' => $tanggal_lahir_ibu,
				// 			'id_jenis_pendidikan_ibu' => $jenis_pendidikan_ibu,
				// 			'id_jenis_pekerjaan_ibu' => $jenis_pekerjaan_ibu,
				// 			'id_jenis_penghasilan_ibu' => $jenis_penghasilan_ibu,
				// 			'id_kebutuhan_khusus_ibu' => $kebutuhan_khusus_ibu,
				// 			'nm_wali' => $nama_wali,
				// 			'nik_wali' => $nik_wali,
				// 			'tgl_lahir_wali' => $tanggal_lahir_wali,
				// 			'id_jenis_pendidikan_wali' => $jenis_pendidikan_wali,
				// 			'id_jenis_pekerjaan_wali' => $jenis_pekerjaan_wali,
				// 			'id_jenis_penghasilan_wali' => $jenis_penghasilan_wali,
				// 			'id_kebutuhan_khusus_wali' => $kebutuhan_khusus_wali,
				// 			'alamat_jalan_ortu' => $alamat_jalan_orang_tua,
				// 			'alamat_dusun_ortu' => $alamat_dusun_orang_tua,
				// 			'alamat_kelurahan_ortu' => $alamat_kelurahan_orang_tua,
				// 			'alamat_rt_ortu' => $alamat_rt_orang_tua,
				// 			'alamat_rw_ortu' => $alamat_rw_orang_tua,
				// 			'alamat_kecamatan_ortu' => $alamat_kecamatan_orang_tua,
				// 			'alamat_kodepos_ortu' => $alamat_kodepos_orang_tua,
				// 			'alamat_kota_ortu' => $alamat_kota_orang_tua,
				// 			'alamat_provinsi_ortu' => $alamat_provinsi_orang_tua,
				// 			'nomor_telp_ortu' => $no_telepon_orang_tua,
				// 			'nomor_hp_ortu' => $no_hp_orang_tua,
				// 			'email_ortu' => $email_orang_tua,
				// 			'tinggi_badan' => $tinggi_badan_cm,
				// 			'berat_badan' => $berat_badan_kg,
				// 			'is_berjilbab' => $apakah_berjilbab,
				// 			'is_buta_warna' => $apakah_buta_warna,
				// 			'ukuran_baju' => $ukuran_baju,
				// 			'riwayat_penyakit' => $riwayat_penyakit,
				// 			'golongan_darah' => $golongan_darah,
				// 			'riwayat_kelainan_jasmani' => $riwayat_kelainan_jasmani,

				// 		);
				// 	}
				// }


				// if (count($arr) != 0) {
				// 	foreach ($arr as $data_siswa_1) {
				// 		$jumlah_nis = 0;
				// 		$jumlah_nisn = 0;
				// 		foreach ($arr as $data_siswa_2) {
				// 			if ($data_siswa_1['nis'] == $data_siswa_2['nis']) {
				// 				$jumlah_nis++;
				// 			}

				// 			if ($data_siswa_1['nisn'] == $data_siswa_2['nisn']) {
				// 				$jumlah_nisn++;
				// 			}
				// 		}

				// 		if ($jumlah_nis > 1) {
				// 			$this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NIS ' . $data_siswa_1['nis'] . ' yang sama di dalam file yang diupload';
				// 			Debugbar::error(
				// 				'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NIS ' . $data_siswa_1['nis'] . ' yang sama di dalam file yang diupload'
				// 			);
				// 			return [
				// 				'status'    => 300, // FAILED
				// 				'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NIS ' . $data_siswa_1['nis'] . ' yang sama di dalam file yang diupload'
				// 			];
				// 		}

				// 		if (!empty($data_siswa_1['nisn']) && $jumlah_nisn > 1) {
				// 			$this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NISN ' . $data_siswa_1['nisn'] . ' yang sama di dalam file yang diupload';
				// 			Debugbar::error(
				// 				'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NISN ' . $data_siswa_1['nisn'] . ' yang sama di dalam file yang diupload'
				// 			);
				// 			return [
				// 				'status'    => 300, // FAILED
				// 				'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NISN ' . $data_siswa_1['nisn'] . ' yang sama di dalam file yang diupload'
				// 			];
				// 		}
				// 	}

				// 	DB::beginTransaction();
				// 	try {
				// 		$pengguna_center = [];
				// 		foreach ($arr as $data_siswa) {
				// 			$row_calon_siswa_baru = [
				// 				'id_penerimaan' => $data_siswa['id_penerimaan'],
				// 				'nm_c_siswa' => $data_siswa['nama_lengkap'],
				// 				'jenis_kelamin' => $data_siswa['jenis_kelamin'],
				// 				'nisn_siswa' => $data_siswa['nisn'],
				// 				'nis_siswa' => $data_siswa['nis'],

				// 				'kode_voucher' => $data_siswa['kode_voucher'],
				// 				'nik_siswa' => $data_siswa['nik_siswa'],
				// 				'id_agama' => $data_siswa['id_agama'],
				// 				'id_kota_lahir' => $data_siswa['id_kota_lahir'],
				// 				'tgl_lahir' => $data_siswa['tgl_lahir'],
				// 				'id_kota_ksk' => $data_siswa['id_kota_ksk'],
				// 				'nomor_ksk' => $data_siswa['nomor_ksk'],
				// 				'nomor_identitas' => $data_siswa['nomor_identitas'],
				// 				'nomor_akta_lahir' => $data_siswa['nomor_akta_lahir'],
				// 				'kewarganegaraan' => $data_siswa['kewarganegaraan'],
				// 				'nm_kewarganegaraan' => $data_siswa['nm_kewarganegaraan'],
				// 				'id_kebutuhan_khusus' => $data_siswa['id_kebutuhan_khusus'],
				// 				'alamat_jalan' => $data_siswa['alamat_jalan'],
				// 				'alamat_dusun' => $data_siswa['alamat_dusun'],
				// 				'alamat_kelurahan' => $data_siswa['alamat_kelurahan'],
				// 				'alamat_rt' => $data_siswa['alamat_rt'],
				// 				'alamat_rw' => $data_siswa['alamat_rw'],
				// 				'alamat_kecamatan' => $data_siswa['alamat_kecamatan'],
				// 				'alamat_kodepos' => $data_siswa['alamat_kodepos'],
				// 				'alamat_kota' => $data_siswa['alamat_kota'],
				// 				'alamat_provinsi' => $data_siswa['alamat_provinsi'],
				// 				'alamat_latitude' => $data_siswa['alamat_latitude'],
				// 				'alamat_longitude' => $data_siswa['alamat_longitude'],
				// 				'nomor_hp' => $data_siswa['nomor_hp'],
				// 				'id_jenis_tinggal' => $data_siswa['id_jenis_tinggal'],
				// 				'anak_ke' => $data_siswa['anak_ke'],
				// 				'dari_x_bersaudara' => $data_siswa['dari_x_bersaudara'],
				// 				'jarak_rumah_sekolah' => $data_siswa['jarak_rumah_sekolah'],
				// 				'waktu_tempuh_sekolah_jam' => $data_siswa['waktu_tempuh_sekolah_jam'],
				// 				'waktu_tempuh_sekolah_menit' => $data_siswa['waktu_tempuh_sekolah_menit'],
				// 				'id_jenis_transportasi' => $data_siswa['id_jenis_transportasi'],
				// 				'nomor_kks' => $data_siswa['nomor_kks'],
				// 				'is_penerima_kps' => $data_siswa['is_penerima_kps'],
				// 				'nomor_kps' => $data_siswa['nomor_kps'],
				// 				'is_punya_kip' => $data_siswa['is_punya_kip'],
				// 				'nomor_kip' => $data_siswa['nomor_kip'],
				// 				'nm_tertera_kip' => $data_siswa['nm_tertera_kip'],
				// 				'is_layak_pip' => $data_siswa['is_layak_pip'],
				// 				'id_jenis_layak_pip' => $data_siswa['id_jenis_layak_pip'],
				// 				'asal_sekolah' => $data_siswa['asal_sekolah'],
				// 				'nomor_ujian_sebelumnya' => $data_siswa['nomor_ujian_sebelumnya'],
				// 				'nomor_ijasah_sebelumnya' => $data_siswa['nomor_ijasah_sebelumnya'],
				// 				'nomor_skhus_sebelumnya' => $data_siswa['nomor_skhus_sebelumnya'],

				// 				'created_at' => $now,
				// 				'created_by' => $data_siswa['created_by'],
				// 				'updated_at' => $now,
				// 				'updated_by' => $data_siswa['created_by'],
				// 			];

				// 			$check_nis_siswa = Siswa::where('nis_siswa', $data_siswa['nis'])->first();

				// 			if ($check_nis_siswa) {
				// 				DB::table('calon_siswa_baru')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($row_calon_siswa_baru);
				// 			} else {
				// 				$row_calon_siswa_baru['id_c_siswa'] = $data_siswa['id_c_siswa'];

				// 				DB::table('calon_siswa_baru')->insert($row_calon_siswa_baru);
				// 			}

				// 			$row_calon_siswa_fisik = [
				// 				'tinggi_badan' => $data_siswa['tinggi_badan'],
				// 				'berat_badan' => $data_siswa['berat_badan'],
				// 				'is_berjilbab' => $data_siswa['is_berjilbab'],
				// 				'is_buta_warna' => $data_siswa['is_buta_warna'],
				// 				'ukuran_baju' => $data_siswa['ukuran_baju'],
				// 				'riwayat_penyakit' => $data_siswa['riwayat_penyakit'],
				// 				'golongan_darah' => $data_siswa['golongan_darah'],
				// 				'riwayat_kelainan_jasmani' => $data_siswa['riwayat_kelainan_jasmani'],

				// 				'created_at' => $now,
				// 				'created_by' => $data_siswa['created_by'],
				// 				'updated_at' => $now,
				// 				'updated_by' => $data_siswa['created_by'],
				// 			];

				// 			if ($check_nis_siswa) {
				// 				DB::table('calon_siswa_fisik')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($row_calon_siswa_fisik);
				// 			} else {
				// 				$row_calon_siswa_fisik['id_c_siswa'] = $data_siswa['id_c_siswa'];

				// 				DB::table('calon_siswa_fisik')->insert($row_calon_siswa_fisik);
				// 			}

				// 			$row_calon_siswa_ortu = [
				// 				'nm_ayah' => $data_siswa['nm_ayah'],
				// 				'nik_ayah' => $data_siswa['nik_ayah'],
				// 				'tgl_lahir_ayah' => $data_siswa['tgl_lahir_ayah'],
				// 				'id_jenis_pendidikan_ayah' => $data_siswa['id_jenis_pendidikan_ayah'],
				// 				'id_jenis_pekerjaan_ayah' => $data_siswa['id_jenis_pekerjaan_ayah'],
				// 				'id_jenis_penghasilan_ayah' => $data_siswa['id_jenis_penghasilan_ayah'],
				// 				'id_kebutuhan_khusus_ayah' => $data_siswa['id_kebutuhan_khusus_ayah'],
				// 				'nm_ibu' => $data_siswa['nm_ibu'],
				// 				'nik_ibu' => $data_siswa['nik_ibu'],
				// 				'tgl_lahir_ibu' => $data_siswa['tgl_lahir_ibu'],
				// 				'id_jenis_pendidikan_ibu' => $data_siswa['id_jenis_pendidikan_ibu'],
				// 				'id_jenis_pekerjaan_ibu' => $data_siswa['id_jenis_pekerjaan_ibu'],
				// 				'id_jenis_penghasilan_ibu' => $data_siswa['id_jenis_penghasilan_ibu'],
				// 				'id_kebutuhan_khusus_ibu' => $data_siswa['id_kebutuhan_khusus_ibu'],
				// 				'nm_wali' => $data_siswa['nm_wali'],
				// 				'nik_wali' => $data_siswa['nik_wali'],
				// 				'tgl_lahir_wali' => $data_siswa['tgl_lahir_wali'],
				// 				'id_jenis_pendidikan_wali' => $data_siswa['id_jenis_pendidikan_wali'],
				// 				'id_jenis_pekerjaan_wali' => $data_siswa['id_jenis_pekerjaan_wali'],
				// 				'id_jenis_penghasilan_wali' => $data_siswa['id_jenis_penghasilan_wali'],
				// 				'id_kebutuhan_khusus_wali' => $data_siswa['id_kebutuhan_khusus_wali'],
				// 				'alamat_jalan_ortu' => $data_siswa['alamat_jalan_ortu'],
				// 				'alamat_dusun_ortu' => $data_siswa['alamat_dusun_ortu'],
				// 				'alamat_kelurahan_ortu' => $data_siswa['alamat_kelurahan_ortu'],
				// 				'almat_rt_ortu' => $data_siswa['alamat_rt_ortu'],
				// 				'alamat_rw_ortu' => $data_siswa['alamat_rw_ortu'],
				// 				'alamat_kecamatan_ortu' => $data_siswa['alamat_kecamatan_ortu'],
				// 				'alamat_kodepos_ortu' => $data_siswa['alamat_kodepos_ortu'],
				// 				'alamat_kota_ortu' => $data_siswa['alamat_kota_ortu'],
				// 				'alamat_provinsi_ortu' => $data_siswa['alamat_provinsi_ortu'],
				// 				'nomor_telp_ortu' => $data_siswa['nomor_telp_ortu'],
				// 				'nomor_hp_ortu' => $data_siswa['nomor_hp_ortu'],
				// 				'email_ortu' => $data_siswa['email_ortu'],

				// 				'created_at' => $now,
				// 				'created_by' => $data_siswa['created_by'],
				// 				'updated_at' => $now,
				// 				'updated_by' => $data_siswa['created_by'],
				// 			];

				// 			if ($check_nis_siswa) {
				// 				DB::table('calon_siswa_ortu')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($row_calon_siswa_ortu);
				// 			} else {
				// 				$row_calon_siswa_ortu['id_c_siswa'] = $data_siswa['id_c_siswa'];

				// 				DB::table('calon_siswa_ortu')->insert($row_calon_siswa_ortu);
				// 			}

				// 			$calon_siswa_sekolah = [
				// 				'nm_sekolah_asal' => $data_siswa['asal_sekolah'],
				// 				'id_kota_sekolah_asal' => $data_siswa['id_kota_sekolah_asal'],
				// 				'nomor_shun' => $data_siswa['nomor_shun'],
				// 				'nilai_shun' => $data_siswa['nilai_shun'],
				// 				'nomor_ijasah' => $data_siswa['nomor_ijasah_sebelumnya'],
				// 				'tahun_lulus' => $data_siswa['tahun_lulus'],
				// 				'nomor_peserta_unas' => $data_siswa['nomor_peserta_unas'],
				// 				'nisn' => $data_siswa['nisn'],

				// 				'created_at' => $now,
				// 				'created_by' => $data_siswa['created_by'],
				// 				'updated_at' => $now,
				// 				'updated_by' => $data_siswa['created_by'],
				// 			];

				// 			if ($check_nis_siswa) {
				// 				DB::table('calon_siswa_sekolah')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($calon_siswa_sekolah);
				// 			} else {
				// 				$calon_siswa_sekolah['id_c_siswa'] = $data_siswa['id_c_siswa'];

				// 				DB::table('calon_siswa_sekolah')->insert($calon_siswa_sekolah);
				// 			}

				// 			if ($check_nis_siswa) {
				// 				DB::table('pengguna')->where('id_pengguna', $check_nis_siswa->id_pengguna)->update(['nm_pengguna' => $data_siswa['nama_lengkap']]);
				// 			} else {
				// 				DB::table('pengguna')->insert(
				// 					[
				// 						'id_pengguna' => $data_siswa['id_pengguna'],
				// 						'id_status_pengguna' => $data_siswa['status_siswa'],
				// 						'id_sekolah' => $data_siswa['id_sekolah'],
				// 						'nm_pengguna' => $data_siswa['nama_lengkap'],
				// 						'username' => $data_siswa['nis'],
				// 						'password' => Hash::make($data_siswa['nis']),
				// 						'must_change_password' => 1,
				// 						'status_join_table' => 3,
				// 						'created_at' => $now,
				// 						'created_by' => $data_siswa['created_by'],
				// 					]
				// 				);
				// 			}

				// 			$row_siswa = [
				// 				'id_kelas' => $data_siswa['kelas'],
				// 				'nisn_siswa' => $data_siswa['nisn'],

				// 				'is_orang_tua' => $data_siswa['is_orang_tua'],

				// 				'thn_masuk_siswa' => $data_siswa['tahun_masuk'],
				// 				'created_at' => $now,
				// 				'created_by' => $data_siswa['created_by'],
				// 				'updated_at' => $now,
				// 				'updated_by' => $data_siswa['created_by'],
				// 			];

				// 			if ($check_nis_siswa) {
				// 				DB::table('siswa')->where('id_siswa', $check_nis_siswa->id_siswa)->update($row_siswa);
				// 			} else {
				// 				$row_siswa['id_siswa'] = $data_siswa['id_siswa'];
				// 				$row_siswa['id_pengguna'] = $data_siswa['id_pengguna'];
				// 				$row_siswa['id_c_siswa'] = $data_siswa['id_c_siswa'];
				// 				$row_siswa['id_kelompok_biaya'] = null;
				// 				$row_siswa['nis_siswa'] = $data_siswa['nis'];

				// 				DB::table('siswa')->insert($row_siswa);
				// 			}

				// 			if ($check_nis_siswa) { } else {
				// 				DB::table('admisi')->insert(
				// 					[
				// 						'id_admisi' => $data_siswa['id_admisi'],
				// 						'id_siswa' => $data_siswa['id_siswa'],
				// 						'id_semester' => $data_siswa['semester_masuk'],
				// 						'id_status_pengguna' => $data_siswa['status_siswa'],
				// 						'id_jalur' => $data_siswa['jalur'],
				// 						'created_at' => $now,
				// 						'created_by' => $data_siswa['created_by'],
				// 					]
				// 				);

				// 				DB::table('jalur_siswa')->insert(
				// 					[
				// 						'id_jalur_siswa' => $data_siswa['id_jalur_siswa'],
				// 						'id_siswa' => $data_siswa['id_siswa'],
				// 						'id_semester' => $data_siswa['semester_masuk'],
				// 						'id_jalur' => $data_siswa['jalur'],
				// 						'id_admisi' => $data_siswa['id_admisi'],
				// 						'is_jalur_aktif' => 1,
				// 						'created_at' => $now,
				// 						'created_by' => $data_siswa['created_by'],
				// 					]
				// 				);

				// 				DB::table('role_pengguna')->insert(
				// 					[
				// 						'id_pengguna' => $data_siswa['id_pengguna'],
				// 						'id_role' => 3,
				// 						'keterangan_role_pengguna' => "Input Pendidikan",
				// 						'is_aktif' => 1,
				// 						'created_at' => $now,
				// 						'created_by' => $data_siswa['created_by'],
				// 					]
				// 				);

				// 				DB::table('log_kelas_siswa')->insert(
				// 					[
				// 						'id_log_kelas_siswa' => $data_siswa['id_log_kelas_siswa'],
				// 						'id_siswa' => $data_siswa['id_siswa'],
				// 						'id_kelas' => $data_siswa['kelas'],
				// 						'created_at' => $now,
				// 						'updated_at' => $now,
				// 						'created_by' => $data_siswa['created_by'],
				// 					]
				// 				);

				// 				$pengguna_center[] = [
				// 					"id_pengguna" => $data_siswa['id_pengguna'],
				// 					"id_sekolah" => $data_siswa['id_sekolah'],
				// 					"username" => $data_siswa['nis'],
				// 				];
				// 			}
				// 		}

				// 		LibGlobal::insertUpdateUserInCenter($pengguna_center);
				// 		DB::commit();
				// 		$this->message[] = 'Save Siswa Successfully';
				// 		Debugbar::error('Save Siswa Successfully');
				// 		return [
				// 			'status'    => 300, // FAILED
				// 			'message'   =>  'Save Siswa Successfully'
				// 		];
				// 	} catch (\Exception $e) {

				// 		DB::rollback();
				// 		// something went wrong
				// 		//    Debugbar::error( (env('APP_DEBUG', 'true') == 'true') ? 'tesst' : 'Operation error');
				// 		$this->message[] = (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error';

				// 		Debugbar::error((env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error');
				// 		return [
				// 			'status'    => 300, // FAILED
				// 			'message'   =>  'Operation error'
				// 		];
				// 	}
				// } else {
				// 	$this->message[] = 'File Excel Anda Kosong';
				// 	return [
				// 		'status'    => 300, // FAILED
				// 		'message'   => "File Excel Anda Kosong"
				// 	];
				// }}
			}
		}

		$data['validasi'] = $validasi;
		$data['nis'] = $data_nis;
		$data['jenis'] = $jenis;
		return $data;
		// dd($validasi);
	}



	public function viewUploadDataSiswa(Request $request)
	{
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		return view('pendidikan/siswa/upload-data-siswa/view-upload-data-siswa', compact('auth_data'));
	}

	public function downloadFileExcel()
	{
		$file = public_path() . "/excel/ContohFileExcelUploadDataSiswa.xls";
		$headers = [
			'Content-Type' => 'application/xls',
		];

		return response()->download($file, 'ContohFileExcelUploadDataSiswa.xls', $headers);
	}

	public function downloadFileExcelLite()
	{
		$file = public_path() . "/excel/ContohFileExcelUploadDataSiswaLite.xls";
		$headers = [
			'Content-Type' => 'application/xls',
		];

		return response()->download($file, 'ContohFileExcelUploadDataSiswaLite.xls', $headers);
	}

	public function updateDataSiswa(Request $request)
	{
		set_time_limit(-1);

		DB::beginTransaction();

		try {

			$path = public_path() . "/excel-ijazah.csv";

			$csv = array();
			$lines = file($path, FILE_IGNORE_NEW_LINES);

			foreach ($lines as $key => $value) {

				$csv[$key] = str_getcsv($value);
			}

			foreach ($csv as $key => $value) {

				$siswa = Siswa::where('nis_siswa', $value[1])->first();

				if ($siswa) {
					$pengjuan_wisuda = PengajuanWisuda::where('id_siswa', $siswa->id_siswa)->first();

					if ($pengjuan_wisuda) {
						$pengjuan_wisuda->nomor_ijasah = $value[4];
						$pengjuan_wisuda->save();
					}
				}
			}

			DB::commit();

			return 'success';
		} catch (\Exception $e) {

			DB::rollback();

			return (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : $e->getMessage();
		}
	}



	public function uploadEmailExcel(Request $request)
	{
		// validasi
		// $this->validate($request, [
		// 	'file' => 'required|mimes:csv,xls,xlsx'
		// ]);
		// dd($request);
		// menangkap file excel

		// import data
		Excel::import(new SiswaImport, $request->file('file-excel'));

		// notifikasi dengan session
		return back();
		// // alihkan halaman kembali
		// return redirect('/siswa');
	}

	public function uploadFileExcel(Request $request)
	{
		set_time_limit(-1);
		$input = (object) $request->input();
		$auth_data = $input->auth_data;
		$now = Carbon::now();
		if ($request->hasFile('file-excel')) {
			$data = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
			$data = $data[0];
			if (count($data)) {

				$data_nis = array();
				foreach ($data as $key => $data_row) {
					if (!empty($data_row['nis'])) {
						$data_nis[] = (string) $data_row['nis'];
					}
				}

				$data_siswa = Siswa::whereIn('nis_siswa', $data_nis)->get();
				$data_status_pengguna = StatusPengguna::where('status_join_table', '=', '3')->get();
				$data_kelas = Kelas::where('is_aktif', 1)->get();
				$data_jalur = Jalur::get();
				$data_semester_masuk = Semester::get();
				$data_penerimaan = Penerimaan::get();
				$data_agama = Agama::get();
				$data_kota = Kota::get();
				$data_kebutuhan_khusus = KebutuhanKhusus::get();
				$data_provinsi = Provinsi::get();
				$data_jenis_tinggal = JenisTinggal::get();
				$data_jenis_transportasi = JenisTransportasi::get();
				$data_jenis_layak_pip = JenisLayakPip::get();
				$data_jenis_pendidikan = JenisPendidikan::get();
				$data_jenis_pekerjaan = JenisPekerjaan::get();
				$data_jenis_penghasilan = JenisPenghasilan::get();

				$arr = array();
				foreach ($data as $key => $data_row) {
					$value = new \stdClass();
					foreach ($data_row as $key => $temp_item) {
						$value->$key = $temp_item;
					}

					if (empty($value->nis)) {
						continue;
					}

					$check_nis_siswa = Siswa::where('nis_siswa', (string) $value->nis)->first();

					// $check_nisn_siswa = Siswa::where('nisn_siswa', (string) $value->nisn)->first();

					// if ($check_nis_siswa) {
					//     Debugbar::error(
					//         'Upload Data Siswa '.$value->nis. ' Gagal, NIS ' . $value->nis . ' ditemukan sama di dalam sistem'
					//     );
					// } else{

					$status = $data_status_pengguna->firstWhere('nm_status_pengguna', '=', $value->status_siswa);

					if (empty($status)) {
						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, status ' . $value->status_siswa . ' tidak ditemukan di dalam sistem';
						Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, status ' . $value->status_siswa . ' tidak ditemukan di dalam sistem');
						return [
							'status'    => 300, // FAILED
							'message'   => 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, status ' . $value->status_siswa . ' tidak ditemukan di dalam sistem'
						];
					}
					// }

					// find id_kelas
					$kelas = $data_kelas->firstWhere('nm_kelas', '=', $value->kelas);

					if (empty($kelas)) {
						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kelas ' . $value->kelas . ' tidak ditemukan di dalam sistem';
						Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, kelas ' . $value->kelas . ' tidak ditemukan di dalam sistem');
						return [
							'status'    => 300, // FAILED
							'message'   => "Upload Data Siswa '.$value->nis. ' Gagal,
						 kelas ' . $value->kelas . ' tidak ditemukan di dalam sistem"
						];
					}

					//find id_jalur
					$jalur = $data_jalur->firstWhere('nm_jalur', '=', $value->jalur);

					if (empty($jalur)) {
						$this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jalur ' . $value->jalur . ' tidak ditemukan di dalam sistem';
						Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, jalur ' . $value->jalur . ' tidak ditemukan di dalam sistem');
						return [
							'status'    => 300, // FAILED
							'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jalur ' . $value->jalur . ' tidak ditemukan di dalam sistem'
						];
					}

					//find jenis_kelamin
					if ($value->jenis_kelamin == "L") {
						$jenis_kelamin = 1;
					} elseif ($value->jenis_kelamin == "P") {
						$jenis_kelamin = 2;
					} else {
						$jenis_kelamin = null;
					}

					//find id_semester
					$semester_masuk = $data_semester_masuk->firstWhere('kode_semester', '=', $value->semester_masuk);

					if (empty($semester_masuk)) {
						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, semester masuk ' . $value->semester_masuk . ' tidak ditemukan di dalam sistem';
						Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, semester masuk ' . $value->semester_masuk . ' tidak ditemukan di dalam sistem');
						return [
							'status'    => 300, // FAILED
							'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, semester masuk ' . $value->semester_masuk . ' tidak ditemukan di dalam sistem'
						];
					}

					//find id_penerimaan
					$id_penerimaan = $data_penerimaan->firstWhere('tahun_penerimaan', '=', (int) $value->tahun_masuk);

					if (empty($id_penerimaan)) {
						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, tahun masuk ' . $value->tahun_masuk . ' tidak ditemukan di dalam sistem';
						Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, tahun masuk ' . $value->tahun_masuk . ' tidak ditemukan di dalam sistem');
						return [
							'status'    => 300, // FAILED
							'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, tahun masuk ' . $value->tahun_masuk . ' tidak ditemukan di dalam sistem'
						];
					}

					// find is_orang_tua
					if ($value->orang_tua_kandung == 1 || $value->orang_tua_kandung == 0) {
						$is_orang_tua = (int) $value->orang_tua_kandung;
					} else {
						$is_orang_tua = null;
					}

					// find kode voucher
					if (empty($value->kode_voucher)) {
						$kode_voucher = null;
					} else {
						$find_voucher = Voucher::where('kode_voucher', $value->kode_voucher)->first();
						if ($find_voucher) {
							$kode_voucher = $find_voucher->id_voucher;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kode voucher ' . $value->kode_voucher . ' tidak ditemukan di dalam sistem';
							Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, kode voucher ' . $value->kode_voucher . ' tidak ditemukan di dalam sistem');
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kode voucher ' . $value->kode_voucher . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// find nik siswa
					if (empty($value->nik)) {
						$nik = null;
					} else {
						$nik = $value->nik;
					}

					//find id agama
					if (empty($value->agama)) {
						$agama = null;
					} else {
						$find_agama = $data_agama->firstWhere('kode_agama', $value->agama);
						if ($find_agama) {
							$agama = $find_agama->id_agama;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, agama ' . $value->agama . ' tidak ditemukan di dalam sistem';
							Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, agama ' . $value->agama . ' tidak ditemukan di dalam sistem');
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, agama ' . $value->agama . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// find id kota lahir
					if (empty($value->kota_lahir)) {
						$kota_lahir = null;
					} else {
						$find_kota = $data_kota->firstWhere('nm_kota', $value->kota_lahir);
						if ($find_kota) {
							$kota_lahir = $find_kota->id_kota;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kota lahir ' . $value->kota_lahir . ' tidak ditemukan di dalam sistem';
							Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, kota lahir ' . $value->kota_lahir . ' tidak ditemukan di dalam sistem');
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kota lahir ' . $value->kota_lahir . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// tanggal lahir
					if (empty($value->tanggal_lahir)) {
						$tanggal_lahir = null;
					} else {
						$tanggal_lahir = date('Y-m-d', strtotime($value->tanggal_lahir));
					}

					// find kota ksk
					if (empty($value->nama_kota_ksk)) {
						$kota_ksk = null;
					} else {
						$find_kota_ksk = $data_kota->firstWhere('nm_kota', $value->nama_kota_ksk);
						if ($find_kota_ksk) {
							$kota_ksk = $find_kota_ksk->id_kota;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, nama kota ksk ' . $value->nama_kota_ksk . ' tidak ditemukan di dalam sistem';
							Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, nama kota ksk ' . $value->nama_kota_ksk . ' tidak ditemukan di dalam sistem');
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, nama kota ksk ' . $value->nama_kota_ksk . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// nomor ksk

					if (empty($value->nomor_ksk)) {
						$nomor_ksk = null;
					} else {
						$nomor_ksk = $value->nomor_ksk;
					}

					// nomor identitas

					if (empty($value->nomor_identitas_ktp_sim_lainya)) {
						$nomor_identitas = null;
					} else {
						$nomor_identitas = $value->nomor_identitas_ktp_sim_lainya;
					}

					// nomor akta lahir

					if (empty($value->nomor_registrasi_akta_lahir)) {
						$nomor_akta_lahir = null;
					} else {
						$nomor_akta_lahir = $value->nomor_registrasi_akta_lahir;
					}

					//find kewarganegaraan
					if ($value->kewarganegaraan == 1 || $value->kewarganegaraan == 0) {
						$kewarganegaraan = (int) $value->kewarganegaraan;
					} else {
						$kewarganegaraan = null;
					}

					// nm_kewarganegaraan
					if (empty($value->nama_kewarganegaraan_jika_dari_wna)) {
						$nm_kewarganegaraan = null;
					} else {
						$nm_kewarganegaraan = $value->nama_kewarganegaraan_jika_dari_wna;
					}

					// find kebutuhan khusus
					if (empty($value->kebutuhan_khusus)) {
						$kebutuhan_khusus = null;
					} else {
						$find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus);
						if ($find_kebutuhan_khusus) {
							$kebutuhan_khusus = $find_kebutuhan_khusus->id_kebutuhan_khusus;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, Kebutuhan Khusus ' . $value->kebutuhan_khusus . ' tidak ditemukan di dalam sistem';
							Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, Kebutuhan Khusus ' . $value->kebutuhan_khusus . ' tidak ditemukan di dalam sistem');
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, Kebutuhan Khusus ' . $value->kebutuhan_khusus . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// alamat jalan
					if (empty($value->alamat_jalan)) {
						$alamat_jalan = null;
					} else {
						$alamat_jalan = $value->alamat_jalan;
					}

					// alamat dusun
					if (empty($value->alamat_dusun)) {
						$alamat_dusun = null;
					} else {
						$alamat_dusun = $value->alamat_dusun;
					}

					// alamat kelurahan
					if (empty($value->alamat_kelurahan)) {
						$alamat_kelurahan = null;
					} else {
						$alamat_kelurahan = $value->alamat_kelurahan;
					}

					// alamat rt
					if (empty($value->alamat_rt)) {
						$alamat_rt = null;
					} else {
						$alamat_rt = $value->alamat_rt;
					}

					// alamat rw
					if (empty($value->alamat_rw)) {
						$alamat_rw = null;
					} else {
						$alamat_rw = $value->alamat_rw;
					}

					// alamat kecamatan
					if (empty($value->alamat_kecamatan)) {
						$alamat_kecamatan = null;
					} else {
						$alamat_kecamatan = $value->alamat_kecamatan;
					}

					// alamat kodepos
					if (empty($value->alamat_kodepos)) {
						$alamat_kodepos = null;
					} else {
						$alamat_kodepos = $value->alamat_kodepos;
					}

					// find alamat kota
					if (empty($value->alamat_kota)) {
						$alamat_kota = null;
					} else {
						$find_alamat_kota = $data_kota->firstWhere('nm_kota', $value->alamat_kota);
						if ($find_alamat_kota) {
							$alamat_kota = $find_alamat_kota->id_kota;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota ' . $value->alamat_kota . ' tidak ditemukan di dalam sistem';
							Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota ' . $value->alamat_kota . ' tidak ditemukan di dalam sistem');
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota ' . $value->alamat_kota . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// find id provinsi
					if (empty($value->alamat_provinsi)) {
						$alamat_provinsi = null;
					} else {
						$find_provinsi = $data_provinsi->firstWhere('nm_provinsi', $value->alamat_provinsi);
						if ($find_provinsi) {
							$alamat_provinsi = $find_provinsi->id_provinsi;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi ' . $value->alamat_provinsi . ' tidak ditemukan di dalam sistem';
							Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi ' . $value->alamat_provinsi . ' tidak ditemukan di dalam sistem');
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi ' . $value->alamat_provinsi . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// alamat lattitude
					if (empty($value->alamat_latitude)) {
						$alamat_latitude = null;
					} else {
						$alamat_latitude = $value->alamat_latitude;
					}

					// alamat longitude
					if (empty($value->alamat_longitude)) {
						$alamat_longitude = null;
					} else {
						$alamat_longitude = $value->alamat_longitude;
					}

					// nomor hp
					if (empty($value->nomor_hp)) {
						$nomor_hp = null;
					} else {
						$nomor_hp = $value->nomor_hp;
					}

					// find jenis tinggal
					if (empty($value->jenis_tinggal)) {
						$jenis_tinggal = null;
					} else {
						$find_jenis_tinggal = $data_jenis_tinggal->firstWhere('kode_jenis_tinggal', $value->jenis_tinggal);
						if ($find_jenis_tinggal) {
							$jenis_tinggal = $find_jenis_tinggal->id_jenis_tinggal;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis tinggal ' . $value->jenis_tinggal . ' tidak ditemukan di dalam sistem';
							Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis tinggal ' . $value->jenis_tinggal . ' tidak ditemukan di dalam sistem');
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis tinggal ' . $value->jenis_tinggal . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// anak ke
					if (empty($value->anak_ke)) {
						$anak_ke = null;
					} else {
						$anak_ke = (int) $value->anak_ke;
					}

					// dari x bersaudara
					if (empty($value->dari_berapa_bersaudara)) {
						$dari_berapa_bersaudara = null;
					} else {
						$dari_berapa_bersaudara = (int) $value->dari_berapa_bersaudara;
					}

					// jarak rumah ke sekolah (km)
					if (empty($value->jarak_rumah_ke_sekolah_km)) {
						$jarak_rumah_ke_sekolah_km = null;
					} else {
						$jarak_rumah_ke_sekolah_km = (float) $value->jarak_rumah_ke_sekolah_km;
					}

					// waktu tempuh sekolah (jam)
					if (empty($value->waktu_tempuh_ke_sekolah_jam)) {
						$waktu_tempuh_ke_sekolah_jam = null;
					} else {
						$waktu_tempuh_ke_sekolah_jam = (float) $value->waktu_tempuh_ke_sekolah_jam;
					}

					// waktu tempuh sekkolah (menit)
					if (empty($value->waktu_tempuh_ke_sekolah_menit)) {
						$waktu_tempuh_ke_sekolah_menit = null;
					} else {
						$waktu_tempuh_ke_sekolah_menit = (float) $value->waktu_tempuh_ke_sekolah_menit;
					}

					// find jenis transportasi
					if (empty($value->jenis_transportasi)) {
						$jenis_transportasi = null;
					} else {
						$find_jenis_transportasi = $data_jenis_transportasi->firstWhere('kode_jenis_transportasi', $value->jenis_transportasi);
						if ($find_jenis_transportasi) {
							$jenis_transportasi = $find_jenis_transportasi->id_jenis_transportasi;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis transportasi ' . $value->jenis_transportasi . ' tidak ditemukan di dalam sistem';
							Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis transportasi ' . $value->jenis_transportasi . ' tidak ditemukan di dalam sistem');
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis transportasi ' . $value->jenis_transportasi . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// nomor kks
					if (empty($value->nomor_kartu_keluarga_sejahtera)) {
						$nomor_kartu_keluarga_sejahtera = null;
					} else {
						$nomor_kartu_keluarga_sejahtera = $value->nomor_kartu_keluarga_sejahtera;
					}

					// is penerima kps
					if ($value->merupakan_penerima_kartu_perlindungan_sosial == 1 || $value->merupakan_penerima_kartu_perlindungan_sosial == 0) {
						$merupakan_penerima_kartu_perlindungan_sosial = (int) $value->merupakan_penerima_kartu_perlindungan_sosial;
					} else {
						$merupakan_penerima_kartu_perlindungan_sosial = null;
					}

					// nomor kps
					if (empty($value->nomor_kartu_perlindungan_sosial)) {
						$nomor_kartu_perlindungan_sosial = null;
					} else {
						$nomor_kartu_perlindungan_sosial = $value->nomor_kartu_perlindungan_sosial;
					}

					// is punya kip
					if ($value->merupakan_penerima_kartu_indonesia_pintar == 1 || $value->merupakan_penerima_kartu_indonesia_pintar == 0) {
						$merupakan_penerima_kartu_indonesia_pintar = (int) $value->merupakan_penerima_kartu_indonesia_pintar;
					} else {
						$merupakan_penerima_kartu_indonesia_pintar = null;
					}

					// nomor kip
					if (empty($value->nomor_kartu_indonesia_pintar)) {
						$nomor_kartu_indonesia_pintar = null;
					} else {
						$nomor_kartu_indonesia_pintar = $value->nomor_kartu_indonesia_pintar;
					}

					// nama tertera kip
					if (empty($value->nama_tertera_pada_kip)) {
						$nama_tertera_pada_kip = null;
					} else {
						$nama_tertera_pada_kip = $value->nama_tertera_pada_kip;
					}

					// is layak pip
					if ($value->layak_pip == 1 || $value->layak_pip == 0) {
						$layak_pip = (int) $value->layak_pip;
					} else {
						$layak_pip = null;
					}

					// find jenis layak pip
					if (empty($value->jenis_layak_pip)) {
						$jenis_layak_pip = null;
					} else {
						$find_jenis_layak = $data_jenis_layak_pip->firstWhere('kode_jenis_layak_pip', $value->jenis_layak_pip);
						if ($find_jenis_layak) {
							$jenis_layak_pip = $find_jenis_layak->id_jenis_layak_pip;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis layak pip ' . $value->jenis_layak_pip . ' tidak ditemukan di dalam sistem';
							Debugbar::error('Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis layak pip ' . $value->jenis_layak_pip . ' tidak ditemukan di dalam sistem');
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis layak pip ' . $value->jenis_layak_pip . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// asal sekolah
					if (empty($value->asal_sekolah)) {
						$asal_sekolah = null;
					} else {
						$asal_sekolah = $value->asal_sekolah;
					}

					// id kota sekolah asal
					if (empty($value->kota_asal_sekolah_sebelumnya)) {
						$kota_asal_sekolah_sebelumnya = null;
					} else {
						$find_kota_sekolah_asal = $data_kota->firstWhere('nm_kota', $value->kota_asal_sekolah_sebelumnya);
						if ($find_kota_sekolah_asal) {
							$kota_asal_sekolah_sebelumnya = $find_kota_sekolah_asal->id_kota;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kota asal sekolah sebelumnya ' . $value->kota_asal_sekolah_sebelumnya . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kota asal sekolah sebelumnya ' . $value->kota_asal_sekolah_sebelumnya . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kota asal sekolah sebelumnya ' . $value->kota_asal_sekolah_sebelumnya . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// nomor ujian sebelumnya
					if (empty($value->nomor_ujian_sebelumnya)) {
						$nomor_ujian_sebelumnya = null;
					} else {
						$nomor_ujian_sebelumnya = $value->nomor_ujian_sebelumnya;
					}

					// nomor ijasah sebelumnya
					if (empty($value->nomor_ijasah_sebelumnya)) {
						$nomor_ijasah_sebelumnya = null;
					} else {
						$nomor_ijasah_sebelumnya = $value->nomor_ijasah_sebelumnya;
					}

					// nomor skhus sebelumnya
					if (empty($value->nomor_skhus_sebelumnya)) {
						$nomor_skhus_sebelumnya = null;
					} else {
						$nomor_skhus_sebelumnya = $value->nomor_skhus_sebelumnya;
					}

					// nomor shun
					if (empty($value->nomor_shun_sebelumnya)) {
						$nomor_shun_sebelumnya = null;
					} else {
						$nomor_shun_sebelumnya = $value->nomor_shun_sebelumnya;
					}

					// nilai shun
					if (empty($value->nilai_shun_sebelumnya)) {
						$nilai_shun_sebelumnya = null;
					} else {
						$nilai_shun_sebelumnya = (float) $value->nilai_shun_sebelumnya;
					}

					// tahun lulus
					if (empty($value->tahun_lulus)) {
						$tahun_lulus = null;
					} else {
						$tahun_lulus = (int) $value->tahun_lulus;
					}

					// no peserta unas
					if (empty($value->nomor_peserta_unas)) {
						$nomor_peserta_unas = null;
					} else {
						$nomor_peserta_unas = $value->nomor_peserta_unas;
					}

					// nama ayah
					if (empty($value->nama_ayah)) {
						$nama_ayah = null;
					} else {
						$nama_ayah = $value->nama_ayah;
					}

					// nik ayah
					if (empty($value->nik_ayah)) {
						$nik_ayah = null;
					} else {
						$nik_ayah = $value->nik_ayah;
					}

					// tanggal lahir ayah
					if (empty($value->tanggal_lahir_ayah)) {
						$tanggal_lahir_ayah = null;
					} else {
						$tanggal_lahir_ayah = date('Y-m-d', strtotime($value->tanggal_lahir_ayah));
					}

					// jenis pendidikan ayah
					if (empty($value->jenis_pendidikan_ayah)) {
						$jenis_pendidikan_ayah = null;
					} else {
						$find_jenis_pendidikan = $data_jenis_pendidikan->firstWhere('kode_jenis_pendidikan', $value->jenis_pendidikan_ayah);
						if ($find_jenis_pendidikan) {
							$jenis_pendidikan_ayah = $find_jenis_pendidikan->id_jenis_pendidikan;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ayah ' . $value->jenis_pendidikan_ayah . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ayah ' . $value->jenis_pendidikan_ayah . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ayah ' . $value->jenis_pendidikan_ayah . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// jenis pekerjaan ayah
					if (empty($value->jenis_pekerjaan_ayah)) {
						$jenis_pekerjaan_ayah = null;
					} else {
						$find_jenis_pekerjaan = $data_jenis_pekerjaan->firstWhere('kode_jenis_pekerjaan', $value->jenis_pekerjaan_ayah);
						if ($find_jenis_pekerjaan) {
							$jenis_pekerjaan_ayah = $find_jenis_pekerjaan->id_jenis_pekerjaan;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ayah ' . $value->jenis_pekerjaan_ayah . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ayah ' . $value->jenis_pekerjaan_ayah . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ayah ' . $value->jenis_pekerjaan_ayah . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// jenis penghasilan ayah
					if (empty($value->jenis_penghasilan_ayah)) {
						$jenis_penghasilan_ayah = null;
					} else {
						$find_jenis_penghasilan = $data_jenis_penghasilan->firstWhere('kode_jenis_penghasilan', $value->jenis_penghasilan_ayah);
						if ($find_jenis_penghasilan) {
							$jenis_penghasilan_ayah = $find_jenis_penghasilan->id_jenis_penghasilan;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ayah ' . $value->jenis_penghasilan_ayah . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ayah ' . $value->jenis_penghasilan_ayah . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ayah ' . $value->jenis_penghasilan_ayah . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// kebutuhan khusus ayah
					if (empty($value->kebutuhan_khusus_ayah)) {
						$kebutuhan_khusus_ayah = null;
					} else {
						$find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus_ayah);
						if ($find_kebutuhan_khusus) {
							$kebutuhan_khusus_ayah = $find_kebutuhan_khusus->id_kebutuhan_khusus;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ayah ' . $value->kebutuhan_khusus_ayah . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ayah ' . $value->kebutuhan_khusus_ayah . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ayah ' . $value->kebutuhan_khusus_ayah . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// nama ibu
					if (empty($value->nama_ibu)) {
						$nama_ibu = null;
					} else {
						$nama_ibu = $value->nama_ibu;
					}

					// nik ibu
					if (empty($value->nik_ibu)) {
						$nik_ibu = null;
					} else {
						$nik_ibu = $value->nik_ibu;
					}

					// tanggal lahir ibu
					if (empty($value->tanggal_lahir_ibu)) {
						$tanggal_lahir_ibu = null;
					} else {
						$tanggal_lahir_ibu = date('Y-m-d', strtotime($value->tanggal_lahir_ibu));
					}

					// jenis pendidikan ibu
					if (empty($value->jenis_pendidikan_ibu)) {
						$jenis_pendidikan_ibu = null;
					} else {
						$find_jenis_pendidikan = $data_jenis_pendidikan->firstWhere('kode_jenis_pendidikan', $value->jenis_pendidikan_ibu);
						if ($find_jenis_pendidikan) {
							$jenis_pendidikan_ibu = $find_jenis_pendidikan->id_jenis_pendidikan;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ibu ' . $value->jenis_pendidikan_ibu . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ibu ' . $value->jenis_pendidikan_ibu . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan ibu ' . $value->jenis_pendidikan_ibu . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// jenis pekerjaan ibu
					if (empty($value->jenis_pekerjaan_ibu)) {
						$jenis_pekerjaan_ibu = null;
					} else {
						$find_jenis_pekerjaan = $data_jenis_pekerjaan->firstWhere('kode_jenis_pekerjaan', $value->jenis_pekerjaan_ibu);
						if ($find_jenis_pekerjaan) {
							$jenis_pekerjaan_ibu = $find_jenis_pekerjaan->id_jenis_pekerjaan;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ibu ' . $value->jenis_pekerjaan_ibu . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ibu ' . $value->jenis_pekerjaan_ibu . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan ibu ' . $value->jenis_pekerjaan_ibu . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// jenis penghasilan ibu
					if (empty($value->jenis_penghasilan_ibu)) {
						$jenis_penghasilan_ibu = null;
					} else {
						$find_jenis_penghasilan = $data_jenis_penghasilan->firstWhere('kode_jenis_penghasilan', $value->jenis_penghasilan_ibu);
						if ($find_jenis_penghasilan) {
							$jenis_penghasilan_ibu = $find_jenis_penghasilan->id_jenis_penghasilan;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ibu ' . $value->jenis_penghasilan_ibu . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ibu ' . $value->jenis_penghasilan_ibu . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan ibu ' . $value->jenis_penghasilan_ibu . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// kebutuhan khusus ibu
					if (empty($value->kebutuhan_khusus_ibu)) {
						$kebutuhan_khusus_ibu = null;
					} else {
						$find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus_ibu);
						if ($find_kebutuhan_khusus) {
							$kebutuhan_khusus_ibu = $find_kebutuhan_khusus->id_kebutuhan_khusus;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ibu ' . $value->kebutuhan_khusus_ibu . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ibu ' . $value->kebutuhan_khusus_ibu . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus ibu ' . $value->kebutuhan_khusus_ibu . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// nama wali
					if (empty($value->nama_wali)) {
						$nama_wali = null;
					} else {
						$nama_wali = $value->nama_wali;
					}

					// nik wali
					if (empty($value->nik_wali)) {
						$nik_wali = null;
					} else {
						$nik_wali = $value->nik_wali;
					}

					// tanggal lahir wali
					if (empty($value->tanggal_lahir_wali)) {
						$tanggal_lahir_wali = null;
					} else {
						$tanggal_lahir_wali = date('Y-m-d', strtotime($value->tanggal_lahir_wali));
					}

					// jenis pendidikan wali
					if (empty($value->jenis_pendidikan_wali)) {
						$jenis_pendidikan_wali = null;
					} else {
						$find_jenis_pendidikan = $data_jenis_pendidikan->firstWhere('kode_jenis_pendidikan', $value->jenis_pendidikan_wali);
						if ($find_jenis_pendidikan) {
							$jenis_pendidikan_wali = $find_jenis_pendidikan->id_jenis_pendidikan;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan wali ' . $value->jenis_pendidikan_wali . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan wali ' . $value->jenis_pendidikan_wali . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pendidikan wali ' . $value->jenis_pendidikan_wali . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// jenis pekerjaan wali
					if (empty($value->jenis_pekerjaan_wali)) {
						$jenis_pekerjaan_wali = null;
					} else {
						$find_jenis_pekerjaan = $data_jenis_pekerjaan->firstWhere('kode_jenis_pekerjaan', $value->jenis_pekerjaan_wali);
						if ($find_jenis_pekerjaan) {
							$jenis_pekerjaan_wali = $find_jenis_pekerjaan->id_jenis_pekerjaan;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan wali ' . $value->jenis_pekerjaan_wali . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan wali ' . $value->jenis_pekerjaan_wali . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis pekerjaan wali ' . $value->jenis_pekerjaan_wali . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// jenis penghasilan wali
					if (empty($value->jenis_penghasilan_wali)) {
						$jenis_penghasilan_wali = null;
					} else {
						$find_jenis_penghasilan = $data_jenis_penghasilan->firstWhere('kode_jenis_penghasilan', $value->jenis_penghasilan_wali);
						if ($find_jenis_penghasilan) {
							$jenis_penghasilan_wali = $find_jenis_penghasilan->id_jenis_penghasilan;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan wali ' . $value->jenis_penghasilan_wali . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan wali ' . $value->jenis_penghasilan_wali . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, jenis penghasilan wali ' . $value->jenis_penghasilan_wali . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// kebutuhan khusus wali
					if (empty($value->kebutuhan_khusus_wali)) {
						$kebutuhan_khusus_wali = null;
					} else {
						$find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus_wali);
						if ($find_kebutuhan_khusus) {
							$kebutuhan_khusus_wali = $find_kebutuhan_khusus->id_kebutuhan_khusus;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus wali ' . $value->kebutuhan_khusus_wali . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus wali ' . $value->kebutuhan_khusus_wali . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, kebutuhan khusus wali ' . $value->kebutuhan_khusus_wali . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// alamat jalan orang tua
					if (empty($value->alamat_jalan_orang_tua)) {
						$alamat_jalan_orang_tua = null;
					} else {
						$alamat_jalan_orang_tua = $value->alamat_jalan_orang_tua;
					}

					// alamat dusun orang tua
					if (empty($value->alamat_dusun_orang_tua)) {
						$alamat_dusun_orang_tua = null;
					} else {
						$alamat_dusun_orang_tua = $value->alamat_dusun_orang_tua;
					}

					// alamat kelurahan orang tua
					if (empty($value->alamat_kelurahan_orang_tua)) {
						$alamat_kelurahan_orang_tua = null;
					} else {
						$alamat_kelurahan_orang_tua = $value->alamat_kelurahan_orang_tua;
					}

					// alamat rt orang tua
					if (empty($value->alamat_rt_orang_tua)) {
						$alamat_rt_orang_tua = null;
					} else {
						$alamat_rt_orang_tua = $value->alamat_rt_orang_tua;
					}

					// alamat rw orang tua
					if (empty($value->alamat_rw_orang_tua)) {
						$alamat_rw_orang_tua = null;
					} else {
						$alamat_rw_orang_tua = $value->alamat_rw_orang_tua;
					}

					// alamat kecamatan orang tua
					if (empty($value->alamat_kecamatan_orang_tua)) {
						$alamat_kecamatan_orang_tua = null;
					} else {
						$alamat_kecamatan_orang_tua = $value->alamat_kecamatan_orang_tua;
					}

					// alamat kodepos orang tua
					if (empty($value->alamat_kodepos_orang_tua)) {
						$alamat_kodepos_orang_tua = null;
					} else {
						$alamat_kodepos_orang_tua = $value->alamat_kodepos_orang_tua;
					}

					// find alamat kota orang tua
					if (empty($value->alamat_kota_orang_tua)) {
						$alamat_kota_orang_tua = null;
					} else {
						$find_alamat_kota = $data_kota->firstWhere('nm_kota', $value->alamat_kota_orang_tua);
						if ($find_alamat_kota) {
							$alamat_kota_orang_tua = $find_alamat_kota->id_kota;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota orang tua ' . $value->alamat_kota_orang_tua . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota orang tua ' . $value->alamat_kota_orang_tua . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat kota orang tua ' . $value->alamat_kota_orang_tua . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// find id provinsi orang tua
					if (empty($value->alamat_provinsi_orang_tua)) {
						$alamat_provinsi_orang_tua = null;
					} else {
						$find_provinsi = $data_provinsi->firstWhere('nm_provinsi', $value->alamat_provinsi_orang_tua);
						if ($find_provinsi) {
							$alamat_provinsi_orang_tua = $find_provinsi->id_provinsi;
						} else {
							// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi orang tua ' . $value->alamat_provinsi_orang_tua . ' tidak ditemukan di dalam sistem';
							Debugbar::error(
								'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi orang tua ' . $value->alamat_provinsi_orang_tua . ' tidak ditemukan di dalam sistem'
							);
							return [
								'status'    => 300, // FAILED
								'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, alamat provinsi orang tua ' . $value->alamat_provinsi_orang_tua . ' tidak ditemukan di dalam sistem'
							];
						}
					}

					// no telp orang tua
					if (empty($value->no_telepon_orang_tua)) {
						$no_telepon_orang_tua = null;
					} else {
						$no_telepon_orang_tua = $value->no_telepon_orang_tua;
					}

					// no hp orang tua
					if (empty($value->no_hp_orang_tua)) {
						$no_hp_orang_tua = null;
					} else {
						$no_hp_orang_tua = $value->no_hp_orang_tua;
					}

					// email orang tua
					if (empty($value->email_orang_tua)) {
						$email_orang_tua = null;
					} else {
						$email_orang_tua = $value->email_orang_tua;
					}

					// tinggi badan
					if (empty($value->tinggi_badan_cm)) {
						$tinggi_badan_cm = null;
					} else {
						$tinggi_badan_cm = (float) $value->tinggi_badan_cm;
					}

					// berat badan
					if (empty($value->berat_badan_kg)) {
						$berat_badan_kg = null;
					} else {
						$berat_badan_kg = (float) $value->berat_badan_kg;
					}

					// is berjilbab
					if ($value->apakah_berjilbab == 1 || $value->apakah_berjilbab == 0) {
						$apakah_berjilbab = (int) $value->apakah_berjilbab;
					} else {
						$apakah_berjilbab = null;
					}

					// is buta warna
					if ($value->apakah_buta_warna == 1 || $value->apakah_buta_warna == 0) {
						$apakah_buta_warna = (int) $value->apakah_buta_warna;
					} else {
						$apakah_buta_warna = null;
					}

					// ukuran baju
					if (empty($value->ukuran_baju)) {
						$ukuran_baju = null;
					} else {
						$ukuran_baju = $value->ukuran_baju;
					}

					// riwayat penyakit
					if (empty($value->riwayat_penyakit)) {
						$riwayat_penyakit = null;
					} else {
						$riwayat_penyakit = $value->riwayat_penyakit;
					}

					// golongan darah
					if ($value->golongan_darah == 'A' || $value->golongan_darah == 'B' || $value->golongan_darah == 'O' || $value->golongan_darah == 'AB') {
						$golongan_darah = $value->golongan_darah;
					} else {
						$golongan_darah = null;
					}

					// riwayat kelainan jasmani
					if (empty($value->riwayat_kelainan_jasmani)) {
						$riwayat_kelainan_jasmani = null;
					} else {
						$riwayat_kelainan_jasmani = $value->riwayat_kelainan_jasmani;
					}

					if ($id_penerimaan == null || $semester_masuk == null || $jenis_kelamin == null || $jalur == null || $kelas == null || $status == null) {
						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, Data Tidak Valid pada Siswa "' . $value->nama_lengkap . '"';
						Debugbar::error(
							'Upload Data Siswa ' . (string) $value->nis . ' Gagal, Data Tidak Valid pada Siswa "' . $value->nama_lengkap . '"'
						);
						return [
							'status'    => 300, // FAILED
							'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, Data Tidak Valid pada Siswa "' . $value->nama_lengkap . '"'
						];
					} else {
						//generate id
						$id_siswa = $auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
						$id_pengguna = $auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
						$id_c_siswa = $auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
						$id_admisi = $auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
						$id_jalur_siswa = $auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
						$id_log_kelas_siswa = $auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
						$arr[] = array(
							'id_log_kelas_siswa' => $id_log_kelas_siswa,
							'id_siswa' => $id_siswa,
							'id_pengguna' => $id_pengguna,
							'id_c_siswa' => $id_c_siswa,
							'id_admisi' => $id_admisi,
							'id_jalur_siswa' => $id_jalur_siswa,
							'id_penerimaan' => $id_penerimaan->id_penerimaan,
							'nis' => (string) $value->nis,
							'nisn' => (string) $value->nisn,
							'nama_lengkap' => $value->nama_lengkap,
							'status_siswa' => $status->id_status_pengguna,
							'kelas' => $kelas->id_kelas,
							'tahun_masuk' => (int) $value->tahun_masuk,
							'jalur' => $jalur->id_jalur,
							'jenis_kelamin' => $jenis_kelamin,
							'semester_masuk' => $semester_masuk['id_semester'],
							'id_sekolah' => $auth_data->pengguna->id_sekolah,
							'created_by' => $auth_data->pengguna->id_pengguna,
							'is_orang_tua' => $is_orang_tua,
							'kode_voucher' => $kode_voucher,
							'nik_siswa' => $nik,
							'id_agama' => $agama,
							'id_kota_lahir' => $kota_lahir,
							'tgl_lahir' => $tanggal_lahir,
							'id_kota_ksk' => $kota_ksk,
							'nomor_ksk' => $nomor_ksk,
							'nomor_identitas' => $nomor_identitas,
							'nomor_akta_lahir' => $nomor_akta_lahir,
							'kewarganegaraan' => $kewarganegaraan,
							'nm_kewarganegaraan' => $nm_kewarganegaraan,
							'id_kebutuhan_khusus' => $kebutuhan_khusus,
							'alamat_jalan' => $alamat_jalan,
							'alamat_dusun' => $alamat_dusun,
							'alamat_kelurahan' => $alamat_kelurahan,
							'alamat_rt' => $alamat_rt,
							'alamat_rw' => $alamat_rw,
							'alamat_kecamatan' => $alamat_kecamatan,
							'alamat_kodepos' => $alamat_kodepos,
							'alamat_kota' => $alamat_kota,
							'alamat_provinsi' => $alamat_provinsi,
							'alamat_latitude' => $alamat_latitude,
							'alamat_longitude' => $alamat_longitude,
							'nomor_hp' => $nomor_hp,
							'id_jenis_tinggal' => $jenis_tinggal,
							'anak_ke' => $anak_ke,
							'dari_x_bersaudara' => $dari_berapa_bersaudara,
							'jarak_rumah_sekolah' => $jarak_rumah_ke_sekolah_km,
							'waktu_tempuh_sekolah_jam' => $waktu_tempuh_ke_sekolah_jam,
							'waktu_tempuh_sekolah_menit' => $waktu_tempuh_ke_sekolah_menit,
							'id_jenis_transportasi' => $jenis_transportasi,
							'nomor_kks' => $nomor_kartu_keluarga_sejahtera,
							'is_penerima_kps' => $merupakan_penerima_kartu_perlindungan_sosial,
							'nomor_kps' => $nomor_kartu_perlindungan_sosial,
							'is_punya_kip' => $merupakan_penerima_kartu_indonesia_pintar,
							'nomor_kip' => $nomor_kartu_indonesia_pintar,
							'nm_tertera_kip' => $nama_tertera_pada_kip,
							'is_layak_pip' => $layak_pip,
							'id_jenis_layak_pip' => $jenis_layak_pip,
							'asal_sekolah' => $asal_sekolah,
							'id_kota_sekolah_asal' => $kota_asal_sekolah_sebelumnya,
							'nomor_ujian_sebelumnya' => $nomor_ujian_sebelumnya,
							'nomor_ijasah_sebelumnya' => $nomor_ijasah_sebelumnya,
							'nomor_skhus_sebelumnya' => $nomor_skhus_sebelumnya,
							'nomor_shun' => $nomor_shun_sebelumnya,
							'nilai_shun' => $nilai_shun_sebelumnya,
							'tahun_lulus' => $tahun_lulus,
							'nomor_peserta_unas' => $nomor_peserta_unas,
							'nm_ayah' => $nama_ayah,
							'nik_ayah' => $nik_ayah,
							'tgl_lahir_ayah' => $tanggal_lahir_ayah,
							'id_jenis_pendidikan_ayah' => $jenis_pendidikan_ayah,
							'id_jenis_pekerjaan_ayah' => $jenis_pekerjaan_ayah,
							'id_jenis_penghasilan_ayah' => $jenis_penghasilan_ayah,
							'id_kebutuhan_khusus_ayah' => $kebutuhan_khusus_ayah,
							'nm_ibu' => $nama_ibu,
							'nik_ibu' => $nik_ibu,
							'tgl_lahir_ibu' => $tanggal_lahir_ibu,
							'id_jenis_pendidikan_ibu' => $jenis_pendidikan_ibu,
							'id_jenis_pekerjaan_ibu' => $jenis_pekerjaan_ibu,
							'id_jenis_penghasilan_ibu' => $jenis_penghasilan_ibu,
							'id_kebutuhan_khusus_ibu' => $kebutuhan_khusus_ibu,
							'nm_wali' => $nama_wali,
							'nik_wali' => $nik_wali,
							'tgl_lahir_wali' => $tanggal_lahir_wali,
							'id_jenis_pendidikan_wali' => $jenis_pendidikan_wali,
							'id_jenis_pekerjaan_wali' => $jenis_pekerjaan_wali,
							'id_jenis_penghasilan_wali' => $jenis_penghasilan_wali,
							'id_kebutuhan_khusus_wali' => $kebutuhan_khusus_wali,
							'alamat_jalan_ortu' => $alamat_jalan_orang_tua,
							'alamat_dusun_ortu' => $alamat_dusun_orang_tua,
							'alamat_kelurahan_ortu' => $alamat_kelurahan_orang_tua,
							'alamat_rt_ortu' => $alamat_rt_orang_tua,
							'alamat_rw_ortu' => $alamat_rw_orang_tua,
							'alamat_kecamatan_ortu' => $alamat_kecamatan_orang_tua,
							'alamat_kodepos_ortu' => $alamat_kodepos_orang_tua,
							'alamat_kota_ortu' => $alamat_kota_orang_tua,
							'alamat_provinsi_ortu' => $alamat_provinsi_orang_tua,
							'nomor_telp_ortu' => $no_telepon_orang_tua,
							'nomor_hp_ortu' => $no_hp_orang_tua,
							'email_ortu' => $email_orang_tua,
							'tinggi_badan' => $tinggi_badan_cm,
							'berat_badan' => $berat_badan_kg,
							'is_berjilbab' => $apakah_berjilbab,
							'is_buta_warna' => $apakah_buta_warna,
							'ukuran_baju' => $ukuran_baju,
							'riwayat_penyakit' => $riwayat_penyakit,
							'golongan_darah' => $golongan_darah,
							'riwayat_kelainan_jasmani' => $riwayat_kelainan_jasmani,

						);
					}
				}
			}

			if (count($arr) != 0) {
				foreach ($arr as $data_siswa_1) {
					$jumlah_nis = 0;
					$jumlah_nisn = 0;
					foreach ($arr as $data_siswa_2) {
						if ($data_siswa_1['nis'] == $data_siswa_2['nis']) {
							$jumlah_nis++;
						}

						if ($data_siswa_1['nisn'] == $data_siswa_2['nisn']) {
							$jumlah_nisn++;
						}
					}

					if ($jumlah_nis > 1) {
						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NIS ' . $data_siswa_1['nis'] . ' yang sama di dalam file yang diupload';
						Debugbar::error(
							'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NIS ' . $data_siswa_1['nis'] . ' yang sama di dalam file yang diupload'
						);
						return [
							'status'    => 300, // FAILED
							'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NIS ' . $data_siswa_1['nis'] . ' yang sama di dalam file yang diupload'
						];
					}

					if (!empty($data_siswa_1['nisn']) && $jumlah_nisn > 1) {
						// $this->message[] = 'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NISN ' . $data_siswa_1['nisn'] . ' yang sama di dalam file yang diupload';
						Debugbar::error(
							'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NISN ' . $data_siswa_1['nisn'] . ' yang sama di dalam file yang diupload'
						);
						return [
							'status'    => 300, // FAILED
							'message'   =>  'Upload Data Siswa ' . (string) $value->nis . ' Gagal, ditemukan NISN ' . $data_siswa_1['nisn'] . ' yang sama di dalam file yang diupload'
						];
					}
				}

				DB::beginTransaction();
				try {
					$pengguna_center = [];
					foreach ($arr as $data_siswa) {
						$row_calon_siswa_baru = [
							'id_penerimaan' => $data_siswa['id_penerimaan'],
							'nm_c_siswa' => $data_siswa['nama_lengkap'],
							'jenis_kelamin' => $data_siswa['jenis_kelamin'],
							'nisn_siswa' => $data_siswa['nisn'],
							'nis_siswa' => $data_siswa['nis'],

							'kode_voucher' => $data_siswa['kode_voucher'],
							'nik_siswa' => $data_siswa['nik_siswa'],
							'id_agama' => $data_siswa['id_agama'],
							'id_kota_lahir' => $data_siswa['id_kota_lahir'],
							'tgl_lahir' => $data_siswa['tgl_lahir'],
							'id_kota_ksk' => $data_siswa['id_kota_ksk'],
							'nomor_ksk' => $data_siswa['nomor_ksk'],
							'nomor_identitas' => $data_siswa['nomor_identitas'],
							'nomor_akta_lahir' => $data_siswa['nomor_akta_lahir'],
							'kewarganegaraan' => $data_siswa['kewarganegaraan'],
							'nm_kewarganegaraan' => $data_siswa['nm_kewarganegaraan'],
							'id_kebutuhan_khusus' => $data_siswa['id_kebutuhan_khusus'],
							'alamat_jalan' => $data_siswa['alamat_jalan'],
							'alamat_dusun' => $data_siswa['alamat_dusun'],
							'alamat_kelurahan' => $data_siswa['alamat_kelurahan'],
							'alamat_rt' => $data_siswa['alamat_rt'],
							'alamat_rw' => $data_siswa['alamat_rw'],
							'alamat_kecamatan' => $data_siswa['alamat_kecamatan'],
							'alamat_kodepos' => $data_siswa['alamat_kodepos'],
							'alamat_kota' => $data_siswa['alamat_kota'],
							'alamat_provinsi' => $data_siswa['alamat_provinsi'],
							'alamat_latitude' => $data_siswa['alamat_latitude'],
							'alamat_longitude' => $data_siswa['alamat_longitude'],
							'nomor_hp' => $data_siswa['nomor_hp'],
							'id_jenis_tinggal' => $data_siswa['id_jenis_tinggal'],
							'anak_ke' => $data_siswa['anak_ke'],
							'dari_x_bersaudara' => $data_siswa['dari_x_bersaudara'],
							'jarak_rumah_sekolah' => $data_siswa['jarak_rumah_sekolah'],
							'waktu_tempuh_sekolah_jam' => $data_siswa['waktu_tempuh_sekolah_jam'],
							'waktu_tempuh_sekolah_menit' => $data_siswa['waktu_tempuh_sekolah_menit'],
							'id_jenis_transportasi' => $data_siswa['id_jenis_transportasi'],
							'nomor_kks' => $data_siswa['nomor_kks'],
							'is_penerima_kps' => $data_siswa['is_penerima_kps'],
							'nomor_kps' => $data_siswa['nomor_kps'],
							'is_punya_kip' => $data_siswa['is_punya_kip'],
							'nomor_kip' => $data_siswa['nomor_kip'],
							'nm_tertera_kip' => $data_siswa['nm_tertera_kip'],
							'is_layak_pip' => $data_siswa['is_layak_pip'],
							'id_jenis_layak_pip' => $data_siswa['id_jenis_layak_pip'],
							'asal_sekolah' => $data_siswa['asal_sekolah'],
							'nomor_ujian_sebelumnya' => $data_siswa['nomor_ujian_sebelumnya'],
							'nomor_ijasah_sebelumnya' => $data_siswa['nomor_ijasah_sebelumnya'],
							'nomor_skhus_sebelumnya' => $data_siswa['nomor_skhus_sebelumnya'],

							'created_at' => $now,
							'created_by' => $data_siswa['created_by'],
							'updated_at' => $now,
							'updated_by' => $data_siswa['created_by'],
						];

						$check_nis_siswa = Siswa::where('nis_siswa', $data_siswa['nis'])->first();

						if ($check_nis_siswa) {
							DB::table('calon_siswa_baru')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($row_calon_siswa_baru);
						} else {
							$row_calon_siswa_baru['id_c_siswa'] = $data_siswa['id_c_siswa'];

							DB::table('calon_siswa_baru')->insert($row_calon_siswa_baru);
						}

						$row_calon_siswa_fisik = [
							'tinggi_badan' => $data_siswa['tinggi_badan'],
							'berat_badan' => $data_siswa['berat_badan'],
							'is_berjilbab' => $data_siswa['is_berjilbab'],
							'is_buta_warna' => $data_siswa['is_buta_warna'],
							'ukuran_baju' => $data_siswa['ukuran_baju'],
							'riwayat_penyakit' => $data_siswa['riwayat_penyakit'],
							'golongan_darah' => $data_siswa['golongan_darah'],
							'riwayat_kelainan_jasmani' => $data_siswa['riwayat_kelainan_jasmani'],

							'created_at' => $now,
							'created_by' => $data_siswa['created_by'],
							'updated_at' => $now,
							'updated_by' => $data_siswa['created_by'],
						];

						if ($check_nis_siswa) {
							DB::table('calon_siswa_fisik')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($row_calon_siswa_fisik);
						} else {
							$row_calon_siswa_fisik['id_c_siswa'] = $data_siswa['id_c_siswa'];

							DB::table('calon_siswa_fisik')->insert($row_calon_siswa_fisik);
						}

						$row_calon_siswa_ortu = [
							'nm_ayah' => $data_siswa['nm_ayah'],
							'nik_ayah' => $data_siswa['nik_ayah'],
							'tgl_lahir_ayah' => $data_siswa['tgl_lahir_ayah'],
							'id_jenis_pendidikan_ayah' => $data_siswa['id_jenis_pendidikan_ayah'],
							'id_jenis_pekerjaan_ayah' => $data_siswa['id_jenis_pekerjaan_ayah'],
							'id_jenis_penghasilan_ayah' => $data_siswa['id_jenis_penghasilan_ayah'],
							'id_kebutuhan_khusus_ayah' => $data_siswa['id_kebutuhan_khusus_ayah'],
							'nm_ibu' => $data_siswa['nm_ibu'],
							'nik_ibu' => $data_siswa['nik_ibu'],
							'tgl_lahir_ibu' => $data_siswa['tgl_lahir_ibu'],
							'id_jenis_pendidikan_ibu' => $data_siswa['id_jenis_pendidikan_ibu'],
							'id_jenis_pekerjaan_ibu' => $data_siswa['id_jenis_pekerjaan_ibu'],
							'id_jenis_penghasilan_ibu' => $data_siswa['id_jenis_penghasilan_ibu'],
							'id_kebutuhan_khusus_ibu' => $data_siswa['id_kebutuhan_khusus_ibu'],
							'nm_wali' => $data_siswa['nm_wali'],
							'nik_wali' => $data_siswa['nik_wali'],
							'tgl_lahir_wali' => $data_siswa['tgl_lahir_wali'],
							'id_jenis_pendidikan_wali' => $data_siswa['id_jenis_pendidikan_wali'],
							'id_jenis_pekerjaan_wali' => $data_siswa['id_jenis_pekerjaan_wali'],
							'id_jenis_penghasilan_wali' => $data_siswa['id_jenis_penghasilan_wali'],
							'id_kebutuhan_khusus_wali' => $data_siswa['id_kebutuhan_khusus_wali'],
							'alamat_jalan_ortu' => $data_siswa['alamat_jalan_ortu'],
							'alamat_dusun_ortu' => $data_siswa['alamat_dusun_ortu'],
							'alamat_kelurahan_ortu' => $data_siswa['alamat_kelurahan_ortu'],
							'almat_rt_ortu' => $data_siswa['alamat_rt_ortu'],
							'alamat_rw_ortu' => $data_siswa['alamat_rw_ortu'],
							'alamat_kecamatan_ortu' => $data_siswa['alamat_kecamatan_ortu'],
							'alamat_kodepos_ortu' => $data_siswa['alamat_kodepos_ortu'],
							'alamat_kota_ortu' => $data_siswa['alamat_kota_ortu'],
							'alamat_provinsi_ortu' => $data_siswa['alamat_provinsi_ortu'],
							'nomor_telp_ortu' => $data_siswa['nomor_telp_ortu'],
							'nomor_hp_ortu' => $data_siswa['nomor_hp_ortu'],
							'email_ortu' => $data_siswa['email_ortu'],

							'created_at' => $now,
							'created_by' => $data_siswa['created_by'],
							'updated_at' => $now,
							'updated_by' => $data_siswa['created_by'],
						];

						if ($check_nis_siswa) {
							DB::table('calon_siswa_ortu')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($row_calon_siswa_ortu);
						} else {
							$row_calon_siswa_ortu['id_c_siswa'] = $data_siswa['id_c_siswa'];

							DB::table('calon_siswa_ortu')->insert($row_calon_siswa_ortu);
						}

						$calon_siswa_sekolah = [
							'nm_sekolah_asal' => $data_siswa['asal_sekolah'],
							'id_kota_sekolah_asal' => $data_siswa['id_kota_sekolah_asal'],
							'nomor_shun' => $data_siswa['nomor_shun'],
							'nilai_shun' => $data_siswa['nilai_shun'],
							'nomor_ijasah' => $data_siswa['nomor_ijasah_sebelumnya'],
							'tahun_lulus' => $data_siswa['tahun_lulus'],
							'nomor_peserta_unas' => $data_siswa['nomor_peserta_unas'],
							'nisn' => $data_siswa['nisn'],

							'created_at' => $now,
							'created_by' => $data_siswa['created_by'],
							'updated_at' => $now,
							'updated_by' => $data_siswa['created_by'],
						];

						if ($check_nis_siswa) {
							DB::table('calon_siswa_sekolah')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($calon_siswa_sekolah);
						} else {
							$calon_siswa_sekolah['id_c_siswa'] = $data_siswa['id_c_siswa'];

							DB::table('calon_siswa_sekolah')->insert($calon_siswa_sekolah);
						}

						if ($check_nis_siswa) {
							DB::table('pengguna')->where('id_pengguna', $check_nis_siswa->id_pengguna)->update(['nm_pengguna' => $data_siswa['nama_lengkap']]);
						} else {
							DB::table('pengguna')->insert(
								[
									'id_pengguna' => $data_siswa['id_pengguna'],
									'id_status_pengguna' => $data_siswa['status_siswa'],
									'id_sekolah' => $data_siswa['id_sekolah'],
									'nm_pengguna' => $data_siswa['nama_lengkap'],
									'username' => $data_siswa['nis'],
									'password' => Hash::make($data_siswa['nis']),
									'must_change_password' => 1,
									'status_join_table' => 3,
									'created_at' => $now,
									'created_by' => $data_siswa['created_by'],
								]
							);
						}

						$row_siswa = [
							'id_kelas' => $data_siswa['kelas'],
							'nisn_siswa' => $data_siswa['nisn'],

							'is_orang_tua' => $data_siswa['is_orang_tua'],

							'thn_masuk_siswa' => $data_siswa['tahun_masuk'],
							'created_at' => $now,
							'created_by' => $data_siswa['created_by'],
							'updated_at' => $now,
							'updated_by' => $data_siswa['created_by'],
						];

						if ($check_nis_siswa) {
							DB::table('siswa')->where('id_siswa', $check_nis_siswa->id_siswa)->update($row_siswa);
						} else {
							$row_siswa['id_siswa'] = $data_siswa['id_siswa'];
							$row_siswa['id_pengguna'] = $data_siswa['id_pengguna'];
							$row_siswa['id_c_siswa'] = $data_siswa['id_c_siswa'];
							$row_siswa['id_kelompok_biaya'] = null;
							$row_siswa['nis_siswa'] = $data_siswa['nis'];

							DB::table('siswa')->insert($row_siswa);
						}

						if ($check_nis_siswa) {
						} else {
							DB::table('admisi')->insert(
								[
									'id_admisi' => $data_siswa['id_admisi'],
									'id_siswa' => $data_siswa['id_siswa'],
									'id_semester' => $data_siswa['semester_masuk'],
									'id_status_pengguna' => $data_siswa['status_siswa'],
									'id_jalur' => $data_siswa['jalur'],
									'created_at' => $now,
									'created_by' => $data_siswa['created_by'],
								]
							);

							DB::table('jalur_siswa')->insert(
								[
									'id_jalur_siswa' => $data_siswa['id_jalur_siswa'],
									'id_siswa' => $data_siswa['id_siswa'],
									'id_semester' => $data_siswa['semester_masuk'],
									'id_jalur' => $data_siswa['jalur'],
									'id_admisi' => $data_siswa['id_admisi'],
									'is_jalur_aktif' => 1,
									'created_at' => $now,
									'created_by' => $data_siswa['created_by'],
								]
							);

							DB::table('role_pengguna')->insert(
								[
									'id_pengguna' => $data_siswa['id_pengguna'],
									'id_role' => 3,
									'keterangan_role_pengguna' => "Input Pendidikan",
									'is_aktif' => 1,
									'created_at' => $now,
									'created_by' => $data_siswa['created_by'],
								]
							);

							DB::table('log_kelas_siswa')->insert(
								[
									'id_log_kelas_siswa' => $data_siswa['id_log_kelas_siswa'],
									'id_siswa' => $data_siswa['id_siswa'],
									'id_kelas' => $data_siswa['kelas'],
									'created_at' => $now,
									'updated_at' => $now,
									'created_by' => $data_siswa['created_by'],
								]
							);

							$pengguna_center[] = [
								"id_pengguna" => $data_siswa['id_pengguna'],
								"id_sekolah" => $data_siswa['id_sekolah'],
								"username" => $data_siswa['nis'],
							];
						}
					}

					LibGlobal::insertUpdateUserInCenter($pengguna_center);
					DB::commit();
					// $this->message[] = 'Save Siswa Successfully';
					Debugbar::error('Save Siswa Successfully');
					return [
						'status'    => 300, // FAILED
						'message'   =>  'Save Siswa Successfully'
					];
				} catch (\Exception $e) {

					DB::rollback();
					// something went wrong
					//    Debugbar::error( (env('APP_DEBUG', 'true') == 'true') ? 'tesst' : 'Operation error');
					// $this->message[] = (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : $e->getMessage();

					Debugbar::error((env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : $e->getMessage());
					return [
						'status'    => 300, // FAILED
						'message'   =>  $e->getMessage()
					];
				}
			} else {
				// $this->message[] = 'File Excel Anda Kosong';
				return [
					'status'    => 300, // FAILED
					'message'   => "File Excel Anda Kosong"
				];
			}
		} else {
			return [
				'status'    => 300, // FAILED
				'message'   => "File Excel Tidak Ditemukan"
			];
		}
	}



	// public function uploadFileExcel(Request $request)
	// {
	// 	$input = (object) $request->input();
	// 	$auth_data = $input->auth_data;
	// 	$now = Carbon::now();

	// 	if ($request->hasFile('file-excel')) {
	// 		// $path = $request->file('file-excel')->getRealPath();
	// 		// $data = Excel::load($path)->get();
	// 		$uploader = new UploadToInsertUpdateSiswa($auth_data, $now);
	// 		$upload = Excel::import($uploader, $request->file('file-excel'));

	// 		$message = $uploader->getMessage();

	// 		return [
	// 			'status'    => $upload ? 200 : 300,
	// 			'message'   => $message[0]
	// 		];
	// 	} else {
	// 		return [
	// 			'status'    => 300, // FAILED
	// 			'message'   => "File Excel Tidak Ditemukan"
	// 		];
	// 	}
	// }

}
