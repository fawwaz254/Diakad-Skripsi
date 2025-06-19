<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;

class AddingChangeRoleUtkModulDanMenuRapbRoleKeuangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::find(72);
        // $modul->id_role = 9;
        // $modul->save();

        // disable Modul Pemasukan Sekolah dan Pengeluaran Sekolah
        // $modul = Modul::find(69);
        // $modul->akses = 0;
        // $modul->save();

        // $modul = Modul::find(64);
        // $modul->akses = 0;
        // $modul->save();
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
