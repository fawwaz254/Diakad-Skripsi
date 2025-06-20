<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class AddMenuSettingKelasDaringRoleAkademik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $now = Carbon::now();

        // $role_id = Role::where('nm_role', 'Akademik')->first()->id_role;

        // $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kelas Daring')->first();

        // $modul->menus()->createMany([
        //     [
        //         "nm_menu"      => "Setting Kelas Daring",
        //         "page"         => "setting-kelas-daring",
        //         "urutan"       => 3,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ],
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
