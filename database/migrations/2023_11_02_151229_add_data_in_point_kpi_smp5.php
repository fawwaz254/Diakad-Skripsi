<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;


class AddDataInPointKpiSmp5 extends Migration
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
            $point_kpi->nm_point_kpi = "An Nas (Q.S: 114)";
            $point_kpi->tingkat_kelas = '7';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal An Nas (Q.S: 114)",
                'B' => "Lancar menghafal An Nas (Q.S: 114)",
                'C' => "Cukup lancar menghafal An Nas (Q.S: 114)",
                'D' => "Kurang lancar menghafal An Nas (Q.S: 114)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Al Falaq (Q.S: 113)";
            $point_kpi->tingkat_kelas = '7';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al Falaq (Q.S: 113)",
                'B' => "Lancar menghafal Al Falaq (Q.S: 113)",
                'C' => "Cukup lancar menghafal Al Falaq (Q.S: 113)",
                'D' => "Kurang lancar menghafal Al Falaq (Q.S: 113)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Al Ikhlas (Q.S: 112)";
            $point_kpi->tingkat_kelas = '7';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al Ikhlas (Q.S: 112)",
                'B' => "Lancar menghafal Al Ikhlas (Q.S: 112)",
                'C' => "Cukup lancar menghafal Al Ikhlas (Q.S: 112)",
                'D' => "Kurang lancar menghafal Al Ikhlas (Q.S: 112)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Al Lahab (Q.S: 111)";
            $point_kpi->tingkat_kelas = '7';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al Lahab (Q.S: 111)",
                'B' => "Lancar menghafal Al Lahab (Q.S: 111)",
                'C' => "Cukup lancar menghafal Al Lahab (Q.S: 111)",
                'D' => "Kurang lancar menghafal Al Lahab (Q.S: 111)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //----------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Yaasin : 1 s/d 12";
            $point_kpi->tingkat_kelas = '7';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Yaasin : 1 s/d 12",
                'B' => "Lancar menghafal Yaasin : 1 s/d 12",
                'C' => "Cukup lancar menghafal Yaasin : 1 s/d 12",
                'D' => "Kurang lancar menghafal Yaasin : 1 s/d 12",
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
