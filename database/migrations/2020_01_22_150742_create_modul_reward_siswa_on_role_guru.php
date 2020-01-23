<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Menu;

use Carbon\Carbon;

class CreateModulRewardSiswaOnRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $modul              = new Modul;
        $modul->id_modul    = 73;
        $modul->id_role     = 2;
        $modul->nm_modul    = 'Reward Siswa';
        $modul->route       = 'reward-siswa';
        $modul->urutan      = 6;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->created_by  = 'A8bT515358553655b8b4b05a6d86';
        $modul->updated_at  = $now;
        $modul->updated_by  = 'A8bT515358553655b8b4b05a6d86';
        $modul->save();

        $menu               = new Menu;
        $menu->id_modul     = 73;
        $menu->nm_menu      = "Input Reward Siswa";
        $menu->page         = "input-reward-siswa";
        $menu->urutan       = 1;
        $menu->akses        = 1;
        $menu->created_at  = $now;
        $menu->created_by  = 'A8bT515358553655b8b4b05a6d86';
        $menu->updated_at  = $now;
        $menu->updated_by  = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 73;
        $menu->nm_menu      = "Rekap Reward Siswa";
        $menu->page         = "rekap-input-reward-siswa";
        $menu->urutan       = 1;
        $menu->akses        = 1;
        $menu->created_at  = $now;
        $menu->created_by  = 'A8bT515358553655b8b4b05a6d86';
        $menu->updated_at  = $now;
        $menu->updated_by  = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();

        $modul              = Modul::find(61);
        $modul->urutan      = 7;
        $modul->updated_at  = $now;
        $modul->updated_by  = 'A8bT515358553655b8b4b05a6d86';
        $modul->save();

        $modul              = Modul::find(55);
        $modul->urutan      = 8;
        $modul->updated_at  = $now;
        $modul->updated_by  = 'A8bT515358553655b8b4b05a6d86';
        $modul->save();

        $modul              = Modul::find(55);
        $modul->urutan      = 9;
        $modul->updated_at  = $now;
        $modul->updated_by  = 'A8bT515358553655b8b4b05a6d86';
        $modul->save();

        $modul              = Modul::find(36);
        $modul->urutan      = 10;
        $modul->updated_at  = $now;
        $modul->updated_by  = 'A8bT515358553655b8b4b05a6d86';
        $modul->save();

        $modul              = Modul::find(37);
        $modul->urutan      = 11;
        $modul->updated_at  = $now;
        $modul->updated_by  = 'A8bT515358553655b8b4b05a6d86';
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
