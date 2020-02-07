<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;

class AddingMenuPelanggaranSiswaNonKbm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = new Menu;
        $menu->id_modul = 35;
        $menu->nm_menu = 'Input Pelanggaran Non-KBM';
        $menu->page = 'input-pelanggaran';
        $menu->urutan = 4;
        $menu->akses = 1;
        $menu->save();

        $menu = new Menu;
        $menu->id_modul = 34;
        $menu->nm_menu = 'Input Pelanggaran Non-KBM';
        $menu->page = 'input-pelanggaran';
        $menu->urutan = 3;
        $menu->akses = 1;
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
