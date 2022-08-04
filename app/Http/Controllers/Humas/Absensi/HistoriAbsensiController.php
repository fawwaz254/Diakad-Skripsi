<?php

namespace App\Http\Controllers\Humas\Absensi;


use App\Exports\HistoriAbsensiDay;
use App\Exports\HistoriAbsensiMount;
use App\Models\ManajemenHariLibur;
use App\Models\Pengguna;
use App\Models\PresensiPengguna;
use App\Models\Sekolah;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Facades\Excel;

class HistoriAbsensiController extends BaseController
{

    public function export_excel_mount(Request $request, $date = null)
    {
        set_time_limit(1800);
        $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
            ->with('status_pengguna')
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


        $allShiftPengguna = ShiftPengguna::with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::get();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::get();

        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key1]['status_join_table'] = $value->status_join_table;
            $hasil[$key1]['nm_pengguna'] = $value->nm_pengguna;

            foreach ($dates as $key2 => $date) {
                $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));

                $hasil[$key1][$key2]['status'] = '';
                // $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                // $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                // $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();
                $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $attendance =  $allPresensiPengguna>where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna);
                $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master:null;

                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                    }
                    //
                    if(isset($shiftMaster['start_time'])){
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {

                        $hasil[$key1][$key2]['status'] = "Telat";
                    }} 
                    if(isset($shiftMaster['end_time'])){
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

        // dd($hasil);
        $products = $hasil;
        // dd($products);
        return Excel::download(new HistoriAbsensiMount($products), 'download_bulanan.xlsx');
    }

    public function export_excel_day(Request $request, $date = null,$unit_kerja = null)
    {
        if($unit_kerja == null ||$unit_kerja == "0" ){
            $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
            ->with('status_pengguna')
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
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';
            $hasil[$key]['notes'] = '';
            $hasil[$key]['id_presensi_pengguna'] = "";
            $shiftPengguna = $allShiftPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $attendance =  $allPresensiPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master:null;
            // $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            // $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            // $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();
            // $attendance = null;
            //   $shiftMaster = null;

            // dd($shiftPengguna);

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


    public function viewHistoriAbsensi(Request $request, $date = null, $unit_kerja = null)
    {
        set_time_limit(1800);
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }

        if (empty($unit_kerja)) {
            $unit_kerja = 0;}

        if ($unit_kerja != "0") {
            if ($unit_kerja == "1") {
                $pengguna = pengguna::where('status_join_table', 1)->where('username', '!=', 'admin')
                    ->with('status_pengguna', 'guru.unit_kerja')
                    ->whereHas('status_pengguna', function ($query) {
                        $query->where('nm_status_pengguna', '=', 'AKTIF');
                    })
                    ->get();
            } else {
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
        } else {
            $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
                ->with('status_pengguna', 'guru.unit_kerja')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->get();
        }

        //  $pengguna = Pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')->orderBy('status_join_table', 'desc')->get();
        // dd($pengguna);
        // $shiftPengguna = ShiftPengguna::where('id_pengguna', $pengguna->id_pengguna)->where('date', $date)->first();
        $hasil = [];

        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        $tidak_checkout = 0;
        $belum_absent = 0;

        $cek_libur = ManajemenHariLibur::where('date', $date)->first();
        $allShiftPengguna = ShiftPengguna::where('date', $date)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::where('date', $date)->get();

        foreach ($pengguna as $key => $value) {
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';

            $hasil[$key]['id_presensi_pengguna'] = "";
            // $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            // $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            // if (isset($shiftPengguna['id_shift_master'])) {
            //     $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();
            //     // dd($shiftMaster);
            // } else {
            //     $shiftMaster = null;
            // }

            // $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->with('presensi_pengguna','shift_master')->first();
            $shiftPengguna = $allShiftPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $attendance =  $allPresensiPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master:null;
            // dd($attendance);
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
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Masuk | Telat";
                    }
                }

                if (isset($shiftMaster['end_time'])) {
                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                        $jumlah_pulangcepat++;
                        $hasil[$key]['status'] = "Masuk | Pulang lebih awal";
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

                if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                    $hasil[$key]['status'] = 'Masuk | Tidak Checkout';
                    $tidak_checkout++;
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
        $list_unit_kerja = UnitKerja::all();
        return view('humas/absensi/histori-absensi/view-histori-absensi', compact('auth_data', 'list_unit_kerja','date', 'hasil', 'jumlah_hadir','belum_absent', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_pulangcepat', 'jumlah_alpha', 'tidak_checkout', 'cek_libur', 'unit_kerja'));
    }

    public function createHistoriAbsensi(Request $request, $id_pengguna = null, $date = null)
    {
        return view('humas/absensi/histori-absensi/add-histori-absensi', compact('id_pengguna', 'date'));
    }

    public function storeHistoriAbsensi(Request $request, $id_pengguna = null, $date = null)
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $prefix = Sekolah::first()->prefix;
        $uuid = $prefix . strtotime($now) . uniqid();
        $input = $request->input();
        $status = $input['status'];
        $notes = $input['notes'];
        PresensiPengguna::create(['id_presensi_pengguna' => $uuid, 'id_pengguna' => $id_pengguna, 'status_join_table' => 2, 'date' => $date, 'status' => $status, 'notes' => $notes]);
        return redirect("/humas#absensi/histori-absensi/" . $date);
    }

    public function editHistoriAbsensi(Request $request, $id_presensi_pengguna = null, $date = null)
    {
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        return view('humas/absensi/histori-absensi/edit-histori-absensi', compact('presences', 'date'));
    }

    public function updateHistoriAbsensi(Request $request, $id_presensi_pengguna = null, $date = null)
    {
        $input = $request->input();
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        $presences->update(['status' => $input['status'], 'notes' => $input['notes'], 'check_in' => $input['check_in'], 'check_out' => $input['check_out']]);
        // return [
        //     'status' => 202, // SUCCESS AND LOAD CONTENTid_periode_magang
        //     'path' => 'absensi/histori-absensi/',
        //     'message' => 'Data Absensi Berhasil Di Update'
        // ];
        return redirect("/humas#absensi/histori-absensi/" . $date);
    }

    public function destroyHistoriAbsensi(Request $request, $id_presensi_pengguna = null)
    {
        PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->delete();
        return $id_presensi_pengguna;
    }
}
