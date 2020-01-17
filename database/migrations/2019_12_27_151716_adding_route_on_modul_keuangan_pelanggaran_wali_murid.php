<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Modul;

class AddingRouteOnModulKeuanganPelanggaranWaliMurid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // update modul
        $menu               = Modul::find(39);
        $menu->route        = "keuangan";
        $menu->updated_at   = $now;
        $menu->save();

        // update menu
        $menu               = Menu::find(150);
        $menu->page         = "tagihan";
        $menu->updated_at   = $now;
        $menu->save();

        // update menu
        $menu               = Menu::find(151);
        $menu->page         = "riwayat-bayar";
        $menu->updated_at   = $now;
        $menu->save();

        // update modul
        $menu               = Modul::find(41);
        $menu->route        = "pelanggaran";
        $menu->updated_at   = $now;
        $menu->save();
 
        // update menu
        $menu               = Menu::find(154);
        $menu->page         = "riwayat-pelanggaran";
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
