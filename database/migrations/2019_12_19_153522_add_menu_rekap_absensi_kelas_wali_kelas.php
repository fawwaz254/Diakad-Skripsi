<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;
use App\Models\Menu;

class AddMenuRekapAbsensiKelasWaliKelas extends Migration
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
        $menu               = Menu::find(139);
        $menu->page         = "rekap-absensi-kelas";
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
        // rollback
        $menu               = Menu::find(139);
        $menu->page         = null;
        $menu->updated_at   = null;
        $menu->save();
    }
}
