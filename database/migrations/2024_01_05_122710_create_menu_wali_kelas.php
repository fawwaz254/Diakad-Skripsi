<?php

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuWaliKelas extends Migration
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

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Wali Kelas')->first();

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Rekap Form",
                "page"         => "rekap-form-wali",
                "urutan"       => 15,
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
    }
}
