<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class AddingMenuApprovePrestasiSiswaOnRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // $role_id = Role::where('nm_role', 'Guru')->first()->id_role;

        // $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Wali Kelas')->first();

        // $modul->menus()->createMany([
        //     [
        //         "nm_menu"      => "Approve Prestasi Siswa",
        //         "page"         => "approve-prestasi-siswa",
        //         "urutan"       => 10,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ],
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
