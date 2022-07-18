<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;

class AddingMenuRekapMonitoringKelasKosong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = new Menu;
        $menu->id_modul = 35;
        $menu->nm_menu = 'Rekap Monitoring Kelas Kosong';
        $menu->page = 'rekap-monitoring-kelas-kosong';
        $menu->urutan = 3;
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
