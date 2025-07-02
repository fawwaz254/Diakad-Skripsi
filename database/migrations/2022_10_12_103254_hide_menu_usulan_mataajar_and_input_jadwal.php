<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Modul;
use App\Models\Role;

class HideMenuUsulanMataajarAndInputJadwal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $role = Role::where('nm_role', 'Guru')->first();

        // $modul = Modul::where('id_role', $role->id_role)->where('nm_modul', 'Jadwal')->first();

        // $modul->menus()->where('nm_menu', 'Input Jadwal')->update([
        //     "akses" => 0
        // ]);
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
