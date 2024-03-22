<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\KomponenJenisRapor;
use App\Models\JenisRapor;
use App\Models\Sekolah;

use Carbon\Carbon;

class ChangeDataForManuInKomponenJenisRaporTable extends Migration
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

            if ($sekolah->nm_singkat_sekolah == 'manu') {
                $komponen_jenis_rapor = new KomponenJenisRapor;

                // delete all komponen with jenis rapor sisipan
                $komponen_jenis_rapor->where('id_jenis_rapor', $jenis_rapor->id_jenis_rapor)->delete();

                // add new data
                $komponen_jenis_rapor->id_komponen_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
                $komponen_jenis_rapor->id_jenis_rapor = $jenis_rapor->id_jenis_rapor;
                $komponen_jenis_rapor->nm_komponen_jenis_rapor = 'FORMATIF';
                $komponen_jenis_rapor->urutan = 1;
                $komponen_jenis_rapor->created_by = 'migration';
                $komponen_jenis_rapor->created_at = $now;
                $komponen_jenis_rapor->save();

                $komponen_jenis_rapor = new KomponenJenisRapor;
                $komponen_jenis_rapor->id_komponen_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
                $komponen_jenis_rapor->id_jenis_rapor = $jenis_rapor->id_jenis_rapor;
                $komponen_jenis_rapor->nm_komponen_jenis_rapor = 'PENGETAHUAN';
                $komponen_jenis_rapor->urutan = 2;
                $komponen_jenis_rapor->created_by = 'migration';
                $komponen_jenis_rapor->created_at = $now;
                $komponen_jenis_rapor->save();

                $komponen_jenis_rapor = new KomponenJenisRapor;
                $komponen_jenis_rapor->id_komponen_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
                $komponen_jenis_rapor->id_jenis_rapor = $jenis_rapor->id_jenis_rapor;
                $komponen_jenis_rapor->nm_komponen_jenis_rapor = 'KETERAMPILAN';
                $komponen_jenis_rapor->urutan = 3;
                $komponen_jenis_rapor->created_by = 'migration';
                $komponen_jenis_rapor->created_at = $now;
                $komponen_jenis_rapor->save();

                $komponen_jenis_rapor = new KomponenJenisRapor;
                $komponen_jenis_rapor->id_komponen_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
                $komponen_jenis_rapor->id_jenis_rapor = $jenis_rapor->id_jenis_rapor;
                $komponen_jenis_rapor->nm_komponen_jenis_rapor = 'STS';
                $komponen_jenis_rapor->urutan = 4;
                $komponen_jenis_rapor->created_by = 'migration';
                $komponen_jenis_rapor->created_at = $now;
                $komponen_jenis_rapor->save();

                $komponen_jenis_rapor = new KomponenJenisRapor;
                $komponen_jenis_rapor->id_komponen_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
                $komponen_jenis_rapor->id_jenis_rapor = $jenis_rapor->id_jenis_rapor;
                $komponen_jenis_rapor->nm_komponen_jenis_rapor = 'PTS';
                $komponen_jenis_rapor->urutan = 5;
                $komponen_jenis_rapor->created_by = 'migration';
                $komponen_jenis_rapor->created_at = $now;
                $komponen_jenis_rapor->save();
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
        Schema::table('komponen_jenis_rapor', function (Blueprint $table) {

        });
    }
}
