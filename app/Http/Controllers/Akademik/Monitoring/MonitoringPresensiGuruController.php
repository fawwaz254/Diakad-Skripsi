<?php

namespace App\Http\Controllers\Akademik\Monitoring;

use Carbon\Carbon;
use App\Models\Bulan;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\Pengguna;
use App\Models\PresensiMp;
use App\Models\Semester;
use DB;

class MonitoringPresensiGuruController extends Controller
{
    public function viewMonitoringPresensiGuru(Request $request, $id_bulan = null, $tahun = null)
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

        $data_guru = LibGuru::fetchDataAllGuru($auth_data);

        $semester_aktif = Semester::where('is_aktif_semester', 1)->first();

        $data_jam_mengajar = DB::select("
            select g.id_pengguna, sum(jjs.jam_ke - jjm.jam_ke + 1) total_jam_seminggu
            from kelas_mp kmp
            join pengampu_mp pmp on pmp.id_kelas_mp = kmp.id_kelas_mp and pmp.deleted_at is null
            join guru g on g.id_guru = pmp.id_guru and g.deleted_at is null
            join jadwal_kelas_mp jkmp on jkmp.id_kelas_mp = kmp.id_kelas_mp and jkmp.deleted_at is null
            join jadwal_jam jjm on jjm.id_jadwal_jam = jkmp.id_jadwal_jam and jjm.deleted_at is null
            join jadwal_jam jjs on jjs.id_jadwal_jam = jkmp.id_jadwal_jam_selesai and jjs.deleted_at is null

            where kmp.deleted_at is null
            and kmp.id_semester = '$semester_aktif->id_semester'

            group by g.id_pengguna
        ");

        $data_jam_mengajar = collect($data_jam_mengajar);

        $data_presensi = PresensiMp::select('nm_pengguna', 'pengguna.id_pengguna', 'tgl_presensi')
            ->join('kelas_mp', 'kelas_mp.id_kelas_mp', 'presensi_mp.id_kelas_mp')
            ->join('pengampu_mp', 'pengampu_mp.id_kelas_mp', 'kelas_mp.id_kelas_mp')
            ->join('guru', 'guru.id_guru', 'pengampu_mp.id_guru')
            ->join('pengguna', 'pengguna.id_pengguna', 'guru.id_pengguna')
            ->join('status_pengguna', 'status_pengguna.id_status_pengguna', 'pengguna.id_status_pengguna')
            ->where('nm_status_pengguna', 'AKTIF')
            ->whereMonth('tgl_presensi', $id_bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->whereIn('pengguna.id_pengguna', $data_guru->pluck('id_pengguna'))
            ->get();

        return view('akademik/monitoring/view-monitoring-presensi-guru', compact('auth_data', 'dates', 'data_bulan', 'bulan', 'tahun', 'data_guru', 'data_presensi', 'data_jam_mengajar'));
    }


    public function actionDeletePresensiGuru(Request $request, $id_presensi_mp)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $p = PresensiMp::find($id_presensi_mp);
        if ($p) {
            $p->deleted_by = $auth_data->pengguna->id_pengguna;
            $p->save();
            $p->delete();
        }
        return $id_presensi_mp;
    }

    public function viewDetailPresensiGuru(Request $request, $day, $id_bulan, $tahun, $id_pengguna)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_presensi = PresensiMp::select('nm_pengguna', 'pengguna.id_pengguna', 'nip_guru', 'pertemuan_ke', 'uraian_materi', 'waktu_mulai', 'waktu_selesai', 'tgl_presensi', 'nm_kelas_mp', 'id_presensi_mp')
            ->join('kelas_mp', 'kelas_mp.id_kelas_mp', 'presensi_mp.id_kelas_mp')
            ->join('pengampu_mp', 'pengampu_mp.id_kelas_mp', 'kelas_mp.id_kelas_mp')
            ->join('guru', 'guru.id_guru', 'pengampu_mp.id_guru')
            ->join('pengguna', 'pengguna.id_pengguna', 'guru.id_pengguna')
            ->join('status_pengguna', 'status_pengguna.id_status_pengguna', 'pengguna.id_status_pengguna')
            ->where('pengguna.id_pengguna', $id_pengguna)
            ->where('nm_status_pengguna', 'AKTIF')
            ->whereDay('tgl_presensi', $day)
            ->whereMonth('tgl_presensi', $id_bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->join('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', 'kelas_mp.id_kelas_mp')
            ->where('jadwal_kelas_mp.id_jadwal_jam', '!=', '0')
            ->get();

        $data_presensi_kbmTanpaJadwal = PresensiMp::select('nm_pengguna', 'pengguna.id_pengguna', 'nip_guru', 'pertemuan_ke', 'uraian_materi', 'waktu_mulai', 'waktu_selesai', 'tgl_presensi', 'nm_kelas_mp', 'id_presensi_mp', )
            ->join('kelas_mp', 'kelas_mp.id_kelas_mp', 'presensi_mp.id_kelas_mp')
            ->join('pengampu_mp', 'pengampu_mp.id_kelas_mp', 'kelas_mp.id_kelas_mp')
            ->join('guru', 'guru.id_guru', 'pengampu_mp.id_guru')
            ->join('pengguna', 'pengguna.id_pengguna', 'guru.id_pengguna')
            ->join('status_pengguna', 'status_pengguna.id_status_pengguna', 'pengguna.id_status_pengguna')
            ->where('pengguna.id_pengguna', $id_pengguna)
            ->where('nm_status_pengguna', 'AKTIF')
            ->whereDay('tgl_presensi', $day)
            ->whereMonth('tgl_presensi', $id_bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->join('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', 'kelas_mp.id_kelas_mp')
            ->where('jadwal_kelas_mp.id_jadwal_jam', '0')
            ->get();
        // dd($data_presensi_kbmTanpaJadwal);
        return view('akademik/monitoring/view-detail-presensi-guru', compact('data_presensi', 'id_bulan', 'tahun', 'data_presensi_kbmTanpaJadwal'));
    }
    public function printViewMonitoringPresensiGuru(Request $request, $id_bulan, $tahun)
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
        $pengguna = Pengguna::find($auth_data->pengguna->id_pengguna);
        $bulan = Bulan::find($id_bulan);
        // $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_guru = LibGuru::fetchDataAllGuru($auth_data);

        $data_presensi = PresensiMp::select('nm_pengguna', 'pengguna.id_pengguna', 'tgl_presensi')
            ->join('kelas_mp', 'kelas_mp.id_kelas_mp', 'presensi_mp.id_kelas_mp')
            ->join('pengampu_mp', 'pengampu_mp.id_kelas_mp', 'kelas_mp.id_kelas_mp')
            ->join('guru', 'guru.id_guru', 'pengampu_mp.id_guru')
            ->join('pengguna', 'pengguna.id_pengguna', 'guru.id_pengguna')
            ->join('status_pengguna', 'status_pengguna.id_status_pengguna', 'pengguna.id_status_pengguna')
            ->where('nm_status_pengguna', 'AKTIF')
            ->whereMonth('tgl_presensi', $id_bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->whereIn('pengguna.id_pengguna', $data_guru->pluck('id_pengguna'))
            ->get();

        return view('akademik/monitoring/print-view-monitoring-presensi-guru', compact('auth_data', 'dates', 'bulan', 'tahun', 'data_guru', 'data_presensi', 'pengguna'));
    }
}
