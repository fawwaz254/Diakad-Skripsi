<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Menu;

class AddingModulDanMenuRapbRoleKeuangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = new Modul;
        $modul->id_role = 6;
        $modul->nm_modul = 'RAPB';
        $modul->route = 'rapb';
        $modul->urutan = 3;
        $modul->akses = 1;
        $modul->save();


        $menu = new Menu;
        $menu->id_modul = 72;
        $menu->nm_menu = 'Kategori Penerimaan';
        $menu->page = 'kategori-penerimaan';
        $menu->urutan = 1;
        $menu->akses = 1;
        $menu->save();

        $menu = new Menu;
        $menu->id_modul = 72;
        $menu->nm_menu = 'Kategori Pengeluaran';
        $menu->page = 'kategori-pengeluaran';
        $menu->urutan = 2;
        $menu->akses = 1;
        $menu->save();

        $menu = new Menu;
        $menu->id_modul = 72;
        $menu->nm_menu = 'Input RAPB';
        $menu->page = 'input-rapb';
        $menu->urutan = 3;
        $menu->akses = 1;
        $menu->save();

        $menu = new Menu;
        $menu->id_modul = 72;
        $menu->nm_menu = 'Realisasi RAPB';
        $menu->page = 'realisasi-rapb';
        $menu->urutan = 4;
        $menu->akses = 1;
        $menu->save();


        // disable Modul Pemasukan Sekolah dan Pengeluaran Sekolah
        // $modul = Modul::find(69);
        // $menu->akses = 0;
        // $menu->save();

        // $modul = Modul::find(64);
        // $menu->akses = 0;
        // $menu->save();

        // ubah nm_realisasi menjadi text agar bisa memakai CKEditor
        Schema::table('realisasi', function (Blueprint $table) {
            $table->text('nm_realisasi')->after('id_rpb_sarpras')->nullable()->change();
        });

        Schema::table('realisasi_pak', function (Blueprint $table) {
            $table->text('nm_realisasi_pak')->after('id_rpb_sarpras')->nullable()->change();
        });

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
