<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class UpdateRouteSettingKelasDaringRoleAkademik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $role_id = Role::where('nm_role', 'Akademik')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kelas Daring')->first();

        $modul->menus()->where('nm_menu', 'Setting Kelas Daring')->update([
            "page" => "jadwal-kelas"
        ]);

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
