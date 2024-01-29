<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;
use Carbon\Carbon;

class AddDataInPointKpiSmp6 extends Migration
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
            $point_kpi->nm_point_kpi = "Adab masuk masjid & doa";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan adab masuk masjid & doa",
                'B' => "Mampu mempraktikkan adab masuk masjid & doa",
                'C' => "Cukup mampu mempraktikkan adab masuk masjid & doa",
                'D' => "kurang mampu mempraktikkan adab masuk masjid & doa",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------2
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Adab keluar masjid & doa";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan adab keluar masjid & doa",
                'B' => "Mampu mempraktikkan adab keluar masjid & doa",
                'C' => "Cukup mampu mempraktikkan adab keluar masjid & doa",
                'D' => "kurang mampu mempraktikkan adab keluar masjid & doa",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------3
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Niat Iktikaf";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafalkan secara fasih niat Iktikaf",
                'B' => "Mampu menghafalkan secara fasih niat Iktikaf",
                'C' => "Cukup mampu menghafalkan secara fasih niat Iktikaf",
                'D' => "kurang mampu menghafalkan secara fasih niat Iktikaf",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------4
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Hal-hal yg disunnah sebelum shalat jum'at";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan hal-hal yg disunnah sebelum shalat jum'at",
                'B' => "Mampu mempraktikkan hal-hal yg disunnah sebelum shalat jum'at",
                'C' => "Cukup mampu mempraktikkan hal-hal yg disunnah sebelum shalat jum'at",
                'D' => "kurang mampu mempraktikkan hal-hal yg disunnah sebelum shalat jum'at",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------5
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Macam-macam darah wanita & ketentuannya";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan Macam-macam darah wanita & ketentuannya",
                'B' => "Mampu menjelaskan Macam-macam darah wanita & ketentuannya",
                'C' => "Cukup mampu menjelaskan Macam-macam darah wanita & ketentuannya",
                'D' => "kurang mampu menjelaskan Macam-macam darah wanita & ketentuannya",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------6
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Qadla shalat bagi wanita haidl / nifas";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '6';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan qadla shalat bagi wanita haidl / nifas",
                'B' => "Mampu menjelaskan qadla shalat bagi wanita haidl / nifas",
                'C' => "Cukup mampu menjelaskan qadla shalat bagi wanita haidl / nifas",
                'D' => "kurang mampu menjelaskan qadla shalat bagi wanita haidl / nifas",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------7
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Shalawat Munjiyat";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '7';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafalkan secara fasih Shalawat Munjiyat",
                'B' => "Mampu menghafalkan secara fasih Shalawat Munjiyat",
                'C' => "Cukup mampu menghafalkan secara fasih Shalawat Munjiyat",
                'D' => "kurang mampu menghafalkan secara fasih Shalawat Munjiyat",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------8
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Shalawat Nariyah";
            $point_kpi->tingkat_kelas = '8';
            $point_kpi->urutan = '8';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafalkan secara fasih Shalawat Nariyah",
                'B' => "Mampu menghafalkan secara fasih Shalawat Nariyah",
                'C' => "Cukup mampu menghafalkan secara fasih Shalawat Nariyah",
                'D' => "kurang mampu menghafalkan secara fasih Shalawat Nariyah",
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
