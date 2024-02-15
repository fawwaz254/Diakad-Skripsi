<?php

namespace App\Imports;

// use Carbon\Carbon;
// use App\Models\Sekolah;

use App\Models\Kelas;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiPribadiSisipan;
use App\Models\NilaiRaporSisipan;
use App\Models\Pengguna;
use App\Models\PribadiSisipan;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use Carbon\Carbon;
use Auth;

class UploadPengembanganDiri implements ToCollection, WithHeadingRow
{
	/**
	 * @param Collection $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function collection(Collection $rows)
	{
		set_time_limit(-1);
		$now = Carbon::now();
		$id_pengguna = Auth::id();
		$list_pribadi_sisipan = PribadiSisipan::get();
		$kelas = Kelas::where('nm_kelas', $rows[0]['kelas'])->first();
		$list_siswa = Siswa::where('id_kelas', $kelas->id_kelas)->get();
		$nilai_pribadi_sisipan = NilaiPribadiSisipan::get();
		$semester = Semester::where('is_aktif_semester', '1')->first();
		$sekolah = Sekolah::first();

		foreach ($rows as $row) {
			foreach ($list_pribadi_sisipan as $pribadi_sisipan) {
				if (isset($row[str_replace([".", " "], ["", "_"], strtolower($pribadi_sisipan->nm_pribadi_sisipan))]) && is_numeric($row[str_replace([".", " "], ["", "_"], strtolower($pribadi_sisipan->nm_pribadi_sisipan))])) {

					$siswa = $list_siswa->firstWhere('nis_siswa', $row['nis']);

					if ($siswa) {
						$nilai = $nilai_pribadi_sisipan->where('id_siswa', $siswa->id_siswa)->where('id_semester', $semester->id_semester)->where('id_pribadi_sisipan', $pribadi_sisipan->id_pribadi_sisipan)->first();

						if ($nilai) {
							$nilai->nilai =   $row[str_replace([".", " "], ["", "_"], strtolower($pribadi_sisipan->nm_pribadi_sisipan))];
							$nilai->updated_by             = $id_pengguna;
							$nilai->updated_at           = $now;
							$nilai->save();
						} else {
							$nilai = new NilaiPribadiSisipan;
							$nilai->id_nilai_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
							$nilai->id_pribadi_sisipan = $pribadi_sisipan->id_pribadi_sisipan;
							$nilai->id_siswa = $siswa->id_siswa;
							$nilai->id_semester = $semester->id_semester;
							$nilai->nilai =   $row[str_replace([".", " "], ["", "_"], strtolower($pribadi_sisipan->nm_pribadi_sisipan))];
							$nilai->created_by = $id_pengguna;
							$nilai->save();
						}
					}
				} elseif (isset($row[str_replace([".", " "], ["", "_"], strtolower($pribadi_sisipan->nm_pribadi_sisipan))]) && $pribadi_sisipan->nm_pribadi_sisipan == 'Catatan Untuk Orang Tua') {
					$siswa = $list_siswa->firstWhere('nis_siswa', $row['nis']);

					if ($siswa) {
						$nilai = $nilai_pribadi_sisipan->where('id_siswa', $siswa->id_siswa)->where('id_semester', $semester->id_semester)->where('id_pribadi_sisipan', $pribadi_sisipan->id_pribadi_sisipan)->first();

						if ($nilai) {
							$nilai->nilai =   $row[str_replace([".", " "], ["", "_"], strtolower($pribadi_sisipan->nm_pribadi_sisipan))];
							$nilai->updated_by             = $id_pengguna;
							$nilai->updated_at           = $now;
							$nilai->save();
						} else {
							$nilai = new NilaiPribadiSisipan;
							$nilai->id_nilai_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
							$nilai->id_pribadi_sisipan = $pribadi_sisipan->id_pribadi_sisipan;
							$nilai->id_siswa = $siswa->id_siswa;
							$nilai->id_semester = $semester->id_semester;
							$nilai->nilai =   $row[str_replace([".", " "], ["", "_"], strtolower($pribadi_sisipan->nm_pribadi_sisipan))];
							$nilai->created_by = $id_pengguna;
							$nilai->save();
						}
					}
				}
			}
		}
	}
}
