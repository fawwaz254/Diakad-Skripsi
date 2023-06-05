<?php

namespace App\Http\Controllers\PembimbingMagang\PresensiMagang;

use App\Http\Controllers\Controller;
use App\Models\PembimbingMagang;
use App\Models\PengambilanMagang;
use App\Models\PresensiMagang;
use Illuminate\Http\Request;

class RekapPresensiMagangController extends Controller
{
    public function viewRekapAbsensiMagang(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;


        // $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $pembimbing_magang = PembimbingMagang::with('rekanan', 'periode')->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $presensi_magang = PresensiMagang::with('presensiMagangSiswa')->where('id_pembimbing_magang', $pembimbing_magang->id_pembimbing_magang)->orderBy('tanggal', 'asc')->get();

        $data_siswa = PengambilanMagang::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna')->where('id_periode_magang', $pembimbing_magang->id_periode_magang)->where('id_rekanan_magang', $pembimbing_magang->id_rekanan_magang)->get();

        return view(
            'pembimbing-magang/presensi-magang/rekap-presensi-magang/view-rekap-presensi-magang',
            compact('auth_data', 'presensi_magang', 'data_siswa', 'pembimbing_magang')
        );
    }

    // public function viewDetailRekapAbsensiEkskul(Request $request, $id_semester, $id_ekskul)
    // {
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;
    //     $auth_data->modul_url = $this->modul_url;
    //     $auth_data->menu_url = $this->menu_url;

    //     $semester_aktif = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

    //     $data_ekskul = Ekskul::find($id_ekskul);

    //     $data_siswa = PengambilanEkskul::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna', 'kelas')->where('id_semester', $id_semester)->where('id_ekskul', $id_ekskul)->get();

    //     $data_presensi = PresensiEkskul::with('presensi_ekskul_peserta')
    //                                 ->where('id_ekskul', $id_ekskul)
    //                                 ->where('id_semester', $id_semester)
    //                                 ->orderBy('pertemuan_ke', 'asc')
    //                                 ->get();

    //     return view(
    //         'pelatih-ekskul/absensi-ekskul/rekap-absensi-ekskul/view-detail-rekap-absensi-ekskul',
    //         compact('auth_data', 'semester_aktif', 'data_ekskul', 'data_siswa', 'data_presensi', 'id_semester', 'id_ekskul')
    //     );
    // }
}
