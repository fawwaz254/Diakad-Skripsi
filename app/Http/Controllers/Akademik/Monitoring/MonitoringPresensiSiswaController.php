<?php

namespace App\Http\Controllers\Akademik\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Bulan;
use Carbon\CarbonPeriod;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\PresensiMp;
use App\Models\PresensiMpSiswa;
use App\Models\Siswa;

class MonitoringPresensiSiswaController extends Controller
{
    public function viewMonitoringPresensiSiswa(Request $request, $id_kelas = null, $id_bulan = null, $tahun = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $now = Carbon::today();
        if (empty($id_bulan)) {
            $id_bulan = $now->month;
        }

        if (empty($tahun)) {
            $tahun = $now->year;
        }

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();


        $data_kelas = Kelas::where('is_aktif', '1')->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();

        return view('akademik/monitoring/view-monitoring-presensi-siswa', compact('auth_data', 'data_bulan', 'bulan', 'tahun', 'data_kelas', 'id_kelas'));
    }

    public function viewDetailMonitoringPresensiSiswa(Request $request, $id_kelas = null, $id_bulan = null, $tahun = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

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
        $data_bulan = Bulan::orderBy('id_bulan', 'ASC')->get();

        $data_siswa = Siswa::where('id_kelas', $id_kelas)->with('pengguna')->get();
        $data_kelas = Kelas::where('is_aktif', '1')->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();

        // with('presensi_mp_siswa')
        $data_presensi = PresensiMp::whereHas('kelas_mp', function ($q) use ($id_kelas) {
            $q->where('id_kelas', $id_kelas);
        })
            ->whereMonth('tgl_presensi', $id_bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->get();

        $data_array_presensi = [];
        foreach ($data_presensi as $presensi) {
            foreach ($presensi->presensi_mp_siswa as $presensi_mp_siswa) {
                if (isset($data_array_presensi[$presensi_mp_siswa->id_siswa . $presensi->tgl_presensi . $presensi_mp_siswa->kehadiran])) {
                    $data_array_presensi[$presensi_mp_siswa->id_siswa . $presensi->tgl_presensi . $presensi_mp_siswa->kehadiran]++;
                } else {
                    $data_array_presensi[$presensi_mp_siswa->id_siswa . $presensi->tgl_presensi . $presensi_mp_siswa->kehadiran] = 1;
                }
            }
        }

        return view('akademik/monitoring/view-monitoring-presensi-siswa-detail', compact('auth_data', 'dates', 'data_bulan', 'bulan', 'tahun', 'data_siswa', 'data_kelas', 'data_array_presensi', 'id_kelas'));
    }
}
