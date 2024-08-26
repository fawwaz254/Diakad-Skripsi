<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuCatatSiswaTerlambatOnGuruPiket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('id_modul', 35)->where('id_role', 2)->first();

        $menu = new Menu();
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Catat Siswa Terlambat';
        $menu->page = 'catat-siswa-terlambat';
        $menu->urutan = 7;
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
        Menu::where('nm_menu', 'Catat Siswa Terlambat')
            ->where('id_modul', 35)
            ->delete();
    }
}
