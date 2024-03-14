<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\KomponenJenisRapor;
use App\Models\JenisRapor;
use App\Models\Sekolah;
use Carbon\Carbon;

class AddDataNilaiTugasInKomponenJenisRaporTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('komponen_jenis_rapor', function (Blueprint $table) {
            $now = Carbon::now();
            $sekolah = Sekolah::first();

            $jenis_rapor = JenisRapor::where('nm_jenis_rapor', 'sisipan')->first();

            $komponen_jenis_rapor = new KomponenJenisRapor;
            $komponen_jenis_rapor->id_komponen_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
            $komponen_jenis_rapor->id_jenis_rapor =  $jenis_rapor->id_jenis_rapor;
            $komponen_jenis_rapor->nm_komponen_jenis_rapor = 'NILAI TUGAS dan ULANGAN 5';
            $komponen_jenis_rapor->urutan = 5;
            $komponen_jenis_rapor->created_by = 'migration';
            $komponen_jenis_rapor->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('komponen_jenis_rapor', function (Blueprint $table) {
            //
        });
    }
}
