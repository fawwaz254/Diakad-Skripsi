<?php

namespace App\Http\Controllers\Guru\Presensi;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\PresensiMp;
use App\Models\PresensiMpSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use Auth;
use DB;
use Session;
use Validator;

class PresensiQrCodeController extends BaseController
{
    public function viewKBMAbsensiSiswaBarcode(Request $request, $id_jadwal_kelas_mp, $pertemuan_ke)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data = PresensiMp::where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)
            ->where('pertemuan_ke', $pertemuan_ke)->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $id_jadwal_kelas_mp);

        if (empty($data_kelas)) {
            return redirect("/guru#presensi/absensi-siswa");
        }

        $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $pertemuan_ke)->first();

        if ($presensi_mp_aktif) {
            return redirect("/guru#presensi/absensi-siswa/view-kbm/$id_jadwal_kelas_mp/$pertemuan_ke");
        }

        $list_data = LibSiswa::fetchDataSiswaKelasMpTanpaPloting($auth_data, $id_jadwal_kelas_mp, $pertemuan_ke);

        return view('guru/presensi/absensi-qr/view-absensi-qr-code', compact('auth_data', 'list_data', 'semester_aktif', 'data_kelas', 'pertemuan_ke', 'id_jadwal_kelas_mp', 'data'));
    }

    public function actionKBMAbsensiSiswaBarcode(Request $request)
    {
        $input = (object) $request->input();
        $presensi_mp = PresensiMp::where('id_jadwal_kelas_mp', '=', $input->id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $input->pertemuan_ke)->first();

        // dd($presensi_mp);
        DB::beginTransaction();
        try {
            if ($presensi_mp) {
                return [
                    'status' => 203,
                    'message' => 'Absensi KBM Gagal Karna Data Sudah Ada'
                ];
            } else {
                $now = Carbon::now();
                $id_presensi_mp = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $auth_data = $input->auth_data;
                $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
                $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $input->id_jadwal_kelas_mp);

                $presensi_mp                        = new PresensiMp;
                $presensi_mp->id_presensi_mp        = $id_presensi_mp;
                $presensi_mp->id_kelas_mp           = $data_kelas->id_kelas_mp;
                $presensi_mp->id_jadwal_kelas_mp    = $input->id_jadwal_kelas_mp;
                $presensi_mp->pertemuan_ke          = $input->pertemuan_ke;
                $presensi_mp->tgl_entry             = $now;
                $presensi_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                $presensi_mp->uraian_materi      = $input->uraian_materi;

                $presensi_mp->waktu_mulai        = Carbon::parse($data_kelas->jam_mulai . ':' .   $data_kelas->menit_mulai)->format('H:i');
                $presensi_mp->waktu_selesai      = Carbon::parse($data_kelas->jam_selesai . ':' .   $data_kelas->menit_selesai)->format('H:i');
                $presensi_mp->tgl_presensi       = $now->format('Y-m-d');
                $presensi_mp->save();
                $list_data = LibSiswa::fetchDataSiswaKelasMpTanpaPloting($auth_data, $input->id_jadwal_kelas_mp, $input->pertemuan_ke);
                $arraySiswaPresensi = explode(",", $input->siswa_presensi);
                $arraySiswaSakit = explode(",", $input->siswa_sakit);
                $arraySiswaIzin = explode(",", $input->siswa_izin);

                foreach ($list_data as $data) {
                    $id_presensi_mp_siswa = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $presensi_mp_siswa                            = new PresensiMpSiswa;
                    $presensi_mp_siswa->id_presensi_mp_siswa      = $id_presensi_mp_siswa;
                    $presensi_mp_siswa->id_presensi_mp            = $presensi_mp->id_presensi_mp;
                    $presensi_mp_siswa->created_by                = $input->auth_data->pengguna->id_pengguna;
                    $presensi_mp_siswa->id_siswa                  = $data->id_siswa;

                    if (count($arraySiswaPresensi) > 0 && in_array($data->nis_siswa, $arraySiswaPresensi)) {
                        $presensi_mp_siswa->kehadiran = '1';
                    } elseif (count($arraySiswaSakit) > 0 && in_array($data->nis_siswa, $arraySiswaSakit)) {
                        $presensi_mp_siswa->kehadiran = '2';
                    } elseif (count($arraySiswaIzin) > 0 && in_array($data->nis_siswa, $arraySiswaIzin)) {
                        $presensi_mp_siswa->kehadiran = '3';
                    } else {
                        $presensi_mp_siswa->kehadiran = '4';
                    }

                    $presensi_mp_siswa->save();
                }
            }

            DB::commit();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                // 'path' => 'presensi/absensi-siswa',
                'message' => 'Save Absensi KBM Siswa Successfully'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            return [
                'status' => 203, // GAGAL
                'message' => 'Absensi KBM Gagal!'
            ];
        }
    }
}
