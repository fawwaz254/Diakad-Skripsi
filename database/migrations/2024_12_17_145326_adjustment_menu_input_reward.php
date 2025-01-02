<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustmentMenuInputReward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = Menu::where('nm_menu', 'Input Aktivitas Bulanan')->whereHas('modul', function($q){
            $q->where('id_role', 3);
        })->first();
        $menu->akses = 0;
        $menu->save();

        $menu = Menu::where('nm_menu', 'Input Reward Bulanan')->whereHas('modul', function($q){
            $q->where('id_role', 2);
        })->first();

        $menu_x = new Menu();
        $menu_x->id_modul = $menu->id_modul;
        $menu_x->nm_menu = 'Input Reward Insidentil';
        $menu_x->page = 'input-reward-insidentil';
        $menu_x->urutan = 4;
        $menu_x->akses = 1;
        $menu_x->save();

        $menu = Menu::where('nm_menu', 'Rekap Reward Siswa')->whereHas('modul', function($q){
            $q->where('id_role', 2);
        })->first();

        $menu->urutan = 5;
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
