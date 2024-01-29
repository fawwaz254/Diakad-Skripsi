<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class RenameMenuRekapPelanggaranRoleGuru extends Migration
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
        $modul_id = Modul::where('id_role', $role_id)->where('nm_modul', 'Pelanggaran Siswa')->first()->id_modul;

        // rename menu rekap pelanggaran
        $menu = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Rekap Pelanggaran')->first();
        $menu->nm_menu = 'Data Pelanggaran Siswa KBM';
        $menu->save();

        // remove menu input pelanggaran siswa
        $menu2 =  Menu::where('id_modul', $modul_id)->where('nm_menu', 'Input Pelanggaran Siswa')->first();
        $menu2->akses = 0;
        $menu2->save();

        // rename menu input pelanggaran non kbm
        $menu3 = Menu::where('id_modul', $modul_id)->where('nm_menu', 'Input Pelanggaran Non-KBM')->first();
        $menu3->nm_menu = 'Data Pelanggaran Siswa Non KBM';
        $menu3->save();
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
