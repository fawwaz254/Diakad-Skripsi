<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixingRewardSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $menu = Menu::where('nm_menu', 'Approve Reward Siswa')->whereHas('modul', function($q){
            $q->where('id_role', 2);
        })->first();
        $menu->akses = 0;
        $menu->save();

        $menu = Menu::where('nm_menu', 'Input Aktivitas Reward')->whereHas('modul', function($q){
            $q->where('id_role', 3);
        })->first();
        $menu->nm_menu = 'Input Aktivitas Harian';
        $menu->page = 'input-aktivitas-harian';
        $menu->urutan = 1;
        $menu->save();

        $menu = Menu::where('nm_menu', 'Aktivitas Reward Saya')->whereHas('modul', function($q){
            $q->where('id_role', 3);
        })->first();
        $menu->nm_menu = 'Input Aktivitas Mingguan';
        $menu->page = 'input-aktivitas-mingguan';
        $menu->urutan = 2;
        $menu->save();

        $menu_x = new Menu();
        $menu_x->id_modul = $menu->id_modul;
        $menu_x->nm_menu = 'Input Aktivitas Bulanan';
        $menu_x->page = 'input-aktivitas-bulanan';
        $menu_x->urutan = 3;
        $menu_x->akses = 1;
        $menu_x->save();

        $menu = Menu::where('nm_menu', 'Rekap Aktivitas Reward')->whereHas('modul', function($q){
            $q->where('id_role', 3);
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
