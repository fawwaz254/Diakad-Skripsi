<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Modul;

class AddingRouteOnModulKesiswaanMenuPrestasiBeasiswaWaliMurid extends Migration
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
        $menu               = Modul::find(40);
        $menu->route        = "kesiswaan";
        $menu->updated_at   = $now;
        $menu->save();

        // update menu
        $menu               = Menu::find(232);
        $menu->page         = "prestasi";
        $menu->updated_at   = $now;
        $menu->save();

        // update menu
        $menu               = Menu::find(233);
        $menu->page         = "beasiswa";
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
