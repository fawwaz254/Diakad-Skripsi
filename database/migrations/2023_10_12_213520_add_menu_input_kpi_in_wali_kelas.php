<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Modul;


class AddMenuInputKpiInWaliKelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::where('nm_modul', 'Wali Kelas')->first();
        // $menu = new Menu;
        // $menu->id_modul = $modul->id_modul;
        // $menu->nm_menu = 'Input KPI';
        // $menu->page = 'input-kpi';
        // $menu->urutan = 1;
        // $menu->akses = 1;
        // $menu->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
