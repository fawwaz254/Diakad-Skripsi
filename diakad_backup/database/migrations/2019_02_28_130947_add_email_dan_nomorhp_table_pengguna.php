<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailDanNomorhpTablePengguna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->string('email_afiliasi', 64)->after('gelar_belakang')->nullable()->comment('email afiliasi/resmi dari sekolah --@nama_sekolah.sch.id');
            $table->string('email_pengguna', 64)->after('email_afiliasi')->nullable()->comment('email pribadi yg juga utk login apk');
            $table->string('nomor_hp_pengguna', 32)->after('email_pengguna')->nullable()->comment('nomor hp yg juga utk login apk');
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
