<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableUrutanRaporSisipan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urutan_rapor_sisipan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
			$table->string('id_urutan_rapor_sisipan', 40)->primary();
			$table->string('id_mata_pelajaran')->nullable();
            $table->integer('urutan')->nullable();
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
        //
    }
}
