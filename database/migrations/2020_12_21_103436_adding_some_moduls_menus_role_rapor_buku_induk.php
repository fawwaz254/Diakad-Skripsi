<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class AddingSomeModulsMenusRoleRaporBukuInduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Rapor & Buku Induk')->first()->id_role;

        $modul_1 = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Rapor",
            "route"         => "rapor",
            "urutan"        => 1,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul_1->menus()->createMany([
            [
                "nm_menu"      => "Cari Siswa",
                "page"         => "cari-siswa",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Cetak by Kelas",
                "page"         => "cetak-by-kelas",
                "urutan"       => 2,
                "akses"        => 1,
                "created_at"   => $now
            ],
        ]);

        $modul_2 = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Buku Induk",
            "route"         => "buku-induk",
            "urutan"        => 1,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul_2->menus()->createMany([
            [
                "nm_menu"      => "Cari Siswa",
                "page"         => "cari-siswa",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Cetak by Kelas",
                "page"         => "cetak-by-kelas",
                "urutan"       => 2,
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
