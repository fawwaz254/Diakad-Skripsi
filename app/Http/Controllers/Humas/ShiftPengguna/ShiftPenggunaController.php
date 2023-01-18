<?php

namespace App\Http\Controllers\Humas\ShiftPengguna;

use AddingMenuAbsensiTanpaJadwal;
use Illuminate\Http\Request;
// use Illuminate\Validation\Validator;
use App\Exports\ShiftMount;
use \Validator;
use App\Http\Controllers\Controller;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use App\Jobs\JobShiftPengguna;
use App\Models\Pengguna;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Sekolah;
use App\Models\UnitKerja;
use Maatwebsite\Excel\Facades\Excel;


class ShiftPenggunaController extends Controller
{

    public function viewShiftPengguna(Request $request, $date = null)
    {

        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }

        $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
            ->with('status_pengguna', 'guru.unit_kerja')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();
        $hasil = [];
        $allShiftMater = ShiftMaster::get();
        $allShiftPengguna = ShiftPengguna::where('date', $date)->get();
        foreach ($pengguna as $key => $value) {
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key]['id_shift_master'] = "-";
            $hasil[$key]['time'] = "-";
            $hasil[$key]['id_shift_pengguna'] = "-";
            $hasil[$key]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';

            $attendance = $allShiftPengguna->firstWhere('id_pengguna', $value->id_pengguna);
            if ($attendance) {

                if ($attendance->id_shift_master) {
                    $hasil[$key]['id_shift_master'] = $attendance->id_shift_master;
                    $shiftM = $allShiftMater->firstWhere('code', $attendance->id_shift_master);

                    if (isset($shiftM)) {
                        $hasil[$key]['time'] = minimalisTime($shiftM['start_time']) . " - " . minimalisTime($shiftM['end_time']);
                    }
                } else {
                    $hasil[$key]['id_shift_master'] = "-";
                }
                if ($attendance->id_shift_pengguna) {
                    $hasil[$key]['id_shift_pengguna'] = $attendance->id_shift_pengguna;
                }
            }
        }
        $unit_kerja = UnitKerja::all();
        return view('humas/absensi/shift-pengguna/view-shift-pengguna', compact('date', 'hasil', 'unit_kerja'));
    }

    public function addShiftPengguna(Request $request)
    {

        $shifts = ShiftMaster::all();
        $penggunas = pengguna::whereIn('status_join_table', [1, 2])
            ->with('status_pengguna', 'guru.unit_kerja', 'staff.unit_kerja')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('aktif_status_pengguna', '=', '1');
            })
            ->whereHas('guru.unit_kerja')->orWhereHas('staff.unit_kerja')->where('username', '!=', 'admin')->orderBy('username', 'asc')->get();

        // foreach($penggunas as $pengguna){}
        $date =  Carbon::now()->format('Y-m-d');

        $shiftsPengguna = ShiftPengguna::where('date', $date)->get();
        return view('humas/absensi/shift-pengguna/add-shift-pengguna', compact('shifts', 'penggunas', 'shiftsPengguna'));
    }

    public function storeShiftPengguna(Request $request)
    {
        set_time_limit(-1);
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

        $startDate = $input->startDate;
        $endDate =  $input->endDate;
        // $nameDay =  ;

        $prefix = Sekolah::first()->prefix;

        if ($startDate > $endDate) {
            return [
                'status' => 300, // fail
                'message' => 'Bulan awal harus lebih kecil dari bulan akhir'
            ];
        }

        $dates = CarbonPeriod::create($startDate, $endDate);
        $allShiftPengguna = ShiftPengguna::whereIn('id_pengguna', $pengguna)->whereBetween('date', [$startDate, $endDate])->get();
        foreach ($pengguna as $user) {
            foreach ($dates as $value) {
                $shiftPenggunaId = $allShiftPengguna->where('id_pengguna', $user)
                    ->where('date', $value->format('Y-m-d'))
                    ->first();
                if ($shiftPenggunaId) {
                    if ($shiftPenggunaId->id_shift_master != $input->dayName[$value->format('l')]) {
                        $dataUpdate['id_shift_master'] = $input->dayName[$value->format('l')];
                        $shiftPenggunaId->update($dataUpdate);
                    }
                } else {
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    $html = '';
                    $list_data[] = [
                        'id_shift_pengguna' =>  $html .= $prefix . strtotime($now) . uniqid(),
                        'id_pengguna' => $user,
                        'date' => $value->format('Y-m-d'),
                        'id_shift_master' => $input->dayName[$value->format('l')],
                    ];
                }
            }
            if (!empty($list_data)) {
                JobShiftPengguna::dispatch($list_data);
                unset($list_data);
            }
        }


        return [
            'status' => 300,
            'message' => 'Tambah data Shift berhasil'
        ];
        // return [
        //     'status' => 202, // SUCCESS AND LOAD CONTENT
        //     'link' => '/humas#absensi/shift_pengguna',
        //     'message' => 'Tambah data Shift berhasil '

        // ];

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

    public function exportShift(Request $request, $date = null)
    {

        set_time_limit(-1);

        $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
            ->with('status_pengguna')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();

        $year = Carbon::parse($date)->format('Y');
        $mount = Carbon::parse($date)->format('M');

        $start_date = new Carbon('first day of' . $mount . $year);
        $end_date =  new Carbon('last day of' . $mount . $year);

        $dates = CarbonPeriod::create($start_date, $end_date);
        $allShiftMater = ShiftMaster::get();
        $list_pengguna = $pengguna->pluck('id_pengguna')->toArray();
        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->whereIn('id_pengguna', $list_pengguna)->get();


        $hariIndo = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['nm_pengguna'] = $value->nm_pengguna;
            $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';

            foreach ($dates as $key2 => $date) {
                $attendance = $allShiftPengguna->where('id_pengguna', $value->id_pengguna)->where('date', $date->format('Y-m-d'))->first();
                $hasil[$key1][$key2]['time'] = "-";
                $hasil[$key1][$key2]['id_shift_master'] = "-";
                $hasil[$key1][$key2]['date'] =   $hariIndo[$date->dayOfWeek] . ", " . $date->format('d-m-Y');
                if ($attendance) {
                    if ($attendance->id_shift_master) {
                        $hasil[$key1][$key2]['id_shift_master'] = $attendance->id_shift_master;
                        $shiftM = $allShiftMater->firstWhere('code', $attendance->id_shift_master);
                        $hasil[$key1][$key2]['time'] = minimalisTime($shiftM['start_time']) . " - " . minimalisTime($shiftM['end_time']);
                    }
                }
            }
        }
        $products = $hasil;
        return Excel::download(new ShiftMount($products), 'shift_bulanan.xlsx');
    }
}
