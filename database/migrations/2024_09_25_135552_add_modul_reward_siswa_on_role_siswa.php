<?php

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModulRewardSiswaOnRoleSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Siswa')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Reward Siswa",
            "route"         => "reward-siswa",
            "urutan"        => 9,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Input Aktivitas Reward",
                "page"         => "input-aktivitas-reward",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Rekap Aktivitas Reward",
                "page"         => "rekap-aktivitas-reward",
                "urutan"       => 2,
                "akses"        => 1,
                "created_at"   => $now
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $modul = Modul::where('nm_modul', 'Reward Siswa')->first();

        if ($modul) {
            $modul->menus()->delete();
            $modul->delete();
        }
    }
}
