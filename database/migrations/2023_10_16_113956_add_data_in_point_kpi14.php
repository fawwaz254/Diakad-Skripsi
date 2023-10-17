<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;


class AddDataInPointKpi14 extends Migration
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
        $kelompok_kpi4 = KelompokKPI::where('urutan', 4)->first();
        if ($kelompok_kpi4) {
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "As Syamsu (Q.S. 91)";
            $point_kpi->tingkat_kelas = '12';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal As Syamsu (Q.S. 91)",
                'B' => "Lancar menghafal As Syamsu (Q.S. 91)",
                'C' => "Cukup lancar menghafal As Syamsu (Q.S. 91)",
                'D' => "Kurang lancar menghafal As Syamsu (Q.S. 91)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Ad Dhuha (Q.S. 93)";
            $point_kpi->tingkat_kelas = '12';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Ad Dhuha (Q.S. 93)",
                'B' => "Lancar menghafal Ad Dhuha (Q.S. 93)",
                'C' => "Cukup lancar menghafal Ad Dhuha (Q.S. 93)",
                'D' => "Kurang lancar menghafal Ad Dhuha (Q.S. 93)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Yaasin (1 s/d 83)";
            $point_kpi->tingkat_kelas = '12';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Yaasin (1 s/d 83)",
                'B' => "Lancar menghafal Yaasin (1 s/d 83)",
                'C' => "Cukup lancar menghafal Yaasin (1 s/d 83)",
                'D' => "Kurang lancar menghafal Yaasin (1 s/d 83)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Al Alaq (Q.S. 99)";
            $point_kpi->tingkat_kelas = '12';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al Alaq (Q.S. 99)",
                'B' => "Lancar menghafal Al Alaq (Q.S. 99)",
                'C' => "Cukup lancar menghafal Al Alaq (Q.S. 99)",
                'D' => "Kurang lancar menghafal Al Alaq (Q.S. 99)",
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
