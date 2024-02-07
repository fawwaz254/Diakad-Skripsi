<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\Modul;
use Carbon\Carbon;


class CreateModulAsrama extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Humas')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Asrama",
            "route"         => "asrama",
            "urutan"        => 10,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Data Siswa Asrama",
                "page"         => "data-siswa-asrama",
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
        // Schema::dropIfExists('modul_asrama');
    }
}
