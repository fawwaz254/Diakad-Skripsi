<?php

namespace App\Libraries\SumberDaya;

use App\Models\Staff as Staff;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibTendik
{
    /** ALL GTENDIK **/
	static function fetchDataAllTendik($auth_data, $id = null) {

        // get all tendik
        if ($id == null) {
            $tendik = Staff::select('staff.id_staff', 'staff.id_pengguna', 'pengguna.id_status_pengguna', 'staff.jenis_jabatan', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'staff.nip_staff', 'unit_kerja.nm_unit_kerja', 'status_pengguna.nm_status_pengguna')
                    ->join('pengguna','pengguna.id_pengguna','=','staff.id_pengguna')
                    ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                    ->join('unit_kerja','unit_kerja.id_unit_kerja','=','staff.id_unit_kerja')
                    ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->where('status_pengguna.aktif_status_pengguna','=',1)
                    ->orderBy('pengguna.nm_pengguna', 'asc')
                    ->get();
        }
        // get mode edit
        else {
            $tendik = Staff::select('staff.*','pengguna.id_status_pengguna', 'pengguna.nm_pengguna','pengguna.id_status_pengguna','pengguna.gelar_depan','pengguna.gelar_belakang')
                    ->join('pengguna','pengguna.id_pengguna','=','staff.id_pengguna')
                    ->where('staff.id_staff','=',$id)
                    ->first();
        }

        return $tendik;
    }
    /** ========== **/


}