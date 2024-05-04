<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenggunaLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengguna_login', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pengguna_login', 40)->primary();
            $table->string('id_pengguna', 40)->nullable()->comment('FK: pengguna.id_pengguna');
            $table->timestamp('login_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengguna_login');
    }
}
