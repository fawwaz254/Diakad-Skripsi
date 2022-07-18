<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;

class RemoveSomeMenuInModulSiswaRolePendidikan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = Menu::find(14);
        $menu->akses = 0;
        $menu->save();
        
        $menu = Menu::find(15);
        $menu->akses = 0;
        $menu->save();
        
        $menu = Menu::find(16);
        $menu->akses = 0;
        $menu->save();
        
        $menu = Menu::find(17);
        $menu->akses = 0;
        $menu->save();
        
        $menu = Menu::find(18);
        $menu->akses = 0;
        $menu->save();
        
        $menu = Menu::find(19);
        $menu->akses = 0;
        $menu->save();
        
        $menu = Menu::find(20);
        $menu->akses = 0;
        $menu->save();
        
        $menu = Menu::find(126);
        $menu->akses = 0;
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
