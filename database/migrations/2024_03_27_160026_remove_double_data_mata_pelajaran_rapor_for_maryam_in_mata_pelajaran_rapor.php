<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\MataPelajaranRapor;
use App\Models\Sekolah;

class RemoveDoubleDataMataPelajaranRaporForMaryamInMataPelajaranRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction(); // Start transaction
        try {
            Schema::table('mata_pelajaran_rapor', function (Blueprint $table) {
                $sekolah = Sekolah::first();

                if ($sekolah->nm_singkat_sekolah == 'smamaryamsby') {
                    $mata_pelajaran_rapor = new MataPelajaranRapor;
                    $mata_pelajaran_rapor->where('id_mata_pelajaran_rapor', 'Qjh121697525450652e2ecad9978')->delete();
                }
            });

            DB::commit(); // Commit transaction
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaction
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mata_pelajaran_rapor', function (Blueprint $table) {
            //
        });
    }
}
