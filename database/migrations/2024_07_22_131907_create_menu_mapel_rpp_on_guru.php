<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuMapelRppOnGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('id_role', 2)->where('nm_modul', 'Jadwal')->first();

        $menu = new Menu();
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Mapel RPP';
        $menu->page = 'mata-pelajaran';
        $menu->urutan = 4;
        $menu->akses = 1;
        $menu->save();

        $menu = Menu::where('id_modul', $modul->id_modul)->where('nm_menu', 'Set Jadwal Kelas')->first();
        $menu->urutan = 5;
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
