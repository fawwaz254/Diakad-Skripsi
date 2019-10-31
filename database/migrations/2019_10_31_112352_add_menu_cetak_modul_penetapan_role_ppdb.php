<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

use App\Models\Modul as Modul;
use App\Models\Menu as Menu;

class AddMenuCetakModulPenetapanRolePpdb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // update menu
        $menu               = Menu::find(168);
        $menu->page         = "cetak";
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
