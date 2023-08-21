<?php

namespace App\Http\Controllers\Humas\Absensi;

use App\Exports\HistoriAbsensiDay;
use App\Exports\HistoriAbsensiMount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Guru;
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
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\WaliKelas;
use Yajra\Datatables\Datatables;

class HistoriAbsensiSiswaController extends Controller
{
    public function viewHistoriAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $date = Carbon::now()->format('Y-m-d');
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = WaliKelas::where('id_guru', $guru->id_guru ?? null)->where('is_aktif', 1)->where('id_semester', $semester_aktif->id_semester)->first();
        if (empty($wali_kelas)) {
            $wali_kelas = WaliKelas::where('id_guru', $guru->id_guru ?? null)->where('is_aktif', 1)->first();
        }
        return view('humas/absensi/histori-absensi-siswa/view-histori-absensi-siswa', compact('auth_data', 'kelas', 'date', 'wali_kelas'));
    }

    public function actionDetailHistoriAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();
        return [
            'status' => 204, // SUCCESS AND LOAD CONTENT
            'path' => 'absensi/histori-absensi-siswa/detail/' . $input->kelas . '/' . $input->date . '/' . $input->status
        ];
    }


    public function viewDetailHistoriAbsensiSiswa(Request $request, $id_kelas, $date, $status)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_alpha = 0;
        $belum_absent = 0;
        $tidak_punya_shift = 0;
        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();

        $pengguna = Pengguna::where('status_join_table', 3)->with([
            'shiftPengguna' => function ($query) use ($date) {
                $query->where('date', $date)->with('shift_master');
            },
            'presensi_pengguna' => function ($query) use ($date) {
                $query->where('date', $date);
            }, 'siswa.kelas'
        ])->orderBy('username', 'desc')->get();

        // if ($id_kelas == '0') {
        //     $pengguna =  $penggunaQuery->get()->sortBy('siswa.kelas.nm_kelas');
        // } else {
        //     $pengguna =  $penggunaQuery->get()->sortBy('nm_pengguna');
        // }

        // ->whereHas('status_pengguna', function ($query) {
        //     $query->where('nm_status_pengguna', '=', 'AKTIF');
        // });

        // if ($id_kelas == "1") {
        //     $penggunaQuery->whereHas('siswa.kelas', function ($query) {
        //         $query->whereIn('tingkat',  [7, 8, 9]);
        //     });
        // } elseif ($id_kelas == "2") {
        //     $penggunaQuery->whereHas('siswa.kelas', function ($query) {
        //         $query->whereIn('tingkat',  [10, 11, 12]);
        //     });
        // } elseif ($id_kelas != "0") {
        //     $penggunaQuery->whereHas('siswa', function ($query) use ($id_kelas) {
        //         $query->where('id_kelas', '=', $id_kelas);
        //     });
        // }

        // $pengguna = $penggunaQuery->get()->sortBy('siswa.kelas.tingkat')
        //     ->sortBy('siswa.kelas.nm_kelas')->sortBy('siswa.nis_siswa');

        $hasil = [];
        foreach ($pengguna as $key => $value) {
            if (empty($value->siswa)) {
                continue;
            }
            if ($value->siswa->id_kelas &&  $id_kelas == '0' || $value->siswa->id_kelas == $id_kelas) {
                $hasil[$key]['check_in'] = '-';
                $hasil[$key]['id_pengguna'] = $value->id_pengguna;
                $hasil[$key]['status_join_table'] = $value->status_join_table;
                $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
                $hasil[$key]['nis'] = $value->username;
                $hasil[$key]['kelas'] = $value->siswa->kelas ? $value->siswa->kelas->nm_kelas : '-';
                $hasil[$key]['check_out'] = '-';
                $hasil[$key]['status'] = '';
                $hasil[$key]['id_presensi_pengguna'] = "";


                if ($cek_libur) {
                    $hasil[$key]['status'] = 'Libur';
                } elseif ($value->presensi_pengguna &&  $value->shiftPengguna && $value->shiftPengguna->shift_master) {
                    $hasil[$key]['status'] = $value->presensi_pengguna->status;
                    if ($value->presensi_pengguna->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($value->presensi_pengguna->status == 'izin') {
                        $jumlah_izin++;
                    }

                    if ($value->presensi_pengguna->id_presensi_pengguna) {
                        $hasil[$key]['id_presensi_pengguna'] = $value->presensi_pengguna->id_presensi_pengguna;
                    }

                    if ($value->presensi_pengguna->check_in) {
                        $hasil[$key]['check_in'] = $value->presensi_pengguna->check_in;
                        $hasil[$key]['status'] = "Masuk";
                        $jumlah_hadir++;
                    }

                    if (!$value->shiftPengguna->shift_master->start_time == null && $value->presensi_pengguna->check_in > $value->shiftPengguna->shift_master->start_time) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Masuk | Telat";
                    }

                    if ($value->presensi_pengguna->check_out) {
                        if ($value->presensi_pengguna->check_out < $value->shiftPengguna->shift_master->end_time && $value->presensi_pengguna->check_out > $value->presensi_pengguna->check_in) {
                            // $jumlah_pulangcepat++;
                            $hasil[$key]['status'] = "Masuk | Pulang lebih awal";
                        }
                        $hasil[$key]['check_out'] = $value->presensi_pengguna->check_out;
                    }

                    if ($value->shiftPengguna->shift_master->start_time && $value->presensi_pengguna->check_in >= $value->shiftPengguna->shift_master->start_time && $value->presensi_pengguna->check_out <  $value->shiftPengguna->shift_master->end_time && $value->presensi_pengguna->check_out != NULL) {
                        $hasil[$key]['status'] = "Masuk | Telat dan Pulang lebih awal";
                    }

                    if ($date < Carbon::now()->format('Y-m-d') && $value->presensi_pengguna->check_in && !$value->presensi_pengguna->check_out) {
                        $hasil[$key]['status'] = 'Masuk | Tidak Checkout';
                        // $tidak_checkout++;
                    }
                    if (isset($value->shiftPengguna->shift_master->start_time)) {
                        if (!$value->shiftPengguna->shift_master->start_time == null && $value->presensi_pengguna->check_in > $value->shiftPengguna->shift_master->start_time && !$value->presensi_pengguna->check_out && $date < Carbon::now()->format('Y-m-d')) {
                            $hasil[$key]['status'] = "Masuk | Telat  | Tidak Checkout";
                        }
                    }
                } elseif (empty($value->presensi_pengguna) && $value->shiftPengguna && $value->shiftPengguna->shift_master) {
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
                } elseif (empty($value->shiftPengguna) && $value->presensi_pengguna) {
                    $hasil[$key]['check_in'] = $value->presensi_pengguna->check_in;
                    $hasil[$key]['status'] = "Masuk | Tidak Punya Shift";
                    $hasil[$key]['check_out'] = $value->presensi_pengguna->check_out;
                    $tidak_punya_shift++;
                } else {
                    unset($hasil[$key]);
                    $tidak_punya_shift++;
                }
            }
        }

        return view('humas/absensi/histori-absensi-siswa/detail-histori-absensi-siswa', compact('auth_data', 'kelas', 'date', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_izin', 'jumlah_telat', 'belum_absent', 'jumlah_alpha', 'tidak_punya_shift', 'pengguna', 'id_kelas', 'status', 'hasil'));
    }

    public function export_excel_mount(Request $request, $id_kelas = null, $date = null)
    {
        set_time_limit(-1);
        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })->orderBy('nm_pengguna', 'asc')->get();
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })->orderBy('nm_pengguna', 'asc')->get();
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::select('pengguna.id_pengguna', 'pengguna.status_join_table', 'pengguna.nm_pengguna')
                ->join('siswa', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
                ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                ->where('nm_status_pengguna', '=', 'AKTIF')
                ->orderBy('nm_pengguna', 'asc')
                ->get();
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })->orderBy('nm_pengguna', 'asc')->get();
        }

        $year = Carbon::parse($date)->format('Y');
        $mount = Carbon::parse($date)->format('M');


        $start_date = new Carbon('first day of' . $mount . $year);
        $end_date =  new Carbon('last day of' . $mount . $year);


        $list_pengguna = $pengguna->pluck('id_pengguna')->toArray();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::get();
        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->with('shift_master')->whereIn('id_pengguna', $list_pengguna)->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->where('status_join_table', 3)->whereIn('id_pengguna', $list_pengguna)->get();
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

                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                    }

                    if ($attendance->check_in) {
                        $hasil[$key1][$key2]['check_in'] = $attendance->check_in;
                        $hasil[$key1][$key2]['status'] = "Masuk";
                    }
                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                            $hasil[$key1][$key2]['status'] = "Telat";
                        }
                    }

                    if (isset($shiftMaster['end_time'])) {
                        if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                            $hasil[$key1][$key2]['status'] = "Pulang lebih awal";
                        }
                    }
                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                            $hasil[$key1][$key2]['status'] = "Telat dan Pulang lebih awal";
                        }
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

        $products = $hasil;
        return Excel::download(new HistoriAbsensiMount($products), 'download_bulanan.xlsx');
    }

    public function export_excel_week(Request $request, $id_kelas = null, $date = null)
    {
        set_time_limit(-1);
        $input = (object) $request->input();

        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })->orderBy('nm_pengguna', 'asc')->get()->sortBy('siswa.kelas.nm_kelas');;
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })->orderBy('nm_pengguna', 'asc')->get()->sortBy('siswa.kelas.nm_kelas');;
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                ->join('siswa', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
                ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                ->where('status_pengguna.nm_status_pengguna', '=', 'AKTIF')
                ->orderBy('kelas.tingkat')
                ->orderBy('kelas.nm_kelas')
                ->orderBy('siswa.nis_siswa')
                ->get();
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })->orderBy('nm_pengguna', 'asc')->get();
        }

        $now = new Carbon($date);
        $start_date = $now->startOfWeek()->format('Y-m-d');
        $end_date = $now->endOfWeek()->format('Y-m-d');

        $list_pengguna = $pengguna->pluck('id_pengguna')->toArray();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::get();
        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->with('shift_master')->whereIn('id_pengguna', $list_pengguna)->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->where('status_join_table', 3)->whereIn('id_pengguna', $list_pengguna)->get();
        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key1]['status_join_table'] = $value->status_join_table;
            $hasil[$key1]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key1]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            foreach ($dates as $key2 => $date) {
                $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
                $hasil[$key1][$key2]['status'] = ' ';
                $hasil[$key1][$key2]['check_in'] = ' ';
                $hasil[$key1][$key2]['check_out'] = ' ';
                $shiftPengguna = $allShiftPengguna->where('id_pengguna', '=', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                $attendance =  $allPresensiPengguna->where('id_pengguna', '=', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                    }

                    if ($attendance->check_in) {
                        $hasil[$key1][$key2]['check_in'] = $attendance->check_in;
                        $hasil[$key1][$key2]['status'] = "Masuk";
                    }

                    if ($attendance->check_out) {
                        $hasil[$key1][$key2]['check_out'] = $attendance->check_out;
                    }

                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                            $hasil[$key1][$key2]['status'] = "Telat";
                        }
                    }

                    if ($input->auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm1taman') {
                        if (isset($shiftMaster['end_time'])) {
                            if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {

                                $hasil[$key1][$key2]['status'] = "Pulang lebih awal";
                            }
                        }
                        if (isset($shiftMaster['start_time'])) {
                            if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                                $hasil[$key1][$key2]['status'] = "Telat dan Pulang lebih awal";
                            }
                        }
                        if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                            $hasil[$key1][$key2]['status'] = 'Tidak Checkout';
                        }
                        if (isset($shiftMaster['start_time'])) {
                            if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                                $hasil[$key1][$key2]['status'] = "Telat & Tidak Checkout";
                            }
                        }
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

        $products = $hasil;
        return Excel::download(new HistoriAbsensiMount($products), 'download_mingguan.xlsx');
    }

    public function batch_edit_status(Request $request, $id_kelas = null, $date = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('id_kelas', $id_kelas)->first();
        return view('humas/absensi/histori-absensi-siswa/batch-edit-status', compact('auth_data',  'kelas', 'date', 'id_kelas'));
    }

    public function action_batch_edit_status(Request $request, $id_kelas = null, $date = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        foreach ($input->id_pengguna as $id_pengguna) {
            $presences = PresensiPengguna::where('id_pengguna', $id_pengguna)->where('date', $date)->first();
            if ($presences) {
                $presences->update(['status' => $input->status, 'notes' => $input->notes]);
            } else {
                PresensiPengguna::create(['id_pengguna' => $id_pengguna, 'status_join_table' => 3, 'date' => $date, 'status' => $input->status, 'notes' => $input->notes]);
            }
        }
        return [
            'status' => 203, // GAGAL
            'message' => 'Batch Edit Status Berhasil'
        ];
    }


    public function datatables_batch_edit_status(Request $request, $id_kelas = null, $date = null)
    {
        set_time_limit(-1);
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();

        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                ->join('siswa', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
                ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                ->where('status_pengguna.nm_status_pengguna', '=', 'AKTIF')
                ->orderBy('kelas.tingkat')
                ->orderBy('kelas.nm_kelas')
                ->orderBy('siswa.nis_siswa')
                ->get();
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })->get()->sortBy('siswa.nis_siswa');
        }
        $list_pengguna = $pengguna->pluck('id_pengguna')->toArray();
        $allShiftPengguna = ShiftPengguna::where('date', $date)->whereIn('id_pengguna', $list_pengguna)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::where('date', $date)->where('status_join_table', 3)->whereIn('id_pengguna', $list_pengguna)->get();
        foreach ($pengguna as $key => $value) {
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['nis'] = $value->username;
            $hasil[$key]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            $hasil[$key]['status'] = '';
            $hasil[$key]['notes'] = '';

            $hasil[$key]['id_presensi_pengguna'] = "";
            $shiftPengguna = $allShiftPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $attendance =  $allPresensiPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;
            $hasil[$key]['shift'] = $shiftMaster;

            if ($attendance) {
                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') { } elseif ($attendance->status == 'izin') { }
                }

                if ($attendance->id_presensi_pengguna) {
                    $hasil[$key]['id_presensi_pengguna'] = $attendance->id_presensi_pengguna;
                }

                if ($attendance->check_in && isset($shiftMaster)) {
                    $hasil[$key]['status'] = "Masuk";
                }
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $hasil[$key]['status'] = "Masuk | Telat";
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
                    if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) { }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
            }
        }

        $list_data = array_filter($hasil, function ($item) {
            return $item['status'] === 'Belum Absent' || $item['status'] === 'Alpha' || $item['status'] === 'sakit' || $item['status'] === 'izin';
        });

        $now = Carbon::now()->format('Y-m-d');

        return Datatables::of($list_data)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_pengguna' => $item['id_pengguna']
                );
                return $data;
            })
            ->make(true);
    }

    public function export_excel_day(Request $request, $id_kelas = null, $date = null)
    {
        $input = (object) $request->input();
        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })->orderBy('nm_pengguna', 'asc')->get();
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })->orderBy('nm_pengguna', 'asc')->get();
        } elseif ($id_kelas == "0") {

            $pengguna = Pengguna::join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                ->join('siswa', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
                ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                ->where('status_pengguna.nm_status_pengguna', '=', 'AKTIF')
                ->orderBy('kelas.tingkat')
                ->orderBy('kelas.nm_kelas')
                ->orderBy('siswa.nis_siswa')
                ->get();
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })->orderBy('nm_pengguna', 'asc')->get();
        }
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }
        $list_pengguna = $pengguna->pluck('id_pengguna')->toArray();
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();
        $allShiftPengguna = ShiftPengguna::where('date', $date)->whereIn('id_pengguna', $list_pengguna)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::where('date', $date)->whereIn('id_pengguna', $list_pengguna)->get();
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
            $shiftPengguna = $allShiftPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $attendance =  $allPresensiPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;
            $hasil[$key]['shift'] =  $shiftMaster;
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

                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = $attendance->check_out;
                }

                if ($input->auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm1taman') {
                    if (isset($shiftMaster['end_time'])) {
                        if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {

                            $hasil[$key]['status'] = "Pulang lebih awal";
                        }
                    }
                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                            $hasil[$key]['status'] = "Telat dan Pulang lebih awal";
                        }
                    }
                    if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                        $hasil[$key]['status'] = 'Tidak Checkout';
                    }
                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                            $hasil[$key]['status'] = "Telat & Tidak Checkout";
                        }
                    }
                }

                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
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

    public function createHistoriAbsensi(Request $request, $id_pengguna = null, $kelas = null, $date = null)
    {
        return view('humas/absensi/histori-absensi-siswa/add-histori-absensi', compact('id_pengguna', 'date', 'kelas'));
    }

    public function storeHistoriAbsensi(Request $request, $id_pengguna = null, $kelas = null, $date = null)
    {

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $prefix = Sekolah::first()->prefix;
        $input = $request->input();
        $status = $input['status'];
        $notes = $input['notes'];
        PresensiPengguna::create(['id_pengguna' => $id_pengguna, 'status_join_table' => 3, 'date' => $date, 'status' => $status, 'notes' => $notes]);
        return redirect("/{{Request::segment(1)}}#absensi/histori-absensi-siswa/detail/" . $kelas . "/"  . $date . "/0");
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
        return redirect("/{{Request::segment(1)}}#absensi/histori-absensi-siswa/detail/" . $id_kelas . '/' . $date . '/0');
    }

    public function destroyHistoriAbsensi(Request $request, $id_presensi_pengguna = null)
    {
        PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->delete();
        return $id_presensi_pengguna;
    }
}
