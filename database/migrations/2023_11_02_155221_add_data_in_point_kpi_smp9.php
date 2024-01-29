<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;

class AddDataInPointKpiSmp9 extends Migration
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
        $kelompok_kpi4 = KelompokKPI::where('urutan', 4)->first();
        if ($kelompok_kpi4) {
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Al-Ma'un (Q.S: 117)";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al-Ma'un (Q.S: 117)",
                'B' => "Lancar menghafal Al-Ma'un (Q.S: 117)",
                'C' => "Cukup lancar menghafal Al-Ma'un (Q.S: 117)",
                'D' => "Kurang lancar menghafal Al-Ma'un (Q.S: 117)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Al-Qura'is (Q.S: 116)";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al-Qura'is (Q.S: 116)",
                'B' => "Lancar menghafal Al-Qura'is (Q.S: 116)",
                'C' => "Cukup lancar menghafal Al-Qura'is (Q.S: 116)",
                'D' => "Kurang lancar menghafal Al-Qura'is (Q.S: 116)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Al-Fiil (Q.S: 115)";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al-Fiil (Q.S: 115)",
                'B' => "Lancar menghafal Al-Fiil (Q.S: 115)",
                'C' => "Cukup lancar menghafal Al-Fiil (Q.S: 115)",
                'D' => "Kurang lancar menghafal Al-Fiil (Q.S: 115)",
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
    {
    }
}
