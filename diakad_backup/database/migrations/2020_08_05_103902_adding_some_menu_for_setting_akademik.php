<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;

class AddingSomeMenuForSettingAkademik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = new Modul;
        $modul->id_role = 7;
        $modul->nm_modul = 'Kelas Daring';
        $modul->route = 'kelas-daring';
        $modul->urutan = 7;
        $modul->akses = 1;
        $modul->created_by = 'A8bT515358553655b8b4b05a6d86';
        $modul->save();

        $menu = new Menu;
        $menu->id_modul = 76;
        $menu->nm_menu = 'Setting Toleransi Keterlambatan';
        $menu->page = 'setting-toleransi';
        $menu->urutan = 1;
        $menu->akses = 1;
        $menu->created_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();

        $menu = new Menu;
        $menu->id_modul = 76;
        $menu->nm_menu = 'Setting Pengampu';
        $menu->page = 'setting-pengampu';
        $menu->urutan = 2;
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
