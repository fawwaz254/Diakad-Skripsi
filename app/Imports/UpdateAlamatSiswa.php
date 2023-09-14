<?php

namespace App\Imports;

use DB;
use App\Models\Kota;
use App\Models\Agama;
use App\Models\Siswa;
use App\Models\Voucher;
use App\Models\Pengguna;
use App\Models\Provinsi;
use App\Libraries\LibGlobal;
use App\Models\JenisTinggal;
use App\Models\JenisLayakPip;
use App\Models\Jalur as Jalur;
use App\Models\JenisPekerjaan;
use App\Models\Kelas as Kelas;
use App\Models\JenisPendidikan;
use App\Models\KebutuhanKhusus;
use App\Models\JenisPenghasilan;
use App\Models\JenisTransportasi;
use Illuminate\Support\Collection;
use DateTime;
use DateTimeZone;
use App\Models\Semester as Semester;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Hash;
use App\Models\Penerimaan as Penerimaan;
use Barryvdh\Debugbar\Facade as Debugbar;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\StatusPengguna as StatusPengguna;
use Carbon\Carbon;

class UpdateAlamatSiswa implements ToCollection, WithHeadingRow
{

	protected $auth_data;
	protected $now;
	protected $message = array();

	public function __construct($auth_data, $now)
	{
		$this->auth_data = $auth_data;
		$this->now = $now;
	}

	/**
	 * @param Collection $row
	 *
	 * @Debugbar::error( \Illuminate\Database\Eloquent\Model|null
	 */
	public function collection(Collection $data)
	{
		set_time_limit(-1);
		$data_nis = array();

		$months = [
			'Januari' => 'January',
			'Februari' => 'February',
			'Maret' => 'March',
			'April' => 'April',
			'Mei' => 'May',
			'Juni' => 'June',
			'Juli' => 'July',
			'Agustus' => 'August',
			'September' => 'September',
			'Oktober' => 'October',
			'November' => 'November',
			'Desember' => 'December',
		];
		foreach ($data as $key => $data_row) {
			if (!empty($data_row['nis'])) {
				$data_nis[] = (string) $data_row['nis'];
			}
		};

		$data_kota = Kota::get();
		foreach ($data as $key => $data_row) {
			$value = new \stdClass();
			foreach ($data_row as $key => $temp_item) {
				$value->$key = $temp_item;
			}

			if (empty($value->nis)) {
				continue;
			}

			$siswa = Siswa::where('nis_siswa', $value->nis)->with('calon_siswa')->first();

			if (empty($siswa)) {
				Debugbar::error(
					'Upload Data Siswa Gagal, NIS ' . $value->nis . ' ditemukan sama di dalam sistem'
				);
			}


			//!..............................................................
			// find id kota lahir
			if (empty($value->kota)) {
				$kota_lahir = null;
			} else {
				$find_kota = $data_kota->firstWhere('nm_kota', $value->kota);
				if ($find_kota) {
					$kota_lahir = $find_kota->id_kota;
				} else {
					$this->message[] = 'Upload Data Siswa Gagal, kota lahir ' . $value->kota . ' tidak ditemukan di dalam sistem';
					Debugbar::error('Upload Data Siswa Gagal, kota lahir ' . $value->kota . ' tidak ditemukan di dalam sistem');
				}
			}

			//!............................................................................
			// tanggal lahir
			if (empty($value->ttl)) {
				$tanggal_lahir = null;
			} else {
				// $dateString = strtr($dateString, $months);
				// $date = DateTime::createFromFormat('j F Y', $dateString, $timezone);
				$dateString = strtr($value->ttl, $months);

				$timezone = new DateTimeZone("Asia/Jakarta");
				$date = DateTime::createFromFormat('j F Y', $dateString, $timezone);
				if ($date) {
					$tanggal_lahir = $date->format('Y-m-d'); // Menampilkan tanggal dalam format standar
				}

				// $tanggal_lahir = Carbon::parse($value->ttl)->format('Y-m-d');
				// dd($tanggal_lahir);
				// $tanggal_lahir = date('Y-m-d', strtotime($value->tanggal_lahir));
			}

			if (empty($siswa->calon_siswa->id_kota_lahir) && empty($siswa->calon_siswa->tgl_lahir)) {
				$siswa->calon_siswa->id_kota_lahir = $kota_lahir;
				$siswa->calon_siswa->tgl_lahir = $tanggal_lahir;
				$siswa->calon_siswa->save();
			}


			// $siswa->Debugbar::error('Save Siswa Successfully');
		}
	}

	public function getMessage()
	{
		return $this->message;
	}
}
