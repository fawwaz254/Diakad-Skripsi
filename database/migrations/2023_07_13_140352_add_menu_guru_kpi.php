<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

class AddMenuGuruKpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $role = Role::where('path','guru')->first();
        // $modul = Modul::where('id_role',$role->id_role)->where('route','guru-kpi')->first();
        // $menu = new Menu;
        // $menu->id_modul = $modul->id_modul;
        // $menu->nm_menu = 'Input Nilai KPI';
        // $menu->page = 'input-nilai-kpi';
        // $menu->urutan = 1;
        // $menu->akses = 1;
        // $menu->save();

        // $menu1 = new Menu;
        // $menu1->id_modul = $modul->id_modul;
        // $menu1->nm_menu = 'Rekap Nilai KPI';
        // $menu1->page = 'rekap-nilai-kpi';
        // $menu1->urutan = 2;
        // $menu1->akses = 1;
        // $menu1->save();
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
