<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_alumni', 40)->primary();
            $table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
            $table->string('email');
            $table->integer('tahun_lulus');
            $table->string('status', 10);
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->string('deleted_by', 40)->nullable();
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
        Schema::dropIfExists('alumni');
    }
}
