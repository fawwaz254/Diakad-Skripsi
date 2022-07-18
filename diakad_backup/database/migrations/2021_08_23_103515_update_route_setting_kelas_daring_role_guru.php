<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class UpdateRouteSettingKelasDaringRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $role_id = Role::where('nm_role', 'Guru')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kelas Daring')->first();

        $modul->menus()->where('nm_menu', 'Setting Kelas Daring')->update([
            "akses" => 0
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
