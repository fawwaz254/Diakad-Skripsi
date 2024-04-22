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

class AddDataKpiKelas8SmpYpmInPointKpi extends Migration
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

                if ($sekolah->nm_singkat_sekolah == 'smpypm1' || $sekolah->nm_singkat_sekolah == 'smpypm2') {
                    if ($kelompok_kpi1) {
                        // header 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Kefasihan hapalan:";
                        $point_kpi->tingkat_kelas = '8';
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
                        $point_kpi->nm_point_kpi = "Do'a awal belajar";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan secara fasih do'a awal belajar",
                            'B' => "Mampu menghafalkan secara fasih do'a awal belajar",
                            'C' => "Cukup mampu menghafalkan secara fasih do'a awal belajar",
                            'D' => "Kurang mampu menghafalkan secara fasih do'a awal belajar",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // header 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Do'a akhir belajar";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafalkan secara fasih do'a akhir belajar",
                            'B' => "Mampu menghafalkan secara fasih do'a akhir belajar",
                            'C' => "Cukup mampu menghafalkan secara fasih do'a akhir belajar",
                            'D' => "Kurang mampu menghafalkan secara fasih do'a akhir belajar",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 2
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menjelaskan cara mencuci najis";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '2';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan cara mencuci najis",
                            'B' => "Mampu menjelaskan cara mencuci najis",
                            'C' => "Cukup mampu menjelaskan cara mencuci najis",
                            'D' => "Kurang mampu menjelaskan cara mencuci najis",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 3
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menjelaskan cara istinja";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '3';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menjelaskan cara istinja",
                            'B' => "Mampu menjelaskan cara istinja",
                            'C' => "Cukup mampu menjelaskan cara istinja",
                            'D' => "Kurang mampu menjelaskan cara istinja",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Menghafal dan mengartikan niat wudlu dan niat tayammum";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu menghafal dan mengartikan niat wudlu dan niat tayammum",
                            'B' => "Mampu menghafal dan mengartikan niat wudlu dan niat tayammum",
                            'C' => "Cukup mampu menghafal dan mengartikan niat wudlu dan niat tayammum",
                            'D' => "Kurang mampu menghafal dan mengartikan niat wudlu dan niat tayammum",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 5
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Mempraktikkan wudlu dan do'anya";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '5';
                        $point_kpi->deskripsi =  [
                            'A' => "Sangat mampu mempraktikkan wudlu dan do'anya",
                            'B' => "Mampu mempraktikkan wudlu dan do'anya",
                            'C' => "Cukup mampu mempraktikkan wudlu dan do'anya",
                            'D' => "Kurang mampu mempraktikkan wudlu dan do'anya",
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
