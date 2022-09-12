<?php

namespace App\Http\Controllers\Humas\Absensi;


use App\Exports\HistoriAbsensiDay;
use App\Exports\HistoriAbsensiMount;
use App\Jobs\ExportPresensi;
use App\Jobs\ExportPresensiDay;
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

    public function export_excel_mount(Request $request, $date = null, $unit_kerja = null)
    {
        // set_time_limit(9800);
       ExportPresensi::dispatch($date,$unit_kerja);
        
    }

    public function export_excel_day(Request $request, $date = null,$unit_kerja = null)
    {
     ExportPresensiDay::dispatch($date,$unit_kerja);
        // $job = new ExportPresensiDay($date,$unit_kerja);
        // $data = $this->dispatch($job);
        // dd($job);
        // return Excel::download(new HistoriAbsensiDay($job), 'download_harian.xlsx');
        // return response()->json($job->getResponse());
    }


    public function viewHistoriAbsensi(Request $request, $date = null, $unit_kerja = null, $status = null)
    {
        set_time_limit(1800);
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }

        if (!isset($unit_kerja)) {
            $unit_kerja = "0";
        }

        if (!isset($status)) {
            $status = "0";
        }

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
            $hasil[$key]['nm_pengguna'] = $value->gelar_depan.' '.$value->nm_pengguna.' '.$value->gelar_belakang;
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';

            $hasil[$key]['id_presensi_pengguna'] = "";
            $shiftPengguna = $allShiftPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $attendance =  $allPresensiPengguna->firstWhere('id_pengguna', '=', $value->id_pengguna);
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master:null;
            $hasil[$key]['shift'] = false;
            if ($shiftPengguna && $shiftMaster) {
                $hasil[$key]['shift'] = true;
            }

            if ($attendance) {
                if (isset($attendance->status)) {
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

                if (isset($shiftMaster['end_time']) && isset($attendance->check_out)) {
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

                        $hasil[$key]['status'] = "Masuk | Telat  | Tidak Checkout";
                    }
                }

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
            }
        }
        $list_unit_kerja = UnitKerja::all();
        return view('humas/absensi/histori-absensi/view-histori-absensi', compact('auth_data', 'list_unit_kerja','date', 'hasil', 'jumlah_hadir','belum_absent', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_pulangcepat', 'jumlah_alpha', 'tidak_checkout', 'cek_libur', 'unit_kerja','status'));
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
        return redirect("/humas#absensi/histori-absensi/" . $date . "/0" . "/0");
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
        return redirect("/humas#absensi/histori-absensi/" . $date . "/0" . "/0");
    }

    public function destroyHistoriAbsensi(Request $request, $id_presensi_pengguna = null)
    {
        PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->delete();
        return $id_presensi_pengguna;
    }
}
