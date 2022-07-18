<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class AddModulLaporanRoleGuruTendik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Guru')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Laporan",
            "route"         => "laporan",
            "urutan"        => 13,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Kerja Harian",
                "page"         => "kerja-harian",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
        ]);

        $role_id2 = Role::where('nm_role', 'Tenaga Pendidik')->first()->id_role;

        $modul2 = Modul::create([
            "id_role"       => $role_id2,
            "nm_modul"      => "Laporan",
            "route"         => "laporan",
            "urutan"        => 3,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul2->menus()->createMany([
            [
                "nm_menu"      => "Kerja Harian",
                "page"         => "kerja-harian",
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
