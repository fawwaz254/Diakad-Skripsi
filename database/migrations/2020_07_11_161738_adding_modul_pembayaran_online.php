<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;

class AddingModulPembayaranOnline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu               = new Menu;
        $menu->id_modul     = Modul::where('nm_modul', 'SIM')->first()->id_modul;
        $menu->nm_menu      = 'Pembayaran online';
        $menu->page         = "pembayaran-online";
        $menu->urutan       = 3;
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
