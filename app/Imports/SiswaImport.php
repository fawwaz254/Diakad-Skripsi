<?php

namespace App\Imports;

// use Carbon\Carbon;
// use App\Models\Sekolah;
use App\Models\Pengguna;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;

class SiswaImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $siswa = Siswa::where('nis_siswa', $row['nis'])->first();

            // $pengguna = Pengguna::where('id_pengguna', $siswa->id_pengguna)->first();
            // dd($pengguna);
            Pengguna::where('id_pengguna', $siswa->id_pengguna)->update([
                'email_afiliasi' => $row['email'],
            ]);
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
