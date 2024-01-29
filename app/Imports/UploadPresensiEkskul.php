<?php

namespace App\Imports;

use App\Models\Ekskul;
use App\Models\PengambilanEkskul;
use App\Models\Pengguna;
use App\Models\PresensiEkskul;
use App\Models\PresensiEkskulPeserta;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

class UploadPresensiEkskul implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        set_time_limit(-1);

        $id_pengguna = Auth::id();
        $sekolah = Sekolah::first();
        $data_presensi_ekskul = array();
        $data_presensi_ekskul_peserta = array();

        DB::beginTransaction();
        try {
            $id_semester = Semester::where('kode_semester', $rows[0]['semester'])->first()->id_semester;
            $id_ekskul = Ekskul::where('nm_ekskul', $rows[0]['ekskul'])->first()->id_ekskul;
            $data_siswa = PengambilanEkskul::with('siswa.pengguna')->where('id_semester', $id_semester)->where('id_ekskul', $id_ekskul)->get();

            foreach ($rows as  $row) {
                $now = Carbon::now();
                $id_presensi_ekskul = $sekolah->prefix . strtotime($now) . uniqid();

                $hadir = 0;
                $kehadiran = 0;
                foreach ($data_siswa as $pengambilan_ekskul) {
                    if ($row[str_replace(' ', '_', strtolower($pengambilan_ekskul->siswa->pengguna->nm_pengguna))] == 'H') {
                        $kehadiran = 1;
                        $hadir++;
                    } else if ($row[str_replace(' ', '_', strtolower($pengambilan_ekskul->siswa->pengguna->nm_pengguna))] == 'S') {
                        $kehadiran = 2;
                    } elseif ($row[str_replace(' ', '_', strtolower($pengambilan_ekskul->siswa->pengguna->nm_pengguna))] == 'I') {
                        $kehadiran = 3;
                    } elseif ($row[str_replace(' ', '_', strtolower($pengambilan_ekskul->siswa->pengguna->nm_pengguna))] == 'A') {
                        $kehadiran = 4;
                    } else {
                        $kehadiran = 4;
                    }
                    $now = Carbon::now();
                    $id_presensi_ekskul_peserta = $sekolah->prefix . strtotime($now) . uniqid();
                    $data_presensi_ekskul_peserta[] = [
                        'id_presensi_ekskul_peserta' => $id_presensi_ekskul_peserta, 'id_presensi_ekskul' => $id_presensi_ekskul, 'id_siswa' => $pengambilan_ekskul->id_siswa, 'id_kelas' => $pengambilan_ekskul->siswa->id_kelas, 'kehadiran' => $kehadiran, 'alasan' => '-', 'created_at' => $now->format('Y-m-d H:i:s'), 'created_by' => $id_pengguna
                    ];
                }
                $presentase = ($hadir / $data_siswa->count()) * 100;

                $data_presensi_ekskul[] = [
                    'id_presensi_ekskul' => $id_presensi_ekskul, 'id_ekskul' => $id_ekskul, 'id_semester' => $id_semester, 'pertemuan_ke' => $row['pertemuan_ke'],
                    'materi_ekskul' => $row['materi'],  'waktu_mulai' => $row['jam_mulai'], 'waktu_selesai' => $row['jam_selesai'], 'tgl_entry' => Carbon::parse($row['tanggal']), 'pertemuan_ke' => $row['pertemuan_ke'],
                    'persentase_presensi_ekskul' =>  $presentase, 'created_at' => $now->format('Y-m-d H:i:s'), 'created_by' => $id_pengguna
                ];
            }

            PresensiEkskul::insert($data_presensi_ekskul);
            PresensiEkskulPeserta::insert($data_presensi_ekskul_peserta);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \RuntimeException('Gagal Insert');
        }
    }
}
