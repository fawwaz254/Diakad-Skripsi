<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Modul;

class AddRouteMenuCetakPresensiKbmRoleAkademik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // update menu
        $menu               = Menu::find(88);
        $menu->page         = "cetak-presensi-kbm";
        $menu->updated_at   = $now;
        $menu->save();

        // update modul
        $menu               = Modul::find(22);
        $menu->page         = "presensi";
        $menu->updated_at   = $now;
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
