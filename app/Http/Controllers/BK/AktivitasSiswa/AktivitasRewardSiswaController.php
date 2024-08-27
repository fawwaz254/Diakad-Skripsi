<?php

namespace App\Http\Controllers\BK\AktivitasSiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AktivitasRewardSiswaController extends Controller
{
    public function viewAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();

        return view('bk/aktivitas-siswa/view-aktivitas-reward-siswa');
    }

    public function addAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();

        return view('bk/aktivitas-siswa/view-add-aktivitas-reward-siswa');
    }
}
