<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;

class AddDataKpiKelas11SmaSmkYpmInPointKpi extends Migration
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
                $semester = Semester::where('is_aktif_semester', '1')->first();
                $kelompok_kpi1 = KelompokKPI::where('urutan', 1)->first();

                $nm_singkat_sma_smk_ypm = [
                    'smawh2',
                    'smkypm1taman',
                    'smkypm2',
                    'smkypm3taman',
                ];

                if (in_array($sekolah->nm_singkat_sekolah, $nm_singkat_sma_smk_ypm)) {
                    if ($kelompok_kpi1) {
                        // urutan 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menjelaskan dan memperagakan shalat dengan keadaan sakit. (duduk, tidur miring, dan telentang)";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan dan memperagakan shalat dengan keadaan sakit. (duduk, tidur miring, dan telentang)",
                            'B' => "Mampu menjelaskan dan memperagakan shalat dengan keadaan sakit. (duduk, tidur miring, dan telentang)",
                            'C' => "Cukup mampu menjelaskan dan memperagakan shalat dengan keadaan sakit. (duduk, tidur miring, dan telentang)",
                            'D' => "Kurang mampu menjelaskan dan memperagakan shalat dengan keadaan sakit. (duduk, tidur miring, dan telentang)",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 2
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menjelaskan dan memperagakan shalat dalam kendaraan.";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '2';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan dan memperagakan shalat dalam kendaraan.",
                            'B' => "Mampu menjelaskan dan memperagakan shalat dalam kendaraan.",
                            'C' => "Cukup mampu menjelaskan dan memperagakan shalat dalam kendaraan.",
                            'D' => "Kurang mampu menjelaskan dan memperagakan shalat dalam kendaraan.",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 3
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menjelaskan dan memperagakan shalat dengan benar:";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '3';
                        $point_kpi->deskripsi = null;
                        $point_kpi->jenis = '0';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 3
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Bagi daimul hadats (selalu berhadats)";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '3';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan dan memperagakan shalat dengan benar bagi daimul hadats (selalu berhadats)",
                            'B' => "Mampu menjelaskan dan memperagakan shalat dengan benar bagi daimul hadats (selalu berhadats)",
                            'C' => "Cukup mampu menjelaskan dan memperagakan shalat dengan benar bagi daimul hadats (selalu berhadats)",
                            'D' => "Kurang mampu menjelaskan dan memperagakan shalat dengan benar bagi daimul hadats (selalu berhadats)",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 3
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Bagi faqidut thahuraini (tidak dapat bersuci)";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '3';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan dan memperagakan shalat dengan benar bagi faqidut thahuraini (tidak dapat bersuci)",
                            'B' => "Mampu menjelaskan dan memperagakan shalat dengan benar bagi faqidut thahuraini (tidak dapat bersuci)",
                            'C' => "Cukup mampu menjelaskan dan memperagakan shalat dengan benar bagi faqidut thahuraini (tidak dapat bersuci)",
                            'D' => "Kurang mampu menjelaskan dan memperagakan shalat dengan benar bagi faqidut thahuraini (tidak dapat bersuci)",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menjelaskan dan memperagakan dengan benar:";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi = null;
                        $point_kpi->jenis = '0';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Memandikan jenazah";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan dan memperagakan dengan benar memandikan jenazah",
                            'B' => "Mampu menjelaskan dan memperagakan dengan benar memandikan jenazah",
                            'C' => "Cukup mampu menjelaskan dan memperagakan dengan benar memandikan jenazah",
                            'D' => "Kurang mampu menjelaskan dan memperagakan dengan benar memandikan jenazah",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Mengkafani jenazah";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan dan memperagakan dengan benar mengkafani jenazah",
                            'B' => "Mampu menjelaskan dan memperagakan dengan benar mengkafani jenazah",
                            'C' => "Cukup mampu menjelaskan dan memperagakan dengan benar mengkafani jenazah",
                            'D' => "Kurang mampu menjelaskan dan memperagakan dengan benar mengkafani jenazah",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Mensholati jenazah";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan dan memperagakan dengan benar mensholati jenazah",
                            'B' => "Mampu menjelaskan dan memperagakan dengan benar mensholati jenazah",
                            'C' => "Cukup mampu menjelaskan dan memperagakan dengan benar mensholati jenazah",
                            'D' => "Kurang mampu menjelaskan dan memperagakan dengan benar mensholati jenazah",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Memakamkan jenazah";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan dan memperagakan dengan benar memakamkan jenazah",
                            'B' => "Mampu menjelaskan dan memperagakan dengan benar memakamkan jenazah",
                            'C' => "Cukup mampu menjelaskan dan memperagakan dengan benar memakamkan jenazah",
                            'D' => "Kurang mampu menjelaskan dan memperagakan dengan benar memakamkan jenazah",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 5
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menjelaskan dengan benar:";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '5';
                        $point_kpi->deskripsi = null;
                        $point_kpi->jenis = '0';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 5
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Adab ta'ziyah";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '5';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan dengan benar adab ta'ziyah",
                            'B' => "Mampu menjelaskan dengan benar adab ta'ziyah",
                            'C' => "Cukup mampu menjelaskan dengan benar adab ta'ziyah",
                            'D' => "Kurang mampu menjelaskan dengan benar adab ta'ziyah",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 5
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Ziarah kubur";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '5';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan dengan benar ziarah kubur",
                            'B' => "Mampu menjelaskan dengan benar ziarah kubur",
                            'C' => "Cukup mampu menjelaskan dengan benar ziarah kubur",
                            'D' => "Kurang mampu menjelaskan dengan benar ziarah kubur",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 6
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menghafalkan dengan fasih dan benar:";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '6';
                        $point_kpi->deskripsi = null;
                        $point_kpi->jenis = '0';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 6
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Niat sholat fardlu sendiri";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '6';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan dengan fasih dan benar niat sholat fardlu sendiri",
                            'B' => "Mampu menghafalkan dengan fasih dan benar niat sholat fardlu sendiri",
                            'C' => "Cukup mampu menghafalkan dengan fasih dan benar niat sholat fardlu sendiri",
                            'D' => "Kurang mampu menghafalkan dengan fasih dan benar niat sholat fardlu sendiri",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 6
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Niat sholat fardlu sebagai makmum";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '6';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan dengan fasih dan benar niat sholat fardlu sebagai makmum",
                            'B' => "Mampu menghafalkan dengan fasih dan benar niat sholat fardlu sebagai makmum",
                            'C' => "Cukup mampu menghafalkan dengan fasih dan benar niat sholat fardlu sebagai makmum",
                            'D' => "Kurang mampu menghafalkan dengan fasih dan benar niat sholat fardlu sebagai makmum",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 6
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Niat sholat fardlu sebagai imam";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '6';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan dengan fasih dan benar niat sholat fardlu sebagai imam",
                            'B' => "Mampu menghafalkan dengan fasih dan benar niat sholat fardlu sebagai imam",
                            'C' => "Cukup mampu menghafalkan dengan fasih dan benar niat sholat fardlu sebagai imam",
                            'D' => "Kurang mampu menghafalkan dengan fasih dan benar niat sholat fardlu sebagai imam",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 7
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Mempraktikan Sholat subuh dengan menghafal do'a qunut.";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '7';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu mempraktikan Sholat subuh dengan menghafal do'a qunut.",
                            'B' => "Mampu mempraktikan Sholat subuh dengan menghafal do'a qunut.",
                            'C' => "Cukup mampu mempraktikan Sholat subuh dengan menghafal do'a qunut.",
                            'D' => "Kurang mampu mempraktikan Sholat subuh dengan menghafal do'a qunut.",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 8
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menghafalkan wirid setelah sholat";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '8';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan wirid setelah sholat",
                            'B' => "Mampu menghafalkan wirid setelah sholat",
                            'C' => "Cukup mampu menghafalkan wirid setelah sholat",
                            'D' => "Kurang mampu menghafalkan wirid setelah sholat",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 9
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menghafalkan dengan fasih bacaan:";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '9';
                        $point_kpi->deskripsi = null;
                        $point_kpi->jenis = '0';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 9
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Sholawat Nariyah";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '9';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan dengan fasih bacaan sholawat Nariyah",
                            'B' => "Mampu menghafalkan dengan fasih bacaan sholawat Nariyah",
                            'C' => "Cukup mampu menghafalkan dengan fasih bacaan sholawat Nariyah",
                            'D' => "Kurang mampu menghafalkan dengan fasih bacaan sholawat Nariyah",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 9
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Sholawat Munjiyat";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '9';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan dengan fasih bacaan sholawat Munjiyat",
                            'B' => "Mampu menghafalkan dengan fasih bacaan sholawat Munjiyat",
                            'C' => "Cukup mampu menghafalkan dengan fasih bacaan sholawat Munjiyat",
                            'D' => "Kurang mampu menghafalkan dengan fasih bacaan sholawat Munjiyat",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                echo "Error message: " . $e->getMessage();
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
