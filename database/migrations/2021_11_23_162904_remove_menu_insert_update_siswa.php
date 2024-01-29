<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Menu;
use App\Models\Role;
use Carbon\Carbon;

class RemoveMenuInsertUpdateSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Kesiswaan')->first()->id_role;
        $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Siswa')->first()->id_modul;

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Insert / Update Siswa')->first();
        $menu->akses = 0;
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
