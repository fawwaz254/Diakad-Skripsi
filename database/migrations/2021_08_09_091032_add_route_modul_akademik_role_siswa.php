<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddRouteModulAkademikRoleSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Siswa')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Akademik')->first();

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Jadwal Kelas Daring",
                "page"         => "jadwal-kelas-daring",
                "urutan"       => 7,
                "akses"        => 1,
                "created_at"   => $now
            ],
        ]);

        // change urutan

        $modul->menus()->where('nm_menu', 'Jadwal Ujian')->update([
            "page" => "8"
        ]);

        $modul->menus()->where('nm_menu', 'Magang')->update([
            "page" => "9"
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
