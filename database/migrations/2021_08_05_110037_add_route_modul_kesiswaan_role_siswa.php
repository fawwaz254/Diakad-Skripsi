<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddRouteModulKesiswaanRoleSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $role_id = Role::where('nm_role', 'Siswa')->first()->id_role;

        // $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kesiswaan')->first();

        // $modul->menus()->where('nm_menu', 'Absensi Ekskul')->update([
        //     "page" => "absensi-ekskul"
        // ]);

        // $modul->menus()->where('nm_menu', 'Nilai Ekskul')->update([
        //     "page" => "nilai-eksul"
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
