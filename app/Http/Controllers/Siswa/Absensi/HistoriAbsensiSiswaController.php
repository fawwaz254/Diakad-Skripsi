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
        $auth_data = auth_data();

        if (empty($start_date) || empty($end_date)) {
            $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $dates = CarbonPeriod::create($start_date, $end_date);
        $dateStrings = collect($dates)->map->format('Y-m-d');

        $presences = PresensiPengguna::where('id_pengguna', $auth_data->pengguna->id_pengguna)
            ->whereBetween('date', [$start_date, $end_date])
            ->get()
            ->keyBy('date');

        $shiftPengguna = ShiftPengguna::where('id_pengguna', $auth_data->pengguna->id_pengguna)
            ->whereIn('date', $dateStrings)
            ->get()
            ->keyBy('date');

        $liburDates = ManajemenHariLibur::whereIn('date', $dateStrings)
            ->pluck('date')
            ->toArray();

        $shiftMasterCodes = $shiftPengguna->pluck('id_shift_master')->unique()->filter();
        $shiftMasters = ShiftMaster::whereIn('code', $shiftMasterCodes)
            ->get()
            ->keyBy('code');

        $counters = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'telat' => 0,
            'pulangcepat' => 0,
            'alpha' => 0,
            'tidak_checkout' => 0,
        ];

        $hariIndo = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $today = Carbon::now()->format('Y-m-d');
        $results = [];

        foreach ($dates as $key => $date) {
            $dateString = $date->format('Y-m-d');
            $isPastDate = $dateString < $today;
            $isToday = $dateString == $today;
            
            $resultItem = [
                'tanggal' => $dateString,
                'hari' => $hariIndo[$date->dayOfWeek],
                'check_in' => '-',
                'check_out' => '-',
                'status' => '',
                'shift' => '',
                'start' => '',
                'end' => '',
            ];

            if (in_array($dateString, $liburDates)) {
                $resultItem['status'] = 'Libur';
                $results[$key] = $resultItem;
                continue;
            }

            $attendance = $presences->get($dateString);
            $userShift = $shiftPengguna->get($dateString);
            $shiftMaster = $userShift ? $shiftMasters->get($userShift['id_shift_master']) : null;

            if ($shiftMaster) {
                $resultItem['shift'] = $shiftMaster['code'];
                $resultItem['start'] = minimalisTime($shiftMaster['start_time']);
                $resultItem['end'] = minimalisTime($shiftMaster['end_time']);
            }

            if ($attendance) {
                if ($attendance->status) {
                    $resultItem['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $counters['sakit']++;
                    } elseif ($attendance->status == 'izin') {
                        $counters['izin']++;
                    }
                }

                if ($shiftMaster && $shiftMaster['start_time'] && $shiftMaster['end_time']) {
                    if ($attendance->check_in) {
                        $resultItem['check_in'] = $attendance->check_in;
                        $resultItem['status'] = "Masuk";
                        $counters['hadir']++;

                        if ($attendance->check_in > $shiftMaster['start_time']) {
                            $counters['telat']++;
                            $resultItem['status'] = "Masuk | Telat";
                        }

                        if ($attendance->check_out && $attendance->check_out < $shiftMaster['end_time']) {
                            $counters['pulangcepat']++;
                            $resultItem['status'] = $resultItem['status'] == "Masuk | Telat" 
                                ? "Masuk | Telat dan Pulang lebih awal" 
                                : "Masuk | Pulang lebih awal";
                        }

                        if ($isPastDate && !$attendance->check_out) {
                            $counters['tidak_checkout']++;
                            $resultItem['status'] = $resultItem['status'] == "Masuk | Telat"
                                ? "Masuk | Telat & Tidak Checkout"
                                : "Masuk | Tidak Checkout";
                        }
                    }

                    if ($attendance->check_out) {
                        $resultItem['check_out'] = $attendance->check_out;
                    }
                }
            } elseif ($shiftMaster) {
                if ($isPastDate) {
                    $resultItem['status'] = 'Alpha';
                    $counters['alpha']++;
                } elseif ($isToday) {
                    $resultItem['status'] = 'Belum Absent';
                }
            }

            $results[$key] = $resultItem;
        }

        return view('siswa.absensi.histori-absensi.view-histori-absensi-siswa', compact('auth_data',  'start_date', 'end_date', 'results', 'counters', ));
    }
}
