<?php

namespace App\Libraries\Ppdb;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

use App\Models\Penerimaan as Penerimaan;

use Carbon\Carbon;
use Auth;
use DB;

/** 
 * Penawaran Jurusan Controller
 * @author irianto
 */
class LibPenerimaan
{
    /** 
     * Get data penerimaan by id and get all data penerimaan
     * @param String id_penerimaan
     * @return Object penerimaan
     */
    static function fetchDataPenerimaan($auth_data, $id = null)
    {
        if($id != null) {
            /** get data penerimaan by id_penerimaan */
            $penerimaan = Penerimaan::select('penerimaan.id_penerimaan', 'penerimaan.id_jalur', 'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.is_aktif', 'penerimaan.tgl_pengumuman', 'penerimaan.jenis_penerimaan', 'penerimaan.is_pendaftaran_online')
                    ->leftJoin('jalur','jalur.id_jalur','=','penerimaan.id_jalur')
                    ->leftJoin('semester','semester.id_semester','=','penerimaan.id_semester')
                    ->where('penerimaan.id_penerimaan','=',$id)
                    ->where('penerimaan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->first();
        } else {
            /** get all data penerimaan */
            $penerimaan = Penerimaan::select('penerimaan.id_penerimaan', 'penerimaan.id_jalur', 'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.is_aktif', 'penerimaan.tgl_pengumuman', 'penerimaan.jenis_penerimaan', 'penerimaan.is_pendaftaran_online')
                    ->leftJoin('jalur','jalur.id_jalur','=','penerimaan.id_jalur')
                    ->leftJoin('semester','semester.id_semester','=','penerimaan.id_semester')
                    ->where('penerimaan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->where('penerimaan.jenis_penerimaan','=','1')
                    ->orderBy('penerimaan.tahun_penerimaan', 'desc')
                    ->orderBy('penerimaan.nm_semester_penerimaan', 'asc')
                    ->orderBy('penerimaan.gelombang_penerimaan', 'asc')
                    ->orderBy('penerimaan.id_jalur', 'asc')
                    ->orderBy('penerimaan.nm_penerimaan', 'asc')
                    ->get();
        }
        return $penerimaan;
    }

    /** 
     * Get data penerimaan by id and get all data penerimaan
     * @param String id_penerimaan
     * @return Object penerimaan
     */
    static function fetchDataPenerimaanAllJenisPenerimaan($auth_data)
    {
        /** get all data penerimaan */
        $penerimaan = Penerimaan::select('penerimaan.id_penerimaan', 'penerimaan.id_jalur', 'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.is_aktif', 'penerimaan.tgl_pengumuman', 'penerimaan.jenis_penerimaan', 'penerimaan.is_pendaftaran_online')
                ->leftJoin('jalur','jalur.id_jalur','=','penerimaan.id_jalur')
                ->leftJoin('semester','semester.id_semester','=','penerimaan.id_semester')
                ->where('penerimaan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                ->orderBy('penerimaan.tahun_penerimaan', 'desc')
                ->orderBy('penerimaan.nm_semester_penerimaan', 'asc')
                ->orderBy('penerimaan.gelombang_penerimaan', 'asc')
                ->orderBy('penerimaan.id_jalur', 'asc')
                ->orderBy('penerimaan.nm_penerimaan', 'asc')
                ->get();

        return $penerimaan;
    }

    /** 
     * Get all data penerimaan 
     * Where not in $id_penerimaan 
     * Where same year
     * @param String id_penerimaan
     * @return Object penerimaan
     */
    static function fetchDataPindahPenerimaan($auth_data, $id)
    {
        $penerimaan = Penerimaan::select(
            'penerimaan.id_penerimaan', 'penerimaan.id_jalur',
            'penerimaan.id_semester', 'penerimaan.tahun_penerimaan', 'jalur.nm_jalur', 'penerimaan.nm_penerimaan', 'penerimaan.gelombang_penerimaan', 'penerimaan.nm_semester_penerimaan', 'penerimaan.is_aktif', 'penerimaan.tgl_pengumuman', 'penerimaan.is_pendaftaran_online')
            ->leftJoin('jalur','jalur.id_jalur','=','penerimaan.id_jalur')
            ->leftJoin('semester','semester.id_semester','=','penerimaan.id_semester')
            ->where('penerimaan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
            ->where('id_penerimaan', '!=', $id)
            ->orderBy('penerimaan.tahun_penerimaan', 'desc')
            ->orderBy('penerimaan.nm_semester_penerimaan', 'asc')
            ->orderBy('penerimaan.gelombang_penerimaan', 'asc')
            ->orderBy('penerimaan.id_jalur', 'asc')
            ->orderBy('penerimaan.nm_penerimaan', 'asc')
            ->get();

        return $penerimaan;
    }
  
}
