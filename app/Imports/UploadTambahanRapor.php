<?php

namespace App\Imports;

// use Carbon\Carbon;
// use App\Models\Sekolah;

use App\Models\Kelas;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiPribadiSisipan;
use App\Models\NilaiRaporSisipan;
use App\Models\NilaiTambahanRapor;
use App\Models\Pengguna;
use App\Models\PribadiSisipan;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TambahanRapor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use Carbon\Carbon;
use Auth;

class UploadTambahanRapor implements ToCollection, WithHeadingRow
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
		$list_tambahan_rapor = TambahanRapor::get();
		$kelas = Kelas::where('nm_kelas', $rows[0]['kelas'])->first();
		$list_siswa = Siswa::where('id_kelas', $kelas->id_kelas)->get();
		$nilai_tambahan_rapor = NilaiTambahanRapor::get();
		$semester = Semester::where('is_aktif_semester', '1')->first();
		$sekolah = Sekolah::first();

		foreach ($rows as $row) {
			// dd($row);
			foreach ($list_tambahan_rapor as $tambahan_rapor) {
				if (isset($row[str_replace([".", " "], ["", "_"], strtolower($tambahan_rapor->nm_tambahan_rapor))])) {
					$siswa = $list_siswa->firstWhere('nis_siswa', $row['nis']);
					if ($siswa) {
						$nilai = $nilai_tambahan_rapor->where('id_siswa', $siswa->id_siswa)->where('id_semester', $semester->id_semester)->where('id_tambahan_rapor', $tambahan_rapor->id_tambahan_rapor)->first();
						if (!empty($nilai)) {
							$nilai->nilai =   $row[str_replace([".", " "], ["", "_"], strtolower($tambahan_rapor->nm_tambahan_rapor))];
							$nilai->updated_by             = $id_pengguna;
							$nilai->updated_at           = $now;
							$nilai->save();
						} else {
							$nilai = new NilaiTambahanRapor;
							$nilai->id_nilai_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
							$nilai->id_tambahan_rapor = $tambahan_rapor->id_tambahan_rapor;
							$nilai->id_siswa = $siswa->id_siswa;
							$nilai->id_semester = $semester->id_semester;
							$nilai->nilai =   $row[str_replace([".", " "], ["", "_"], strtolower($tambahan_rapor->nm_tambahan_rapor))];
							$nilai->created_by = $id_pengguna;
							$nilai->save();
						}
					}
				}
			}
		}
	}
}
