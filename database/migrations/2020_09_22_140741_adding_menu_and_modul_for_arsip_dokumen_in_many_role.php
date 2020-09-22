<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Menu;

class AddingMenuAndModulForArsipDokumenInManyRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = new Modul;
        $modul->id_role = 2;
        $modul->nm_modul = 'Kesekretariatan';
        $modul->route = 'kesekretariatan';
        $modul->urutan = 1;
        $modul->akses = 1;
        $modul->save();


        $menu = new Menu;
        $menu->id_modul = 81;
        $menu->nm_menu = 'Lihat Dokumen';
        $menu->page = 'dokumen';
        $menu->urutan = 1;
        $menu->akses = 1;
        $menu->save();

        $modul = new Modul;
        $modul->id_role = 13;
        $modul->nm_modul = 'Kesekretariatan';
        $modul->route = 'kesekretariatan';
        $modul->urutan = 1;
        $modul->akses = 1;
        $modul->save();


        $menu = new Menu;
        $menu->id_modul = 82;
        $menu->nm_menu = 'Lihat Dokumen';
        $menu->page = 'dokumen';
        $menu->urutan = 1;
        $menu->akses = 1;
        $menu->save();

        $modul = new Modul;
        $modul->id_role = 15;
        $modul->nm_modul = 'Kesekretariatan';
        $modul->route = 'kesekretariatan';
        $modul->urutan = 1;
        $modul->akses = 1;
        $modul->save();


        $menu = new Menu;
        $menu->id_modul = 83;
        $menu->nm_menu = 'Lihat Dokumen';
        $menu->page = 'dokumen';
        $menu->urutan = 1;
        $menu->akses = 1;
        $menu->save();

        $modul = new Modul;
        $modul->id_role = 3;
        $modul->nm_modul = 'Dokumen Publik';
        $modul->route = 'kesekretariatan';
        $modul->urutan = 1;
        $modul->akses = 1;
        $modul->save();


        $menu = new Menu;
        $menu->id_modul = 84;
        $menu->nm_menu = 'Lihat Dokumen';
        $menu->page = 'dokumen';
        $menu->urutan = 1;
        $menu->akses = 1;
        $menu->save();

        $modul = new Modul;
        $modul->id_role = 4;
        $modul->nm_modul = 'Dokumen Publik';
        $modul->route = 'kesekretariatan';
        $modul->urutan = 1;
        $modul->akses = 1;
        $modul->save();


        $menu = new Menu;
        $menu->id_modul = 85;
        $menu->nm_menu = 'Lihat Dokumen';
        $menu->page = 'dokumen';
        $menu->urutan = 1;
        $menu->akses = 1;
        $menu->save();


        $menu = Menu::where(['id_modul' => 16, 'nm_menu' => 'Jurnal Tindakan'])->first();
        $menu->page = 'jurnal-tindakan';
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
