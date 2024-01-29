<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;

class AddDataInPointKpiSmp13 extends Migration
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
            $point_kpi->nm_point_kpi = "Al-Adiyat";
            $point_kpi->tingkat_kelas = '9';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al-Adiyat",
                'B' => "Lancar menghafal Al-Adiyat",
                'C' => "Cukup lancar menghafal Al-Adiyat",
                'D' => "Kurang lancar menghafal Al-Adiyat",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Al-Qariah";
            $point_kpi->tingkat_kelas = '9';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al-Qariah",
                'B' => "Lancar menghafal Al-Qariah",
                'C' => "Cukup lancar menghafal Al-Qariah",
                'D' => "Kurang lancar menghafal Al-Qariah",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Al-Zalzalah";
            $point_kpi->tingkat_kelas = '9';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al-Zalzalah",
                'B' => "Lancar menghafal Al-Zalzalah",
                'C' => "Cukup lancar menghafal Al-Zalzalah",
                'D' => "Kurang lancar menghafal Al-Zalzalah",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Hafalan S. Yaasin : 1 s/d 8";
            $point_kpi->tingkat_kelas = '9';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Hafalan S. Yaasin : 1 s/d 8",
                'B' => "Lancar menghafal Hafalan S. Yaasin : 1 s/d 8",
                'C' => "Cukup lancar menghafal Hafalan S. Yaasin : 1 s/d 8",
                'D' => "Kurang lancar menghafal Hafalan S. Yaasin : 1 s/d 8",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Pengulangan Semester 1 s/d 4";
            $point_kpi->tingkat_kelas = '9';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Pengulangan Semester 1 s/d 4",
                'B' => "Lancar menghafal Pengulangan Semester 1 s/d 4",
                'C' => "Cukup lancar menghafal Pengulangan Semester 1 s/d 4",
                'D' => "Kurang lancar menghafal Pengulangan Semester 1 s/d 4",
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
