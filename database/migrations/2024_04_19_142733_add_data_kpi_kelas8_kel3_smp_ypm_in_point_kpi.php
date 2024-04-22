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

class AddDataKpiKelas8Kel3SmpYpmInPointKpi extends Migration
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
                $kelompok_kpi3 = KelompokKPI::where('urutan', 3)->first();

                if ($sekolah->nm_singkat_sekolah == 'smpypm1' || $sekolah->nm_singkat_sekolah == 'smpypm2') {
                    if ($kelompok_kpi3) {
                        // urutan 1
                        $point_kpi = new PointKPI;
                        $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                        $point_kpi->id_kelompok_kpi = $kelompok_kpi3->id_kelompok_kpi;
                        $point_kpi->id_semester = $semester->id_semester;
                        $point_kpi->nm_point_kpi = "Keistiqomahan dalam mengaji harian di rumah";
                        $point_kpi->tingkat_kelas = '8';
                        $point_kpi->urutan = '1';
                        $point_kpi->deskripsi =  [
                            'A' => "Rutin mengaji di rumah",
                            'B' => "Sering mengaji di rumah",
                            'C' => "Jarang mengaji di rumah",
                            'D' => "Tidak pernah mengaji di rumah",
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
