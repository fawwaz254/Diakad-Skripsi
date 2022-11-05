<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Modul;

class AddMenuLainnyaInModulKegiatanHarian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'Kegiatan Harian')->where('id_role', '2')->first();

        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Form Lainnya';
        $menu->page = 'form-lainnya';
        $menu->urutan = 2;
        $menu->akses = 1;
        $menu->save();

        // Schema::table('modul_kegiatan_harian', function (Blueprint $table) {
        //     //
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('modul_kegiatan_harian', function (Blueprint $table) {
        //     //
        // });
    }
}
