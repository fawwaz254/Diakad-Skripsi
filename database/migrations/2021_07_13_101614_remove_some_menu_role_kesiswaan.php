<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class RemoveSomeMenuRoleKesiswaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $role_id = Role::where('nm_role', 'Kesiswaan')->first()->id_role;

        // remove menu jurnal tindakan modul penanganan siswa
        $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Penanganan Siswa')->first()->id_modul;
        $menu = Menu::where('id_modul',$modul_id)->where('nm_menu','Jurnal Tindakan')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        // remove menu foto , generate nomor induk , report pendaftaran modul pendaftaran
        $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Pendaftaran')->first()->id_modul;

        $menu = Menu::where('id_modul',$modul_id)->where('nm_menu','Foto')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $menu = Menu::where('id_modul',$modul_id)->where('nm_menu','Generate Nomor Induk')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $menu = Menu::where('id_modul',$modul_id)->where('nm_menu','Report Pendaftaran')->first();
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
