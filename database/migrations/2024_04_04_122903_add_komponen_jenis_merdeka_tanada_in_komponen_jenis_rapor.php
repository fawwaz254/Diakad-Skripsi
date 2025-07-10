<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\Sekolah;
use App\Models\JenisRapor;
use App\Models\KomponenJenisRapor;
use Carbon\Carbon;

class AddKomponenJenisMerdekaTanadaInKomponenJenisRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('komponen_jenis_rapor', function (Blueprint $table) {
        //     try {
        //         DB::beginTransaction(); // Begin transaction

        //         $now = Carbon::now();
        //         $sekolah = Sekolah::first();
        //         $jenis_rapor = JenisRapor::where('nm_jenis_rapor', 'Merdeka')->first();

        //         if ($sekolah->nm_singkat_sekolah == 'smktanada') {
        //             $komponen_jenis_rapor = new KomponenJenisRapor;

        //             // Add new komponen jenis rapor merdeka
        //             $komponen_jenis_rapor->insert([
        //                 [
        //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
        //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
        //                     'nm_komponen_jenis_rapor' => 'TP 1',
        //                     'urutan' => 1,
        //                     'created_by' => 'migration',
        //                     'created_at' => $now,
        //                 ],
        //                 [
        //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
        //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
        //                     'nm_komponen_jenis_rapor' => 'TP 2',
        //                     'urutan' => 1,
        //                     'created_by' => 'migration',
        //                     'created_at' => $now,
        //                 ],
        //                 [
        //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
        //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
        //                     'nm_komponen_jenis_rapor' => 'TP 3',
        //                     'urutan' => 1,
        //                     'created_by' => 'migration',
        //                     'created_at' => $now,
        //                 ],
        //                 [
        //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
        //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
        //                     'nm_komponen_jenis_rapor' => 'TP 4',
        //                     'urutan' => 1,
        //                     'created_by' => 'migration',
        //                     'created_at' => $now,
        //                 ],
        //                 [
        //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
        //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
        //                     'nm_komponen_jenis_rapor' => 'TP 5',
        //                     'urutan' => 1,
        //                     'created_by' => 'migration',
        //                     'created_at' => $now,
        //                 ],
        //                 [
        //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
        //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
        //                     'nm_komponen_jenis_rapor' => 'STS',
        //                     'urutan' => 1,
        //                     'created_by' => 'migration',
        //                     'created_at' => $now,
        //                 ],
        //                 [
        //                     'id_komponen_jenis_rapor' => $sekolah->prefix . strtotime($now) . uniqid(),
        //                     'id_jenis_rapor' => $jenis_rapor->id_jenis_rapor,
        //                     'nm_komponen_jenis_rapor' => 'SAS',
        //                     'urutan' => 1,
        //                     'created_by' => 'migration',
        //                     'created_at' => $now,
        //                 ]
        //             ]);
        //         }

        //         DB::commit(); // Commit transaction
        //     } catch (\Exception $e) {
        //         DB::rollback(); // Rollback transaction

        //         throw new \Exception($e->getMessage());
        //     }
        // });
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
