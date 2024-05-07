<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulLogPengguna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = new Modul;
        $modul->id_role = 16;
        $modul->nm_modul = 'Analisis Log';
        $modul->route = 'analisis-log';
        $modul->urutan = 4;
        $modul->akses = 1;
        $modul->created_by = 'A8bT515358553655b8b4b05a6d86';
        $modul->save();

        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Penggunaan Diakad';
        $menu->page = 'penggunaan-diakad';
        $menu->urutan = 1;
        $menu->akses = 1;
        $menu->created_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
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
