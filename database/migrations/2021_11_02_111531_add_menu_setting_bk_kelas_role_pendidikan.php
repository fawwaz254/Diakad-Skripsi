<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class AddMenuSettingBkKelasRolePendidikan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Pendidikan')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Setting Kelas')->first();

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Setting BK Kelas",
                "page"         => "setting-bk-kelas",
                "urutan"       => 5,
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
