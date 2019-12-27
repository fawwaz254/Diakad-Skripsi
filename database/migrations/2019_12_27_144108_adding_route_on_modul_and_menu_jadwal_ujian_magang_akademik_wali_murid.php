<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;
use App\Models\Menu;

class AddingRouteOnModulAndMenuJadwalUjianMagangAkademikWaliMurid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // update menu
        $menu               = Menu::find(157);
        $menu->page         = "jadwal-ujian";
        $menu->updated_at   = $now;
        $menu->save();

        // update menu
        $menu               = Menu::find(158);
        $menu->page         = "magang";
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
