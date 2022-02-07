<?php

namespace App\Http\Controllers\Humas\Absensi;


use App\Exports\HistoriAbsensiDay;
use App\Models\ManajemenHariLibur;
use App\Models\Pengguna;
use App\Models\PresensiPengguna;
use App\Models\Sekolah;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Facades\Excel;

class HistoriAbsensiController extends BaseController
{


    public function export_excel_day(Request $request, $date = null){
        $pengguna = Pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')->orderBy('status_join_table', 'desc')->get();


        foreach ($pengguna as $key => $value) {
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['date'] = $date;
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '-';
            $hasil[$key]['notes'] = '-';
            $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();

            if ($attendance) {
                if ($attendance->check_in) {
                    $hasil[$key]['check_in'] = $attendance->check_in;
        
                }

                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = $attendance->check_out;
                }

                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
               
                }

                if ($attendance->notes) {
                    $hasil[$key]['notes'] = $attendance->notes;
                }
            }
        }
        $products = $hasil;
        return Excel::download(new HistoriAbsensiDay($products), 'download_harian.xlsx');

    }


    public function viewHistoriAbsensi(Request $request, $date = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }

        $pengguna = Pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')->orderBy('status_join_table', 'desc')->get();
        $hasil = [];

        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;

        $cek_libur = ManajemenHariLibur::where('date', $date)->first();

        foreach ($pengguna as $key => $value) {
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '-';
            $hasil[$key]['notes'] = '-';
            $hasil[$key]['id_presensi_pengguna'] = "";
            $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();

            if ($attendance) {

                if ($attendance->id_presensi_pengguna) {
                    $hasil[$key]['id_presensi_pengguna'] = $attendance->id_presensi_pengguna;
                }

                if ($attendance->check_in) {
                    $hasil[$key]['check_in'] = $attendance->check_in;
                    $jumlah_hadir++;
                }

                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = $attendance->check_out;
                }

                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($attendance->status == 'izin') {
                        $jumlah_izin++;
                    }
                }

                if ($attendance->notes) {
                    $hasil[$key]['notes'] = $attendance->notes;
                }
            }
        }

        return view('humas/absensi/histori-absensi/view-histori-absensi', compact('auth_data', 'date', 'hasil', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'cek_libur'));
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
