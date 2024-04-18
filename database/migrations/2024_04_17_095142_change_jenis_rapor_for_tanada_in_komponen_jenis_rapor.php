<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\Sekolah;
use App\Models\JenisRapor;
use App\Models\KomponenJenisRapor;
use Carbon\Carbon;

class ChangeJenisRaporForTanadaInKomponenJenisRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('komponen_jenis_rapor', function (Blueprint $table) {
            DB::beginTransaction();

            try {
                $now = Carbon::now();
                $sekolah = Sekolah::first();

                // Change jenis rapor for komponen Pengetahuan and Keterampilan to 'K13'
                if ($sekolah->nm_singkat_sekolah == 'smktanada') {
                    $jenis_rapor = JenisRapor::where('nm_jenis_rapor', 'K13')->first();
                    $komponen_jenis_rapor = KomponenJenisRapor::where('nm_komponen_jenis_rapor', 'Pengetahuan')->first();
                    $komponen_jenis_rapor->id_jenis_rapor = $jenis_rapor->id_jenis_rapor;
                    $komponen_jenis_rapor->created_by = 'migration';
                    $komponen_jenis_rapor->created_at = $now;
                    $komponen_jenis_rapor->save();

                    $komponen_jenis_rapor = KomponenJenisRapor::where('nm_komponen_jenis_rapor', 'Keterampilan')->first();
                    $komponen_jenis_rapor->id_jenis_rapor = $jenis_rapor->id_jenis_rapor;
                    $komponen_jenis_rapor->created_by = 'migration';
                    $komponen_jenis_rapor->created_at = $now;
                    $komponen_jenis_rapor->save();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw new \Exception($e->getMessage());
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
            //
        });
    }
}
