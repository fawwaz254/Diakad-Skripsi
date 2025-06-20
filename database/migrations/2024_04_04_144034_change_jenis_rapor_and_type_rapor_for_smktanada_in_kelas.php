<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\JenisRapor;
use Carbon\Carbon;

class ChangeJenisRaporAndTypeRaporForSmktanadaInKelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('kelas', function (Blueprint $table) {
        //     DB::beginTransaction();
        //     try {
        //         $now = Carbon::now();
        //         $sekolah = Sekolah::first();
        //         $jenis_rapor = JenisRapor::where('nm_jenis_rapor', 'Merdeka')->first();
        //         $kelas = Kelas::where('is_aktif', '1')->get();

        //         if ($sekolah->nm_singkat_sekolah == 'smktanada') {
        //             foreach ($kelas as $key => $value) {
        //                 if ($value->tingkat == 1 || $value->tingkat == 2) {
        //                     $value->id_jenis_rapor = $jenis_rapor->id_jenis_rapor;
        //                     $value->type_rapor = '3';
        //                     $value->created_by = 'migration';
        //                     $value->created_at = $now;
        //                     $value->save();
        //                 }
        //             }
        //         }

        //         DB::commit();
        //     } catch (\Exception $e) {
        //         DB::rollback();
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
        Schema::table('kelas', function (Blueprint $table) {
            //
        });
    }
}
