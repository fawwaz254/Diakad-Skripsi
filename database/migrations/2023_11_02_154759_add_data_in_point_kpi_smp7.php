<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;

class AddDataInPointKpiSmp7 extends Migration
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
        $kelompok_kpi2 = KelompokKPI::where('urutan', 2)->first();
        if ($kelompok_kpi2) {
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi2->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Subuh";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Rutin melaksanakan Shalat Subuh",
                'B' => "Sering melaksanakan Shalat Subuh",
                'C' => "Jarang melaksakan Shalat Subuh",
                'D' => "Tidak pernah melaksanakan Shalat Subuh",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi2->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Dhuhur";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Rutin melaksanakan Shalat Dhuhur",
                'B' => "Sering melaksanakan Shalat Dhuhur",
                'C' => "Jarang melaksakan Shalat Dhuhur",
                'D' => "Tidak pernah melaksanakan Shalat Dhuhur",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi2->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Ashar";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Rutin melaksanakan Shalat Ashar",
                'B' => "Sering melaksanakan Shalat Ashar",
                'C' => "Jarang melaksakan Shalat Ashar",
                'D' => "Tidak pernah melaksanakan Shalat Ashar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi2->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Maghrib";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Rutin melaksanakan Shalat Maghrib",
                'B' => "Sering melaksanakan Shalat Maghrib",
                'C' => "Jarang melaksakan Shalat Maghrib",
                'D' => "Tidak pernah melaksanakan Shalat Maghrib",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi2->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Isya'";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Rutin melaksanakan Shalat Isya'",
                'B' => "Sering melaksanakan Shalat Isya'",
                'C' => "Jarang melaksakan Shalat Isya'",
                'D' => "Tidak pernah melaksanakan Shalat Isya'",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------------
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
