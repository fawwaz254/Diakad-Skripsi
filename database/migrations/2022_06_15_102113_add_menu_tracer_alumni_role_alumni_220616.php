<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddMenuTracerAlumniRoleAlumni220616 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $now = Carbon::now();

        // $role_id = Role::where('nm_role', 'Alumni')->first()->id_role;

        // $modul = Modul::create([
        //     "id_role"       => $role_id,
        //     "nm_modul"      => "Alumni",
        //     "route"         => "alumni",
        //     "urutan"        => 1,
        //     "akses"         => 1,
        //     "created_at"    => $now
        // ]);

        // $modul->menus()->createMany([
        //     [
        //         "nm_menu"      => "Tracer Alumni",
        //         "page"         => "tracer-alumni",
        //         "urutan"       => 1,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ],
        // ]);
        //   Modul::where('nm_modul', 'Tracer Study')->where('id_role', '12')->first()->delete();
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
