<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenuNilaiUjianModulElearningSoal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'E-Learning Soal')->where('id_role', '3')->first();

        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Nilai Ujian';
        $menu->page = 'nilai-ujian';
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
