<?php

use App\Models\Modul;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;

class AddJurnalPimpinanInRoleAdministrator010922 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Administrator')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Jurnal Pimpinan",
            "route"         => "jurnal-pimpinan",
            "urutan"        => 3,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Tambah Jurnal Pimpinan",
                "page"         => "tambah-jurnal-pimpinan",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Jenis Jurnal Pimpinan",
                "page"         => "jenis-jurnal-pimpinan",
                "urutan"       => 2,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Laporan Jurnal Pimpinan",
                "page"         => "laporan-jurnal-pimpinan",
                "urutan"       => 3,
                "akses"        => 1,
                "created_at"   => $now
            ]

        ]); //
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
