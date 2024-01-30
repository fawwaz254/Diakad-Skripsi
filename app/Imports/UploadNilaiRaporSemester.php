<?php

namespace App\Imports;

// use Carbon\Carbon;
// use App\Models\Sekolah;

use App\Models\KomponenJenisRapor;
use App\Models\NilaiRapor;
use App\Models\Pengguna;
use App\Models\Rapor;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use Carbon\Carbon;
use Auth;

class UploadNilaiRaporSemester implements ToCollection, WithHeadingRow
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
		// $sekolah = Sekolah::first();
		$rapor = Rapor::with('kelas')->find($rows[0]['id']);
		$list_komponen = KomponenJenisRapor::where('id_jenis_rapor', $rapor->kelas->id_jenis_rapor)->get();
		$nilais = NilaiRapor::join('siswa', 'siswa.id_siswa', '=', 'nilai_rapor.id_siswa')
			->join('komponen_jenis_rapor', 'komponen_jenis_rapor.id_komponen_jenis_rapor', '=', 'nilai_rapor.id_komponen_jenis_rapor')
			->where('nilai_rapor.id_rapor', $rows[0]['id'])
			->get();

		// dd($rows);
		foreach ($rows as $row) {
			foreach ($list_komponen as $komponen) {
				if (isset($row[str_replace([".", " "], ["", "_"], strtolower($komponen->nm_komponen_jenis_rapor))]) && is_numeric($row[str_replace([".", " "], ["", "_"], strtolower($komponen->nm_komponen_jenis_rapor))])) {
					$nis = str_replace("'", "", $row['nis']);

					$nilai = $nilais->where('nis_siswa', $nis)->where('nm_komponen_jenis_rapor', $komponen->nm_komponen_jenis_rapor)->first();
					if ($nilai) {

						$nilai->nilai =   $row[str_replace([".", " "], ["", "_"], strtolower($komponen->nm_komponen_jenis_rapor))];
						// $nilai->keterangan = $row[str_replace([".", " "], ["", "_"], strtolower('keterangan_' . $komponen->nm_komponen_jenis_rapor))];
						// $nilai->keterangan2 = $row[str_replace([".", " "], ["", "_"], strtolower('keterangan2_' . $komponen->nm_komponen_jenis_rapor))];
						$nilai->updated_by             = $id_pengguna;
						$nilai->updated_at           = $now;
						$nilai->save();
					}
				}
			}
		}
	}


	// $data = [
	//     'email_pengguna' => $row['email'],
	// ];
	// Siswa::create($data);


	// DB::table('pegawai')->where('pegawai_id',$request->id)->update([
	// 	'pegawai_nama' => $request->nama,
	// 	'pegawai_jabatan' => $request->jabatan,
	// 	'pegawai_umur' => $request->umur,
	// 	'pegawai_alamat' => $request->alamat
	// ]);

	// {
	//     
	//     return new Siswa([
	//         



	//     ]);
	// }
}
