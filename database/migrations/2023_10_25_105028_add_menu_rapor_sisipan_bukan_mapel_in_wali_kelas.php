<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Modul;

class AddMenuRaporSisipanBukanMapelInWaliKelas extends Migration
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
        // $menu->nm_menu = 'Rapor Sisipan Input Non Mapel';
        // $menu->page = 'rapor-sisipan-input-non-mapel';
        // $menu->urutan = 13;
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
