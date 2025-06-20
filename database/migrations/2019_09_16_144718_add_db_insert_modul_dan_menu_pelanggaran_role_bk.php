<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

use App\Models\Modul as Modul;
use App\Models\Menu as Menu;

class AddDbInsertModulDanMenuPelanggaranRoleBk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // insert modul
        $modul              = new Modul;
        $modul->id_role     = 5;
        $modul->nm_modul    = "Data Pelanggaran";
        $modul->route       = "data-pelanggaran";
        $modul->urutan      = 1;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->save();

        // update urutan modul lain
        // $modul               = Modul::find(16);
        // $modul->urutan       = 2;
        // $modul->updated_at   = $now;
        // $modul->save();


        // insert menu
        $menu               = new Menu;
        $menu->id_modul     = 70;
        $menu->nm_menu      = "Kategori Pelanggaran";
        $menu->page         = "kategori-pelanggaran";
        $menu->urutan       = 1;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 70;
        $menu->nm_menu      = "Sub-Kategori Pelanggaran";
        $menu->page         = "subkategori-pelanggaran";
        $menu->urutan       = 2;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 70;
        $menu->nm_menu      = "Kesimpulan Pelanggaran";
        $menu->page         = "kesimpulan-pelanggaran";
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
