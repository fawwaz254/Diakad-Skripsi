<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumniSmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('alumni_smp');
        Schema::create('alumni_smp', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_alumni_smp', 40)->primary();
            $table->string('id_alumni', 40)->nullable();
            $table->string('nm_sekolah', 40)->nullable();
            $table->string('alamat_sekolah', 256)->nullable();
            $table->string('jurusan', 40)->nullable();
            $table->string('jenis_sekolah', 40)->nullable();
            $table->integer('tahun_masuk_sekolah')->nullable();;
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
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
        Schema::dropIfExists('alumni_smp');
    }
}
