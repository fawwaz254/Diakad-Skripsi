<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddModulFormGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Guru')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Form Guru",
            "route"         => "form-guru",
            "urutan"        => 11,
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
            ],
            // [
            //     "nm_menu"      => "Input Form Bebas",
            //     "page"         => "input-form-bebas",
            //     "urutan"       => 2,
            //     "akses"        => 1,
            //     "created_at"   => $now
            // ],
            // [
            //     "nm_menu"      => "Rekap Form Harian",
            //     "page"         => "rekap-form-harian",
            //     "urutan"       => 3,
            //     "akses"        => 1,
            //     "created_at"   => $now
            // ],
            // [
            //     "nm_menu"      => "Rekap Form Bebas",
            //     "page"         => "rekap-form-bebas",
            //     "urutan"       => 4,
            //     "akses"        => 1,
            //     "created_at"   => $now
            // ],
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
