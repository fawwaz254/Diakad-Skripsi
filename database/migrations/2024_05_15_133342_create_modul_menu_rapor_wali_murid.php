<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulMenuRaporWaliMurid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = new Menu;
        $menu->id_modul = 38; // wali murid
        $menu->nm_menu = 'Rapor Semester';
        $menu->page = 'rapor-semester';
        $menu->urutan = 8;
        $menu->akses = 1;
        $menu->created_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();

        $menu = new Menu;
        $menu->id_modul = 38; // wali murid
        $menu->nm_menu = 'Rapor Sisipan';
        $menu->page = 'rapor-sisipan';
        $menu->urutan = 9;
        $menu->akses = 1;
        $menu->created_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();

        $menu = new Menu;
        $menu->id_modul = 38; // wali murid
        $menu->nm_menu = 'Rapor Pendukung';
        $menu->page = 'rapor-pendukung';
        $menu->urutan = 10;
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
        Schema::dropIfExists('modul_menu_rapor_wali_murid');
    }
}
