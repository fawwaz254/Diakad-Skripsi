<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HideModulKesehatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu1 = Menu::where('nm_menu', 'Rekap Kesehatan Guru/Tendik')->get();
        $menu2 = Menu::where('nm_menu', 'Mengisi form kesehatan')->get();

        foreach ($menu1 as $menu) {
            $menu->akses = 0;
            $menu->save();
        }

        foreach ($menu2 as $menu) {
            $menu->akses = 0;
            $menu->save();
        }
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
