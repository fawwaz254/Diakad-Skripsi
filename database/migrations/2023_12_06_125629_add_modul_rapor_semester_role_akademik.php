<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Models\Modul;
use App\Models\Role;


class AddModulRaporSemesterRoleAkademik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Akademik')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Rapor Semester",
            "route"         => "rapor-semester",
            "urutan"        => 3,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Jenis Rapor",
                "page"         => "jenis-rapor",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Komponen Mata Pelajaran",
                "page"         => "komponen-mata-pelajaran",
                "urutan"       => 2,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Nilai Rapor",
                "page"         => "nilai-rapor",
                "urutan"       => 3,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Cetak Rapor",
                "page"         => "cetak-rapor",
                "urutan"       => 4,
                "akses"        => 1,
                "created_at"   => $now
            ]

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
