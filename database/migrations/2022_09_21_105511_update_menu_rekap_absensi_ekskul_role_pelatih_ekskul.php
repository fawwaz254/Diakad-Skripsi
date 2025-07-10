<?php

use App\Models\Modul;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class UpdateMenuRekapAbsensiEkskulRolePelatihEkskul extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // $role = Role::where('nm_role', 'Pelatih Ekskul')->first();

        // $modul = Modul::where('id_role', $role->id_role)->where('nm_modul', 'Absensi Ekskul')->first();

        // $modul->menus()->where('nm_menu', 'Rekap Absensi Ekskul')->update([
        //     "page" => "rekap-absensi-ekskul"
        // ]);
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
