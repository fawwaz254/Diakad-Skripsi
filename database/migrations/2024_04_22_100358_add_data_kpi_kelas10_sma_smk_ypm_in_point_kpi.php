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

class AddDataKpiKelas10SmaSmkYpmInPointKpi extends Migration
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
                        // header 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menghafalkan dengan fasih dan benar:";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi = null;
                        $point_kpi->jenis = '0';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Adzan dan jawabannya";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan dengan fasih dan benar Adzan dan jawabannya",
                            'B' => "Mampu menghafalkan dengan fasih dan benar Adzan dan jawabannya",
                            'C' => "Cukup mampu menghafalkan dengan fasih dan benar Adzan dan jawabannya",
                            'D' => "Kurang mampu menghafalkan dengan fasih dan benar Adzan dan jawabannya",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Iqamah dan jawabannya";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan dengan fasih dan benar Iqamah dan jawabannya",
                            'B' => "Mampu menghafalkan dengan fasih dan benar Iqamah dan jawabannya",
                            'C' => "Cukup mampu menghafalkan dengan fasih dan benar Iqamah dan jawabannya",
                            'D' => "Kurang mampu menghafalkan dengan fasih dan benar Iqamah dan jawabannya",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Doa setelah adzan";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan dengan fasih dan benar Doa setelah adzan",
                            'B' => "Mampu menghafalkan dengan fasih dan benar Doa setelah adzan",
                            'C' => "Cukup mampu menghafalkan dengan fasih dan benar Doa setelah adzan",
                            'D' => "Kurang mampu menghafalkan dengan fasih dan benar Doa setelah adzan",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 2
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menghafalkan niat sholat 5 waktu (Maktubah)";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '2';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan dengan fasih niat sholat 5 waktu (Maktubah)",
                            'B' => "Mampu menghafalkan dengan fasih niat sholat 5 waktu (Maktubah)",
                            'C' => "Cukup mampu menghafalkan dengan fasih niat sholat 5 waktu (Maktubah)",
                            'D' => "Kurang mampu menghafalkan dengan fasih niat sholat 5 waktu (Maktubah)",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 3
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Mempraktikkan shalat subuh dengan menghafal do'a qunut";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '3';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu mempraktikkan shalat subuh dengan menghafal do'a qunut",
                            'B' => "Mampu mempraktikkan shalat subuh dengan menghafal do'a qunut",
                            'C' => "Cukup mampu mempraktikkan shalat subuh dengan menghafal do'a qunut",
                            'D' => "Kurang mampu mempraktikkan shalat subuh dengan menghafal do'a qunut",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menjelaskan hal-hal yang berkaitan dengan sholat jama'ah:";
                        $point_kpi->tingkat_kelas = '10';
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
                        $point_kpi->nm_point_kpi = "Kriteria memilih imam";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan kriteria memilih imam",
                            'B' => "Mampu menjelaskan kriteria memilih imam",
                            'C' => "Cukup mampu menjelaskan kriteria memilih imam",
                            'D' => "Kurang mampu menjelaskan kriteria memilih imam",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Ketentuan menjadi imam dan makmum";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan ketentuan menjadi imam dan makmum",
                            'B' => "Mampu menjelaskan ketentuan menjadi imam dan makmum",
                            'C' => "Cukup mampu menjelaskan ketentuan menjadi imam dan makmum",
                            'D' => "Kurang mampu menjelaskan ketentuan menjadi imam dan makmum",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Cara menata shof";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan cara menata shof",
                            'B' => "Mampu menjelaskan cara menata shof",
                            'C' => "Cukup mampu menjelaskan cara menata shof",
                            'D' => "Kurang mampu menjelaskan cara menata shof",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Cara menjadi makmum muwafiq";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan cara menjadi makmum muwafiq",
                            'B' => "Mampu menjelaskan cara menjadi makmum muwafiq",
                            'C' => "Cukup mampu menjelaskan cara menjadi makmum muwafiq",
                            'D' => "Kurang mampu menjelaskan cara menjadi makmum muwafiq",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Cara menjadi makmum masbuq";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan cara menjadi makmum masbuq",
                            'B' => "Mampu menjelaskan cara menjadi makmum masbuq",
                            'C' => "Cukup mampu menjelaskan cara menjadi makmum masbuq",
                            'D' => "Kurang mampu menjelaskan cara menjadi makmum masbuq",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Cara menegur imam";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan cara menegur imam",
                            'B' => "Mampu menjelaskan cara menegur imam",
                            'C' => "Cukup mampu menjelaskan cara menegur imam",
                            'D' => "Kurang mampu menjelaskan cara menegur imam",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Cara mengganti imam";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan cara mengganti imam",
                            'B' => "Mampu menjelaskan cara mengganti imam",
                            'C' => "Cukup mampu menjelaskan cara mengganti imam",
                            'D' => "Kurang mampu menjelaskan cara mengganti imam",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 5
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menghafalkan wirid dan do'a setelah shalat maktubah dengan fasih dan benar.";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '5';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan wirid dan do'a setelah shalat maktubah dengan fasih dan benar",
                            'B' => "Mampu menghafalkan wirid dan do'a setelah shalat maktubah dengan fasih dan benar",
                            'C' => "Cukup mampu menghafalkan wirid dan do'a setelah shalat maktubah dengan fasih dan benar",
                            'D' => "Kurang mampu menghafalkan wirid dan do'a setelah shalat maktubah dengan fasih dan benar",
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
