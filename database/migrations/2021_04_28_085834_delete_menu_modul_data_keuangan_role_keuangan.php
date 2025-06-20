<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class DeleteMenuModulDataKeuanganRoleKeuangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // $role_id = Role::where('nm_role', 'Keuangan')->first()->id_role;

        // $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Data Keuangan')->first()->id_modul;

        // $menu1 = Menu::where(['id_modul' => $modul_id, 'nm_menu' => 'Data Detail Biaya Internal'])->first();
        // $menu1->akses = 0;
        // $menu1->save();

        // $menu2 = Menu::where(['id_modul' => $modul_id, 'nm_menu' => 'Data Biaya Internal'])->first();
        // $menu2->akses = 0;
        // $menu2->save();

        // $menu3 = Menu::where(['id_modul' => $modul_id, 'nm_menu' => 'Data Detail Biaya'])->first();
        // $menu3->akses = 0;
        // $menu3->save();
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
