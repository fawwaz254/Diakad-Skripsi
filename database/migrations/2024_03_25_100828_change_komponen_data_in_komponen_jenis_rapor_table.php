<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\Sekolah;
use App\Models\JenisRapor;
use App\Models\KomponenJenisRapor;
use Carbon\Carbon;

class ChangeKomponenDataInKomponenJenisRaporTable extends Migration
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
            // Schema::table('komponen_jenis_rapor', function (Blueprint $table) {
            //     $now = Carbon::now();
            //     $sekolah = Sekolah::first();
            //     $jenis_rapor = JenisRapor::where('nm_jenis_rapor', 'sisipan')->first();

            //     if ($sekolah->nm_singkat_sekolah == 'manu') {
            //         $komponen_jenis_rapor = new KomponenJenisRapor;

            //         // Delete all components with the specified type within the transaction
            //         $komponen_jenis_rapor->where('id_jenis_rapor', $jenis_rapor->id_jenis_rapor)->delete();

            //         // Insert new components only if all old components are successfully deleted
            //         if ($komponen_jenis_rapor->where('id_jenis_rapor', $jenis_rapor->id_jenis_rapor)->count() == 0) {
            //             // Insert new components
            //             $komponen_jenis_rapor->insert([
            //                 [
            //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
            //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
            //                     'nm_komponen_jenis_rapor' => 'NILAI SUMATIF 1',
            //                     'urutan' => 1,
            //                     'created_by' => 'migration',
            //                     'created_at' => $now,
            //                 ],
            //                 [
            //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
            //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
            //                     'nm_komponen_jenis_rapor' => 'NILAI SUMATIF 2',
            //                     'urutan' => 2,
            //                     'created_by' => 'migration',
            //                     'created_at' => $now,
            //                 ],
            //                 [
            //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
            //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
            //                     'nm_komponen_jenis_rapor' => 'PENGETAHUAN',
            //                     'urutan' => 3,
            //                     'created_by' => 'migration',
            //                     'created_at' => $now,
            //                 ],
            //                 [
            //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
            //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
            //                     'nm_komponen_jenis_rapor' => 'KETERAMPILAN',
            //                     'urutan' => 4,
            //                     'created_by' => 'migration',
            //                     'created_at' => $now,
            //                 ],
            //                 [
            //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
            //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
            //                     'nm_komponen_jenis_rapor' => 'STS',
            //                     'urutan' => 5,
            //                     'created_by' => 'migration',
            //                     'created_at' => $now,
            //                 ],
            //                 [
            //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
            //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
            //                     'nm_komponen_jenis_rapor' => 'PTS',
            //                     'urutan' => 6,
            //                     'created_by' => 'migration',
            //                     'created_at' => $now,
            //                 ],
            //             ]);
            //         }
            //     }
            // });

            DB::commit(); // Commit transaction if everything is successful
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction if an error occurs
            throw new \Exception('Migration failed: ' . $e->getMessage());
        }
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
