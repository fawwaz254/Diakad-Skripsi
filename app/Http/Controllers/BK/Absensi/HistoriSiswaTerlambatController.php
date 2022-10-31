<?php

namespace App\Http\Controllers\BK\Absensi;

use App\Http\Controllers\Controller;
use App\Models\PresensiPengguna;
use App\Models\ShiftMaster;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoriSiswaTerlambatController extends Controller
{  
    public function viewSiswaTerlambat(Request $request, $date = null){
        if (empty($date)) {
            $now = Carbon::now(env('APP_TIMEZONE', ''))->toDateString();
        }
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $absensi_siswa = ShiftMaster::where('type', 'Siswa')->first();
        $now = Carbon::parse($date)->toDateString();
        // dd($now);
        $terlambat = PresensiPengguna::with('pengguna.siswa.kelas')->where('date', $now)->where('status_join_table', 3)->whereTime('check_in','>=',$absensi_siswa->start_time )->get();
        // $belum_datang = 
        // dd($terlambat);

        return view('bk/absensi/view-absensi-terlambat', compact('auth_data', 'terlambat', 'now', 'absensi_siswa'));
        
    }

}
