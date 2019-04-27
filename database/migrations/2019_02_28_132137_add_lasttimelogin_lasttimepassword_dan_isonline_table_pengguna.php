<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLasttimeloginLasttimepasswordDanIsonlineTablePengguna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dateTime('last_time_password')->after('nomor_hp_pengguna')->nullable()->comment('terakhir kali ganti password');
            $table->dateTime('last_time_login')->after('last_time_password')->nullable()->comment('terakhir kali login');
            $table->boolean('is_online')->after('last_time_login')->nullable()->comment('0 = offline; 1 = user sedang online;');
        });
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
