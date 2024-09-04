<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableJenisAktivitasReward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_aktivitas_reward', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id_jenis_aktivitas_reward');
            $table->string('nm_jenis_aktivitas_reward', 255);
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
        Schema::dropIfExists('jenis_aktivitas_reward');
    }
}
