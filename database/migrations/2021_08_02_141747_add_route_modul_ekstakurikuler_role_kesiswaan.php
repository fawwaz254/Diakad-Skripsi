<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddRouteModulEkstakurikulerRoleKesiswaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role_id = Role::where('nm_role', 'Kesiswaan')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Ekstrakurikuler')->first();

        $modul->menus()->where('nm_menu', 'Monitoring Absensi Ekskul')->update([
            "page" => "monitoring-absensi-ekskul"
        ]);

        $modul->menus()->where('nm_menu', 'Monitoring Nilai Ekskul')->update([
            "page" => "monitoring-nilai-eksul"
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
