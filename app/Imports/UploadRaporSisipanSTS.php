<?php

namespace App\Imports;

// use Carbon\Carbon;
// use App\Models\Sekolah;

use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use App\Models\Pengguna;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use Carbon\Carbon;
use Auth;

class UploadRaporSisipanSTS implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {

        set_time_limit(9800);
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $id_pengguna = Auth::id();
        $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->get();
        foreach ($rows as $row) {
            foreach ($list_komponen as $komponen) {
                // dd( str_replace(" ","_",strtolower($komponen->nm_nilai)));
                if ($row[str_replace(" ", "_", strtolower($komponen->nm_nilai))] ) {
                    $nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $row['id'])
                        ->with('siswa', 'komponen_nilai')
                        ->whereHas('siswa', function ($query) use ($row) {
                            $query->where('nis_siswa', $row['nis']);
                        })
                        ->whereHas('komponen_nilai', function ($query) use ($komponen) {
                            $query->where('nm_nilai', $komponen->nm_nilai);
                        })
                        ->first();
                        if(is_numeric($row[str_replace(" ", "_", strtolower($komponen->nm_nilai))])){
                            $nilai->nilai =   $row[str_replace(" ", "_", strtolower($komponen->nm_nilai))] ;
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
