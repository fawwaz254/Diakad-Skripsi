<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

use App\Models\Modul as Modul;
use App\Models\Menu as Menu;

class AddDbInsertModulDanMenuPemasukanRoleKeuangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // insert modul
        $modul              = new Modul;
        $modul->id_role     = 9;
        $modul->nm_modul    = "Pemasukan Sekolah";
        $modul->route       = "pemasukan-sekolah";
        $modul->urutan      = 3;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->save();

        // update urutan modul lain
        $modul               = Modul::find(64);
        $modul->urutan       = 4;
        $modul->updated_at   = $now;
        $modul->save();

        $modul               = Modul::find(30);
        $modul->urutan       = 5;
        $modul->updated_at   = $now;
        $modul->save();


        // insert menu
        $menu               = new Menu;
        $menu->id_modul     = 69;
        $menu->nm_menu      = "Kategori Pemasukan";
        $menu->page         = "kategori-pemasukan";
        $menu->urutan       = 1;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 69;
        $menu->nm_menu      = "Sub-Kategori Pemasukan";
        $menu->page         = "subkategori-pemasukan";
        $menu->urutan       = 2;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 69;
        $menu->nm_menu      = "Input Pemasukan";
        $menu->page         = "input-pemasukan";
        $menu->urutan       = 3;
        $menu->akses        = 1;
        $menu->created_at   = $now;
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
