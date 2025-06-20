<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Modul;

class AddingRouteOnModulAndMenuAkademikWaliMurid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // update modul
        // $menu               = Modul::find(38);
        // $menu->route        = "akademik";
        // $menu->updated_at   = $now;
        // $menu->save();

        // update menu
        // $menu               = Menu::find(155);
        // $menu->page         = "kalender-akademik";
        // $menu->updated_at   = $now;
        // $menu->save();

        // update menu
        // $menu               = Menu::find(156);
        // $menu->page         = "jadwal-kbm";
        // $menu->updated_at   = $now;
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
