<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Menu;
use App\Models\Modul;


class AddMenuJenisMgmp220608 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::where('nm_modul', 'MGMP')->where('id_role', '7')->first();
        // $menu = new Menu;
        // $menu->id_modul = $modul->id_modul;
        // $menu->nm_menu = 'Jenis MGMP';
        // $menu->page = 'jenis-mgmp';
        // $menu->urutan = 1;
        // $menu->akses = 1;
        // $menu->save();
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
