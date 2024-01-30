<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class AddMonitoringKesehatanInKesiswaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role_id = Role::where('nm_role', 'Bimbingan Konseling')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Monitoring Kesehatan')->first();

        $modul->menus()->where('nm_menu', 'Rekap Kesehatan Siswa')->update([
            "page" => "rekap-kesehatan"
        ]);

        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Kesiswaan')->first()->id_role;

        $modul              = new Modul;
        $modul->id_role     = $role_id;
        $modul->nm_modul    = "Monitoring Kesehatan";
        $modul->route       = "monitoring-kesehatan";
        $modul->urutan      = 0;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->save();

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Rekap Kesehatan Siswa",
                "page"         => "rekap-kesehatan",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
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
