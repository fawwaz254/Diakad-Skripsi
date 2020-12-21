<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;

class AddingSomeModulsMenusRoleRaporBukuInduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = new Modul;
        $modul->id_role = 17;
        $modul->nm_modul = 'Rapor';
        $modul->route = 'rapor';
        $modul->urutan = 1;
        $modul->akses = 1;
        $modul->save();

        $menu               = new Menu;
        $menu->id_modul     = 0;
        $menu->nm_menu      = 'Cari Siswa';
        $menu->page         = "cari-siswa";
        $menu->urutan       = 1;
        $menu->akses        = 1;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 0;
        $menu->nm_menu      = 'Cetak by Kelas';
        $menu->page         = "cetak-by-kelas";
        $menu->urutan       = 2;
        $menu->akses        = 1;
        $menu->save();
        
        $modul = new Modul;
        $modul->id_role = 17;
        $modul->nm_modul = 'Buku Induk';
        $modul->route = 'buku-induk';
        $modul->urutan = 2;
        $modul->akses = 1;
        $modul->save();

        $menu               = new Menu;
        $menu->id_modul     = 0;
        $menu->nm_menu      = 'Cari Siswa';
        $menu->page         = "cari-siswa";
        $menu->urutan       = 1;
        $menu->akses        = 1;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 0;
        $menu->nm_menu      = 'Cetak by Kelas';
        $menu->page         = "cetak-by-kelas";
        $menu->urutan       = 2;
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
