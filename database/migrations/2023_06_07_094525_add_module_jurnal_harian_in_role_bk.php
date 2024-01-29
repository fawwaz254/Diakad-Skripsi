<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Models\Modul;
use App\Models\Role;

class AddModuleJurnalHarianInRoleBk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Bimbingan Konseling')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Jurnal Harian",
            "route"         => "jurnal-harian",
            "urutan"        => 4,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Tambah Jurnal Harian",
                "page"         => "tambah-jurnal-harian",
                "urutan"       => 1,
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
    }
}
