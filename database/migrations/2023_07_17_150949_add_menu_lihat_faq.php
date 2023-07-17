<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

class AddMenuLihatFaq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::where('path','guru')->first();
        $modul = Modul::where('id_role',$role->id_role)->where('route','faq')->first();
        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Lihat FAQ';
        $menu->page = 'lihat-faq';
        $menu->urutan = 1;
        $menu->akses = 1;
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
