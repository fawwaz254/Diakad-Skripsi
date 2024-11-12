<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameIsSekretarisToIsSiswaInAktivitasRewardSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aktivitas_reward_siswa', function (Blueprint $table) {
            $table->renameColumn('is_sekretaris', 'is_siswa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aktivitas_reward_siswa', function (Blueprint $table) {
            $table->renameColumn('is_siswa', 'is_sekretaris');
        });
    }
}
