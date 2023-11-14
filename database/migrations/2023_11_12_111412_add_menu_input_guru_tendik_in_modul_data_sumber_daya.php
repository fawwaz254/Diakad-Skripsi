<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuInputGuruTendikInModulDataSumberDaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'Data Sumber Daya')->where('id_role', 8)->first();
        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Input Guru Baru';
        $menu->page = 'input-guru';
        $menu->urutan = 1;
        $menu->akses = 1;
        $menu->save();

        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Input Tendik Baru';
        $menu->page = 'input-tendik';
        $menu->urutan = 2;
        $menu->akses = 1;
        $menu->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
