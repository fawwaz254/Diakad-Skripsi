<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumniBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumni_business', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_alumni_business', 40)->primary();
            $table->string('id_alumni', 40)->comment('FK: alumni.id_alumni');
            $table->string('nm_usaha');
            $table->string('alamat');
            $table->string('kontak');
            $table->integer('jumlah_karyawan');
            $table->integer('tahun_masuk');
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
        Schema::dropIfExists('alumni_business');
    }
}
