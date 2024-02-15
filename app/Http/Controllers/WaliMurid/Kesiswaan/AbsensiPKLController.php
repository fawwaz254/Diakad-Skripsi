<?php

namespace App\Http\Controllers\WaliMurid\Kesiswaan;

use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibMagangSiswa;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\PembimbingMagang;
use App\Models\PengambilanMagang;
use App\Models\PresensiMagang;
use App\Models\PresensiMagangSiswa;
use App\Models\Semester;
use App\Models\Siswa;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiPKLController extends Controller
{
    protected $modul_url = 'kesiswaan';
    protected $menu_url = 'absensi-ekskul';

	public function viewAbsensiMagang(Request $request){

		$input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;
        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        

        // $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);
        $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data);
        
        $semester_aktif =  Semester::where('is_aktif_semester', '1')->first();

        $date = Carbon::now()->format('Y-m-d');


        return view('wali-murid/kesiswaan/absensi-magang/view-rekap-absensi-magang',compact('auth_data','data_periode_magang','data_rekanan_magang','semester_aktif','data_semester','date'));

	}

    public function viewDetailRekapAbsensiMagang(Request $request, $id_rekanan, $id_periode, $date)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);
        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);
        $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data);

        $list_pengambilan_magang = PengambilanMagang::with(['siswa.pengguna', 'siswa.kelas', 'rekanan', 'presensiMagangSiswa.presensiMagang' => function ($query) use ($date) {
            $query->whereDate('tanggal', '=', $date);
        }])->when($id_rekanan != '0', function ($q) use ($id_rekanan) {
            $q->where('id_rekanan_magang', $id_rekanan);
        })->when($id_periode != '0', function ($q) use ($id_periode) {
            $q->where('id_periode_magang', $id_periode);
        })->where('id_siswa',$data_anak_murid_aktif->id_siswa)->get()->sortBy('siswa.nis_siswa');


        $pembimbing_magang = PembimbingMagang::when($id_rekanan != '0', function ($q) use ($id_rekanan) {
            $q->where('id_rekanan_magang', $id_rekanan);
        })->when($id_periode != '0', function ($q) use ($id_periode) {
            $q->where('id_periode_magang', $id_periode);
        })->get()->pluck('id_pembimbing_magang');

        $presensi_magang_siswa = PresensiMagangSiswa::whereHas('presensiMagang', function ($query) use ($pembimbing_magang) {
            $query->whereIn('id_pembimbing_magang', $pembimbing_magang);
        })->get();

        return view('wali-murid/kesiswaan/absensi-magang/view-detail-rekap-absensi-magang', compact('auth_data', 'data_periode_magang', 'data_rekanan_magang', 'id_rekanan', 'id_periode', 'list_pengambilan_magang', 'date', 'presensi_magang_siswa'));
    }


    public function printRekapPresensiMagang(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $pengambilan_magang = PengambilanMagang::where('id_siswa', $id_siswa)->first();
        $pembimbing_magang = PembimbingMagang::with('rekanan', 'periode', 'pengguna')->where('id_periode_magang', $pengambilan_magang->id_periode_magang)->where('id_rekanan_magang', $pengambilan_magang->id_rekanan_magang)->first();

        if(!$pembimbing_magang){
            echo 'Hubungi Pihak Humas Untuk Menambah Pembimbing Magang';
            exit();
        }
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
