<?php

use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleOnRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = new Modul;
        $modul->id_role = 19; // humas
        $modul->nm_modul = 'Magang siswa';
        $modul->route = 'magang-siswa';
        $modul->urutan = 2;
        $modul->akses = 1;
        $modul->save();
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
