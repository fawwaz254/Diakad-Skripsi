<?php

namespace App\Libraries\Pendidikan;

use App\Models\Wisuda as Wisuda;
use App\Models\PeriodeWisuda as PeriodeWisuda;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibWisuda
{
    /** WISUDA **/
	static function fetchDataWisuda($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $wisuda = Wisuda::select('id_wisuda', 'nm_wisuda', 'keterangan_wisuda')
                        ->where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('nm_wisuda', 'asc')->get();
        }
        // get mode edit
        else{
            $wisuda = Wisuda::where('id_wisuda','=',$id)->first();
        }

        return $wisuda;
    }
    /** ========== **/

    /** PERIODE WISUDA **/
    static function fetchDataPeriodeWisuda($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $periodeWisuda = PeriodeWisuda::select('periode_wisuda.id_periode_wisuda', 'semester.tahun_ajaran', 'semester.nm_semester', 'wisuda.nm_wisuda', 'periode_wisuda.nm_periode_wisuda','periode_wisuda.besar_biaya', 'periode_wisuda.tgl_bayar_mulai', 'periode_wisuda.tgl_bayar_selesai', 'periode_wisuda.is_aktif')
                    ->join('wisuda','wisuda.id_wisuda','=','periode_wisuda.id_wisuda')
                    ->join('semester','semester.id_semester','=','periode_wisuda.id_semester')
                    ->where('wisuda.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('semester.tahun_ajaran', 'desc')
                    ->orderBy('semester.nm_semester', 'desc')
                    ->orderBy('periode_wisuda.tgl_bayar_mulai', 'desc')
                    ->get();
        }
        // get mode edit
        else{
            $periodeWisuda = PeriodeWisuda::join('semester','semester.id_semester','=','periode_wisuda.id_semester')->where('periode_wisuda.id_periode_wisuda','=',$id)->first();
        }

        return $periodeWisuda;
    }
    /** ========== **/


}