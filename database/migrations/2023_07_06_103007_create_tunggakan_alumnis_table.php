<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTunggakanAlumnisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tunggakan_alumni', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_tunggakan_alumni', 40)->primary();
            $table->string('nm_siswa', 256);
            $table->string('eks', 40);
            $table->string('tahun_pelajaran', 40);
            $table->integer('jumlah_tunggakan')->nullable();
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
        // Schema::dropIfExists('tunggakan_alumnis');
    }
}
