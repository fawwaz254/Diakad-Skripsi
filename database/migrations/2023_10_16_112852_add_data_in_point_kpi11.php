<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;


class AddDataInPointKpi11 extends Migration
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
            //!-----------------------------------------no 1
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Praktek Wudhu";
            $point_kpi->tingkat_kelas = '12';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu melakukan wudhu dengan sempurna",
                'B' => "Mampu melakukan wudhu dengan sempurna",
                'C' => "Cukup mampu melakukan wudhu dengan sempurna",
                'D' => "kurang mampu melakukan wudhu dengan sempurna",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!-----------------------------------------no 2
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Praktek Tayammum";
            $point_kpi->tingkat_kelas = '12';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu melakukan tayammum dengan sempurna",
                'B' => "Mampu melakukan tayammum dengan sempurna",
                'C' => "Cukup mampu melakukan tayammum dengan sempurna",
                'D' => "kurang mampu melakukan tayammum dengan sempurna",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!-----------------------------------------no 3
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Praktek Sholat Shubuh";
            $point_kpi->tingkat_kelas = '12';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu melakukan sholat subuh dengan benar",
                'B' => "Mampu melakukan sholat subuh dengan benar",
                'C' => "Cukup mampu melakukan sholat subuh dengan benar",
                'D' => "kurang mampu melakukan sholat subuh dengan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!-----------------------------------------no 4
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Praktek Wirid";
            $point_kpi->tingkat_kelas = '12';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu membaca wirid dengan benar dan lancar",
                'B' => "Mampu membaca wirid dengan benar dan lancar",
                'C' => "Cukup mampu membaca wirid dengan benar dan lancar",
                'D' => "kurang mampu membaca wirid dengan benar dan lancar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!-----------------------------------------no 5
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Praktek Sholat Jenazah";
            $point_kpi->tingkat_kelas = '12';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu melakukan sholat jenazah benar",
                'B' => "Mampu melakukan sholat jenazah benar",
                'C' => "Cukup mampu melakukan sholat jenazah benar",
                'D' => "kurang mampu melakukan sholat jenazah benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!-----------------------------------------no 6
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Praktek Hafalan Bacaan dan Do'anya Tahlil";
            $point_kpi->tingkat_kelas = '12';
            $point_kpi->urutan = '6';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal bacaan dan do'anya tahlil dengan benar dan lancar",
                'B' => "Mampu menghafal bacaan dan do'anya tahlil dengan benar dan lancar",
                'C' => "Cukup mampu menghafal bacaan dan do'anya tahlil dengan benar dan lancar",
                'D' => "kurang mampu menghafal bacaan dan do'anya tahlil dengan benar dan lancar",
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
