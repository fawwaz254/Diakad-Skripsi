<?php

namespace App\Libraries\Kesiswaan;

use App\Models\Ijazah;
use App\Models\Wisuda as Wisuda;
use App\Models\PeriodeWisuda as PeriodeWisuda;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibIjazah
{
    /** PENGAMBILAN IJAZAH **/
	static function fetchDataPengambilanIjazah($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $ijazah = Ijazah::select('id_ijazah',  
                                    'penerima_ijazah', 
                                    'catatan_ijazah', 
                                    'tgl_pengambilan_ijazah',
                                    'p_siswa.nm_pengguna as nm_siswa',
                                    'siswa.nis_siswa',
                                    DB::raw('concat(siswa.nis_siswa, " - ", p_siswa.nm_pengguna) as nm_pengguna'),
                                    'pengguna.nm_pengguna as pemberi_ijazah',
                                    'nomor_ijasah')
                        ->join('siswa', function($join){
                            $join->on('siswa.id_siswa', '=', 'ijazah.id_siswa');
                            $join->whereNull('siswa.deleted_at');
                        })
                        ->join('pengguna as p_siswa', function($join){
                            $join->on('siswa.id_pengguna', '=', 'p_siswa.id_pengguna');
                            $join->whereNull('p_siswa.deleted_at');
                        })
                        ->join('pengguna', function($join){
                            $join->on('pengguna.id_pengguna', '=', 'ijazah.id_pemberi_ijazah');
                            $join->whereNull('pengguna.deleted_at');
                        })
                        ->join('pengajuan_wisuda', function($join){
                            $join->on('pengajuan_wisuda.id_siswa', '=', 'siswa.id_siswa');
                            $join->whereNull('pengajuan_wisuda.deleted_at');
                        })
                        ->where('ijazah.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('ijazah.tgl_pengambilan_ijazah', 'desc')
                        ->get()
                        ->map(function($row){
                            $row->tgl_pengambilan_ijazah = Carbon::createFromTimeString($row->tgl_pengambilan_ijazah)->format('H:i - d F Y');
                            return $row;
                        });
        }
        // get mode edit
        else{
            $ijazah = Ijazah::with('siswa.pengguna', 'siswa.pengajuan_wisuda')->find($id);
        }

        return $ijazah;
    }
    /** ========== **/

}