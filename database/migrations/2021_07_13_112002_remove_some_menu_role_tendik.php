<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class RemoveSomeMenuRoleTendik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $role_id = Role::where('nm_role', 'Tenaga Pendidik')->first()->id_role;

        // remove menu pesan masuk, pesan keluar, pesan dihapus modul pesan

        $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Pesan')->first()->id_modul;

        $menu = Menu::where('id_modul',$modul_id)->where('nm_menu','Pesan Masuk')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $menu = Menu::where('id_modul',$modul_id)->where('nm_menu','Pesan Keluar')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $menu = Menu::where('id_modul',$modul_id)->where('nm_menu','Pesan Dihapus')->first();
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
