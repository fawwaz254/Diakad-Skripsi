<?php

namespace App\Libraries\Pendidikan;

use App\Models\Kelas as Kelas;
use App\Models\RuanganKelas as RuanganKelas;
use App\Models\Semester as Semester;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use DB;

class LibKelas
{
    /** KELAS **/
	static function fetchDataKelas($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $semester_aktif = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->where('is_aktif_semester','=',1)->first();

            $kelas = Kelas::select('p3.nm_pengguna as nama_guru_bk','kelas.id_kelas', 'jurusan.nm_jurusan', 'kelas.nm_kelas', 'kelas.tingkat', 'kelas.keterangan_kelas', 'p1.nm_pengguna as nm_sekretaris', 'ruangan.nm_ruangan', 'p2.nm_pengguna as nm_wali_kelas', 'p2.gelar_depan as gelar_depan_wali_kelas', 'p2.gelar_belakang as gelar_belakang_wali_kelas', DB::raw("(SELECT COUNT(*) FROM siswa WHERE siswa.id_kelas = kelas.id_kelas AND siswa.deleted_at IS NULL) AS total_siswa"))
                ->join('jurusan','jurusan.id_jurusan','=','kelas.id_jurusan')
                ->leftJoin('ruangan_kelas', function ($join) use ($semester_aktif) {
                    $join->on('ruangan_kelas.id_kelas', '=', 'kelas.id_kelas')
                         ->where('ruangan_kelas.is_aktif', '=', 1)
                         ->where('ruangan_kelas.id_semester', '=', $semester_aktif->id_semester);
                })
                ->leftJoin('ruangan','ruangan.id_ruangan','=','ruangan_kelas.id_ruangan')
                ->leftJoin('sekretaris_kelas', function ($join) use ($semester_aktif) {
                    $join->on('sekretaris_kelas.id_kelas', '=', 'kelas.id_kelas')
                         ->where('sekretaris_kelas.is_aktif', '=', 1)
                         ->where('sekretaris_kelas.id_semester', '=', $semester_aktif->id_semester);
                })
                ->leftJoin('siswa','siswa.id_siswa','=','sekretaris_kelas.id_siswa')
                ->leftJoin('pengguna as p1','p1.id_pengguna','=','siswa.id_pengguna')
                ->leftJoin('wali_kelas', function ($join) use ($semester_aktif) {
                    $join->on('wali_kelas.id_kelas', '=', 'kelas.id_kelas')
                         ->where('wali_kelas.is_aktif', '=', 1)
                         ->where('wali_kelas.id_semester', '=', $semester_aktif->id_semester)
                         ->whereNull('wali_kelas.deleted_at');
                })
                ->leftJoin('bk_kelas', function ($join) use ($semester_aktif) {
                    $join->on('bk_kelas.id_kelas', '=', 'kelas.id_kelas')
                        ->whereNull('bk_kelas.deleted_at')
                        ->where('bk_kelas.is_aktif', '=', 1)
                        ->where('bk_kelas.id_semester', '=', $semester_aktif->id_semester);
                })
                ->leftJoin('guru','guru.id_guru','=','wali_kelas.id_guru')
                ->leftJoin('pengguna as p2','p2.id_pengguna','=','guru.id_pengguna')
                ->leftJoin('pengguna as p3','p3.id_pengguna','=','bk_kelas.id_pengguna')
                ->where('jurusan.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                ->orderBy('jurusan.kode_jurusan', 'asc')
                ->orderBy('kelas.tingkat', 'asc')
                ->orderBy('kelas.nm_kelas', 'asc')
                ->get();
        }
        // get mode edit
        else{
            $kelas = Kelas::where('kelas.id_kelas','=',$id)->first();
        }

        return $kelas;
    }
    /** ========== **/

    /** RUANGAN KELAS **/
    static function fetchDataRuanganKelas($auth_data, $id_kelas, $id_semester = null, $id = null){

        // get mode view
        if ($id == null){
            if(! empty($id_semester)) {
                $ruanganKelas = RuanganKelas::select('ruangan_kelas.id_ruangan_kelas', 'ruangan_kelas.id_kelas','ruangan_kelas.id_semester', 'ruangan.id_ruangan', 'kelas.nm_kelas', 'ruangan.nm_ruangan', 'semester.tahun_ajaran', 'semester.nm_semester', 'ruangan_kelas.is_aktif')
                    ->join('kelas','kelas.id_kelas','=','ruangan_kelas.id_kelas')
                    ->join('ruangan', function ($join) {
                        $join->on('ruangan.id_ruangan', '=', 'ruangan_kelas.id_ruangan')
                             ->where('ruangan.is_aktif', '=', 1);
                    })
                    ->join('semester','semester.id_semester','=','ruangan_kelas.id_semester')
                    ->where('ruangan_kelas.id_kelas','=',$id_kelas)
                    ->where('ruangan_kelas.id_semester','=',$id_semester)
                    ->orderBy('semester.thn_akademik_semester', 'asc')
                    ->orderBy('semester.nm_semester', 'asc')
                    ->orderBy('ruangan.nm_ruangan', 'asc')
                    ->first();
            }
            else {
                $ruanganKelas = RuanganKelas::select('ruangan_kelas.id_ruangan_kelas', 'ruangan_kelas.id_kelas','ruangan_kelas.id_semester', 'kelas.nm_kelas', 'ruangan.nm_ruangan', 'semester.tahun_ajaran', 'semester.nm_semester', 'ruangan_kelas.is_aktif')
                    ->join('kelas','kelas.id_kelas','=','ruangan_kelas.id_kelas')
                    ->join('ruangan', function ($join) {
                        $join->on('ruangan.id_ruangan', '=', 'ruangan_kelas.id_ruangan')
                             ->where('ruangan.is_aktif', '=', 1);
                    })
                    ->join('semester','semester.id_semester','=','ruangan_kelas.id_semester')
                    ->where('ruangan_kelas.id_kelas','=',$id_kelas)
                    ->orderBy('semester.thn_akademik_semester', 'asc')
                    ->orderBy('semester.nm_semester', 'asc')
                    ->orderBy('ruangan.nm_ruangan', 'asc')
                    ->get();
            }
        }
        // get mode edit
        else{
            $ruanganKelas = RuanganKelas::where('ruangan_kelas.id_ruangan_kelas','=',$id)->first();
        }

        return $ruanganKelas;
    }

}