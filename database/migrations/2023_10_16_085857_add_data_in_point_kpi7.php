<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;


class AddDataInPointKpi7 extends Migration
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
            //!-----------------------------------------no 1
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Menjelaskan adab masuk dan keluar masjid beserta hafal do'anya";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan adab masuk dan keluar masjid beserta hafal do'anya dengan benar",
                'B' => "Mampu menjelaskan adab masuk dan keluar masjid beserta hafal do'anya dengan benar",
                'C' => "Cukup mampu menjelaskan adab masuk dan keluar masjid beserta hafal do'anya dengan benar",
                'D' => "kurang mampu menjelaskan adab masuk dan keluar masjid beserta hafal do'anya dengan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //----------------------------------------------

            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Hafal lafal niat i'tikaf";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal lafal niat i'tikaf dengan fasih dan benar",
                'B' => "Mampu menghafal lafal niat i'tikaf dengan fasih dan benar",
                'C' => "Cukup mampu menghafal lafal niat i'tikaf dengan fasih dan benar",
                'D' => "kurang mampu menghafal lafal niat i'tikaf dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!---------------------------------------------- no 2
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Menjelaskan hal-hal yang disunnahkan sebelum sholat jum'at";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '2';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan hal - hal yang di sunnahkan sebelum sholat jum'at dengan benar",
                'B' => "Mampu menjelaskan hal - hal yang di sunnahkan sebelum sholat jum'at dengan benar",
                'C' => "Cukup mampu menjelaskan hal - hal yang di sunnahkan sebelum sholat jum'at dengan benar",
                'D' => "kurang mampu menjelaskan hal - hal yang di sunnahkan sebelum sholat jum'at dengan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!---------------------------------------------- no 3
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Menjelaskan dengan benar tentang sholat sunnah rawatib";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '3';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan tentang sholat rawatib dengan benar",
                'B' => "Mampu menjelaskan tentang sholat rawatib dengan benar",
                'C' => "Cukup mampu menjelaskan tentang sholat rawatib dengan benar",
                'D' => "kurang mampu menjelaskan tentang sholat rawatib dengan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!---------------------------------------------- no 4
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Hafal dengan fasih dan benar :";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  null;
            $point_kpi->jenis = '0';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Niat sholat tahiyatul masjid";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat tahiyatul masjid dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat tahiyatul masjid dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat tahiyatul masjid dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat tahiyatul masjid dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Niat sholat dhuha";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat dhuha dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat dhuha dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat dhuha dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat dhuha dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "3. Niat sholat hajat";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat hajat dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat hajat dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat hajat dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat hajat dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "4. Niat sholat tahajjud";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat tahajud dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat tahajud dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat tahajud dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat tahajud dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //---------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "5. Niat sholat tasbih";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '4';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat tasbih dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat tasbih dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat tasbih dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat tasbih dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!---------------------------------------------------- no 5

            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Hafal dengan fasih dan benar :";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  null;
            $point_kpi->jenis = '0';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Niat sholat tarawih";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat tarawih dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat tarawih dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat tarawih dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat tarawih dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Niat sholat witir";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat witir dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat witir dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat witir dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat witir dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "3. Niat sholat idul fitri dan idul adha";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '5';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat idul fitri dan Idul adha dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat idul fitri dan Idul adha dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat idul fitri dan Idul adha dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat idul fitri dan Idul adha dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!------------------------------------------------------- no 6
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Hafal dengan fasih dan benar :";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '6';
            $point_kpi->deskripsi =  null;
            $point_kpi->jenis = '0';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Niat sholat istisqa";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '6';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat istisqa dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat istisqa dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat istisqa dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat istisqa dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //-------------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Niat sholat istikharah";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '6';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat istikharah dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat istikharah dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat istikharah dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat istikharah dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "3. Niat sholat khusuful qomar dan kusyufus syamsi";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '6';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafal niat sholat kusyuful qomar dan kusyufus syamsi dengan fasih dan benar",
                'B' => "Mampu menghafal niat sholat kusyuful qomar dan kusyufus syamsi dengan fasih dan benar",
                'C' => "Cukup mampu menghafal niat sholat kusyuful qomar dan kusyufus syamsi dengan fasih dan benar",
                'D' => "kurang mampu menghafal niat sholat kusyuful qomar dan kusyufus syamsi dengan fasih dan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!--------------------------------------------------- no 7
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Menjelaskan dengan benar kaifiyah :";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '7';
            $point_kpi->deskripsi =  null;
            $point_kpi->jenis = '0';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Sholat Jamak";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '7';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan kaifiyah sholat jamak dengan benar",
                'B' => "Mampu menjelaskan kaifiyah sholat jamak dengan benar",
                'C' => "Cukup mampu menjelaskan kaifiyah sholat jamak dengan benar",
                'D' => "kurang mampu menjelaskan kaifiyah sholat jamak dengan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Sholat Qoshor";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '7';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan kaifiyah sholat qashar dengan benar",
                'B' => "Mampu menjelaskan kaifiyah sholat qashar dengan benar",
                'C' => "Cukup mampu menjelaskan kaifiyah sholat qashar dengan benar",
                'D' => "kurang mampu menjelaskan kaifiyah sholat qashar dengan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "3. Sholat Jamak Qoshor";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '7';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan kaifiyah sholat jamak qashar dengan benar",
                'B' => "Mampu menjelaskan kaifiyah sholat jamak qashar dengan benar",
                'C' => "Cukup mampu menjelaskan kaifiyah sholat jamak qashar dengan benar",
                'D' => "kurang mampu menjelaskan kaifiyah sholat jamak qashar dengan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //!------------------------------------------------------- no 8
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "Mampu menjelaskan dengan benar :";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '8';
            $point_kpi->deskripsi =  null;
            $point_kpi->jenis = '0';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Macam-macam darah wanita dan ketentuannya";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '8';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menjelaskan macam - macam darah wanita dan ketentuannya dengan benar",
                'B' => "Mampu menjelaskan macam - macam darah wanita dan ketentuannya dengan benar",
                'C' => "Cukup mampu menjelaskan macam - macam darah wanita dan ketentuannya dengan benar",
                'D' => "kurang mampu menjelaskan macam - macam darah wanita dan ketentuannya dengan benar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;

            //-------------------------------------------------
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Qadla sholat bagi wanita haid dan nifas";
            $point_kpi->tingkat_kelas = '11';
            $point_kpi->urutan = '8';
            $point_kpi->deskripsi =  [
                'A' => "Sangat menjelaskan qadla sholat bagi wanita haid dan nifas dengan benar",
                'B' => "menjelaskan qadla sholat bagi wanita haid dan nifas dengan benar",
                'C' => "Cukup menjelaskan qadla sholat bagi wanita haid dan nifas dengan benar",
                'D' => "kurang menjelaskan qadla sholat bagi wanita haid dan nifas dengan benar",
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
