<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;

class AddingMenuDeletePlotting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = new Menu;
        $menu->id_modul = 20;
        $menu->nm_menu = 'Hapus Plotting Mapel Siswa';
        $menu->page = 'hapus-plotting-mapel-siswa';
        $menu->urutan = 4;
        $menu->created_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        
        // $menu = Menu::find(84);
        // $menu->urutan = 5;
        // $menu->save();

        // $menu = Menu::find(85);
        // $menu->urutan = 6;
        // $menu->save();
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
