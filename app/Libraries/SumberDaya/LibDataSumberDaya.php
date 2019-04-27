<?php

namespace App\Libraries\SumberDaya;

use App\Models\UnitKerja as UnitKerja;
use App\Models\StatusPengguna as StatusPengguna;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibDataSumberDaya
{
    /** UNIT KERJA **/
	static function fetchDataUnitKerja($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $unitKerja = UnitKerja::select('unit_kerja.id_unit_kerja', 'unit_kerja.nm_unit_kerja', 'unit_kerja.deskripsi_unit_kerja', 'unit_kerja.tipe_unit_kerja', 'uk.nm_unit_kerja as nm_unit_kerja_induk', 'unit_kerja.nm_singkatan_unit')
                    ->leftJoin('unit_kerja as uk','uk.id_unit_kerja','=','unit_kerja.id_unit_kerja_induk')
                    ->where('unit_kerja.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('unit_kerja.nm_unit_kerja', 'asc')
                    ->get();
        }
        // get mode edit
        else{
            $unitKerja = UnitKerja::where('unit_kerja.id_unit_kerja','=',$id)->first();
        }

        return $unitKerja;
    }
    /** ========== **/

    /** STATUS AKTIF GURU **/
    static function fetchDataStatusAktifGuru($auth_data, $id = null){
        // get mode view
        if ($id == null){
            $statusPengguna = StatusPengguna::select('id_status_pengguna', 'nm_status_pengguna', 'aktif_status_pengguna')
                    ->where('status_join_table','=',2)
                    ->where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('aktif_status_pengguna', 'asc')
                    ->orderBy('nm_status_pengguna', 'asc')
                    ->get();
        }
        // get mode edit
        else{
            $statusPengguna = StatusPengguna::where('id_status_pengguna','=',$id)->first();
        }

        return $statusPengguna;
    }
    /** ========== **/

    /** STATUS AKTIF TENDIK **/
    static function fetchDataStatusAktifTendik($auth_data, $id = null){
        // get mode view
        if ($id == null){
            $statusPengguna = StatusPengguna::select('id_status_pengguna', 'nm_status_pengguna', 'aktif_status_pengguna')
                    ->where('status_join_table','=',1)
                    ->where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('aktif_status_pengguna', 'asc')
                    ->orderBy('nm_status_pengguna', 'asc')
                    ->get();
        }
        // get mode edit
        else{
            $statusPengguna = StatusPengguna::where('id_status_pengguna','=',$id)->first();
        }

        return $statusPengguna;
    }
    /** ========== **/

}