<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogSesiPenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_sesi_pengguna', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_log_sesi_pengguna', 40)->primary();
            $table->string('id_pengguna', 40)->nullable()->comment('FK: pengguna.id_pengguna');
            $table->timestamp('login_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sesi_pengguna');
    }
}
