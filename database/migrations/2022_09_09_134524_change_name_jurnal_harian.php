<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNameJurnalHarian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul1 = Modul::where('id_role', 19)->where('nm_modul', 'Jurnal Harian')->first();
        // $modul1->update([
        //     "nm_modul" => 'Jurnal Harian Tendik'
        // ]);
        // $modul2 = Modul::where('id_role', 7)->where('nm_modul', 'Jurnal Harian')->first();
        // $modul2->update([
        //     "nm_modul" => 'Jurnal Harian Guru'
        // ]);
        // $modul3 = Modul::where('id_role', 2)->where('nm_modul', 'E-Learning')->first();
        // $modul3->update([
        //     "nm_modul" => 'E-Learning Materi'
        // ]);
        // $modul4 = Modul::where('id_role', 3)->where('nm_modul', 'E-Learning')->first();
        // $modul4->update([
        //     "nm_modul" => 'E-Learning Materi'
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
