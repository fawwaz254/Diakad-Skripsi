<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenuKpiOnRoleWaliMurid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'Akademik')->where('id_role', 4)->first();

        if ($modul) {
            $menu = new Menu();
            $menu->id_modul = $modul->id_modul;
            $menu->nm_menu = 'Rapor KPI';
            $menu->page = 'kpi';
            $menu->urutan = 11;
            $menu->akses = 1;
            $menu->save();
        } else {
            throw new \Exception("Modul 'Akademik' untuk role 4 tidak ditemukan");
        }
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
