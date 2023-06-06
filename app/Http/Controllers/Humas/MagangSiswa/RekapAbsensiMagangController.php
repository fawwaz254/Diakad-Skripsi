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

        $list_pengambilan_magang = PengambilanMagang::with(['siswa.pengguna', 'siswa.kelas', 'rekanan', 'presensiMagangSiswa.presensiMagang' => function ($query) use ($date) {
            $query->whereDate('tanggal', '=', $date);
        }])->when($id_rekanan != '0', function ($q) use ($id_rekanan) {
            $q->where('id_rekanan_magang', $id_rekanan);
        })->when($id_periode != '0', function ($q) use ($id_periode) {
            $q->where('id_periode_magang', $id_periode);
        })->get()->sortBy('siswa.nis_siswa');

        $pembimbing_magang = PembimbingMagang::when($id_rekanan != '0', function ($q) use ($id_rekanan) {
            $q->where('id_rekanan_magang', $id_rekanan);
        })->when($id_periode != '0', function ($q) use ($id_periode) {
            $q->where('id_periode_magang', $id_periode);
        })->get()->pluck('id_pembimbing_magang');

        $presensi_magang_siswa = PresensiMagangSiswa::whereHas('presensiMagang', function ($query) use ($pembimbing_magang) {
            $query->whereIn('id_pembimbing_magang', $pembimbing_magang);
        })->get();

        return view('humas/magang-siswa/rekap-absensi-magang/view-detail-rekap-absensi-magang', compact('auth_data', 'data_periode_magang', 'data_rekanan_magang', 'id_rekanan', 'id_periode', 'list_pengambilan_magang', 'date', 'presensi_magang_siswa'));
    }
}
