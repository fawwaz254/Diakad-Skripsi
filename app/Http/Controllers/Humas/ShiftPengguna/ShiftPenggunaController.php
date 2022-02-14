<?php

namespace App\Http\Controllers\Humas\ShiftPengguna;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use App\Models\Pengguna;

class ShiftPenggunaController extends Controller
{
    public function viewShiftPengguna(Request $request)
    {
        return view('humas/absensi/shift-pengguna/view-shift-pengguna');
    }
    public function addShiftPengguna(Request $request)
    {
        $shifts = ShiftMaster::all();
        $penggunas = Pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')->orderBy('status_join_table', 'desc')->get();

        return view('humas/absensi/shift-pengguna/add-shift-pengguna', compact('shifts', 'penggunas'));
    }

    public function storeShiftPengguna(Request $request)
    {
        var_dump($request);
        return view('humas/absensi/shift-pengguna/view-shift-pengguna');
    }
}
