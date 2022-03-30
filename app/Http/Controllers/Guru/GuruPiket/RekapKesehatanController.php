<?php

namespace App\Http\Controllers\Guru\GuruPiket;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Siswa;
use App\Models\WaliMurid;
use App\Models\Bulan;
use App\Models\Guru;
use App\Models\PresensiHarian;
use App\Models\PresensiHarianSiswa;
use App\Models\PengisianKegiatanHarian;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\LibGlobal;

use Auth;
use DB;
use Session;
use Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapKesehatanSiswa;

class RekapKesehatanController extends BaseController
{

    public function viewRekapKesehatan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('guru/guru-piket/rekap-kesehatan/view-rekap-kesehatan', compact('auth_data', 'data_kelas'));
    }

    public function viewDetailRekapKesehatan(Request $request, $id_kelas = '-', $id_bulan = null, $tahun = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::today();
        if (empty($id_bulan)) {
            $id_bulan = $now->month;
        }

        if (empty($tahun)) {
            $tahun = $now->year;
        }

        $start_month = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $id_kelas, null, 'only-aktif');

        $data_pengisian = PengisianKegiatanHarian::whereMonth('tgl_pengisian', $id_bulan)->whereYear('tgl_pengisian', $tahun)->whereIn('id_pengguna_pengisi', $data_siswa->pluck('id_pengguna'))->get();

        return view('guru/guru-piket/rekap-kesehatan/view-rekap-kesehatan-siswa', compact('auth_data', 'dates', 'data_bulan', 'bulan', 'data_kelas', 'data_siswa', 'data_pengisian', 'tahun'));
    }

    public function downloadDetailRekapKesehatan(Request $request, $id_kelas = '-', $id_bulan = null, $tahun = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::today();
        if (empty($id_bulan)) {
            $id_bulan = $now->month;
        }

        if (empty($tahun)) {
            $tahun = $now->year;
        }

        $start_month = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $id_kelas, null, 'only-aktif');

        $data_pengisian = PengisianKegiatanHarian::whereMonth('tgl_pengisian', $id_bulan)->whereYear('tgl_pengisian', $tahun)->whereIn('id_pengguna_pengisi', $data_siswa->pluck('id_pengguna'))->get();

        return Excel::download(new RekapKesehatanSiswa($auth_data, $dates, $data_bulan, $bulan, $data_kelas, $data_siswa, $data_pengisian), 'Download Data Rekap Kesehatan Kelas ' . $data_kelas->nm_kelas . ' Bulan ' . $bulan->nm_bulan . '.xlsx');
    }
}
