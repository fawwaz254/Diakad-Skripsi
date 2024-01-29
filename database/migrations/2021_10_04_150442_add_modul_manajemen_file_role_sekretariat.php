<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddModulManajemenFileRoleSekretariat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Sekretariat')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Manajemen File",
            "route"         => "manajemen-file",
            "urutan"        => 1,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Data Kategori",
                "page"         => "data-kategori",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Data Sub Kategori",
                "page"         => "data-sub-kategori",
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
