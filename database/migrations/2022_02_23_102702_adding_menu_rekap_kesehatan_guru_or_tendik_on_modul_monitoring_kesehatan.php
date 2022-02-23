<?php

use App\Models\Modul;
use App\Models\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingMenuRekapKesehatanGuruOrTendikOnModulMonitoringKesehatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $modul = Modul::where('nm_modul', 'Monitoring Kesehatan')->where('id_role', '6')->first();

        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Rekap Kesehatan Guru/Tendik';
        $menu->page = 'rekap-kesehatan-guru';
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
