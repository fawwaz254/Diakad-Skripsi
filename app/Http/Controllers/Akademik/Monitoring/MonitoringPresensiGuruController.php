<?php

namespace App\Http\Controllers\Akademik\Monitoring;

use Carbon\Carbon;
use App\Models\Bulan;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\PresensiMp;

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

        return view('akademik/monitoring/view-monitoring-presensi-guru', compact('auth_data', 'dates', 'data_bulan', 'bulan', 'tahun', 'data_guru', 'data_presensi'));
    }

    public function viewDetailPresensiGuru(Request $request, $day,  $id_bulan, $tahun, $id_pengguna)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_presensi = PresensiMp::select('nm_pengguna', 'pengguna.id_pengguna', 'nip_guru', 'pertemuan_ke', 'uraian_materi', 'waktu_mulai', 'waktu_selesai', 'tgl_presensi', 'nm_kelas_mp')
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
            ->get();

        return view('akademik/monitoring/view-detail-presensi-guru', compact('data_presensi', 'id_bulan', 'tahun'));
    }
}
