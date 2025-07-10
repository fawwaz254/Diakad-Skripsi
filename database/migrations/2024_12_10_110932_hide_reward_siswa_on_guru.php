<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideRewardSiswaOnGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $menu = Menu::where('nm_menu', 'Approve Reward Siswa')->whereHas('modul', function($q){
        //     $q->where('id_role', 2);
        // })->first();

        // $modul = Modul::find($menu->id_modul);
        // $modul->akses = 0;
        // $modul->save();
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
