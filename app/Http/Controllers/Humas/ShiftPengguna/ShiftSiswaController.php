<?php

namespace App\Http\Controllers\Humas\ShiftPengguna;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShiftSiswaController extends Controller
{
    public function selectKelas(Request $request){
        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $date =  Carbon::now()->format('Y-m-d');
        return view('humas/absensi/shift-pengguna/select-shift-siswa', compact('date', 'kelas'));
    }

    public function viewShiftSiswa(Request $request, $id_kelas, $date){

        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $date =  Carbon::parse($date)->format('Y-m-d');
        return view('humas/absensi/shift-pengguna/add-shift-siswa', compact('date', 'kelas','id_kelas'));
    }
}
