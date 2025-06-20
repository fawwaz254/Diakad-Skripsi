<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class RemoveSomeMenuRoleSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $now = Carbon::now();
        // $role_id = Role::where('nm_role', 'Siswa')->first()->id_role;

        // remove menu absensi kelas modul akademik

        // $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Akademik')->first()->id_modul;

        // $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Absensi Kelas')->first();
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
