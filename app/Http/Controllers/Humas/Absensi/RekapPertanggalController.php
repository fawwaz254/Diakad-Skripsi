<?php

namespace App\Http\Controllers\Humas\Absensi;

use App\Http\Controllers\Controller;
use App\Exports\ExportRekapPerTanggal;
use App\Exports\ExportRekapPerTanggalSiswa;
use App\Models\Bulan;
use App\Imports\DataImportExcel;
use App\Models\Setting;
use App\Models\ShiftPengguna;
use App\Models\PresensiPengguna;
use App\Models\ManajemenHariLibur;
use App\Models\Pengguna;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Psr7\Query;

class RekapPertanggalController extends Controller
{
    public function viewRekapPertanggal(Request $request, $bulan = null, $tahun = null)
    {
        // set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $date = Carbon::now()->format('Y-m-d');
        if (empty($tahun)) {
            $tahun = $date->year;
        }
        if (empty($bulan)) {
            $bulan = $date->month;
        }

        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);
        $hari_libur = ManajemenHariLibur::pluck('date')->toArray();

        $query = Pengguna::whereIn('status_join_table', [1, 2])
            ->where('username', '!=', 'admin')
            ->with([
                'shiftPenggunas' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month])->with('shift_master');
                },
                'presensi_penggunas' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month]);
                },
            ])
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            });

        $pengguna = $query->get();

        foreach ($pengguna as $key1 => $value) {
            $hasil[$value->id_pengguna]['nm_pengguna'] = $value->gelar_depan . ' ' . $value->nm_pengguna . ' ' . $value->gelar_belakang;

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                if (in_array($shiftPengguna->date, $hari_libur) || Carbon::make($shiftPengguna->date)->isWeekend()) {
                    $hasil[$value->id_pengguna][$shiftPengguna->date] = 'L'; // 'Libur'
                } elseif ($shiftPengguna->shift_master && $shiftPengguna->date < Carbon::now()->format('Y-m-d')) {
                    $hasil[$value->id_pengguna][$shiftPengguna->date] = 'A';
                }
            }

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                foreach ($value->presensi_penggunas as $presensi_pengguna) {
                    if ($shiftPengguna->date == $presensi_pengguna->date) {
                        // $hasil[$value->id_pengguna . $presensi_pengguna->date . 'status'] = 'M';
                        if ($shiftPengguna->shift_master) {
                            $hasil[$value->id_pengguna][$presensi_pengguna->date] = $presensi_pengguna->status;
                            if ($presensi_pengguna->check_in) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; // "Masuk"
                            }

                            if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat"
                            }

                            if ($presensi_pengguna->check_out) {
                                if ($presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out > $presensi_pengguna->check_in) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Pulang lebih awal"
                                }
                            }

                            if ($shiftPengguna->shift_master->start_time && $presensi_pengguna->check_in >= $shiftPengguna->shift_master->start_time && $presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out != null) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat dan Pulang lebih awal"
                            }

                            if ($date < Carbon::now()->format('Y-m-d') && $presensi_pengguna->check_in && !$presensi_pengguna->check_out) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Tidak Checkout
                            }

                            if (isset($shiftPengguna->shift_master->start_time)) {
                                if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time && !$presensi_pengguna->check_out && $date < Carbon::now()->format('Y-m-d')) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Telat  | Tidak Checkout
                                }
                            }
                        }
                    }
                }
            }
        }

        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();
        dump($hari_libur, $hasil);

        return view('humas/absensi/rekap-pertanggal/view-rekap-pertanggal', compact('auth_data', 'dates', 'hasil', 'pengguna', 'bulan', 'data_bulan', 'tahun', 'hari_libur'));
    }

    public function selectRekapPertanggal(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun)) {
            $tahun = Carbon::now()->year;
        }
        if (empty($bulan)) {
            $bulan = Carbon::now()->month;
        }
        $date = Carbon::now()->format('Y-m-d');

        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        return view('humas/absensi/rekap-pertanggal/select-rekap-pertanggal', compact('auth_data', 'data_bulan', 'bulan', 'tahun'));
    }

    public function cetakRekapPertanggal(Request $request, $bulan, $tahun)
    {
        // set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun)) {
            $tahun = Carbon::now()->year();
        }
        if (empty($bulan)) {
            $bulan = Carbon::now()->month();
        }

        $data_bulan = Carbon::createFromDate(null, $bulan, 1)->format('F');
        $data_tahun = Carbon::createFromDate($tahun, null, null)->format('Y');

        $date = Carbon::now()->format('Y-m-d');
        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $query = Pengguna::whereIn('status_join_table', [1, 2])
            ->where('username', '!=', 'admin')
            ->with([
                'shiftPenggunas' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month])->with('shift_master');
                },
                'presensi_penggunas' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month]);
                },
                // 'guru.unit_kerja'
            ])
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            });

        $pengguna = $query->get();

        foreach ($pengguna as $key1 => $value) {
            $hasil[$value->id_pengguna]['nm_pengguna'] = $value->gelar_depan . ' ' . $value->nm_pengguna . ' ' . $value->gelar_belakang;
            foreach ($value->shiftPenggunas as $shiftPengguna) {
                if ($shiftPengguna->shift_master && $shiftPengguna->date < Carbon::now()->format('Y-m-d')) {
                    $hasil[$value->id_pengguna][$shiftPengguna->date] = 'A';
                }
            }

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                foreach ($value->presensi_penggunas as $presensi_pengguna) {
                    if ($shiftPengguna->date == $presensi_pengguna->date) {
                        if ($shiftPengguna->shift_master) {
                            $hasil[$value->id_pengguna][$presensi_pengguna->date] = $presensi_pengguna->status;
                            if ($presensi_pengguna->check_in) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; // "Masuk"
                            }

                            if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat"
                            }

                            if ($presensi_pengguna->check_out) {
                                if ($presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out > $presensi_pengguna->check_in) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Pulang lebih awal"
                                }
                            }

                            if ($shiftPengguna->shift_master->start_time && $presensi_pengguna->check_in >= $shiftPengguna->shift_master->start_time && $presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out != null) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat dan Pulang lebih awal"
                            }

                            if ($date < Carbon::now()->format('Y-m-d') && $presensi_pengguna->check_in && !$presensi_pengguna->check_out) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Tidak Checkout
                            }

                            if (isset($shiftPengguna->shift_master->start_time)) {
                                if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time && !$presensi_pengguna->check_out && $date < Carbon::now()->format('Y-m-d')) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Telat  | Tidak Checkout
                                }
                            }
                        }
                    }
                }
            }
        }

        return view('humas/absensi/rekap-pertanggal/cetak-rekap-pertanggal', compact('auth_data', 'data_bulan', 'data_tahun', 'dates', 'pengguna', 'bulan', 'tahun', 'hasil'));
    }

    public function exportExcelRekapPertanggal(Request $request, $bulan, $tahun)
    {
        // set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun)) {
            $tahun = Carbon::now()->year();
        }
        if (empty($bulan)) {
            $bulan = Carbon::now()->month();
        }

        $data_bulan = Carbon::createFromDate(null, $bulan, 1)->format('F');
        $data_tahun = Carbon::createFromDate($tahun, null, null)->format('Y');

        $date = Carbon::now()->format('Y-m-d');
        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $query = Pengguna::whereIn('status_join_table', [1, 2])
            ->where('username', '!=', 'admin')
            ->with([
                'shiftPenggunas' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month])->with('shift_master');
                },
                'presensi_penggunas' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month]);
                },
                // 'guru.unit_kerja'
            ])
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            });

        $pengguna = $query->get();

        foreach ($pengguna as $value) {
            $hasil[$value->id_pengguna]['nm_pengguna'] = $value->gelar_depan . ' ' . $value->nm_pengguna . ' ' . $value->gelar_belakang;

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                if ($shiftPengguna->shift_master && $shiftPengguna->date < Carbon::now()->format('Y-m-d')) {
                    $hasil[$value->id_pengguna][$shiftPengguna->date] = 'A';
                }
            }

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                foreach ($value->presensi_penggunas as $presensi_pengguna) {
                    if ($shiftPengguna->date == $presensi_pengguna->date) {
                        // $hasil[$value->id_pengguna . $presensi_pengguna->date . 'status'] = 'M';
                        if ($shiftPengguna->shift_master) {
                            $hasil[$value->id_pengguna][$presensi_pengguna->date] = $presensi_pengguna->status;
                            if ($presensi_pengguna->check_in) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; // "Masuk"
                            }

                            if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat"
                            }

                            if ($presensi_pengguna->check_out) {
                                if ($presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out > $presensi_pengguna->check_in) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Pulang lebih awal"
                                }
                            }

                            if ($shiftPengguna->shift_master->start_time && $presensi_pengguna->check_in >= $shiftPengguna->shift_master->start_time && $presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out != null) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat dan Pulang lebih awal"
                            }

                            if ($date < Carbon::now()->format('Y-m-d') && $presensi_pengguna->check_in && !$presensi_pengguna->check_out) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Tidak Checkout
                            }

                            if (isset($shiftPengguna->shift_master->start_time)) {
                                if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time && !$presensi_pengguna->check_out && $date < Carbon::now()->format('Y-m-d')) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Telat  | Tidak Checkout
                                }
                            }
                        }
                    }
                }
            }
        }

        $bulan = $data_bulan;
        $tahun = $data_tahun;

        return Excel::download(new ExportRekapPerTanggal($hasil, $bulan, $tahun, $dates, $pengguna), 'download_rekap_perTanggal.xlsx');
    }

    public function selectRekapPertanggalSiswa(Request $request, $bulan = null, $tahun = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun)) {
            $tahun = Carbon::now()->year;
        }
        if (empty($bulan)) {
            $bulan = Carbon::now()->month;
        }

        $list_kelas = Kelas::where('is_aktif', 1)->get();
        $date = Carbon::now()->format('Y-m-d');
        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        return view('humas/absensi/rekap-pertanggal-siswa/select-rekap-pertanggal-siswa', compact('auth_data', 'data_bulan', 'bulan', 'tahun', 'list_kelas'));
    }

    public function viewRekapPertanggalSiswa(Request $request, $id_kelas, $bulan = null, $tahun = null)
    {
        // set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $date = Carbon::now()->format('Y-m-d');

        if (empty($tahun)) {
            $tahun = $date->year;
        }
        if (empty($bulan)) {
            $bulan = $date->month;
        }

        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $nama_kelas = Kelas::select('nm_kelas')
            ->where('id_kelas', $id_kelas)
            ->first();

        $pengguna = Pengguna::where('status_join_table', 3)
            ->with([
                'shiftPengguna' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month])->with('shift_master');
                },
                'presensi_pengguna' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month]);
                },
            ])
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })
            ->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', $id_kelas);
            })
            ->orderBy('nm_pengguna')
            ->get();

        foreach ($pengguna as $key1 => $value) {
            $hasil[$value->id_pengguna]['nm_pengguna'] = $value->nm_pengguna;

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                if ($shiftPengguna->shift_master && $shiftPengguna->date < Carbon::now()->format('Y-m-d')) {
                    $hasil[$value->id_pengguna][$shiftPengguna->date] = 'A';
                }
            }

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                foreach ($value->presensi_penggunas as $presensi_pengguna) {
                    if ($shiftPengguna->date == $presensi_pengguna->date) {
                        if ($shiftPengguna->shift_master) {
                            $hasil[$value->id_pengguna][$presensi_pengguna->date] = $presensi_pengguna->status;
                            if ($presensi_pengguna->check_in) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; // "Masuk"
                            }

                            if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat"
                            }

                            if ($presensi_pengguna->check_out) {
                                if ($presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out > $presensi_pengguna->check_in) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Pulang lebih awal"
                                }
                            }

                            if ($shiftPengguna->shift_master->start_time && $presensi_pengguna->check_in >= $shiftPengguna->shift_master->start_time && $presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out != null) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat dan Pulang lebih awal"
                            }

                            if ($date < Carbon::now()->format('Y-m-d') && $presensi_pengguna->check_in && !$presensi_pengguna->check_out) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Tidak Checkout
                            }

                            if (isset($shiftPengguna->shift_master->start_time)) {
                                if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time && !$presensi_pengguna->check_out && $date < Carbon::now()->format('Y-m-d')) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Telat  | Tidak Checkout
                                }
                            }
                        }
                    }
                }
            }
        }

        $list_kelas = Kelas::where('is_aktif', 1)->get();
        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        return view('humas/absensi/rekap-pertanggal-siswa/view-rekap-pertanggal-siswa', compact('auth_data', 'dates', 'hasil', 'pengguna', 'bulan', 'data_bulan', 'tahun', 'list_kelas', 'id_kelas'));
    }

    public function cetakRekapPertanggalSiswa(Request $request, $id_kelas, $bulan, $tahun)
    {
        // set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun)) {
            $tahun = Carbon::now()->year();
        }
        if (empty($bulan)) {
            $bulan = Carbon::now()->month();
        }

        $data_bulan = Carbon::createFromDate(null, $bulan, 1)->format('F');
        $data_tahun = Carbon::createFromDate($tahun, null, null)->format('Y');

        $date = Carbon::now()->format('Y-m-d');
        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $nama_kelas = Kelas::select('nm_kelas')
            ->where('id_kelas', $id_kelas)
            ->first();

        $pengguna = Pengguna::where('status_join_table', 3)
            ->with([
                'shiftPengguna' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month])->with('shift_master');
                },
                'presensi_pengguna' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month]);
                },
            ])
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })
            ->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', $id_kelas);
            })
            ->orderBy('nm_pengguna')
            ->get();

        foreach ($pengguna as $key1 => $value) {
            $hasil[$value->id_pengguna]['nm_pengguna'] = $value->nm_pengguna;

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                if ($shiftPengguna->shift_master && $shiftPengguna->date < Carbon::now()->format('Y-m-d')) {
                    $hasil[$value->id_pengguna][$shiftPengguna->date] = 'A';
                }
            }

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                foreach ($value->presensi_penggunas as $presensi_pengguna) {
                    if ($shiftPengguna->date == $presensi_pengguna->date) {
                        if ($shiftPengguna->shift_master) {
                            $hasil[$value->id_pengguna][$presensi_pengguna->date] = $presensi_pengguna->status;
                            if ($presensi_pengguna->check_in) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; // "Masuk"
                            }

                            if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat"
                            }

                            if ($presensi_pengguna->check_out) {
                                if ($presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out > $presensi_pengguna->check_in) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Pulang lebih awal"
                                }
                            }

                            if ($shiftPengguna->shift_master->start_time && $presensi_pengguna->check_in >= $shiftPengguna->shift_master->start_time && $presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out != null) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat dan Pulang lebih awal"
                            }

                            if ($date < Carbon::now()->format('Y-m-d') && $presensi_pengguna->check_in && !$presensi_pengguna->check_out) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Tidak Checkout
                            }

                            if (isset($shiftPengguna->shift_master->start_time)) {
                                if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time && !$presensi_pengguna->check_out && $date < Carbon::now()->format('Y-m-d')) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Telat  | Tidak Checkout
                                }
                            }
                        }
                    }
                }
            }
        }

        return view('humas/absensi/rekap-pertanggal-siswa/cetak-rekap-pertanggal-siswa', compact('auth_data', 'dates', 'hasil', 'pengguna', 'data_bulan', 'data_tahun', 'nama_kelas'));
    }

    public function exportExcelRekapPertanggalSiswa(Request $request, $id_kelas, $bulan, $tahun)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun)) {
            $tahun = Carbon::now()->year();
        }
        if (empty($bulan)) {
            $bulan = Carbon::now()->month();
        }

        $data_bulan = Carbon::createFromDate(null, $bulan, 1)->format('F');
        $data_tahun = Carbon::createFromDate($tahun, null, null)->format('Y');

        $date = Carbon::now()->format('Y-m-d');
        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $nama_kelas = Kelas::select('nm_kelas')
            ->where('id_kelas', $id_kelas)
            ->first();

        $pengguna = Pengguna::where('status_join_table', 3)
            ->with([
                'shiftPengguna' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month])->with('shift_master');
                },
                'presensi_pengguna' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month]);
                },
            ])
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })
            ->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', $id_kelas);
            })
            ->orderBy('nm_pengguna')
            ->get();

        foreach ($pengguna as $key1 => $value) {
            $hasil[$value->id_pengguna]['nm_pengguna'] = $value->nm_pengguna;

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                if ($shiftPengguna->shift_master && $shiftPengguna->date < Carbon::now()->format('Y-m-d')) {
                    $hasil[$value->id_pengguna][$shiftPengguna->date] = 'A';
                }
            }

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                foreach ($value->presensi_penggunas as $presensi_pengguna) {
                    if ($shiftPengguna->date == $presensi_pengguna->date) {
                        if ($shiftPengguna->shift_master) {
                            $hasil[$value->id_pengguna][$presensi_pengguna->date] = $presensi_pengguna->status;
                            if ($presensi_pengguna->check_in) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; // "Masuk"
                            }

                            if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat"
                            }

                            if ($presensi_pengguna->check_out) {
                                if ($presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out > $presensi_pengguna->check_in) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Pulang lebih awal"
                                }
                            }

                            if ($shiftPengguna->shift_master->start_time && $presensi_pengguna->check_in >= $shiftPengguna->shift_master->start_time && $presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out != null) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //"Masuk | Telat dan Pulang lebih awal"
                            }

                            if ($date < Carbon::now()->format('Y-m-d') && $presensi_pengguna->check_in && !$presensi_pengguna->check_out) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Tidak Checkout
                            }

                            if (isset($shiftPengguna->shift_master->start_time)) {
                                if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time && !$presensi_pengguna->check_out && $date < Carbon::now()->format('Y-m-d')) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Telat  | Tidak Checkout
                                }
                            }
                        }
                    }
                }
            }
        }

        $bulan = $data_bulan;
        $tahun = $data_tahun;

        return Excel::download(new ExportRekapPerTanggalSiswa($hasil, $bulan, $tahun, $dates, $pengguna, $nama_kelas), 'download_rekap_perTanggal_siswa.xlsx');
    }
}
