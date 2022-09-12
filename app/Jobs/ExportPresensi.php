<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel;

// use App\Exports\HistoriAbsensiDay;
use App\Exports\HistoriAbsensiMount;
use App\Models\ManajemenHariLibur;
use App\Models\Pengguna;
use App\Models\PresensiPengguna;
// use App\Models\Sekolah;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
// use App\Models\UnitKerja;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ExportPresensi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $date;
    protected $unit_kerja;
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
        if($this->unit_kerja == "0" ){
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
            ->whereHas('guru.unit_kerja', function ($query) use ( $unit_kerja ) {
                $query->where('id_unit_kerja', '=', $unit_kerja);
            })
            ->get();
        }


        $year = Carbon::parse($this->date)->format('Y');
        $mount = Carbon::parse($this->date)->format('M');


        $start_date = new Carbon('first day of' . $mount . $year);
        $end_date =  new Carbon('last day of' . $mount . $year);

       
        // $allShiftPengguna = ShiftPengguna::with('shift_master')->get();
        // $allPresensiPengguna = PresensiPengguna::get();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::get();
        
        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            // $hasil[$key1]['status_join_table'] = $value->status_join_table;
            $hasil[$key1]['nm_pengguna'] = $value->gelar_depan.' '.$value->nm_pengguna.' '.$value->gelar_belakang;
            $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';

            foreach ($dates as $key2 => $date) {
                $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
                $hasil[$key1][$key2]['status'] = '';
                // $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                // $attendance =  $allPresensiPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                // $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master:null;
                $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                if( $shiftPengguna){
                    $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();
                }
               


                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                    }
                    if(isset($shiftMaster['start_time'])){
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {

                        $hasil[$key1][$key2]['status'] = "Telat";
                    }}
                    if(isset($shiftMaster['end_time']) && isset($attendance->check_out)){
                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {

                        $hasil[$key1][$key2]['status'] = "Pulang lebih awal";
                    }}

                    if(isset($shiftMaster['start_time'])){
                    if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key1][$key2]['status'] = "Telat dan Pulang lebih awal";
                    }}

                    if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                        $hasil[$key1][$key2]['status'] = 'Tidak Checkout';
                    }
                    if(isset($shiftMaster['start_time'])){
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                        $hasil[$key1][$key2]['status'] = "Telat & Tidak Checkout";
                    }}
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
        // return $products;
    return Excel::download(new HistoriAbsensiMount($products), 'download_bulanan.xlsx');    
    }
}
