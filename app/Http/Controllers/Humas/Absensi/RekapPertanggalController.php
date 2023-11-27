<?php

namespace App\Http\Controllers\Humas\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Bulan;
use App\Models\ManajemenHariLibur;
use App\Models\Pengguna;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class RekapPertanggalController extends Controller
{
    public function viewRekapPertanggal(Request $request, $bulan = null, $tahun = null)
    {
        // set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // $hasil = [];
        // $jumlah_hadir = 0;
        // $jumlah_sakit = 0;
        // $jumlah_izin = 0;
        // $jumlah_telat = 0;
        // $jumlah_pulangcepat = 0;
        // $jumlah_alpha = 0;
        // $tidak_checkout = 0;
        // $belum_absent = 0;

        // if (empty($date)) {
        //     $date = Carbon::now()->format('Y-m-d');
        // }
        // if (empty($unit_kerja)) {
        //     $unit_kerja = "0";
        // }
        // if (empty($status)) {
        //     $status = "0";
        // }


        if (empty($tahun)) {
            $tahun = Carbon::now()->year;
        }
        if (empty($bulan)) {
            $bulan = Carbon::now()->month;
        }
        $date = Carbon::now()->format('Y-m-d');

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
                    $hasil[$value->id_pengguna][$shiftPengguna->date] = "A";
                }
            }

            foreach ($value->shiftPenggunas as $shiftPengguna) {
                foreach ($value->presensi_penggunas as $presensi_pengguna) {
                    if ($shiftPengguna->date == $presensi_pengguna->date) {
                        // $hasil[$value->id_pengguna . $presensi_pengguna->date . 'status'] = 'M';
                        if ($shiftPengguna->shift_master) {
                            $hasil[$value->id_pengguna][$presensi_pengguna->date] =  $presensi_pengguna->status;
                            if ($presensi_pengguna->check_in) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = "M"; // "Masuk"
                            }

                            if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = "M"; //"Masuk | Telat"
                            }

                            if ($presensi_pengguna->check_out) {
                                if ($presensi_pengguna->check_out < $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out > $presensi_pengguna->check_in) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = "M"; //"Masuk | Pulang lebih awal"
                                }
                            }

                            if ($shiftPengguna->shift_master->start_time && $presensi_pengguna->check_in >= $shiftPengguna->shift_master->start_time && $presensi_pengguna->check_out <  $shiftPengguna->shift_master->end_time && $presensi_pengguna->check_out != NULL) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = "M"; //"Masuk | Telat dan Pulang lebih awal"
                            }

                            if ($date < Carbon::now()->format('Y-m-d') && $presensi_pengguna->check_in && !$presensi_pengguna->check_out) {
                                $hasil[$value->id_pengguna][$presensi_pengguna->date] = 'M'; //Masuk | Tidak Checkout
                            }

                            if (isset($shiftPengguna->shift_master->start_time)) {
                                if (!$shiftPengguna->shift_master->start_time == null && $presensi_pengguna->check_in > $shiftPengguna->shift_master->start_time && !$presensi_pengguna->check_out && $date < Carbon::now()->format('Y-m-d')) {
                                    $hasil[$value->id_pengguna][$presensi_pengguna->date] = "M"; //Masuk | Telat  | Tidak Checkout
                                }
                            }
                        }
                    }
                }
            }
        }

        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $list_unit_kerja = UnitKerja::all();
        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        return view('humas/absensi/rekap-pertanggal/view-rekap-pertanggal', compact('auth_data', 'list_unit_kerja', 'dates', 'hasil', 'start_month', 'end_month', 'pengguna', 'bulan', 'data_bulan', 'tahun'));
    }
}
