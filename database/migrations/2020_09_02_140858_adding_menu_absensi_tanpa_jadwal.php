<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Menu;

class AddingMenuAbsensiTanpaJadwal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu               = new Menu;
        $menu->id_modul     = 10;
        $menu->nm_menu      = "Absensi Tanpa Jadwal";
        $menu->page         = "absensi-tanpa-jadwal";
        $menu->urutan       = 2;
        $menu->akses        = 1;
        $menu->save();
        
        // $menu = Menu::where('id_modul', 10)->where('nm_menu', 'Rekap Absen')->first();
        // $menu->urutan       = 3;
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
