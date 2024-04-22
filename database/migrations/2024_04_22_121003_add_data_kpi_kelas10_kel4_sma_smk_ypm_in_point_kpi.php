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

class AddDataKpiKelas10Kel4SmaSmkYpmInPointKpi extends Migration
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

                $nm_singkat_sma_smk_ypm = [
                    'smawh2',
                    'smkypm1taman',
                    'smkypm2',
                    'smkypm3taman',
                ];

                if (in_array($sekolah->nm_singkat_sekolah, $nm_singkat_sma_smk_ypm)) {
                    if ($kelompok_kpi4) {
                        // urutan 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Al-Kautsar (Q.S. 108)";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi = [
                            'A' => "Sangat lancar menghafal Al-Kautsar (Q.S. 108)",
                            'B' => "Lancar menghafal Al-Kautsar (Q.S. 108)",
                            'C' => "Cukup lancar menghafal Al-Kautsar (Q.S. 108)",
                            'D' => "Kurang lancar menghafal Al-Kautsar (Q.S. 108)",
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
                        $point_kpi->tingkat_kelas = '10';
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
                        $point_kpi->tingkat_kelas = '10';
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
                        $point_kpi->nm_point_kpi = "Yaasin: 13 s/d 27";
                        $point_kpi->tingkat_kelas = '10';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi = [
                            'A' => "Sangat lancar menghafal Yaasin: 13 s/d 27",
                            'B' => "Lancar menghafal Yaasin: 13 s/d 27",
                            'C' => "Cukup lancar menghafal Yaasin: 13 s/d 27",
                            'D' => "Kurang lancar menghafal Yaasin: 13 s/d 27",
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
