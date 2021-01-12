<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBerkasKerjasamasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berkas_kerjasama', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_berkas_kerjasama', 40)->primary();
            $table->string('id_kerjasama', 40);
            $table->string('nama_file');
            $table->string('type');
            $table->string('path');
            $table->string('versi');
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
        Schema::dropIfExists('berkas_kerjasama');
    }
}
