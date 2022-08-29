<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Menu;
use App\Models\Modul;

class AddMenuHistoriAbsensiSiswaInRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'Absensi')->where('id_role', '2')->first();

        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Histori Absensi Siswa';
        $menu->page = 'histori-absensi-siswa';
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
