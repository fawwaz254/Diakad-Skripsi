<?php

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMenuNameApprovePrestasiSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Guru')->first()->id_role;

        $modulWalas = Modul::where('id_role', $role_id)->where('nm_modul', 'Wali Kelas')->first();

        $modulWalas->menus()->where('nm_menu', 'Approve Prestasi Siswa')->update([
            "nm_menu" => "Approve SKPI Siswa"
        ]);

        $role_id = Role::where('nm_role', 'Kesiswaan')->first()->id_role;

        $modulSkpi = Modul::where('id_role', $role_id)->where('nm_modul', 'SKPI')->first();

        $modulSkpi->menus()->where('nm_menu', 'Approve Prestasi Siswa')->update([
            "nm_menu" => "Approve SKPI Siswa"
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
