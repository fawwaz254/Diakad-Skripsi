<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsApprovedToRewardSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reward_siswa', function (Blueprint $table) {
            $table->integer('is_aproved')->devault(1)->after('deskripsi_reward_siswa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reward_siswa', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
}
