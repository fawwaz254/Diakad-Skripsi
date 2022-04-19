<?php

namespace App\Http\Controllers\Humas\ShiftPengguna;

use Illuminate\Http\Request;
// use Illuminate\Validation\Validator;
use \Validator;
use App\Http\Controllers\Controller;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use App\Models\Pengguna;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Sekolah;


class ShiftPenggunaController extends Controller
{

    public function viewShiftPengguna(Request $request, $date = null)
    {

        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }

        $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
        ->with('status_pengguna')
        ->whereHas('status_pengguna', function($query) {
        $query->where('nm_status_pengguna','=','AKTIF');
        })->get();
        foreach ($pengguna as $key => $value) {
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['id_shift_master'] = "-";
            $hasil[$key]['time'] = "-";
            $hasil[$key]['id_shift_pengguna'] = "-";

            $attendance = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            if ($attendance) {

                if ($attendance->id_shift_master) {
                    $hasil[$key]['id_shift_master'] = $attendance->id_shift_master;
                    $shiftM = ShiftMaster::where('code', $attendance->id_shift_master)->first();
                    $hasil[$key]['time'] =minimalisTime($shiftM['start_time']) . " - " . minimalisTime($shiftM['end_time']);
                } else {
                    $hasil[$key]['id_shift_master'] = "-";
                }
                if ($attendance->id_shift_pengguna) {
                    $hasil[$key]['id_shift_pengguna'] = $attendance->id_shift_pengguna;
                }
            }
        }
        return view('humas/absensi/shift-pengguna/view-shift-pengguna', compact('date', 'hasil'));
    }

    public function addShiftPengguna(Request $request)
    {

        $shifts = ShiftMaster::all();
        $penggunas = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
        ->with('status_pengguna')
        ->whereHas('status_pengguna', function($query) {
        $query->where('nm_status_pengguna','=','AKTIF');
        })->get();

        // foreach($penggunas as $pengguna){}
        $date =  Carbon::now()->format('Y-m-d');

        $shiftsPengguna = ShiftPengguna::where('date', $date)->get();
        return view('humas/absensi/shift-pengguna/add-shift-pengguna', compact('shifts', 'penggunas', 'shiftsPengguna'));
    }

    public function storeShiftPengguna(Request $request)
    {
        $v = Validator::make($request->all(), [

            'pengguna' => 'required',
        ]);

        if ($v->fails()) {
            // return redirect()->back()->withErrors($v->errors());

            return [
                'status' => 300, // fail
                'message' => 'Harus pilih minimal 1 user'
            ];
            // $eror = ('Harus dicentang 1');
            // $pesan = "Harus dicentang salah satu";
            // return redirect("/humas#absensi/shift_pengguna/add/" . $pesan);
        }

        $input = (object) $request->input();


        //validasi cekin

        $pengguna = $input->pengguna;

        $startDate = new Carbon('first day of' . $input->firstMount . '2022');
        $endDate =  new Carbon('last day of' . $input->endMount . '2022');
        // $nameDay =  ;

        $prefix = Sekolah::first()->prefix;

        if ($startDate > $endDate) {
            return [
                'status' => 300, // fail
                'message' => 'Bulan awal harus lebih kecil dari bulan akhir'
            ];
        }


        $dates = CarbonPeriod::create($startDate, $endDate);
        foreach ($pengguna as $user) {
            foreach ($dates as $value) {
                $shiftPenggunaId = ShiftPengguna::where('id_pengguna', $user)
                    ->where('date', $value->format('Y-m-d'))
                    ->first();
                //validasi apakah sudah ada apa belum datanya
                if ($shiftPenggunaId) {
                    $dataUpdate['id_shift_master'] = $input->dayName[$value->format('l')];
                    $shiftPenggunaId->update($dataUpdate);
                } else {
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    $html = '';
                    $list_data['id_shift_pengguna'] =   $html .= $prefix . strtotime($now) . uniqid();
                    $list_data['id_pengguna'] = $user;
                    $list_data['date'] =  $value->format('Y-m-d');
                    $list_data['id_shift_master'] = $input->dayName[$value->format('l')];

                    ShiftPengguna::create($list_data);
                }
            }
        }

        return [
            'status' => 201, // SUCCESS AND LOAD CONTENT
            'link' => '/humas#absensi/shift_pengguna',
            'message' => 'Tambah data Shift berhasil '

        ];

        // return redirect("/humas#absensi/shift_pengguna");
    }

    public function editShiftAbsensi(Request $request, $id_shift_pengguna = null, $date = null)
    {

        $shifts = ShiftMaster::all();
        return view('humas/absensi/shift-pengguna/edit-shift-pengguna', compact('id_shift_pengguna', 'date', 'shifts'));
    }

    public function updateShiftAbsensi(Request $request, $id_shift_pengguna = null, $date = null)
    {
        $shiftPenggunaId = ShiftPengguna::where('id_shift_pengguna', $id_shift_pengguna)->first();

        $input = (object) $request->input();

        $dataUpdate['id_shift_master'] = $input->shift;
        $shiftPenggunaId->update($dataUpdate);

        return redirect("/humas#absensi/shift_pengguna/" . $date);
    }
}
