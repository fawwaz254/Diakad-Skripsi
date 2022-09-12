<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HistoriAbsensiDay;
use App\Exports\HistoriAbsensiMount;
use App\Jobs\ExportPresensi;
use App\Models\ManajemenHariLibur;
use App\Models\Pengguna;
use App\Models\PresensiPengguna;
use App\Models\Sekolah;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
// use App\Models\UnitKerja;
use Carbon\Carbon;
// use Carbon\CarbonPeriod;

class ExportPresensiDay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $date;
    protected $unit_kerja;
    // protected $products;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($date, $unit_kerja)
    {
        $this->date = $date;
        $this->unit_kerja = $unit_kerja;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $unit_kerja = $this->unit_kerja;
        if($unit_kerja == null ||$unit_kerja == "0" ){
            $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
            ->with('status_pengguna', 'guru.unit_kerja')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();
        }else{
            $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
            ->with('status_pengguna', 'guru.unit_kerja')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })
            ->whereHas('guru.unit_kerja', function ($query) use ($unit_kerja) {
                $query->where('id_unit_kerja', '=', $unit_kerja);
            })
            ->get();
        }

        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }
        $allShiftPengguna = ShiftPengguna::where('date', $date)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::where('date', $date)->get();
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();
        foreach ($pengguna as $key => $value) {
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->gelar_depan.' '.$value->nm_pengguna.' '.$value->gelar_belakang;
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';
            $hasil[$key]['notes'] = '';
            $hasil[$key]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';
            $hasil[$key]['id_presensi_pengguna'] = "";
            $shiftPengguna = $allShiftPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $attendance =  $allPresensiPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master:null;

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

                if (isset($shiftMaster['end_time'])) {
                if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {

                    $hasil[$key]['notes'] = "Pulang lebih awal";
                } }
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key]['notes'] = "Telat dan Pulang lebih awal";
                    }
                }

                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = $attendance->check_out;
                }
                if (isset($attendance->status)) {
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
        // return $hasil;
        $products = $hasil;
    //   $this->products = $hasil;
      return Excel::download(new HistoriAbsensiDay($this->products), 'download_harian.xlsx');
        // dd($products);
      
    //    dd($test);
    //    return "ok";
    }
//     public function getResponse()
// {
//     return Excel::download(new HistoriAbsensiDay($this->products), 'download_harian.xlsx');
//     // return $this->response;
// }
}
