<?php

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModulKegiatanSiswaOnRoleBk extends Migration
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

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Aktivitas Siswa",
            "route"         => "aktivitas-siswa",
            "urutan"        => 6,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Aktivitas Reward Siswa",
                "page"         => "aktivitas-reward-siswa",
                "urutan"       => 1,
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
        $modul = Modul::where('nm_modul', 'Aktivitas Siswa')->first();

        if ($modul) {
            $modul->menus()->delete();
            $modul->delete();
        }
    }
}
