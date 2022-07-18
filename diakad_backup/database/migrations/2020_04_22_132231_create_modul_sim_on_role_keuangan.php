<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;

class CreateModulSimOnRoleKeuangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul              = new Modul;
        $modul->id_role     = 9;
        $modul->nm_modul    = "SIM";
        $modul->route       = "sim";
        $modul->urutan      = 4;
        $modul->akses       = 1;
        $modul->save();

        $new_modul = Modul::orderBy('id_modul', 'desc')->first();

        $menu = new Menu;
        $menu->id_modul = $new_modul->id_modul;
        $menu->nm_menu = 'SPP';
        $menu->page = 'spp';
        $menu->urutan = 1;
        $menu->akses = 1;
        $menu->save();

        $menu = new Menu;
        $menu->id_modul = $new_modul->id_modul;
        $menu->nm_menu = 'Pengeluaran';
        $menu->page = 'pengeluaran';
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
    {
        //
    }
}
