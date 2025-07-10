<?php

use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDataUrutanElearning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::where('id_role', 2)->where('nm_modul', 'Jadwal')->first();
        // $modul->update([
        //     "urutan" => 3
        // ]);

        // $modul_elerning = Modul::where('id_role', 2)->where('nm_modul', 'E-Learning Soal')->first();
        // $modul_elerning->update([
        //     "urutan" => 2
        // ]);

        // $modul_elerning1 = Modul::where('id_role', 2)->where('nm_modul', 'E-Learning')->first();
        // $modul_elerning1->update([
        //     "urutan" => 2
        // ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
