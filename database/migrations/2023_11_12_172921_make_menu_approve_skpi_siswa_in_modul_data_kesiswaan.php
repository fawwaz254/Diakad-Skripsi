<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeMenuApproveSkpiSiswaInModulDataKesiswaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::where('nm_modul', 'Data Kesiswaan')->where('id_role', 6)->first();
        // $menu = new Menu;
        // $menu->id_modul = $modul->id_modul;
        // $menu->nm_menu = 'Approve SKPI Siswa';
        // $menu->page = 'approve-prestasi-siswa';
        // $menu->urutan = 8;
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
