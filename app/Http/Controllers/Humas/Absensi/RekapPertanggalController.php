<?php

namespace App\Http\Controllers\Humas\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Bulan;
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

        // $bulan = Bulan::find($bulan);
        // $data_bulan = Bulan::orderBy('id_bulan')->get();

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

        // if ($unit_kerja != "0") {
        //     if ($unit_kerja == '1') {
        //         $query->whereHas('staff');
        //     } else {
        //         $query->whereHas('guru.unit_kerja', function ($query) use ($unit_kerja) {
        //             $query->where('id_unit_kerja', '=', $unit_kerja);
        //         });
        //     }
        // }

        $pengguna = $query->get();
        // $cek_libur = ManajemenHariLibur::where('date', $date)->first();
        // $year = Carbon::parse($date)->format('Y');
        // $mount = Carbon::parse($date)->format('M');

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

        return view('humas/absensi/rekap-pertanggal/view-rekap-pertanggal', compact('auth_data', 'dates', 'hasil', 'pengguna', 'bulan', 'data_bulan', 'tahun'));
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

        // if (empty($id_kelas)) {
        //     $id_kelas = Kelas::where('is_aktif', '1')->first()->id_kelas;
        //     dd($id_kelas);
        // }
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
        // dd($nama_kelas);

        $pengguna = Pengguna::where('status_join_table', 3)
            ->with([
                'shiftPengguna' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month])->with('shift_master');
                },
                'presensi_pengguna' => function ($query) use ($start_month, $end_month) {
                    $query->whereBetween('date', [$start_month, $end_month]);
                },
                // 'siswa' => function ($query) use ($id_kelas) {
                //     $query->where('id_kelas', $id_kelas);
                // },
                // 'shiftPengguna.shift_master'
            ])
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', $id_kelas);
            })
            ->orderBy('nm_pengguna')->get();

        // dd($query);
        // $pengguna = $query->get();

        // $id_presensi_pengguna = PresensiPengguna::where('id_pengguna', $id_pengguna)->where('date', Carbon::now()->format('Y-m-d'))->first()->id_presensi_pengguna;

        foreach ($pengguna as $key1 => $value) {
            // $hasil[$value->id_pengguna]['nm_pengguna'] = $value->gelar_depan . ' ' . $value->nm_pengguna . ' ' . $value->gelar_belakang;
            // $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            $hasil[$value->id_pengguna]['nm_pengguna'] = $value->nm_pengguna;
            // $hasil[$key1]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            // $hasil[$key1]['nis'] = $value->username;

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

        $list_kelas = Kelas::where('is_aktif', 1)->get();
        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        return view('humas/absensi/rekap-pertanggal-siswa/view-rekap-pertanggal-siswa', compact('auth_data', 'dates', 'hasil', 'pengguna', 'bulan', 'data_bulan', 'tahun', 'list_kelas', 'id_kelas'));
    }
}
