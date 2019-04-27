<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKelompokBiayaInternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelompok_biaya_internal', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kelompok_biaya_internal', 40)->primary();
            $table->string('id_biaya', 40)->comment('FK: biaya.id_biaya');
            $table->string('nm_kelompok_biaya_internal', 128)->nullable();
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
        Schema::dropIfExists('kelompok_biaya_internal');
    }
}
