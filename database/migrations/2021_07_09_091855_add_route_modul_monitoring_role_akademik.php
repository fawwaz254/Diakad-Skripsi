<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddRouteModulMonitoringRoleAkademik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role_id = Role::where('nm_role', 'Akademik')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Monitoring')->first();

        $modul->update(['route'=>'monitoring']);

        $modul->menus()->where('nm_menu', 'Monitoring Presensi')->update([
            "page" => "monitoring-presensi"
        ]);

        $modul->menus()->where('nm_menu', 'Status Entri Nilai')->update([
            "page" => "status-entri-nilai"
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
