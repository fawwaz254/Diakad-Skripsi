<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaNotifKehadiranSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wa_notif_kehadiran_siswa', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_notif', 40)->primary();
            $table->string('id_siswa', 40)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wa_notif_kehadiran_siswa');
    }
}
