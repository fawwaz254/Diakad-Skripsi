<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideMenuNotUseInRoleSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul_akademik = Modul::where('id_role', 3)->where('nm_modul', 'Akademik')->first();
        // if ($modul_akademik) {
        //     $menu = Menu::where('id_modul', $modul_akademik->id_modul)->where('nm_menu', 'Lihat Nilai KBM')->first();
        //     if ($menu) {
        //         $menu->akses = '0';
        //         $menu->save();
        //     }

        //     $menu = Menu::where('id_modul', $modul_akademik->id_modul)->where('nm_menu', 'Jadwal Kelas Daring')->first();
        //     if ($menu) {
        //         $menu->akses = '0';
        //         $menu->save();
        //     }
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
