<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;

class AddDataInPointKpi6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();
        $sekolah = Sekolah::first();
        $semester = Semester::where('is_aktif_semester', '1')->first();

        $point_kpi = new PointKPI;
        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
        $point_kpi->id_kelompok_kpi = null;
        $point_kpi->id_semester = $semester->id_semester;
        $point_kpi->nm_point_kpi = "Sertifikasi";
        $point_kpi->tingkat_kelas = null;
        $point_kpi->urutan = '1';
        $point_kpi->deskripsi =  null;
        $point_kpi->jenis = '3';
        $point_kpi->save();
        $point_kpi = null;
        //--------------------------------------------
        $point_kpi = new PointKPI;
        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
        $point_kpi->id_kelompok_kpi = null;
        $point_kpi->id_semester = $semester->id_semester;
        $point_kpi->nm_point_kpi = "Nilai Sertifikasi";
        $point_kpi->tingkat_kelas = null;
        $point_kpi->urutan = '2';
        $point_kpi->deskripsi =  null;
        $point_kpi->jenis = '3';
        $point_kpi->save();
        $point_kpi = null;
        //--------------------------------------------
        $point_kpi = new PointKPI;
        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
        $point_kpi->id_kelompok_kpi = null;
        $point_kpi->id_semester = $semester->id_semester;
        $point_kpi->nm_point_kpi = "Tingkat/Jilid";
        $point_kpi->tingkat_kelas = null;
        $point_kpi->urutan = '3';
        $point_kpi->deskripsi =  null;
        $point_kpi->jenis = '3';
        $point_kpi->save();
        $point_kpi = null;
        //--------------------------------------------
        $point_kpi = new PointKPI;
        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
        $point_kpi->id_kelompok_kpi = null;
        $point_kpi->id_semester = $semester->id_semester;
        $point_kpi->nm_point_kpi = "Nilai";
        $point_kpi->tingkat_kelas = null;
        $point_kpi->urutan = '4';
        $point_kpi->deskripsi =  null;
        $point_kpi->jenis = '3';
        $point_kpi->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
