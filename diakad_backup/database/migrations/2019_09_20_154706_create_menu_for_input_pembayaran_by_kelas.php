<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;

class CreateMenuForInputPembayaranByKelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $menu               = new Menu;
        $menu->id_modul     = 29;
        $menu->nm_menu      = "Input Pembayaran By Kelas";
        $menu->page         = "pembayaran-by-kelas";
        $menu->urutan       = 4;
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
