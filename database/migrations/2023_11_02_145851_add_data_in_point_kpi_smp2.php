<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;


class AddDataInPointKpiSmp2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $sekolah = Sekolah::first();
        $semester = Semester::where('is_aktif_semester', '1')->first();
        $kelompok_kpi1 = KelompokKPI::where('urutan', 1)->first();
        if ($kelompok_kpi1) {
            //------------------------------nomor 2
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Menjelaskan cara mencuci najis";
            $point_kpi->tingkat_kelas = '7';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan cara mencuci najis",
                'B' => "Mampu menjelaskan cara mencuci najis",
                'C' => "Cukup mampu menjelaskan cara mencuci najis",
                'D' => "kurang mampu menjelaskan cara mencuci najis",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------ nomor 3
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Menjelaskan cara istinja";
            $point_kpi->tingkat_kelas = '7';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan cara istinja",
                'B' => "Mampu menjelaskan cara istinja",
                'C' => "Cukup mampu menjelaskan cara istinja",
                'D' => "kurang mampu menjelaskan cara istinja",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------ nomor 4
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Menghapal dan mengartikan niat wudlu, niat tayamum";
            $point_kpi->tingkat_kelas = '7';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghapal dan mengartikan niat wudlu, niat tayamum",
                'B' => "Mampu menghapal dan mengartikan niat wudlu, niat tayamum",
                'C' => "Cukup mampu menghapal dan mengartikan niat wudlu, niat tayamum",
                'D' => "kurang mampu menghapal dan mengartikan niat wudlu, niat tayamum",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------ nomor 5
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Mempraktikkan wudlu dan do'anya";
            $point_kpi->tingkat_kelas = '7';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan wudlu dan do'anya",
                'B' => "Mampu mempraktikkan wudlu dan do'anya",
                'C' => "Cukup mampu mempraktikkan wudlu dan do'anya",
                'D' => "kurang mampu mempraktikkan wudlu dan do'anya",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------ nomor 6
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Mempraktikkan tayamum dan do'anya";
            $point_kpi->tingkat_kelas = '7';
            $point_kpi->urutan = '6';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan tayamum dan do'anya",
                'B' => "Mampu mempraktikkan tayamum dan do'anya",
                'C' => "Cukup mampu mempraktikkan tayamum dan do'anya",
                'D' => "kurang mampu mempraktikkan tayamum dan do'anya",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
