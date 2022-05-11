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
        Schema::create('alumni_smp', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_alumni_smp', 40)->primary();
            $table->string('id_alumni', 40);
            $table->string('nm_sekolah', 40);
            $table->string('alamat_sekolah', 40);
            $table->string('jurusan', 40);
            $table->tinyInteger('jenis_sekolah')->comment('0=tidak melanjutkan sekolah 1=SMA; 2=SMK; 3=MA;');
            $table->integer('tahun_masuk_sekolah');
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
        Schema::dropIfExists('alumni_smp');
    }
}
