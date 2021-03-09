<?php

namespace App\Http\Controllers\Kesiswaan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kelas;
use App\Models\Siswa;

use Auth;
use DB;
use Session;

class WelcomeController extends BaseController{
    public function indexWelcome(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_tingkat = Kelas::select('tingkat')->distinct()->orderBy('tingkat', 'asc')->get();
        $count_siswa = Siswa::with('pengguna')->whereHas('pengguna.status_pengguna', function($q){
            $q->where('aktif_status_pengguna', 1);
        })->whereNotNull('id_kelas')->count();

        $last_siswa = Siswa::select('created_at')->orderBy('created_at', 'desc')->first();

        $jenis_kelamin = Siswa::select('jenis_kelamin', DB::raw('count(*) as user_count'))
                                ->join('calon_siswa_baru', function($join){
                                    $join->on('calon_siswa_baru.id_c_siswa', 'siswa.id_c_siswa');
                                    $join->whereNull('calon_siswa_baru.deleted_at');
                                })
                                ->with('pengguna')->whereHas('pengguna.status_pengguna', function($q){
                                    $q->where('aktif_status_pengguna', 1);
                                })
                                ->groupBy('jenis_kelamin')
                                ->get();

        return view('kesiswaan/welcome', compact('auth_data', 'data_tingkat', 'count_siswa', 'last_siswa', 'jenis_kelamin'));
    }

}