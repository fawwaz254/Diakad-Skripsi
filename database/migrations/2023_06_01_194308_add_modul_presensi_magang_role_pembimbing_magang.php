<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Models\Modul;
use App\Models\Role;


class AddModulPresensiMagangRolePembimbingMagang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // $role_id = Role::where('nm_role', 'Pembimbing Magang')->first()->id_role;

        // $modul = Modul::create([
        //     "id_role"       => $role_id,
        //     "nm_modul"      => "Presensi Magang",
        //     "route"         => "presensi-magang",
        //     "urutan"        => 1,
        //     "akses"         => 1,
        //     "created_at"    => $now
        // ]);

        // $modul->menus()->createMany([
        //     [
        //         "nm_menu"      => "Input Presensi Magang",
        //         "page"         => "input-presensi-magang",
        //         "urutan"       => 1,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ],
        //     [
        //         "nm_menu"      => "Rekap Presensi Magang",
        //         "page"         => "rekap-presensi-magang",
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
