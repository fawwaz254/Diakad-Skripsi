<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class RearrengeMenuModulMagangSiswaRoleHumas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $now = Carbon::now();
        $role_id = Role::where('nm_role', 'Humas')->first()->id_role;

        $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Magang Siswa')->first()->id_modul;

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Pengajuan Siswa Magang')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Approve Siswa Magang')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Pengajuan Magang')->first();
        $menu->urutan = 4;
        $menu->save();

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Komponen Nilai Magang')->first();
        $menu->urutan = 5;
        $menu->save();

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Input Nilai Magang')->first();
        $menu->urutan = 6;
        $menu->save();

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Laporan Magang')->first();
        $menu->urutan = 7;
        $menu->page = 'laporan-magang';
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
