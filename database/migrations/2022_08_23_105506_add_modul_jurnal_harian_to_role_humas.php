<?php

use App\Models\Modul;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;

class AddModulJurnalHarianToRoleHumas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // $role_id = Role::where('nm_role', 'Humas')->first()->id_role;

        // $modul = Modul::create([
        //     "id_role"       => $role_id,
        //     "nm_modul"      => "Jurnal Harian",
        //     "route"         => "jurnal-harian",
        //     "urutan"        => 1,
        //     "akses"         => 1,
        //     "created_at"    => $now
        // ]);

        // $modul->menus()->createMany([
        //     [
        //         "nm_menu"      => "Kelompok Jurnal Harian Tendik",
        //         "page"         => "kelompok-jurnal-harian-tendik",
        //         "urutan"       => 1,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ],
        //     [
        //         "nm_menu"      => "Jenis Jurnal Harian",
        //         "page"         => "jenis-jurnal-harian",
        //         "urutan"       => 2,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ],
        //     [
        //         "nm_menu"      => "Laporan Jurnal Harian",
        //         "page"         => "laporan-jurnal-harian",
        //         "urutan"       => 3,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ],

        // ]); //
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
