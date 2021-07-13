<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class RemoveSomeMenuRolePelatihEkskul extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $role_id = Role::where('nm_role', 'Pelatih Ekskul')->first()->id_role;

        // remove menu lihat dokumen, upload dokumen modul kesekretariatan

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kesekretariatan')->first();

        $menu = Menu::where('id_modul',$modul->id_modul)->where('nm_menu','Lihat Dokumen')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $menu = Menu::where('id_modul',$modul->id_modul)->where('nm_menu','Upload Dokumen')->first();
        $menu->deleted_at = $now;
        $menu->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $menu->save();
        $menu->delete();

        $modul->deleted_at = $now;
        $modul->deleted_by = 'A8bT515358553655b8b4b05a6d86';
        $modul->save();
        $modul->delete();
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
