<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRewardSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_siswa', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_reward_siswa', 40)->primary();
            $table->string('id_siswa', 40)->comment('FK: siswa.id_siswa (ada di DB lain)');
            $table->string('id_kelas', 40)->comment('FK: kelas.id_kelas (ada di DB lain)');
            $table->string('id_pengguna_reward_siswa', 40)->comment('FK: pengguna.id_pengguna (ada di DB lain). id_pengguna pemberi reward');
            $table->string('nm_reward_siswa', 256)->nullable();
            $table->text('deskripsi_reward_siswa')->nullable();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reward_siswa');
    }
}
