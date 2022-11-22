<?php

namespace App\Http\Controllers\Siswa\Absensi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use App\Models\PresensiPengguna;
use App\Models\ManajemenHariLibur;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HistoriAbsensiSiswaController extends BaseController
{
    public function viewHistoriAbsensi(Request $request, $start_date = null, $end_date = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($start_date) || empty($end_date)) {
            $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $dates = CarbonPeriod::create($start_date, $end_date);

        $presences = PresensiPengguna::where('id_pengguna', $auth_data->pengguna->id_pengguna)->whereBetween('date', [$start_date, $end_date])->get();

        $hasil = [];

        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        $tidak_checkout = 0;


        foreach ($dates as $key => $value) {

            $hasil[$key]['tanggal'] = $value->format('d');
            $hasil[$key]['hari'] = $value->format('l');
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';
            $hasil[$key]['shift'] = '';
            $hasil[$key]['start'] = '';
            $hasil[$key]['end'] = '';

            $cek_libur = ManajemenHariLibur::where('date', $value->format('Y-m-d'))->first();
            $shiftPengguna = ShiftPengguna::where('id_pengguna', $auth_data->pengguna->id_pengguna)->where('date', $value->format('Y-m-d'))->first();
            $attendance = $presences->where('date', $value->format('Y-m-d'))->first();

            if (!empty($shiftPengguna)) {
                $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();
                if (!empty($shiftMaster)) {
                    $hasil[$key]['shift'] = $shiftMaster['code'];
                    $hasil[$key]['start'] = minimalisTime($shiftMaster['start_time']);
                    $hasil[$key]['end'] = minimalisTime($shiftMaster['end_time']);
                }
            }

            if (!empty($attendance)) {

                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($attendance->status == 'izin') {
                        $jumlah_izin++;
                    }
                }

                if (isset($shiftMaster['start_time']) && $shiftMaster['end_time']) {

                    if ($attendance->check_in) {
                        $hasil[$key]['check_in'] = $attendance->check_in;
                        $hasil[$key]['status'] = "Masuk";
                        $jumlah_hadir++;
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Masuk | Telat";
                    }

                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                        $jumlah_pulangcepat++;
                        $hasil[$key]['status'] = "Masuk | Pulang lebih awal";
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key]['status'] = "Masuk | Telat dan Pulang lebih awal";
                    }

                    if ($attendance->check_out) {
                        $hasil[$key]['check_out'] = $attendance->check_out;
                    }

                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                        $hasil[$key]['status'] = 'Masuk | Tidak Checkout';
                        $tidak_checkout++;
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $value->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = "Masuk | Telat & Tidak Checkout";
                    }
                }
            } else {
                if (!empty($shiftMaster)) {
                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                        $jumlah_alpha++;
                    } else if ($value->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Belum Absent';
                    } else {
                        $hasil[$key]['status'] = '';
                    }

                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d') && $cek_libur) {
                        $jumlah_alpha--;
                    }
                }
            }
            if (!empty($cek_libur)) {
                $hasil[$key]['status'] = 'Libur';
            }
        }

        return view('siswa.absensi.histori-absensi.view-histori-absensi-siswa', compact('auth_data', 'presences', 'start_date', 'end_date', 'dates', 'hasil', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_pulangcepat', 'jumlah_alpha', 'tidak_checkout', 'cek_libur'));
    }
}
