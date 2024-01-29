<?php

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlumniModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $humasRoleId = Role::where('nm_role', 'Humas')->first()->id_role;

        $alumniModule = Modul::create([
            "id_role"       => $humasRoleId,
            "nm_modul"      => "Alumni",
            "route"         => "alumni",
            "urutan"         => 2,
            "akses"         => 1,
            "created_at"    => Carbon::now()
        ]);

        $alumniModule->menus()->createMany([
            [
                "nm_menu"      => "Tracer Alumni",
                "page"         => "/",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => Carbon::now()
            ],
            [
                "nm_menu"      => "Tambah Alumni",
                "page"         => "add",
                "urutan"       => 2,
                "akses"        => 1,
                "created_at"   => Carbon::now()
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
