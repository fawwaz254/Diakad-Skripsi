<?php

use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Sekolah;
use App\Models\Semester;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AddDataInPointKpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $sekolah = Sekolah::first();
        $semester = Semester::where('is_aktif_semester', '1')->first();
        $kelompok_kpi1 = KelompokKPI::where('urutan', 1)->first();
        if ($kelompok_kpi1) {
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = 'Menghafalkan dengan fasih dan benar.';
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi = null;
            $point_kpi->jenis = '0';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------1
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "1. Do'a awal belajar";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafalkan secara fasih do'a awal belajar",
                'B' => "Mampu menghafalkan secara fasih do'a awal belajar",
                'C' => "Cukup mampu menghafalkan secara fasih do'a awal belajar",
                'D' => "kurang mampu menghafalkan secara fasih do'a awal belajar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------2
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "2. Do'a akhir Tatap Muka";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafalkan secara fasih do'a akhir tatap muka",
                'B' => "Mampu menghafalkan secara fasih do'a akhir tatap muka",
                'C' => "Cukup mampu menghafalkan secara fasih do'a akhir tatap muka",
                'D' => "kurang mampu menghafalkan secara fasih do'a akhir tatap muka",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
            $point_kpi = null;
            //------------------------------3
            $point_kpi = new PointKPI;
            $point_kpi->id_point_kpi = $sekolah->prefix . strtotime($now) . uniqid();
            $point_kpi->id_kelompok_kpi = $kelompok_kpi1->id_kelompok_kpi;
            $point_kpi->id_semester = $semester->id_semester;
            $point_kpi->nm_point_kpi = "3. Do'a akhir belajar";
            $point_kpi->tingkat_kelas = '10';
            $point_kpi->urutan = '1';
            $point_kpi->deskripsi =  [
                'A' => "Sangat mampu menghafalkan secara fasih do'a akhir belajar",
                'B' => "Mampu menghafalkan secara fasih do'a akhir belajar",
                'C' => "Cukup mampu menghafalkan secara fasih do'a akhir belajar",
                'D' => "kurang mampu menghafalkan secara fasih do'a akhir belajar",
            ];
            $point_kpi->jenis = '1';
            $point_kpi->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
