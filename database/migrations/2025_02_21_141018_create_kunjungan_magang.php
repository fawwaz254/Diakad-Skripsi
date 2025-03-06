<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKunjunganMagang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('kunjungan_magang');
        Schema::create('kunjungan_magang', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kunjungan_magang', 40)->primary();
            $table->string('id_periode_magang', 40);
            $table->string('id_rekanan_magang', 40);
            $table->string('keterangan_kunjungan', 128);
            $table->string('foto_kunjungan', 256)->nullable();
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
        Schema::dropIfExists('kunjungan_magang');
    }
}
