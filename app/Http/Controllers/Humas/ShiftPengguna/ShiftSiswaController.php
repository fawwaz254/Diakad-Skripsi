<?php

namespace App\Http\Controllers\Humas\ShiftPengguna;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShiftSiswaController extends Controller
{
    public function selectKelas(Request $request)
    {
        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $date =  Carbon::now()->format('Y-m-d');
        return view('humas/absensi/shift-pengguna/select-shift-siswa', compact('date', 'kelas'));
    }

    public function viewShiftSiswa(Request $request, $id_kelas, $date)
    {

        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
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
        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        return view('humas/absensi/shift-siswa/select-kelas-siswa', compact('kelas'));
    }

    public function addShiftSiswa(Request $request, $id_kelas)
    {
        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();

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
            ->get()->sortBy('siswa.kelas.nm_kelas');

        $date =  Carbon::now()->format('Y-m-d');
        $shiftsPengguna = ShiftPengguna::where('date', $date)->get();
        return view('humas/absensi/shift-siswa/add-shift-siswa', compact('kelas', 'id_kelas', 'shifts', 'penggunas', 'shiftsPengguna'));
    }

    // public function storeShiftSiswa(Request $request )
    // {
    //     set_time_limit(1800);
    //     $v = Validator::make($request->all(), [

    //         'pengguna' => 'required',
    //     ]);

    //     if ($v->fails()) {
    //         // return redirect()->back()->withErrors($v->errors());

    //         return [
    //             'status' => 300, // fail
    //             'message' => 'Harus pilih minimal 1 user'
    //         ];
    //         // $eror = ('Harus dicentang 1');
    //         // $pesan = "Harus dicentang salah satu";
    //         // return redirect("/humas#absensi/shift_pengguna/add/" . $pesan);
    //     }

    //     $input = (object) $request->input();


    //     //validasi cekin

    //     $pengguna = $input->pengguna;

    //     $startDate = new Carbon('first day of' . $input->firstMount . '2022');
    //     $endDate =  new Carbon('last day of' . $input->endMount . '2022');
    //     // $nameDay =  ;

    //     $prefix = Sekolah::first()->prefix;

    //     if ($startDate > $endDate) {
    //         return [
    //             'status' => 300, // fail
    //             'message' => 'Bulan awal harus lebih kecil dari bulan akhir'
    //         ];
    //     }


    //     $dates = CarbonPeriod::create($startDate, $endDate);
    //     foreach ($pengguna as $user) {
    //         foreach ($dates as $value) {
    //             $shiftPenggunaId = ShiftPengguna::where('id_pengguna', $user)
    //                 ->where('date', $value->format('Y-m-d'))
    //                 ->first();
    //             //validasi apakah sudah ada apa belum datanya
    //             if ($shiftPenggunaId) {
    //                 $dataUpdate['id_shift_master'] = $input->dayName[$value->format('l')];
    //                 $shiftPenggunaId->update($dataUpdate);
    //             } else {
    //                 $now = Carbon::now(env('APP_TIMEZONE', ''));
    //                 $html = '';
    //                 $list_data['id_shift_pengguna'] =   $html .= $prefix . strtotime($now) . uniqid();
    //                 $list_data['id_pengguna'] = $user;
    //                 $list_data['date'] =  $value->format('Y-m-d');
    //                 $list_data['id_shift_master'] = $input->dayName[$value->format('l')];

    //                 ShiftPengguna::create($list_data);
    //             }
    //         }
    //     }

    //     return [
    //         'status' => 202, // SUCCESS AND LOAD CONTENT
    //         'link' => '/humas#absensi/shift_pengguna',
    //         'message' => 'Tambah data Shift berhasil '

    //     ];
    // }

}
