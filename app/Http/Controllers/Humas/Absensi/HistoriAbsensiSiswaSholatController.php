<?php

namespace App\Http\Controllers\Humas\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\HistoriAbsensiSholatDay;
use App\Exports\HistoriAbsensiDay;
use App\Exports\HistoriAbsensiMount;
use App\Models\FPAttendance;
use App\Models\Jalur;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\ManajemenHariLibur;
use App\Models\Pengguna;
use App\Models\PresensiPengguna;
use App\Models\Sekolah;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use App\Models\Siswa;
use App\Models\StatusPengguna;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class HistoriAbsensiSiswaSholatController extends Controller
{
    public function viewHistoriAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $date = Carbon::now()->format('Y-m-d');
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();

        return view('humas/absensi/histori-absensi-siswa-sholat/view-histori-absensi-siswa-sholat', compact('auth_data', 'kelas', 'date'));
    }


    public function actionDetailHistoriAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();

        return [
            'status' => 204, // SUCCESS AND LOAD CONTENT
            'path' => 'absensi/histori-absensi-siswa-sholat/detail/' . $input->kelas . '/' . $input->date
        ];
    }

    public function viewDetailHistoriAbsensiSiswa(Request $request, $id_kelas, $date)
    {
        set_time_limit(9800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();

        $jumlah_subuh = 0;
        $jumlah_dzuhur = 0;
        $jumlah_izin = 0;
        $jumlah_magrib = 0;
        $jumlah_isya = 0;

        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })
                ->whereHas('siswa.calon_siswa')
                ->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })
                ->whereHas('siswa.calon_siswa')
                ->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa.calon_siswa')
                ->get()->sortBy('siswa.kelas.nm_kelas')->sortBy('siswa.kelas.tingkat');
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas', 'siswa.calon_siswa')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })
                ->whereHas('siswa.calon_siswa')
                ->orderBy('nm_pengguna', 'asc')->get();
        }
        $hasil = [];
        $allPresensiPengguna = FPAttendance::where('tanggal', $date)->where('unit', 'Sholat')->get();

        $sevenDay = now()->subDays(6)->format('Y-m-d'); // Tanggal 7 hari yang lalu
        $rekapMinggu = FPAttendance::where('unit', 'Sholat')->whereDate('tanggal', '>=', $sevenDay)
            ->whereDate('tanggal', '<=', $date)
            ->get();

        // $rekapPresensoSholat = FPAttendance::where('unit', 'Sholat')->get();
        $dates =  Carbon::parse($date);

        $firstSubuh = Carbon::create($dates->year, $dates->month, $dates->day, 3, 55, 0);
        $endSubuh = Carbon::create($dates->year, $dates->month, $dates->day, 4, 35, 0);

        $firstDzuhur = Carbon::create($dates->year, $dates->month, $dates->day, 11, 55, 0);
        $endDzuhur = Carbon::create($dates->year, $dates->month, $dates->day, 12, 35, 0);

        $firstMaghrib = Carbon::create($dates->year, $dates->month, $dates->day, 17, 40, 0);
        $endMaghrib = Carbon::create($dates->year, $dates->month, $dates->day, 18, 20, 0);

        $firstIsya = Carbon::create($dates->year, $dates->month, $dates->day, 19, 10, 0);
        $endIsya = Carbon::create($dates->year, $dates->month, $dates->day, 19, 50, 0);

        foreach ($pengguna as $key => $value) {
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['nis'] = $value->username;
            $hasil[$key]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            $hasil[$key]['subuh'] = '-';
            $hasil[$key]['dzuhur'] = '-';
            $hasil[$key]['maghrib'] = '-';
            $hasil[$key]['isya'] = '-';
            // $hasil[$key]['rekap'] = $rekapMinggu->where('username', $value->username)->count();

            $hasil[$key]['id_presensi_pengguna'] = "";
            $attendances =  $allPresensiPengguna->where('username', $value->username);

            foreach ($attendances as $attendance) {
                $fpDate =  Carbon::parse($attendance->fp_date);
                if ($fpDate->between($firstSubuh, $endSubuh)) {
                    $hasil[$key]['subuh'] =  date_format(date_create($attendance->fp_date), 'H:i:s');
                    $jumlah_subuh++;
                } elseif ($fpDate->between($firstDzuhur, $endDzuhur)) {
                    $hasil[$key]['dzuhur'] =  date_format(date_create($attendance->fp_date), 'H:i:s');
                    $jumlah_dzuhur++;
                } elseif ($fpDate->between($firstMaghrib, $endMaghrib)) {
                    $hasil[$key]['maghrib'] =  date_format(date_create($attendance->fp_date), 'H:i:s');
                    $jumlah_magrib++;
                } elseif ($fpDate->between($firstIsya, $endIsya)) {
                    $hasil[$key]['isya'] =  date_format(date_create($attendance->fp_date), 'H:i:s');
                    $jumlah_isya++;
                } else { }
            }
        }
        return view('humas/absensi/histori-absensi-siswa-sholat/detail-histori-absensi-siswa-sholat', compact('auth_data', 'kelas', 'date', 'jumlah_subuh', 'jumlah_dzuhur', 'jumlah_magrib', 'jumlah_isya', 'pengguna', 'hasil', 'id_kelas'));
    }

    public function exportday(Request $request, $date, $id_kelas)
    {
        set_time_limit(9800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();

        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_alpha = 0;
        $belum_absent = 0;

        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })
                ->whereHas('siswa.calon_siswa')
                ->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })
                ->whereHas('siswa.calon_siswa')
                ->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa.calon_siswa')
                ->get()->sortBy('siswa.kelas.nm_kelas')->sortBy('siswa.kelas.tingkat');
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas', 'siswa.calon_siswa')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })
                ->whereHas('siswa.calon_siswa')
                ->orderBy('nm_pengguna', 'asc')->get();
        }
        $hasil = [];
        // $allShiftPengguna = ShiftPengguna::where('id_shift_master', 'Siswa')->where('date', $date)->with('shift_master')->get();
        $allPresensiPengguna = FPAttendance::where('tanggal', $date)->where('unit', 'Sholat')->get();
        // $shiftMaster = ShiftMaster::where('code', 'Pondok')->first();
        $dates =  Carbon::parse($date);

        $firstSubuh = Carbon::create($dates->year, $dates->month, $dates->day, 3, 55, 0);
        $endSubuh = Carbon::create($dates->year, $dates->month, $dates->day, 4, 35, 0);

        $firstDzuhur = Carbon::create($dates->year, $dates->month, $dates->day, 11, 55, 0);
        $endDzuhur = Carbon::create($dates->year, $dates->month, $dates->day, 12, 35, 0);

        $firstMaghrib = Carbon::create($dates->year, $dates->month, $dates->day, 17, 40, 0);
        $endMaghrib = Carbon::create($dates->year, $dates->month, $dates->day, 18, 20, 0);

        $firstIsya = Carbon::create($dates->year, $dates->month, $dates->day, 19, 10, 0);
        $endIsya = Carbon::create($dates->year, $dates->month, $dates->day, 19, 50, 0);

        foreach ($pengguna as $key => $value) {
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['nis'] = $value->username;
            $hasil[$key]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            $hasil[$key]['subuh'] = '-';
            $hasil[$key]['dzuhur'] = '-';
            $hasil[$key]['maghrib'] = '-';
            $hasil[$key]['isya'] = '-';

            $hasil[$key]['id_presensi_pengguna'] = "";
            $attendances =  $allPresensiPengguna->where('username', $value->username);
            // if ($value->username == '4266') {
            //     // dd($value->username);
            //     dd($attendances);
            // }

            foreach ($attendances as $attendance) {
                $fpDate =  Carbon::parse($attendance->fp_date);
                if ($fpDate->between($firstSubuh, $endSubuh)) {
                    $hasil[$key]['subuh'] =  date_format(date_create($attendance->fp_date), 'H:i:s');
                } elseif ($fpDate->between($firstDzuhur, $endDzuhur)) {
                    $hasil[$key]['dzuhur'] =  date_format(date_create($attendance->fp_date), 'H:i:s');
                } elseif ($fpDate->between($firstMaghrib, $endMaghrib)) {
                    $hasil[$key]['maghrib'] =  date_format(date_create($attendance->fp_date), 'H:i:s');
                } elseif ($fpDate->between($firstIsya, $endIsya)) {
                    $hasil[$key]['isya'] =  date_format(date_create($attendance->fp_date), 'H:i:s');
                } else { }
            }


            // $shiftMaster = $shiftMaster;
            // $hasil[$key]['shift'] = false;
            // if ($shiftPengguna) {
            //     $hasil[$key]['shift'] = true;
            // }

            // if ($attendance) {
            //     if ($attendance->status) {
            //         $hasil[$key]['status'] = $attendance->status;
            //         if ($attendance->status == 'sakit') {
            //             $jumlah_sakit++;
            //         } elseif ($attendance->status == 'izin') {
            //             $jumlah_izin++;
            //         }
            //     }

            //     if ($attendance->id_presensi_pengguna) {
            //         $hasil[$key]['id_presensi_pengguna'] = $attendance->id_presensi_pengguna;
            //     }

            //     if ($attendance->check_in) {
            //         $hasil[$key]['check_in'] = $attendance->check_in;
            //         $hasil[$key]['status'] = "Masuk";
            //         $jumlah_hadir++;
            //     }
            //     if (isset($shiftMaster['start_time'])) {
            //         if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
            //             $jumlah_telat++;
            //             $hasil[$key]['status'] = "Masuk | Telat";
            //         }
            //     }

            //     if ($attendance->check_out) {
            //         $hasil[$key]['check_out'] = $attendance->check_out;
            //     }
            // } else {

            //     if ($shiftMaster) {

            //         if ($date < Carbon::now()->format('Y-m-d')) {
            //             $hasil[$key]['status'] = 'Alpha';
            //             $jumlah_alpha++;
            //         } else if ($date == Carbon::now()->format('Y-m-d')) {
            //             $hasil[$key]['status'] = 'Belum Absent';
            //             $belum_absent++;
            //         } else {
            //             $hasil[$key]['status'] = '';
            //         }

            //         if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
            //             $jumlah_alpha--;
            //         }
            //     }
            // }
            // if ($cek_libur) {
            //     $hasil[$key]['status'] = 'Libur';
            // }
        }
        $products = $hasil;
        return Excel::download(new HistoriAbsensiSholatDay($products), 'download_harian.xlsx');
    }
}
