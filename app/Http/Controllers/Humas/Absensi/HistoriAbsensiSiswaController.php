<?php

namespace App\Http\Controllers\Humas\Absensi;

use App\Exports\HistoriAbsensiDay;
use App\Exports\HistoriAbsensiMount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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


class HistoriAbsensiSiswaController extends Controller
{
    public function viewHistoriAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }

        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_alpha = 0;
        return view('humas/absensi/histori-absensi-siswa/view-histori-absensi-siswa', compact('auth_data', 'kelas', 'date', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_izin', 'jumlah_telat', 'jumlah_alpha'));
    }

    public function actionDetailHistoriAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();
        if ($input->kelas == '0') {
            return [
                'status' => 300, // FAILED
                'message' => 'Pilih Kelas Dahulu'
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'absensi/histori-absensi-siswa/detail/' . $input->kelas . '/' . $input->date
            ];
        }
    }

    public function storeShiftPengguna(Request $request, $date1, $date2)
    {
        set_time_limit(9800);
        // $input = (object) $request->input();

        $startDate = new Carbon('first day of' .  $date1 . '2022');

        $endDate =  new Carbon('last day of' . $date2 . '2022');

        $prefix = Sekolah::first()->prefix;

        if ($startDate > $endDate) {
            return [
                'status' => 300, // fail
                'message' => 'Bulan awal harus lebih kecil dari bulan akhir'
            ];
        }

        $pengguna = Siswa::with('pengguna', 'pengguna.status_pengguna')
            ->whereHas('pengguna.status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();

        $dates = CarbonPeriod::create($startDate, $endDate);
        foreach ($pengguna as $user) {
            foreach ($dates as $value) {
                $shiftPenggunaId = ShiftPengguna::where('id_pengguna', $user->pengguna->id_pengguna)
                    ->where('date', $value->format('Y-m-d'))
                    ->first();
                //validasi apakah sudah ada apa belum datanya
                if ($shiftPenggunaId) {
                    $dataUpdate['id_shift_master'] = 'Siswa';
                    $shiftPenggunaId->update($dataUpdate);
                } else {
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    $html = '';
                    $list_data['id_shift_pengguna'] =   $html .= $prefix . strtotime($now) . uniqid();
                    $list_data['id_pengguna'] = $user->pengguna->id_pengguna;
                    $list_data['date'] =  $value->format('Y-m-d');
                    $list_data['id_shift_master'] = 'Siswa';

                    ShiftPengguna::create($list_data);
                }
            }
        }

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'link' => '/humas#',
            'message' => 'Tambah data Shift berhasil '

        ];

        // return redirect("/humas#absensi/shift_pengguna");
    }



    public function viewDetailHistoriAbsensiSiswa(Request $request, $id_kelas, $date)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();


        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_alpha = 0;
        // dd($id_kelas );
        $pengguna = Siswa::where('id_kelas', $id_kelas)->with('pengguna')->with('pengguna.status_pengguna')
            ->whereHas('pengguna.status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();


        foreach ($pengguna as $key => $value) {
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->pengguna->nm_pengguna;
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';

            $hasil[$key]['id_presensi_pengguna'] = "";
            $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            if (isset($shiftPengguna['start_time'])) {
                $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();
            } else {
                $shiftMaster = NULL;
            }
            $hasil[$key]['shift'] = false;
            if ($shiftPengguna) {
                $hasil[$key]['shift'] = true;
            }

            if ($attendance) {
                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($attendance->status == 'izin') {
                        $jumlah_izin++;
                    }
                }

                if ($attendance->id_presensi_pengguna) {
                    $hasil[$key]['id_presensi_pengguna'] = $attendance->id_presensi_pengguna;
                }

                if ($attendance->check_in) {
                    $hasil[$key]['check_in'] = $attendance->check_in;
                    $jumlah_hadir++;
                }
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Masuk | Telat";
                    }
                }

                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in >= $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out != NULL) {
                        $hasil[$key]['status'] = "Masuk | Telat dan Pulang lebih awal";
                    }
                }
                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = $attendance->check_out;
                }
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                        $hasil[$key]['status'] = "Masuk | Telat  | Tidak Checkout ";
                    }
                }

                // if ($attendance->notes) {
                //     $hasil[$key]['notes'] = $attendance->notes;
                // }
            } else {

                if ($shiftMaster) {

                    if ($date < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                        $jumlah_alpha++;
                    } else if ($date == Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Belum Absent';
                    } else {
                        $hasil[$key]['status'] = '';
                    }

                    if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
                        $jumlah_alpha--;
                    }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
                // $hasil[$key]['notes'] = $cek_libur->explanation;
            }
        }

        return view('humas/absensi/histori-absensi-siswa/detail-histori-absensi-siswa', compact('auth_data', 'kelas', 'date', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_izin', 'jumlah_telat', 'jumlah_alpha', 'pengguna', 'hasil', 'id_kelas'));
    }




    public function export_excel_mount(Request $request, $id_kelas = null, $date = null)
    {
        set_time_limit(1800);
        $pengguna = pengguna::with('status_pengguna', 'siswa')
            ->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', '=', $id_kelas);
            })
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();
        // if (empty($date) || empty($end_date)) {
        //     $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
        //     $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        // }

        $year = Carbon::parse($date)->format('Y');
        $mount = Carbon::parse($date)->format('M');


        $start_date = new Carbon('first day of' . $mount . $year);
        $end_date =  new Carbon('last day of' . $mount . $year);



        $dates = CarbonPeriod::create($start_date, $end_date);

        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key1]['status_join_table'] = $value->status_join_table;
            $hasil[$key1]['nm_pengguna'] = $value->nm_pengguna;

            foreach ($dates as $key2 => $date) {
                $cek_libur = ManajemenHariLibur::where('date', $date->format('Y-m-d'))->first();

                $hasil[$key1][$key2]['status'] = '';
                $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();

                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                    }
                    //
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {

                        $hasil[$key1][$key2]['status'] = "Telat";
                    }

                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {

                        $hasil[$key1][$key2]['status'] = "Pulang lebih awal";
                    }

                    if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key1][$key2]['status'] = "Telat dan Pulang lebih awal";
                    }

                    if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                        $hasil[$key1][$key2]['status'] = 'Tidak Checkout';
                    }
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                        $hasil[$key1][$key2]['status'] = "Telat & Tidak Checkout";
                    }
                } else {

                    if ($shiftMaster) {

                        if ($date->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                            $hasil[$key1][$key2]['status'] = 'Alpha';
                        } else {
                            $hasil[$key1][$key2]['status'] = '';
                        }
                    }
                }
                if ($cek_libur) {
                    $hasil[$key1][$key2]['status'] = 'Libur';
                }
                $hasil[$key1][$key2]['date'] = $date->format('d-m-Y');
            }
        }

        // dd($hasil);
        $products = $hasil;
        // dd($products);
        return Excel::download(new HistoriAbsensiMount($products), 'download_bulanan.xlsx');
    }

    public function export_excel_day(Request $request, $id_kelas = null, $date = null)
    {
        $pengguna = pengguna::with('status_pengguna', 'siswa')
            ->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', '=', $id_kelas);
            })
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();

        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }

        $cek_libur = ManajemenHariLibur::where('date', $date)->first();
        foreach ($pengguna as $key => $value) {
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';
            $hasil[$key]['notes'] = '';
            $hasil[$key]['id_presensi_pengguna'] = "";
            $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();

            if ($attendance) {

                if ($attendance->id_presensi_pengguna) {
                    $hasil[$key]['id_presensi_pengguna'] = $attendance->id_presensi_pengguna;
                }

                if ($attendance->check_in) {
                    $hasil[$key]['check_in'] = $attendance->check_in;
                }
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {

                        $hasil[$key]['notes'] = "Telat";
                    }
                }


                if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {

                    $hasil[$key]['notes'] = "Pulang lebih awal";
                }
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key]['notes'] = "Telat dan Pulang lebih awal";
                    }
                }

                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = $attendance->check_out;
                }
                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                }
                if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                    $hasil[$key]['notes'] = 'Tidak Checkout';
                }
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                        $hasil[$key]['notes'] = "Telat & Tidak Checkout";
                    }
                }

                if ($attendance->notes) {
                    $hasil[$key]['notes'] = $attendance->notes;
                }
            } else {

                if ($shiftMaster) {

                    if ($date < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                    } else if ($date == Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Belum Absent';
                    } else {
                        $hasil[$key]['status'] = '';
                    }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
                $hasil[$key]['notes'] = $cek_libur->explanation;
            }
            $hasil[$key]['date'] = $date;
        }


        $products = $hasil;
        return Excel::download(new HistoriAbsensiDay($products), 'download_harian.xlsx');
    }
}
