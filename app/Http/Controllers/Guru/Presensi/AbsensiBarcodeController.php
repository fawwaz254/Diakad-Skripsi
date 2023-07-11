<?php

namespace App\Http\Controllers\Guru\Presensi;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\Guru;
use App\Models\JadwalKelasMp;
use App\Models\PresensiMp;
use App\Models\Setting;

class AbsensiBarcodeController extends BaseController
{
    public function viewAbsensiBarcode(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_jadwal_hari = $now->format('N');

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kbm = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        $data_uts = LibGuru::fetchDataJadwalUTS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, 0);

        $data_uas = LibGuru::fetchDataJadwalUAS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, 0);

        $setting =  Setting::where('key_setting', 'is_presensi_one_day')->pluck('value')->first();

        if ($setting == '1') {
            $grup_kbm_perhari = $data_kbm->where('id_jadwal_hari', $id_jadwal_hari)->groupBy('nm_jadwal_hari');
        } else {
            $grup_kbm_perhari = $data_kbm->groupBy('nm_jadwal_hari');
        }

        $jam_sekarang = $now->format('H:i');
        // $jam_sekarang =   '10:00'; //untuk uji waktu sebelum di push harus di hapus;
        $cek =  Carbon::parse($jam_sekarang);
        foreach ($grup_kbm_perhari as $g) {
            foreach ($g as $a) {
                $start =  Carbon::parse($a->jam_mulai . ':' . $a->menit_mulai);
                $end =  Carbon::parse($a->jam_selesai . ':' . $a->menit_selesai);
                if ($cek->between($start, $end)) {
                    $jam_mulai = $a->jam_mulai;
                    $menit_mulai = $a->menit_mulai;
                    $jam_selesai = $a->jam_selesai;
                    $menit_selesai = $a->menit_selesai;
                    $id_guru = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first()->id_guru;

                    $data_jadwal_kelas_mp = JadwalKelasMp::with('jadwal_jam_mulai', 'jadwal_jam_selesai', 'kelas_mp.pengampu_mp_utama')->where('id_jadwal_hari', $id_jadwal_hari)
                        ->whereHas('jadwal_jam_mulai', function ($query) use ($jam_mulai, $menit_mulai) {
                            $query->where('jam_mulai', $jam_mulai)->where('menit_mulai', $menit_mulai);
                        })
                        ->whereHas('jadwal_jam_selesai', function ($query) use ($jam_selesai, $menit_selesai) {
                            $query->where('jam_selesai', $jam_selesai)->where('menit_selesai', $menit_selesai);
                        })->whereHas('kelas_mp.semester', function ($query) {
                            $query->where('is_aktif_semester', '1');
                        })
                        ->whereHas('kelas_mp.pengampu_mp_utama', function ($query) use ($id_guru) {
                            $query->where('id_guru', $id_guru);
                        })
                        ->first();

                    $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $data_jadwal_kelas_mp->id_jadwal_kelas_mp);

                    if (empty($data_kelas)) {
                        return view('guru/presensi/absensi-siswa/view-absensi-siswa', compact('auth_data', 'semester_aktif', 'data_uts', 'data_uas', 'grup_kbm_perhari'));
                    }

                    $start = $start->format('H:i');
                    $end = $end->format('H:i');
                    $id_jadwal_kelas_mp = $data_jadwal_kelas_mp->id_jadwal_kelas_mp;
                    $data_pertemuan = array();
                    $data_presensiMp = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->get();

                    for ($i = 1; $i < 26; $i++) {
                        $presensiMp = $data_presensiMp->firstWhere('pertemuan_ke', $i);
                        if ($presensiMp) {
                            $pertemuan = array(
                                'text' => $i . " (Sudah)",
                                'value' => $i,
                                'status' => true,
                            );
                        } else {
                            $pertemuan = array(
                                'text' => $i,
                                'value' => $i,
                                'status' => false,
                            );
                        }

                        $data_pertemuan[] = $pertemuan;
                    }

                    return view('guru/presensi/absensi-barcode/view-kbm-absensi-barcode', compact('auth_data', 'semester_aktif', 'data_kelas', 'id_jadwal_kelas_mp', 'start', 'end', 'data_pertemuan'));
                }
            }
        }

        return view('guru/presensi/absensi-siswa/view-absensi-siswa', compact('auth_data', 'semester_aktif', 'data_uts', 'data_uas', 'grup_kbm_perhari'));
    }
}
