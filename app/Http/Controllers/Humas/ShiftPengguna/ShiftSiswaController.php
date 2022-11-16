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
            ->whereHas('siswa', function ($query) use ($id_kelas) {
                if ($id_kelas != "0") {
                    $query->where('id_kelas', '=', $id_kelas);
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

    public function selectKelasShiftSiswa(Request $request){
        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        return view('humas/absensi/shift-siswa/select-kelas-siswa', compact( 'kelas'));
    }

    public function addShiftSiswa(Request $request, $id_kelas){
        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();

        $shifts = ShiftMaster::all();
        $penggunas = Pengguna::where('status_join_table', 3)
        ->with('status_pengguna', 'siswa.kelas')
        ->whereHas('status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })
        ->whereHas('siswa', function ($query) use ($id_kelas) {
            if ($id_kelas != "0") {
                $query->where('id_kelas', '=', $id_kelas);
            }
        })
        ->get()->sortBy('siswa.kelas.nm_kelas');

        $date =  Carbon::now()->format('Y-m-d');

        $shiftsPengguna = ShiftPengguna::where('date', $date)->get();


        return view('humas/absensi/shift-siswa/add-shift-siswa', compact( 'kelas', 'id_kelas','shifts', 'penggunas', 'shiftsPengguna'));
    }

}
