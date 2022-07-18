<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailBiayaInternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_biaya_internal', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_detail_biaya_internal', 40)->primary();
            $table->string('id_kelompok_biaya_internal', 40)->comment('FK: kelompok_biaya_internal.id_kelompok_biaya_internal');
            $table->string('nm_detail_biaya_internal', 128)->nullable();
            $table->float('besar_biaya', 10, 0)->nullable();
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
        Schema::dropIfExists('detail_biaya_internal');
    }
}
