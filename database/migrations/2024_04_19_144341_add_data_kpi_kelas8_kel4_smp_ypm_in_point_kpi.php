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

class AddDataKpiKelas8Kel4SmpYpmInPointKpi extends Migration
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
                $kelompok_kpi4 = KelompokKPI::where('urutan', 4)->first();

                if ($sekolah->nm_singkat_sekolah == 'smpypm1' || $sekolah->nm_singkat_sekolah == 'smpypm2') {
                    if ($kelompok_kpi4) {
                        // urutan 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Al Nashr (Q.S. 110)";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi = [
                            'A' => "Sangat lancar menghafal Al Nashr (Q.S. 110)",
                            'B' => "Lancar menghafal Al Nashr (Q.S. 110)",
                            'C' => "Cukup lancar menghafal Al Nashr (Q.S. 110)",
                            'D' => "Kurang lancar menghafal Al Nashr (Q.S. 110)",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 2
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Al-Ma'un (Q.S. 107)";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '2';
                        $point_kpi->deskripsi = [
                            'A' => "Sangat lancar menghafal Al-Ma'un (Q.S. 107)",
                            'B' => "Lancar menghafal Al-Ma'un (Q.S. 107)",
                            'C' => "Cukup lancar menghafal Al-Ma'un (Q.S. 107)",
                            'D' => "Kurang lancar menghafal Al-Ma'un (Q.S. 107)",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 3
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Al-Quraisy (Q.S. 106)";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '3';
                        $point_kpi->deskripsi = [
                            'A' => "Sangat lancar menghafal Al-Quraisy (Q.S. 106)",
                            'B' => "Lancar menghafal Al-Quraisy (Q.S. 106)",
                            'C' => "Cukup lancar menghafal Al-Quraisy (Q.S. 106)",
                            'D' => "Kurang lancar menghafal Al-Quraisy (Q.S. 106)",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Yaasin: 1 s/d 13";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi = [
                            'A' => "Sangat lancar menghafal Yaasin: 1 s/d 13",
                            'B' => "Lancar menghafal Yaasin: 1 s/d 13",
                            'C' => "Cukup lancar menghafal Yaasin: 1 s/d 13",
                            'D' => "Kurang lancar menghafal Yaasin: 1 s/d 13",
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
