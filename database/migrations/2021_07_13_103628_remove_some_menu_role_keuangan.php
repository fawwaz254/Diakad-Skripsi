<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class RemoveSomeMenuRoleKeuangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // remove menu cari siswa modul laporan keuangan

        $now = Carbon::now();
        // $role_id = Role::where('nm_role', 'Keuangan')->first()->id_role;

        // $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Laporan Keuangan')->first()->id_modul;
        // $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Cari Siswa')->first();
        // $menu->deleted_at = $now;
        // $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        // $menu->save();
        // $menu->delete();
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
