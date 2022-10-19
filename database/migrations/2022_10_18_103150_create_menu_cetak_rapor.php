<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Modul;

class CreateMenuCetakRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $modul = Modul::where('nm_modul', 'Rapor Sisipan')->where('id_role', '7')->first();

        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Cetak Rapor';
        $menu->page = 'cetak-rapor';
        $menu->urutan = 3;
        $menu->akses = 1;
        $menu->save();
        // Schema::create('menu_cetak_rapor', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // public function down()
    // {
    //     Schema::dropIfExists('menu_cetak_rapor');
    // }
}
