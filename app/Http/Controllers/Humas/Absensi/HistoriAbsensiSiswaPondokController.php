<?php

namespace App\Http\Controllers\Humas\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Exports\HistoriAbsensiDay;
use App\Exports\HistoriAbsensiMount;
// use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
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


class HistoriAbsensiSiswaPondokController extends Controller
{
    public function viewHistoriAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $date = Carbon::now()->format('Y-m-d');
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();

        return view('humas/absensi/histori-absensi-siswa-pondok/view-histori-absensi-siswa-pondok', compact('auth_data', 'kelas', 'date'));
    }

    public function actionDetailHistoriAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();
        // if ($input->kelas == '0' || $input->kelas == '1' || $input->kelas == '2') {
        //     return [
        //         'status' => 204, // SUCCESS AND LOAD CONTENT
        //         'path' => 'absensi/histori-absensi-siswa/details/' . $input->kelas . '/' . $input->date
        //     ];
        // } else {
        return [
            'status' => 204, // SUCCESS AND LOAD CONTENT
            'path' => 'absensi/histori-absensi-siswa-pondok/detail/' . $input->kelas . '/' . $input->date . '/' . $input->status
        ];
        // }
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
                    ->where('id_shift_master', 'Pondok')
                    ->first();
                //validasi apakah sudah ada apa belum datanya
                if ($shiftPenggunaId) {
                    // $dataUpdate['id_shift_master'] = 'Pondok';
                    // $shiftPenggunaId->update($dataUpdate);
                } else {
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    $html = '';
                    $list_data['id_shift_pengguna'] =   $html .= $prefix . strtotime($now) . uniqid();
                    $list_data['id_pengguna'] = $user->pengguna->id_pengguna;
                    $list_data['date'] =  $value->format('Y-m-d');
                    $list_data['id_shift_master'] = 'Pondok';

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


    public function viewDetailHistoriAbsensiSiswa(Request $request, $id_kelas, $date, $status)
    {
        set_time_limit(9800);
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
        $belum_absent = 0;

        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->get()->sortBy('siswa.kelas.nm_kelas')->sortBy('siswa.kelas.tingkat');
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas', 'siswa.calon_siswa')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })
                ->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();
        }

        $allShiftPengguna = ShiftPengguna::where('id_shift_master', 'Siswa')->where('date', $date)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::where('date', $date)->where('unit', 'Pondok')->get();
        $shiftMaster = ShiftMaster::where('code', 'Pondok')->first();
        foreach ($pengguna as $key => $value) {
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['nis'] = $value->username;
            $hasil[$key]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';

            $hasil[$key]['id_presensi_pengguna'] = "";
            $shiftPengguna = $allShiftPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $attendance =  $allPresensiPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $shiftMaster = $shiftMaster;
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
                    $hasil[$key]['status'] = "Masuk";
                    $jumlah_hadir++;
                }
                // dd($shiftMaster['start_time']);
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Masuk | Telat";
                    }
                }

                // if (isset($shiftMaster['start_time'])) {
                //     if (!$shiftMaster['start_time'] == null && $attendance->check_in >= $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out != NULL) {
                //         $hasil[$key]['status'] = "Masuk | Telat dan Pulang lebih awal";
                //     }
                // }
                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = $attendance->check_out;
                }
                // if (isset($shiftMaster['start_time'])) {
                //     if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                //         $hasil[$key]['status'] = "Masuk | Telat  | Tidak Checkout ";
                //     }
                // }

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
                        $belum_absent++;
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
        // dd($hasil);
        return view('humas/absensi/histori-absensi-siswa-pondok/detail-histori-absensi-siswa-pondok', compact('auth_data', 'kelas', 'date', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_izin', 'jumlah_telat', 'belum_absent', 'jumlah_alpha', 'pengguna', 'hasil', 'id_kelas', 'status'));
    }

    public function export_excel_mount(Request $request, $id_kelas = null, $date = null)
    {
        set_time_limit(9800);
        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })->orderBy('nm_pengguna', 'asc')->get();
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();

            // $pengguna = Pengguna::select('pengguna.id_pengguna', 'pengguna.status_join_table', 'pengguna.nm_pengguna')
            //     ->join('siswa', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
            //     ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            //     ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
            //     ->where('nm_status_pengguna', '=', 'AKTIF')
            //     ->orderBy('nm_pengguna', 'asc')
            //     ->get();
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();
        }

        $year = Carbon::parse($date)->format('Y');
        $mount = Carbon::parse($date)->format('M');


        $start_date = new Carbon('first day of' . $mount . $year);
        $end_date =  new Carbon('last day of' . $mount . $year);



        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::get();
        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->where('status_join_table', 3)->get();
        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key1]['status_join_table'] = $value->status_join_table;
            $hasil[$key1]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key1]['kelas'] = $value->siswa->kelas->nm_kelas;
            foreach ($dates as $key2 => $date) {
                $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));

                $hasil[$key1][$key2]['status'] = '';
                $shiftPengguna = $allShiftPengguna->where('id_pengguna', '=', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                $attendance =  $allPresensiPengguna->where('id_pengguna', '=', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();;
                $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;
                // $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                // $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                // $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();

                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                    }

                    if ($attendance->check_in) {
                        $hasil[$key1][$key2]['check_in'] = $attendance->check_in;
                        $hasil[$key1][$key2]['status'] = "Masuk";
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {

                        $hasil[$key1][$key2]['status'] = "Telat";
                    }

                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {

                        $hasil[$key1][$key2]['status'] = "Pulang lebih awal";
                    }

                    if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key1][$key2]['status'] = "Telat dan Pulang lebih awal";
                    }

                    // if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                    //     $hasil[$key1][$key2]['status'] = 'Tidak Checkout';
                    // }
                    // if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                    //     $hasil[$key1][$key2]['status'] = "Telat & Tidak Checkout";
                    // }
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

    public function export_excel_week(Request $request, $id_kelas = null, $date = null)
    {
        set_time_limit(9800);
        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();

            // $pengguna = Pengguna::select('pengguna.id_pengguna', 'pengguna.status_join_table', 'pengguna.nm_pengguna')
            //     ->join('siswa', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
            //     ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            //     ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
            //     ->where('nm_status_pengguna', '=', 'AKTIF')
            //     ->orderBy('nm_pengguna', 'asc')
            //     ->get();
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();
        }

        // $year = Carbon::parse($date)->format('Y');
        // $mount = Carbon::parse($date)->format('M');


        // $start_date = new Carbon('first day of' . $mount . $year);
        // $end_date =  new Carbon('last day of' . $mount . $year);

        $now = new Carbon($date);
        $start_date = $now->startOfWeek()->format('Y-m-d');
        $end_date = $now->endOfWeek()->format('Y-m-d');


        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::get();
        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->where('status_join_table', 3)->get();
        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key1]['status_join_table'] = $value->status_join_table;
            $hasil[$key1]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key1]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            foreach ($dates as $key2 => $date) {
                $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));

                $hasil[$key1][$key2]['status'] = '';
                $shiftPengguna = $allShiftPengguna->where('id_pengguna', '=', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                $attendance =  $allPresensiPengguna->where('id_pengguna', '=', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;
                // $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                // $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                // $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();

                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                    }

                    if ($attendance->check_in) {
                        $hasil[$key1][$key2]['check_in'] = $attendance->check_in;
                        $hasil[$key1][$key2]['status'] = "Masuk";
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {

                        $hasil[$key1][$key2]['status'] = "Telat";
                    }

                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {

                        $hasil[$key1][$key2]['status'] = "Pulang lebih awal";
                    }

                    if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key1][$key2]['status'] = "Telat dan Pulang lebih awal";
                    }

                    // if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                    //     $hasil[$key1][$key2]['status'] = 'Tidak Checkout';
                    // }
                    // if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                    //     $hasil[$key1][$key2]['status'] = "Telat & Tidak Checkout";
                    // }
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
        return Excel::download(new HistoriAbsensiMount($products), 'download_mingguan.xlsx');
    }

    public function export_excel_day(Request $request, $id_kelas = null, $date = null)
    {
        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();

            // $pengguna = Pengguna::select('pengguna.id_pengguna', 'pengguna.status_join_table', 'pengguna.nm_pengguna')
            //     ->join('siswa', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
            //     ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            //     ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
            //     ->where('nm_status_pengguna', '=', 'AKTIF')
            //     ->orderBy('nm_pengguna', 'asc')
            //     ->get();
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })->whereHas('siswa.calon_siswa', function ($query) {
                    $query->where('jenis_kelamin', '=', '1');
                })->orderBy('nm_pengguna', 'asc')->get();
        }
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }

        $cek_libur = ManajemenHariLibur::where('date', $date)->first();
        $allShiftPengguna = ShiftPengguna::where('date', $date)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::where('date', $date)->get();
        foreach ($pengguna as $key => $value) {
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';
            $hasil[$key]['notes'] = '';
            $hasil[$key]['id_presensi_pengguna'] = "";
            // $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            // $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            // $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();
            $shiftPengguna = $allShiftPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $attendance =  $allPresensiPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

            if ($attendance) {

                if ($attendance->id_presensi_pengguna) {
                    $hasil[$key]['id_presensi_pengguna'] = $attendance->id_presensi_pengguna;
                }

                if ($attendance->check_in) {
                    $hasil[$key]['check_in'] = $attendance->check_in;
                    $hasil[$key]['status'] = "Masuk";
                }

                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {

                        $hasil[$key]['notes'] = "Telat";
                    }
                }


                // if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {

                //     $hasil[$key]['notes'] = "Pulang lebih awal";
                // }
                // if (isset($shiftMaster['start_time'])) {
                //     if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                //         $hasil[$key]['notes'] = "Telat dan Pulang lebih awal";
                //     }
                // }

                // if ($attendance->check_out) {
                //     $hasil[$key]['check_out'] = $attendance->check_out;
                // }
                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                }
                // if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                //     $hasil[$key]['notes'] = 'Tidak Checkout';
                // }
                // if (isset($shiftMaster['start_time'])) {
                //     if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                //         $hasil[$key]['notes'] = "Telat & Tidak Checkout";
                //     }
                // }

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

    public function createHistoriAbsensi(Request $request, $id_pengguna = null, $kelas = null, $date = null)
    {
        return view('humas/absensi/histori-absensi-siswa/add-histori-absensi', compact('id_pengguna', 'date', 'kelas'));
    }

    public function storeHistoriAbsensi(Request $request, $id_pengguna = null, $kelas = null, $date = null)
    {

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $prefix = Sekolah::first()->prefix;
        // $uuid = $prefix . strtotime($now) . uniqid();
        $input = $request->input();
        $status = $input['status'];
        $notes = $input['notes'];
        PresensiPengguna::create(['id_pengguna' => $id_pengguna, 'status_join_table' => 2, 'date' => $date, 'status' => $status, 'notes' => $notes]);
        return redirect("/humas#absensi/histori-absensi-siswa/detail/" . $kelas . "/"  . $date . "/0");
    }

    public function destroyHistoriAbsensi(Request $request, $id_presensi_pengguna = null)
    {
        PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->delete();
        return $id_presensi_pengguna;
    }

    public function editHistoriAbsensi(Request $request, $id_presensi_pengguna = null, $id_kelas = null, $date = null)
    {
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        return view('humas/absensi/histori-absensi-siswa/edit-histori-absensi', compact('presences', 'date', 'id_kelas'));
    }

    public function updateHistoriAbsensi(Request $request, $id_presensi_pengguna = null, $id_kelas = null, $date = null)
    {
        $input = $request->input();
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        $presences->update(['status' => $input['status'], 'notes' => $input['notes'], 'check_in' => $input['check_in'], 'check_out' => $input['check_out']]);
        // return [
        //     'status' => 202, // SUCCESS AND LOAD CONTENTid_periode_magang
        //     'path' => 'absensi/histori-absensi/',
        //     'message' => 'Data Absensi Berhasil Di Update'
        // ];
        return redirect("/humas#absensi/histori-absensi-siswa/detail/" . $id_kelas . '/' . $date . '/0');
    }
}
