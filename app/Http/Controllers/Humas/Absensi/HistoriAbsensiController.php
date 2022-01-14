<?php

namespace App\Http\Controllers\Humas\Absensi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PresensiPengguna;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use App\Models\Sekolah;

use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class HistoriAbsensiController extends BaseController
{

    public function viewHistoriAbsensi(Request $request, $id_pengguna = null, $start_date = null, $end_date = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($start_date) || empty($end_date)) {
            $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $dates = CarbonPeriod::create($start_date, $end_date);
        $guru = LibGuru::fetchDataAllGuru($auth_data);

        $presences = PresensiPengguna::where('id_pengguna', $id_pengguna)->whereBetween('date', [$start_date, $end_date])->get();
        $hasil = [];

        foreach ($dates as $key => $value) {
            $hasil[$key]['date'] = $value->format('Y-m-d');
            $hasil[$key]['tanggal'] = $value->format('d');
            $hasil[$key]['hari'] = $value->format('l');
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '-';
            $hasil[$key]['notes'] = '-';
            $hasil[$key]['id_presensi_pengguna'] = "";
            $attendance = $presences->where('date', $value->format('Y-m-d'))->first();

            if ($attendance) {

                if ($attendance->id_presensi_pengguna) {
                    $hasil[$key]['id_presensi_pengguna'] = $attendance->id_presensi_pengguna;
                }

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

        return view('humas/absensi/histori-absensi/view-histori-absensi', compact('auth_data', 'id_pengguna', 'start_date', 'end_date', 'dates', 'guru', 'hasil'));
    }

    public function createHistoriAbsensi(Request $request, $id_pengguna = null, $date = null, $start_date = null, $end_date = null)
    {
        return view('humas/absensi/histori-absensi/add-histori-absensi', compact('id_pengguna', 'start_date', 'end_date'));
    }

    public function storeHistoriAbsensi(Request $request, $id_pengguna = null, $date = null, $start_date = null, $end_date = null)
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $prefix = Sekolah::first()->prefix;
        $uuid = $prefix . strtotime($now) . uniqid();
        $input = $request->input();
        $status = $input['status'];
        $notes = $input['notes'];
        PresensiPengguna::create(['id_presensi_pengguna' => $uuid, 'id_pengguna' => $id_pengguna, 'status_join_table' => 2, 'date' => $date, 'status' => $status, 'notes' => $notes]);
        return redirect("/humas#absensi/histori-absensi" . "/" . $id_pengguna . "/" . $start_date . "/" . $end_date);
    }


    public function editHistoriAbsensi(Request $request, $id_presensi_pengguna = null, $start_date = null, $end_date = null)
    {
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        return view('humas/absensi/histori-absensi/edit-histori-absensi', compact('presences', 'start_date', 'end_date'));
    }

    public function updateHistoriAbsensi(Request $request, $id_presensi_pengguna = null, $start_date = null, $end_date = null)
    {
        $input = $request->input();
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        $presences->update(['status' => $input['status'], 'notes' => $input['notes'], 'check_in' => $input['check_in'], 'check_out' => $input['check_out']]);
        return redirect("/humas#absensi/histori-absensi" . "/" . $presences['id_pengguna'] . "/" . $start_date . "/" . $end_date);
    }

    public function destroyHistoriAbsensi(Request $request, $id_pengguna = null, $start_date = null, $end_date = null)
    {
        $id_presensi_pengguna = $request->input()['id_presensi_pengguna'];
        PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->delete();
        return redirect("/humas#absensi/histori-absensi" . "/" . $id_pengguna . "/" . $start_date . "/" . $end_date);
    }
}
