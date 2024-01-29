<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;

class AddDataInPointKpi2 extends Migration
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
            //------------------------------nomor 2
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Menjelaskan Macam-macam air";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan macam-macam air",
                'B' => "Mampu menjelaskan macam-macam air",
                'C' => "Cukup mampu menjelaskan macam-macam air",
                'D' => "kurang mampu menjelaskan macam-macam air",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------ nomor 3
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = 'Menjelaskan dan mempraktekkan cara menyucikan :';
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi = null;
            $point_kpi->jenis = '0';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------1
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Najis mukhaffafah";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan dan mempraktikkan cara menyucikan najis mukhaffafah",
                'B' => "Mampu menjelaskan dan mempraktikkan cara menyucikan najis mukhaffafah",
                'C' => "Cukup mampu menjelaskan dan mempraktikkan cara menyucikan najis mukhaffafah",
                'D' => "kurang mampu menjelaskan dan mempraktikkan cara menyucikan najis mukhaffafah",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------2
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Najis Mutawassithah";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan dan mempraktikkan cara menyucikan najis mutawasithah",
                'B' => "Mampu menjelaskan dan mempraktikkan cara menyucikan najis mutawasithah",
                'C' => "Cukup mampu menjelaskan dan mempraktikkan cara menyucikan najis mutawasithah",
                'D' => "kurang mampu menjelaskan dan mempraktikkan cara menyucikan najis mutawasithah",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------3
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "3. Najis Mughalladhah";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan dan mempraktikkan cara menyucikan najis mughalladhah",
                'B' => "Mampu menjelaskan dan mempraktikkan cara menyucikan najis mughalladhah",
                'C' => "Cukup mampu menjelaskan dan mempraktikkan cara menyucikan najis mughalladhah",
                'D' => "kurang mampu menjelaskan dan mempraktikkan cara menyucikan najis mughalladhah",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------nomor4
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Menjelaskan cara istinja'";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan cara istinja",
                'B' => "Mampu menjelaskan cara istinja",
                'C' => "Cukup mampu menjelaskan cara istinja",
                'D' => "kurang mampu menjelaskan cara istinja",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------nomor5
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Menyebutkan tatacara wudlu secara sempurna dan hal - hal yang  membatalkannya";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menyebutkan tatacara wudlu secara sempurna dan hal - hal yang membatalkannya",
                'B' => "Mampu menyebutkan tatacara wudlu secara sempurna dan hal - hal yang membatalkannya",
                'C' => "Cukup mampu menyebutkan tatacara wudlu secara sempurna dan hal - hal yang membatalkannya",
                'D' => "kurang mampu menyebutkan tatacara wudlu secara sempurna dan hal - hal yang membatalkannya",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Mempraktekkan wudlu dan do'a setelah wudlu";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan wudlu dan do'a setelah wudlu",
                'B' => "Mampu mempraktikkan wudlu dan do'a setelah wudlu",
                'C' => "Cukup mampu mempraktikkan wudlu dan do'a setelah wudlu",
                'D' => "kurang mampu mempraktikkan wudlu dan do'a setelah wudlu",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------nomor 6
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Menyebutkan tatacara tayammum secara sempurna dan hal - hal yang membatalkannya";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '6';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menyebutkan tatacara tayammum secara sempurna dan hal - hal yang membatalkannya",
                'B' => "Mampu menyebutkan tatacara tayammum secara sempurna dan hal - hal yang membatalkannya",
                'C' => "Cukup mampu menyebutkan tatacara tayammum secara sempurna dan hal - hal yang membatalkannya",
                'D' => "kurang mampu menyebutkan tatacara tayammum secara sempurna dan hal - hal yang membatalkannya",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Mempraktikkan tayammum dan do'a setelahnya";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '6';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan tayammum dan do'a setelah wudlu",
                'B' => "Mampu mempraktikkan tayammum dan do'a setelah wudlu",
                'C' => "Cukup mampu mempraktikkan tayammum dan do'a setelah wudlu",
                'D' => "kurang mampu mempraktikkan tayammum dan do'a setelah wudlu",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------ nomor 7
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = 'Menyebutkan :';
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '7';
            $point_kpi->deskripsi = null;
            $point_kpi->jenis = '0';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Hal - hal yang mewajibkan mandi besar.";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '7';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menyebutkan hal - hal yang mewajibkan mandi besar",
                'B' => "Mampu menyebutkan hal - hal yang mewajibkan mandi besar",
                'C' => "Cukup mampu menyebutkan hal - hal yang mewajibkan mandi besar",
                'D' => "kurang mampu menyebutkan hal - hal yang mewajibkan mandi besar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. fardlu dan sunnah mandi besar.";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '7';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menyebutkan fardlu dan sunnah mandi besar",
                'B' => "Mampu menyebutkan fardlu dan sunnah mandi besar",
                'C' => "Cukup mampu menyebutkan fardlu dan sunnah mandi besar",
                'D' => "kurang mampu menyebutkan fardlu dan sunnah mandi besar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "3. Melafalkan niat mandi besar bagi laki - laki dan perempuan.";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '7';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu melafalkan niat mandi besar bagi laki - laki dan perempuan",
                'B' => "Mampu melafalkan niat mandi besar bagi laki - laki dan perempuan",
                'C' => "Cukup mampu melafalkan niat mandi besar bagi laki - laki dan perempuan",
                'D' => "kurang mampu melafalkan niat mandi besar bagi laki - laki dan perempuan",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------Nomor 8
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Mempraktikkan bersuci bagi pemakai pembalut luka pada anggota wudlu";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '8';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mempraktikkan bersuci bagi pemakai pembalut luka pada anggota wudlu",
                'B' => "Mampu mempraktikkan bersuci bagi pemakai pembalut luka pada anggota wudlu",
                'C' => "Cukup mampu mempraktikkan bersuci bagi pemakai pembalut luka pada anggota wudlu",
                'D' => "kurang mampu mempraktikkan bersuci bagi pemakai pembalut luka pada anggota wudlu",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //---------------------------------------Nomor 9

            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = 'Menyebutkan hal - hal yang terlarang bagi orang :';
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '9';
            $point_kpi->deskripsi = null;
            $point_kpi->jenis = '0';
            $point_kpi->save();
            $point_kpi = null;
            //-----------------------------------------

            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Berhadats kecil";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '9';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menyebutkan hal - hal yang terlarang bagi orang berhadats besar",
                'B' => "Mampu menyebutkan hal - hal yang terlarang bagi orang berhadats besar",
                'C' => "Cukup mampu menyebutkan hal - hal yang terlarang bagi orang berhadats besar",
                'D' => "kurang mampu menyebutkan hal - hal yang terlarang bagi orang berhadats besar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //-----------------------------------------

            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Berhadats besar";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '9';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu mampu menyebutkan hal - hal yang terlarang bagi orang berhadats besar",
                'B' => "Mampu mampu menyebutkan hal - hal yang terlarang bagi orang berhadats besar",
                'C' => "Cukup mampu mampu menyebutkan hal - hal yang terlarang bagi orang berhadats besar",
                'D' => "kurang mampu mampu menyebutkan hal - hal yang terlarang bagi orang berhadats besar",
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
