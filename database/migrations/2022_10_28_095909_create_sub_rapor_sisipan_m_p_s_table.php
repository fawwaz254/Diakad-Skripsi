<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubRaporSisipanMPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_rapor_sisipan_mp', function (Blueprint $table) {
            $table->engine = 'InnoDB';
			$table->string('id_sub_rapor_sisipan_mp', 40)->primary();
			$table->string('id_sub_rapor_sisipan',40)->nullable();
            $table->string('id_mata_pelajaran',40)->nullable();
            // $table->integer('urutan')->nullable();
			$table->timestamps();
			$table->string('created_by', 40)->nullable();
			$table->string('updated_by', 40)->nullable();
			$table->softDeletes();
			$table->string('deleted_by', 40)->nullable();
        });
        // Schema::create('sub_rapor_sisipan_m_p_s', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('sub_rapor_sisipan_m_p_s');
    }
}
