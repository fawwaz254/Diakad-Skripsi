<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumniBekerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumni_bekerja', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_alumni_bekerja', 40)->primary();
            $table->string('id_alumni', 40)->comment('FK: alumni.id_alumni');
            $table->string('nm_instansi');
            $table->string('alamat_instansi');
            $table->string('kontak_instansi');
            $table->string('bidang_usaha_instansi');
            $table->integer('tahun_masuk_instansi');
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
        Schema::dropIfExists('alumni_bekerja');
    }
}
