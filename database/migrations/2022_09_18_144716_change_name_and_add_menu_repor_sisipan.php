<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNameAndAddMenuReporSisipan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = Menu::where('nm_menu', 'Laporan Repor Sisipan')->first();
        $menu->update([
            "nm_menu" => 'Daftar Nilai STS',
            "page" => 'daftar-nilai-sts'
        ]);

        $modul = Modul::where('nm_modul', 'Rapor Sisipan')->where('id_role', '2')->first();

        $menu2 = new Menu;
        $menu2->id_modul = $modul->id_modul;
        $menu2->nm_menu = 'Daftar Nilai SAS';
        $menu2->page = 'daftar-nilai-sas';
        $menu2->urutan = 2;
        $menu2->akses = 1;
        $menu2->save();
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
