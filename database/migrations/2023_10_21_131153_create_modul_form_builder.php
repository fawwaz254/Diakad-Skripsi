<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class CreateModulFormBuilder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Humas')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Form Builder",
            "route"         => "form-builder",
            "urutan"        => 1,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "List Form",
                "page"         => "list-form",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Rekap Form Harian",
                "page"         => "rekap-form-harian",
                "urutan"       => 2,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Rekap Form Bebas",
                "page"         => "rekap-form-bebas",
                "urutan"       => 3,
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
    { }
}
