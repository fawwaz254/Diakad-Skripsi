<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideMenuRekapNilaiKelasWaliMurid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'Wali Kelas')->first();
        $menu = Menu::where('nm_menu', 'Rekap Nilai Kelas')->where('id_modul', $modul->id_modul)->first();
        $menu->akses = 0;
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
