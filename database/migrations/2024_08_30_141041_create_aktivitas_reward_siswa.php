<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAktivitasRewardSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aktivitas_reward_siswa', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id_aktivitas_reward_siswa');
            $table->integer('id_jenis_aktivitas_reward');
            $table->longText('nm_aktivitas_reward_siswa');
            $table->integer('nilai_aktivitas');
            $table->longText('nilai_karakter');
            $table->boolean('is_guru');
            $table->boolean('is_sekretaris');
            $table->boolean('is_aktif');
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aktivitas_reward_siswa');
    }
}
