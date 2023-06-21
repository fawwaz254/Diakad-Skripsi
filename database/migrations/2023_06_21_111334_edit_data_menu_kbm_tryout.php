<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditDataMenuKbmTryout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = Menu::where('nm_menu','Input Nilai KBM/Try Out')->first();
        $menu->nm_menu = 'Input Nilai KBM';
        $menu->save();
        $menu1 = Menu::where('nm_menu','Lihat Nilai KBM/Try Out')->where('id_modul',13)->first();
        $menu1->nm_menu = 'Lihat Nilai KBM';
        $menu1->save();
        $menu2 = Menu::where('nm_menu','Lihat Nilai KBM/Try Out')->where('id_modul',38)->first();
        $menu2->nm_menu = 'Lihat Nilai KBM';
        $menu2->save();
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
