<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

use App\Models\Modul as Modul;
use App\Models\Menu as Menu;

class AddDbMenuPengadaanSarprasRoleSarpras extends Migration
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

        // update modul
        $modul               = Modul::find(68);
        $modul->nm_modul     = "Pengadaan/Perawatan Sarpras";
        $modul->updated_at   = $now;
        $modul->save();

        // update menu
        $menu               = Menu::find(234);
        $menu->urutan       = 2;
        $menu->updated_at   = $now;
        $menu->save();

        // add menu
        $menu               = new Menu;
        $menu->id_modul     = 68;
        $menu->nm_menu      = "Pengadaan Barang/Sarpras";
        $menu->page         = "pengadaan-sarpras";
        $menu->urutan       = 1;
        $menu->akses        = 1;
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
