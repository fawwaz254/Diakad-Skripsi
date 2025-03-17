<?php

namespace App\Http\Controllers\PembimbingMagang\PresensiMagang;

use App\Http\Controllers\Controller;
use App\Models\PembimbingMagang;
use App\Models\PengambilanMagang;
use App\Models\PresensiMagang;
use App\Models\PresensiMagangSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RekapPresensiMagangController extends Controller
{
    public function viewRekapAbsensiMagang(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $pembimbing_magang = PembimbingMagang::with('rekanan', 'periode')->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $presensi_magang = PresensiMagang::with('presensiMagangSiswa')->where('id_pembimbing_magang', $pembimbing_magang->id_pembimbing_magang)->orderBy('tanggal', 'asc')->get();

        $data_siswa = PengambilanMagang::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna')->where('id_periode_magang', $pembimbing_magang->id_periode_magang)->where('id_rekanan_magang', $pembimbing_magang->id_rekanan_magang)->get();

        return view(
            'pembimbing-magang/presensi-magang/rekap-presensi-magang/view-rekap-presensi-magang',
            compact('auth_data', 'presensi_magang', 'data_siswa', 'pembimbing_magang')
        );
    }

    public function printAllRekapPresensiMagang(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $pembimbing_magang = PembimbingMagang::with('rekanan', 'periode', 'pengguna')->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $data_siswa = PengambilanMagang::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna')->where('id_periode_magang', $pembimbing_magang->id_periode_magang)->where('id_rekanan_magang', $pembimbing_magang->id_rekanan_magang)->get();
        $presensi_magang = PresensiMagang::with('presensiMagangSiswa')->where('id_pembimbing_magang', $pembimbing_magang->id_pembimbing_magang)->orderBy('tanggal', 'asc')->get();

        return view(
            'pembimbing-magang/presensi-magang/rekap-presensi-magang/print-rekap-presensi-magang',
            compact('auth_data', 'pembimbing_magang', 'data_siswa', 'presensi_magang')
        );
    }

    public function printRekapPresensiMagang(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $pembimbing_magang = PembimbingMagang::with('rekanan', 'periode', 'pengguna')->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        // $presensi_magang = PresensiMagang::with('presensiMagangSiswa')->where('id_pembimbing_magang', $pembimbing_magang->id_pembimbing_magang)->orderBy('tanggal', 'asc')->get();
        $data_siswa = Siswa::with('kelas', 'pengguna')->where('id_siswa', $id_siswa)->first();

        $data_presensi = PresensiMagang::with('presensiMagangSiswa.siswa.kelas', 'presensiMagangSiswa.siswa.pengguna')->whereHas('presensiMagangSiswa', function ($query) use ($id_siswa) {
            $query->where('id_siswa', '=', $id_siswa);
        })->get();

        $data_presensi = PresensiMagangSiswa::where('id_siswa', $id_siswa)->with('presensiMagang')->orderBy('created_at', 'asc')->get();

        $hadir = 0;
        $izin = 0;
        $alpha = 0;
        $sakit = 0;

        foreach ($data_presensi as  $presensi) {
            // $rekap_absen[$presensi_ekskul->pertemuan_ke]['total_siswa'] = $presensi_ekskul->presensi_ekskul_peserta->count();
            // $rekap_absen[$presensi_ekskul->pertemuan_ke]['total_hadir'] = $presensi_ekskul->presensi_ekskul_peserta->where('kehadiran', 1)->count();

            if ($presensi->kehadiran == '1') {
                $hadir = $hadir + 1;
            } elseif ($presensi->kehadiran == '2') {
                $sakit = $sakit + 1;
            } elseif ($presensi->kehadiran == '3') {
                $izin = $izin + 1;
            } elseif ($presensi->kehadiran == '0') {
                $alpha = $alpha + 1;
            }
        }
        return view(
            'pembimbing-magang/presensi-magang/rekap-presensi-magang/print-detail-rekap-presensi-magang',
            compact('auth_data', 'pembimbing_magang',  'data_siswa', 'hadir', 'sakit', 'izin', 'alpha', 'data_presensi')
        );
    }
}
