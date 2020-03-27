<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Modul;

class CreateMenuInputPerawatanRutinAndBiayaInternal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $modul              = new Modul;
        $modul->id_role     = 10;
        $modul->nm_modul    = "Data Sarpras Gedung";
        $modul->route       = "data-sarpras-gedung";
        $modul->urutan      = 1;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->save();

        $modul              = new Modul;
        $modul->id_role     = 10;
        $modul->nm_modul    = "Data Sarpras Buku/Alat";
        $modul->route       = "perawatan-sarpras";
        $modul->urutan      = 5;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->save();

        $modul              = new Modul;
        $modul->id_role     = 10;
        $modul->nm_modul    = "Pengadaan/Perawatan Sarpras";
        $modul->route       = "data-sarpras-buku-alat";
        $modul->urutan      = 3;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->save();

        // insert menu
        $menu               = new Menu;
        $menu->id_modul     = 68;
        $menu->nm_menu      = "Input Perawatan Rutin";
        $menu->page         = "input-perawatan-rutin";
        $menu->urutan       = 2;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 28;
        $menu->nm_menu      = "Data Biaya Internal";
        $menu->page         = "biaya-internal";
        $menu->urutan       = 2;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 28;
        $menu->nm_menu      = "Data Detail Biaya Internal";
        $menu->page         = "detail-biaya-internal";
        $menu->urutan       = 3;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        // make object to find id
        $menu              	= Menu::find(110);
        $menu->urutan      	= 4;
        $menu->updated_at  	= $now;
        $menu->save();

        // make object to find id
        $menu              	= Menu::find(111);
        $menu->urutan      	= 5;
        $menu->updated_at  	= $now;
        $menu->save();

        // make object to find id
        $menu              	= Menu::find(113);
        $menu->urutan      	= 6;
        $menu->updated_at  	= $now;
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
