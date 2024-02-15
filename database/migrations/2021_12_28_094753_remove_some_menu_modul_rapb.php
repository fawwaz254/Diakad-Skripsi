<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Menu;
use App\Models\Role;
use Carbon\Carbon;

class RemoveSomeMenuModulRapb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Keuangan')->first()->id_role;
        $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'RAPB')->first()->id_modul;

        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Input RAPB')->first();
        $menu->akses = 0;
        $menu->save();

        $menu2 = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Realisasi RAPB')->first();
        $menu2->akses = 0;
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
