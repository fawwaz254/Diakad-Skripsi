<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class RemoveSomeMenuRolePendidikan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $now = Carbon::now();
        $role_id = Role::where('nm_role', 'Pendidikan')->first()->id_role;

        // remove menu cek pembayaran siswa dan modul pembayaran

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Pembayaran')->first();

        $menu = Menu::where('id_modul', $modul->id_modul)->where('nm_menu', 'Cek Pembayaran Siswa')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $modul->deleted_at = $now;
        $modul->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $modul->save();
        $modul->delete();

        // remove menu jurnal tindakan, nilai tryout, nilai kbm modul laporan akademik

        $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Laporan Akademik')->first()->id_modul;

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Jurnal Tindakan')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Nilai Try Out')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Nilai KBM')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();
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
