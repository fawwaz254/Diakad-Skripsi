<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJenisDetailBiayaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_detail_biaya', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->boolean('id_jenis_detail_biaya')->primary();
            $table->string('kode_jenis_detail_biaya', 4)->nullable();
            $table->string('nm_jenis_detail_biaya', 32)->nullable();
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
        Schema::dropIfExists('jenis_detail_biaya');
    }
}
