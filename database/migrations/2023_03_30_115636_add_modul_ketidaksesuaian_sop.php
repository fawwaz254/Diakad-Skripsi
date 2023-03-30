<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Models\Modul;
use App\Models\Role;

class AddModulKetidaksesuaianSop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Guru')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Ketidaksesuaian SOP",
            "route"         => "ketidaksesuaian-sop",
            "urutan"        => 6,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Input Ketidaksesuaian SOP",
                "page"         => "input-ketidaksesuaian-sop",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Ketidaksesuaian SOP Pribadi",
                "page"         => "ketidaksesuaian-sop-pribadi",
                "urutan"       => 2,
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
