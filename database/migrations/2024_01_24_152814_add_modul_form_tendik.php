<?php

use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
use App\Models\Modul;
use App\Models\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModulFormTendik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Tenaga Pendidik')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Form Tendik",
            "route"         => "form-tendik",
            "urutan"        => 5,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Input Form Harian",
                "page"         => "input-form-harian",
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
        //
    }
}
