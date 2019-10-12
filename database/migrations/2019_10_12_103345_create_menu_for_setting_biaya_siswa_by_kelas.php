<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;

class CreateMenuForSettingBiayaSiswaByKelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu               = new Menu;
        $menu->id_modul     = 29;
        $menu->nm_menu      = "Setting Biaya Siswa By Kelas";
        $menu->page         = "biaya-siswa-by-kelas";
        $menu->urutan       = 2;
        $menu->akses        = 1;
        $menu->save();
        
        $menu               = Menu::find(115);
        $menu->urutan       = 3;
        $menu->save();

        $menu               = Menu::find(116);
        $menu->urutan       = 4;
        $menu->save();

        $menu               = Menu::find(253);
        $menu->urutan       = 5;
        $menu->save();

        $menu               = Menu::find(134);
        $menu->urutan       = 6;
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
