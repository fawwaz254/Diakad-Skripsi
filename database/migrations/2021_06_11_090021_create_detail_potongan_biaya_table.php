<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPotonganBiayaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_potongan_biaya', function (Blueprint $table) {
            $table->engine = 'InnoDB';
			$table->string('id_detail_potongan_biaya', 40)->primary();
			$table->string('id_potongan_biaya', 40)->nullable();
			$table->string('id_detail_biaya_internal', 40)->nullable();
            $table->decimal('potongan_biaya', 10, 0)->nullable();
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
