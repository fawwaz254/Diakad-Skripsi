<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;


class AddDataInPointKpi10 extends Migration
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
            $point_kpi->nm_point_kpi = "AL fiil (Q.S. 105)";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal AL fiil (Q.S. 105)",
                'B' => "Lancar menghafal AL fiil (Q.S. 105)",
                'C' => "Cukup lancar menghafal AL fiil (Q.S. 105)",
                'D' => "Kurang lancar menghafal AL fiil (Q.S. 105)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Al Humazah (Q.S. 104)";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Al Humazah (Q.S. 104)",
                'B' => "Lancar menghafal Al Humazah (Q.S. 104)",
                'C' => "Cukup lancar menghafal Al Humazah (Q.S. 104)",
                'D' => "Kurang lancar menghafal Al Humazah (Q.S. 104)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "At Takatsur (Q.S. 102)";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal At Takatsur (Q.S. 102)",
                'B' => "Lancar menghafal At Takatsur (Q.S. 102)",
                'C' => "Cukup lancar menghafal At Takatsur (Q.S. 102)",
                'D' => "Kurang lancar menghafal At Takatsur (Q.S. 102)",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //--------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Yaasin : 28 s/d 40";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Yaasin : 28 s/d 40",
                'B' => "Lancar menghafal Yaasin : 28 s/d 40",
                'C' => "Cukup lancar menghafal Yaasin : 28 s/d 40",
                'D' => "Kurang lancar menghafal Yaasin : 28 s/d 40",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //-------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Pengulangan materi smt 1 & 2";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat lancar menghafal Pengulangan materi smt 1 & 2",
                'B' => "Lancar menghafal Pengulangan materi smt 1 & 2",
                'C' => "Cukup lancar menghafal Pengulangan materi smt 1 & 2",
                'D' => "Kurang lancar menghafal Pengulangan materi smt 1 & 2",
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
