<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;

use Carbon\Carbon;

class AddDataKpiKelas7SmpYpmInPointKpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('point_kpi', function (Blueprint $table) {
            try {
                DB::beginTransaction();

                $now = Carbon::now();
                $sekolah = Sekolah::first();
                $semester = Semester::where('is_aktif_semester', '1')->first(); // Get semester aktif
                $kelompok_kpi1 = KelompokKPI::where('urutan', 1)->first(); // Get kelompok kpi dengan urutan 1

                // Specify sekolah with nm_singkat_sekolah = 'smpypm1' and 'smpypm2'
                if ($sekolah->nm_singkat_sekolah == 'smpypm1' || $sekolah->nm_singkat_sekolah == 'smpypm2') {
                    if ($kelompok_kpi1) {
                        // urutan 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menjelaskan hal-hal yang mengharuskan mandi besar dan cara mandi besar";
                        $point_kpi->tingkat_kelas = '7';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan hal-hal yang mengharuskan mandi besar dan cara mandi besar",
                            'B' => "Mampu menjelaskan hal-hal yang mengharuskan mandi besar dan cara mandi besar",
                            'C' => "Cukup mampu menjelaskan hal-hal yang mengharuskan mandi besar dan cara mandi besar",
                            'D' => "Kurang mampu menjelaskan hal-hal yang mengharuskan mandi besar dan cara mandi besar",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 2
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menjelaskan hal-hal yang terlarang bagi orang yang berhadast kecil dan besar";
                        $point_kpi->tingkat_kelas = '7';
                        $point_kpi->urutan = '2';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan hal-hal yang terlarang bagi orang yang berhadast kecil dan besar",
                            'B' => "Mampu menjelaskan hal-hal yang terlarang bagi orang yang berhadast kecil dan besar",
                            'C' => "Cukup mampu menjelaskan hal-hal yang terlarang bagi orang yang berhadast kecil dan besar",
                            'D' => "Kurang mampu menjelaskan hal-hal yang terlarang bagi orang yang berhadast kecil dan besar",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 3
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Kecakapan menghapal lafal adzan dan iqamah beserta jawaban dan do'a setelah adzan";
                        $point_kpi->tingkat_kelas = '7';
                        $point_kpi->urutan = '3';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghapal lafal adzan dan iqamah beserta jawaban dan do'a setelah adzan",
                            'B' => "Mampu menghapal lafal adzan dan iqamah beserta jawaban dan do'a setelah adzan",
                            'C' => "Cukup mampu menghapal lafal adzan dan iqamah beserta jawaban dan do'a setelah adzan",
                            'D' => "Kurang mampu menghapal lafal adzan dan iqamah beserta jawaban dan do'a setelah adzan",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Mempraktekkan sholat subuh dengan bacaan yang fasih dan benar";
                        $point_kpi->tingkat_kelas = '7';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu mempraktekkan sholat subuh dengan bacaan yang fasih dan benar",
                            'B' => "Mampu mempraktekkan sholat subuh dengan bacaan yang fasih dan benar",
                            'C' => "Cukup mampu mempraktekkan sholat subuh dengan bacaan yang fasih dan benar",
                            'D' => "Kurang mampu mempraktekkan sholat subuh dengan bacaan yang fasih dan benar",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                echo 'Error message: ' . $e->getMessage();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_kpi', function (Blueprint $table) {
            //
        });
    }
}
