<?php

namespace App\Imports;

// use Carbon\Carbon;
// use App\Models\Sekolah;

use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use App\Models\Pengguna;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use Carbon\Carbon;
use Auth;

class UploadRaporSisipanSAS implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {

        set_time_limit(-1);
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $id_pengguna = Auth::id();
        $sekolah = Sekolah::first();
        $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->get();

        $nilais = NilaiRaporSisipan::join('siswa', 'siswa.id_siswa', '=', 'nilai_rapor_sisipan.id_siswa')
            ->join('komponen_nilai_rapor_sisipan', 'komponen_nilai_rapor_sisipan.id_komponen_nilai', '=', 'nilai_rapor_sisipan.id_komponen_nilai')
            ->where('nilai_rapor_sisipan.id_rapor_sisipan', $rows[0]['id'])
            ->get();

        $siswas = Siswa::get();
        foreach ($rows as $row) {
            foreach ($list_komponen as $komponen) {
                if (isset($row[str_replace([".", " "], ["", "_"], strtolower($komponen->nm_nilai))]) && is_numeric($row[str_replace([".", " "], ["", "_"], strtolower($komponen->nm_nilai))])) {
                    $nis = str_replace("'", "", $row['nis']);
                    $nilai = $nilais->where('nis_siswa', $nis)->where('nm_nilai', $komponen->nm_nilai)->first();
                    if ($nilai) {
                        $nilai->nilai =   $row[str_replace([".", " "], ["", "_"], strtolower($komponen->nm_nilai))];
                        $nilai->updated_by             = $id_pengguna;
                        $nilai->updated_at           = $now;
                        $nilai->save();
                    } else {
                        $id_siswa  = $siswas->where('nis_siswa', $nis)->first()->nis_siswa;
                        if ($id_siswa) {
                            $nilai = new NilaiRaporSisipan;
                            $nilai->id_nilai_rapor_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                            $nilai->rapor_sisipan = $rows[0]['id'];
                            $nilai->id_komponen_nilai = $komponen->id_komponen_nilai;
                            $nilai->id_siswa = $id_siswa;
                            $nilai->nilai =   $row[str_replace([".", " "], ["", "_"], strtolower($komponen->nm_nilai))];
                            $nilai->updated_by  = 'new data';
                            $nilai->save();
                        }
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
