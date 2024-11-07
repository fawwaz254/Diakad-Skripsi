<?php

use App\Models\Modul;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddModulRewardSiswaOnRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $now = Carbon::now();
            $role = Role::where('nm_role', 'Guru')->firstOrFail();

            $modul = Modul::create([
                "id_role"       => $role->id_role,
                "nm_modul"      => "Reward Siswa",
                "route"         => "reward-siswa",
                "urutan"        => 12,
                "akses"         => 1,
                "created_at"    => $now
            ]);

            $modul->menus()->createMany([
                [
                    "nm_menu"      => "Input Capaian Karakter",
                    "page"         => "input-capaian-karakter",
                    "urutan"       => 1,
                    "akses"        => 1,
                    "created_at"   => $now
                ],
                [
                    "nm_menu"      => "Approve Reward Siswa",
                    "page"         => "approve-reward-siswa",
                    "urutan"       => 2,
                    "akses"        => 1,
                    "created_at"   => $now
                ],
                [
                    "nm_menu"      => "Rekap Aktivitas Reward",
                    "page"         => "rekap-aktivitas-reward",
                    "urutan"       => 3,
                    "akses"        => 1,
                    "created_at"   => $now
                ],
            ]);
        });
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
