<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;

class MoveModulSiswaPendaftaranWisudaRolePendidikanToRoleKesiswaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data_menu = Menu::where('id_modul', 2)->get();

        foreach($data_menu as $menu){
            $new_menu = new Menu;
            $new_menu->id_modul = 17;
            $new_menu->nm_menu = $menu->nm_menu;
            $new_menu->page = $menu->page;
            $new_menu->urutan = $menu->urutan;
            $new_menu->akses = $menu->akses;
            $new_menu->created_by = $menu->created_by;
            $new_menu->updated_by = $menu->updated_by;
            $new_menu->deleted_by = $menu->deleted_by;
            $new_menu->save();
        }

        $modul = Modul::find(4);
        $modul->id_role = 6;
        $modul->save();

        $modul = Modul::find(6);
        $modul->id_role = 6;
        $modul->save();
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
