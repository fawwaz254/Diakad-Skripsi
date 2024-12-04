<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeGuruRewardSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = Menu::where('nm_menu', 'Input Reward Siswa')->whereHas('modul', function($q){
            $q->where('id_role', 2);
        })->first();
        $menu->nm_menu = 'Input Reward Harian';
        $menu->page = 'input-reward-harian';
        $menu->urutan = 1;
        $menu->save();

        $menu_x = new Menu();
        $menu_x->id_modul = $menu->id_modul;
        $menu_x->nm_menu = 'Input Reward Mingguan';
        $menu_x->page = 'input-reward-mingguan';
        $menu_x->urutan = 2;
        $menu_x->akses = 1;
        $menu_x->save();

        $menu_x = new Menu();
        $menu_x->id_modul = $menu->id_modul;
        $menu_x->nm_menu = 'Input Reward Bulanan';
        $menu_x->page = 'input-reward-bulanan';
        $menu_x->urutan = 3;
        $menu_x->akses = 1;
        $menu_x->save();

        $menu = Menu::where('nm_menu', 'Rekap Reward Siswa')->whereHas('modul', function($q){
            $q->where('id_role', 2);
        })->first();
        $menu->urutan = 4;
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
