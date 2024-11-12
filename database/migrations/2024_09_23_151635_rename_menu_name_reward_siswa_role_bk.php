<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameMenuNameRewardSiswaRoleBk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('modul')
            ->where('id_role', 5)
            ->where('nm_modul', 'Aktivitas Siswa')
            ->update([
                'nm_modul' => 'Reward Siswa',
                'route' => 'reward-siswa',
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('modul')
            ->where('id_role', 5)
            ->where('nm_modul', 'Reward Siswa')
            ->update([
                'nm_modul' => 'Aktivitas Siswa',
                'route' => 'aktivitas-siswa', 
            ]);
    }
}
