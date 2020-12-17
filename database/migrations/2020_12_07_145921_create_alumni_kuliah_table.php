<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumniKuliahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumni_kuliah', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_alumni_kuliah', 40)->primary();
            $table->string('id_alumni', 40)->comment('FK: alumni.id_alumni');
            $table->string('nm_perguruan');
            $table->string('alamat_perguruan');
            $table->string('fakultas');
            $table->string('prodi');
            $table->string('jenjang');
            $table->integer('tahun_masuk_perguruan');
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
        Schema::dropIfExists('alumni_kuliah');
    }
}
