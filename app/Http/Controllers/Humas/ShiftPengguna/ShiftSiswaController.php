<?php

namespace App\Http\Controllers\Humas\ShiftPengguna;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use Carbon\Carbon;
use App\Exports\ShiftMount;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;

class ShiftSiswaController extends Controller
{
    public function selectKelas(Request $request)
    {
        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $date =  Carbon::now()->format('Y-m-d');
        return view('humas/absensi/shift-pengguna/select-shift-siswa', compact('date', 'kelas'));
    }

    public function viewShiftSiswa(Request $request, $id_kelas, $date)
    {

        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $date =  Carbon::parse($date)->format('Y-m-d');

        $pengguna = Pengguna::where('status_join_table', 3)
            ->with('status_pengguna', 'siswa.kelas')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('aktif_status_pengguna', '=', '1');
            })
            ->whereHas('siswa.kelas', function ($query) use ($id_kelas) {
                if ($id_kelas == "1") {
                    $query->whereIn('tingkat', [10, 7, 1]);
                } elseif ($id_kelas == "2") {
                    $query->whereIn('tingkat', [11, 8, 2]);
                } elseif ($id_kelas == "3") {
                    $query->whereIn('tingkat', [12, 9, 3]);
                } elseif ($id_kelas == "0") { } else {
                    $query->where('id_kelas', $id_kelas);
                }
            })
            ->get()->sortBy('siswa.kelas.nm_kelas');

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
            $hasil[$key]['kelas'] = $value->siswa->kelas->nm_kelas;

            $attendance = $allShiftPengguna->firstWhere('id_pengguna', $value->id_pengguna);
            if ($attendance) {

                if ($attendance->id_shift_master) {
                    $hasil[$key]['id_shift_master'] = $attendance->id_shift_master;
                    $shiftM = $allShiftMater->firstWhere('code', $attendance->id_shift_master);
                    $hasil[$key]['time'] = minimalisTime($shiftM['start_time']) . " - " . minimalisTime($shiftM['end_time']);
                } else {
                    $hasil[$key]['id_shift_master'] = "-";
                }
                if ($attendance->id_shift_pengguna) {
                    $hasil[$key]['id_shift_pengguna'] = $attendance->id_shift_pengguna;
                }
            }
        }

        return view('humas/absensi/shift-pengguna/view-shift-siswa', compact('date', 'kelas', 'id_kelas', 'hasil'));
    }

    public function selectKelasShiftSiswa(Request $request)
    {
        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        return view('humas/absensi/shift-siswa/select-kelas-siswa', compact('kelas'));
    }

    public function addShiftSiswa(Request $request, $id_kelas)
    {
        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();

        $shifts = ShiftMaster::all();
        $penggunas = Pengguna::where('status_join_table', 3)
            ->with('status_pengguna', 'siswa.kelas')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('aktif_status_pengguna', '=', '1');
            })
            ->whereHas('siswa.kelas', function ($query) use ($id_kelas) {
                if ($id_kelas == "1") {
                    $query->whereIn('tingkat', [10, 7, 1]);
                } elseif ($id_kelas == "2") {
                    $query->whereIn('tingkat', [11, 8, 2]);
                } elseif ($id_kelas == "3") {
                    $query->whereIn('tingkat', [12, 9, 3]);
                } else {
                    $query->where('id_kelas', $id_kelas);
                }
            })
            ->get()->sortBy('siswa.kelas.nm_kelas')->sortBy('username');

        $date =  Carbon::now()->format('Y-m-d');
        $shiftsPengguna = ShiftPengguna::where('date', $date)->get();
        return view('humas/absensi/shift-siswa/add-shift-siswa', compact('kelas', 'id_kelas', 'shifts', 'penggunas', 'shiftsPengguna'));
    }

    public function exportShift(Request $request, $id_kelas, $date)
    {
        set_time_limit(-1);

        if ($id_kelas == '0') {
            $pengguna = pengguna::where('status_join_table', 3)
                ->with('siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->get();
        } else {
            $pengguna = pengguna::where('status_join_table', 3)
                ->with('siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })
                ->get();
        }


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
            $hasil[$key1]['kelas'] = $value->siswa->kelas->nm_kelas;

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
