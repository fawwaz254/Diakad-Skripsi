<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Libraries\Pendidikan\LibMagangSiswa;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\PembimbingMagang;
use App\Models\PengambilanMagang;
use App\Models\PresensiMagang;
use App\Models\PresensiMagangSiswa;
use App\Models\Semester;
use App\Models\Siswa;
use Carbon\Carbon;
use Auth;
use DB;
use Session;
use Validator;
use Excel;

class RekapAbsensiMagangController extends Controller
{

    public function viewRekapAbsensiMagang(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);
        $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data);
        $semester_aktif =  Semester::where('is_aktif_semester', '1')->first();
        $date = Carbon::now()->format('Y-m-d');

        return view('humas/magang-siswa/rekap-absensi-magang/view-rekap-absensi-magang', compact('auth_data', 'data_periode_magang', 'data_rekanan_magang', 'semester_aktif', 'date'));
    }
    //

    public function viewDetailRekapAbsensiMagang(Request $request, $id_rekanan, $id_periode, $date)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);
        $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data);

        $list_pengambilan_magang = PengambilanMagang::with(['siswa.pengguna', 'siswa.kelas', 'rekanan', 'presensiMagangSiswa.presensiMagang.pembimbingMagang.pengguna',  'presensiMagangSiswa.presensiMagang' => function ($query) use ($date) {
            // dd($query);
            $query->whereDate('tanggal', '=', $date);
        }, 'createdBy'])->when($id_rekanan != '0', function ($q) use ($id_rekanan) {
            $q->where('id_rekanan_magang', $id_rekanan);
        })->when($id_periode != '0', function ($q) use ($id_periode) {
            $q->where('id_periode_magang', $id_periode);
        })->get()->sortBy('siswa.nis_siswa');

        $pembimbing_magang = PembimbingMagang::when($id_rekanan != '0', function ($q) use ($id_rekanan) {
            $q->where('id_rekanan_magang', $id_rekanan);
        })->when($id_periode != '0', function ($q) use ($id_periode) {
            $q->where('id_periode_magang', $id_periode);
        })->with('pengguna')->get()->pluck('id_pembimbing_magang');

        $presensi_magang_siswa = PresensiMagangSiswa::whereHas('presensiMagang', function ($query) use ($pembimbing_magang) {
            $query->whereIn('id_pembimbing_magang', $pembimbing_magang);
        })->with("presensiMagang.pembimbingMagang.pengguna")->get();



        return view('humas/magang-siswa/rekap-absensi-magang/view-detail-rekap-absensi-magang', compact('auth_data', 'data_periode_magang', 'data_rekanan_magang', 'id_rekanan', 'id_periode', 'list_pengambilan_magang', 'date', 'presensi_magang_siswa'));
    }

    public function printRekapPresensiMagang(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $pengambilan_magang = PengambilanMagang::where('id_siswa', $id_siswa)->first();
        $pembimbing_magang = PembimbingMagang::with('rekanan', 'periode', 'pengguna')->where('id_periode_magang', $pengambilan_magang->id_periode_magang)->where('id_rekanan_magang', $pengambilan_magang->id_rekanan_magang)->first();

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
