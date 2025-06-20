<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Modul;

class AddMenuKomponenNilai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::where('nm_modul', 'Rapor Sisipan')->where('id_role', '7')->first();

        // $menu = new Menu;
        // $menu->id_modul = $modul->id_modul;
        // $menu->nm_menu = 'Komponen Nilai';
        // $menu->page = 'komponen-nilai';
        // $menu->urutan = 1;
        // $menu->akses = 1;
        // $menu->save();

        // $modul = Modul::where('id_role', '7')->where('nm_modul', 'Rapor Sisipan')->first();

        // $modul->menus()->where('nm_menu', 'Daftar Nilai STS')->update([
        //     "urutan" => "2"
        // ]);
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
