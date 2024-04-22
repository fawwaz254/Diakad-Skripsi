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

class AddDataKpiKelas11Kel4SmaSmkYpmInPointKpi extends Migration
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
                        $point_kpi->nm_point_kpi = "Al 'Adiyaat (Q.S. 100)";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi = [
                            'A' => "Sangat lancar menghafal Al 'Adiyaat (Q.S. 100)",
                            'B' => "Lancar menghafal Al 'Adiyaat (Q.S. 100)",
                            'C' => "Cukup lancar menghafal Al 'Adiyaat (Q.S. 100)",
                            'D' => "Kurang lancar menghafal Al 'Adiyaat (Q.S. 100)",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 2
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Al Qori'ah (Q.S. 101)";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '2';
                        $point_kpi->deskripsi = [
                            'A' => "Sangat lancar menghafal Al Qori'ah (Q.S. 101)",
                            'B' => "Lancar menghafal Al Qori'ah (Q.S. 101)",
                            'C' => "Cukup lancar menghafal Al Qori'ah (Q.S. 101)",
                            'D' => "Kurang lancar menghafal Al Qori'ah (Q.S. 101)",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 3
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Al Zalzalah (Q.S. 99)";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '3';
                        $point_kpi->deskripsi = [
                            'A' => "Sangat lancar menghafal Al Zalzalah (Q.S. 99)",
                            'B' => "Lancar menghafal Al Zalzalah (Q.S. 99)",
                            'C' => "Cukup lancar menghafal Al Zalzalah (Q.S. 99)",
                            'D' => "Kurang lancar menghafal Al Zalzalah (Q.S. 99)",
                        ];
                        $point_kpi->jenis = '1';
                        $point_kpi->save();
                        $point_kpi = null;

                        // urutan 4
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi4->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Yaasin: 41 s/d 53";
                        $point_kpi->tingkat_kelas = '11';
                        $point_kpi->urutan = '4';
                        $point_kpi->deskripsi = [
                            'A' => "Sangat lancar menghafal Yaasin: 41 s/d 53",
                            'B' => "Lancar menghafal Yaasin: 41 s/d 53",
                            'C' => "Cukup lancar menghafal Yaasin: 41 s/d 53",
                            'D' => "Kurang lancar menghafal Yaasin: 41 s/d 53",
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
