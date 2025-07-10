<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

class AddMenuInputSkpiSiswaInWaliKelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $id_modul = Modul::where('id_role', 2)->where('nm_modul', 'Wali Kelas')->first()->id_modul;

        // $menu = new Menu;
        // $menu->id_modul = $id_modul;
        // $menu->nm_menu = 'Input SKPI Siswa';
        // $menu->page = 'input-skpi-siswa';
        // $menu->urutan = 7;
        // $menu->akses = 1;
        // $menu->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
