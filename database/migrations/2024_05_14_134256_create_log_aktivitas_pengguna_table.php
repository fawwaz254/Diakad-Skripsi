<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAktivitasPenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_aktivitas_pengguna', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_log_aktivitas_pengguna', 40)->primary();
            $table->string('id_pengguna', 40)->nullable()->comment('FK: pengguna.id_pengguna');
            $table->string('ip_address');
            $table->string('method');
            $table->string('route');
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
        Schema::dropIfExists('log_aktivitas_pengguna');
    }
}
