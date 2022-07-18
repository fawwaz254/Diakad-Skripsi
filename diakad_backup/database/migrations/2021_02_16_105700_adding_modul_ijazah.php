<?php

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingModulIjazah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Kesiswaan')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Ijazah",
            "route"         => "ijazah",
            "urutan"        => 9,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Pengambilan Ijazah",
                "page"         => "pengambilan-ijazah",
                "urutan"       => 1,
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
        $role_id = Role::where('nm_role', 'Kesiswaan')->first()->id_role;

        $modul = Modul::where('route', 'ijazah')->where('id_role', $role_id)->first();

        $menu = Menu::where('id_modul', $modul->id_modul)->where('page', 'pengambilan-ijazah')->first();
        $menu->forceDelete();

        $modul->forceDelete();
    }
}
