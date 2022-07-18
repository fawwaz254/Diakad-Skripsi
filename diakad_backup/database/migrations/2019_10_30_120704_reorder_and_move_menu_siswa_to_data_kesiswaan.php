<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;

class ReorderAndMoveMenuSiswaToDataKesiswaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $menu = Menu::find(244);
        $menu->id_modul = 71;
        $menu->urutan = 1;
        $menu->save();
        
        $menu = Menu::find(245);
        $menu->id_modul = 71;
        $menu->urutan = 2;
        $menu->save();
        
        $menu = Menu::find(202);
        $menu->id_modul = 71;
        $menu->urutan = 3;
        $menu->save();
        
        $menu = Menu::find(203);
        $menu->id_modul = 71;
        $menu->urutan = 4;
        $menu->save();
        
        $menu = Menu::find(219);
        $menu->id_modul = 71;
        $menu->urutan = 5;
        $menu->save();
        
        $menu = Menu::find(208);
        $menu->id_modul = 71;
        $menu->urutan = 6;
        $menu->save();


        $menu = Menu::find(240);
        $menu->urutan = 1;
        $menu->save();

        $menu = Menu::find(68);
        $menu->urutan = 2;
        $menu->save();

        $menu = Menu::find(241);
        $menu->urutan = 3;
        $menu->save();

        $menu = Menu::find(69);
        $menu->urutan = 4;
        $menu->save();

        $menu = Menu::find(242);
        $menu->urutan = 5;
        $menu->save();

        $menu = Menu::find(243);
        $menu->urutan = 6;
        $menu->save();

        $menu = Menu::find(246);
        $menu->urutan = 7;
        $menu->save();

        $menu = Menu::find(247);
        $menu->urutan = 8;
        $menu->save();

        $menu = Menu::find(248);
        $menu->urutan = 9;
        $menu->save();

        $menu = Menu::find(249);
        $menu->urutan = 10;
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
