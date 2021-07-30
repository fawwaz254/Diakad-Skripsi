<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddRouteMenuLaporanWisudaRoleKesiswaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $role_id = Role::where('nm_role', 'Kesiswaan')->first()->id_role;
        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Wisuda')->first();
        $modul->menus()->where('nm_menu', 'Laporan Wisuda')->update([
            "page" => "laporan-wisuda"
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
