<?php

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuSettingGuruKpiSumberDaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $role = Role::where('path','sumber-daya')->first();
        // $modul = Modul::where('id_role',$role->id_role)->where('route','guru')->first();
        // $menu = new Menu;
        // $menu->id_modul = $modul->id_modul;
        // $menu->nm_menu = 'Setting Guru KPI';
        // $menu->page = 'setting-guru-kpi';
        // $menu->urutan = 9;
        // $menu->akses = 1;
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
