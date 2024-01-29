<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;

class AddDataInPointKpiSmp10 extends Migration
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
        $kelompok_kpi1 = KelompokKPI::where('urutan', 1)->first();
        if ($kelompok_kpi1) {
            //------------------------------1
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Pelaksanaan wudlu";
            $point_kpi->tingkat_kelas = '9';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan pelaksanaan wudlu",
                'B' => "Mampu mempraktikkan pelaksanaan wudlu",
                'C' => "Cukup mampu mempraktikkan pelaksanaan wudlu",
                'D' => "kurang mampu mempraktikkan pelaksanaan wudlu",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------2
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Pelaksanaan tayammum";
            $point_kpi->tingkat_kelas = '9';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan pelaksanaan tayammum",
                'B' => "Mampu mempraktikkan pelaksanaan tayammum",
                'C' => "Cukup mampu mempraktikkan pelaksanaan tayammum",
                'D' => "kurang mampu mempraktikkan pelaksanaan tayammum",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------3
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Praktik shalat shubuh";
            $point_kpi->tingkat_kelas = '9';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan praktik shalat shubuh",
                'B' => "Mampu mempraktikkan praktik shalat shubuh",
                'C' => "Cukup mampu mempraktikkan praktik shalat shubuh",
                'D' => "kurang mampu mempraktikkan praktik shalat shubuh",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------4
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Menghapal wirid setelah shalat dan do'a";
            $point_kpi->tingkat_kelas = '9';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghapal wirid setelah shalat dan do'a",
                'B' => "Mampu menghapal wirid setelah shalat dan do'a",
                'C' => "Cukup mampu menghapal wirid setelah shalat dan do'a",
                'D' => "kurang mampu menghapal wirid setelah shalat dan do'a",
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
