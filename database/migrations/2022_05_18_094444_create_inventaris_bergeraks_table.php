<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventarisBergeraksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventaris_bergerak', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_inventaris_bergerak', 40)->primary();
            $table->string('nm_inventaris_ruangan', 64)->nullable();
            $table->integer('jumlah_inventaris_ruangan')->nullable();
            $table->integer('jumlah_kondisi_baik')->nullable();
            $table->integer('jumlah_kondisi_rusak')->nullable();
            $table->string('spesifikasi_inventaris_ruangan', 64)->nullable()->comment('Spesifikasi sarana, seperti ukuran, bahan, dan merk');
            $table->string('keterangan_inventaris_ruangan', 128)->nullable();
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
        Schema::dropIfExists('inventaris_bergerak');
    }
}
