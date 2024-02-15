<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Menu;
use App\Models\Role;
use Carbon\Carbon;

class ChangeRouteRoleHumas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Humas')->first()->id_role;
        $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Data Guru')->first()->id_modul;

        $menu1 = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Data Prestasi Guru')->first();
        $menu1->page = 'data-prestasi';
        $menu1->save();

        $menu2 = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Data Kegiatan Guru')->first();
        $menu2->page = 'data-kegiatan';
        $menu2->save();
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
