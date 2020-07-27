<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;

class AddingMenuPembayaranOnlineOnWaliKelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = new Menu;
        $menu->id_modul = 36;
        $menu->nm_menu = 'Pembayaran Online';
        $menu->page = 'pembayaran-online';
        $menu->urutan = 5;
        $menu->akses = 1;
        $menu->created_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();

        $menu = Menu::where('id_modul', 36)->where('nm_menu', 'Data Inventaris Kelas/Sarana')->first();
        $menu->urutan = 6;
        $menu->save();

        $menu = Menu::where('id_modul', 36)->where('nm_menu', 'Input Pelanggaran Siswa')->first();
        $menu->urutan = 7;
        $menu->save();

        $menu = Menu::where('id_modul', 36)->where('nm_menu', 'Home Visit')->first();
        $menu->urutan = 8;
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
