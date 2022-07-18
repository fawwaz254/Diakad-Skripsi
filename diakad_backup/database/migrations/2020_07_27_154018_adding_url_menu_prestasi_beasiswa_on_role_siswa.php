<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;

class AddingUrlMenuPrestasiBeasiswaOnRoleSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = Menu::where('id_modul', 15)->where('nm_menu', 'Prestasi')->first();
        $menu->page = 'prestasi';
        $menu->save();
        
        $menu = Menu::where('id_modul', 15)->where('nm_menu', 'Beasiswa')->first();
        $menu->page = 'beasiswa';
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
