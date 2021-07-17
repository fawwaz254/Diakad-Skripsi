<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class AddMenuPengajuanMaganRoleHumas extends Migration
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

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Magang Siswa')->first();

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Pengajuan Magang",
                "page"         => "pengajuan-magang",
                "urutan"       => 9,
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
