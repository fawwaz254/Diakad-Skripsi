<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;

class MoveMagangSiswaRolePendidikanToHumas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'Magang Siswa')->where('id_role', 1)->first();
        $modul->id_role      = 19;
        $modul->urutan       = 2;
        $modul->save();

        $modul = Modul::where('nm_modul', 'Alumni')->where('id_role', 19)->first();
        $modul->urutan       = 3;
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
