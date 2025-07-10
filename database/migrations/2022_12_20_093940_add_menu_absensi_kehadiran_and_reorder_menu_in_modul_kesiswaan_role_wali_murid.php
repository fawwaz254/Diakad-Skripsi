<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenuAbsensiKehadiranAndReorderMenuInModulKesiswaanRoleWaliMurid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ADDED NEW MENU
        // $modul = Modul::where('nm_modul', 'Kesiswaan')->where('id_role', '4')->first();

        // $menu = new Menu;
        // $menu->id_modul = $modul->id_modul;
        // $menu->nm_menu = 'Absensi Kehadiran';
        // $menu->page = 'absensi-kehadiran';
        // $menu->urutan = 3;
        // $menu->akses = 1;
        // $menu->save();


        // REORDER MENU IN MODUL KESISWAAN
        // $menu = Menu::find(152);
        // $menu->page = 'absensi-ekskul';
        // $menu->urutan = 4;
        // $menu->save();

        // $menu = Menu::find(153);
        // $menu->page = 'nilai-ekskul';
        // $menu->urutan = 5;
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
