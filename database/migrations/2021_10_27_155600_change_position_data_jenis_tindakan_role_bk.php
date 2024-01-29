<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class ChangePositionDataJenisTindakanRoleBk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();
        $role_id = Role::where('nm_role', 'Bimbingan Konseling')->first()->id_role;

        $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Penanganan Siswa')->first()->id_modul;
        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Data Jenis Tindakan')->first();
        $menu->id_modul = 70;
        $menu->save();
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
