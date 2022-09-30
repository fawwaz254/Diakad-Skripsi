<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;

class CreateMenuInputAbsensiEkskulPembinaEkskulTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu               = new Menu;
        $menu->id_modul     = 37;
        $menu->nm_menu      = "Input Absensi Ekskul";
        $menu->page         = "input-absensi-ekskul";
        $menu->urutan       = 0;
        $menu->akses        = 1;
        $menu->save();

        $menus = Menu::where('id_modul', 37)->get();

        foreach ($menus as $menu) {
            $menu->urutan += 1;
            $menu->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_input_absensi_ekskul_pembina_ekskul');
    }
}
