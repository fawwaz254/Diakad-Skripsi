<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class RemoveMenuTambahAlumni extends Migration
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

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Alumni')->first();

        $menu = Menu::where('id_modul', $modul->id_modul)->where('nm_menu', 'Tambah Alumni')->first();
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
