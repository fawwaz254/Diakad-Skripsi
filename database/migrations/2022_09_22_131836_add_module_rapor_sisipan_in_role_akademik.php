<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use App\Models\Modul;
use App\Models\Role;


class AddModuleRaporSisipanInRoleAkademik extends Migration
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

        // $modul = Modul::create([
        //     "id_role"       => $role_id,
        //     "nm_modul"      => "Rapor Sisipan",
        //     "route"         => "rapor-sisipan",
        //     "urutan"        => 2,
        //     "akses"         => 1,
        //     "created_at"    => $now
        // ]);

        // $modul->menus()->createMany([
        //     [
        //         "nm_menu"      => "Daftar Nilai STS",
        //         "page"         => "daftar-nilai-sts",
        //         "urutan"       => 1,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ],
        //     [
        //         "nm_menu"      => "Daftar Nilai SAS",
        //         "page"         => "daftar-nilai-sas",
        //         "urutan"       => 2,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ]
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
