<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class ChangeModulAkademikRoleSiswa extends Migration
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

        // change urutan

        $modul->menus()->where('nm_menu', 'Jadwal Ujian')->update([
            "urutan" => "8",
            "page"   => "jadwal-ujian"
        ]);

        $modul->menus()->where('nm_menu', 'Magang')->update([
            "urutan" => "9",
            "page" => "magang"
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
