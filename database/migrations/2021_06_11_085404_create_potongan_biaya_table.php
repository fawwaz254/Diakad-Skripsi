<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePotonganBiayaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potongan_biaya', function (Blueprint $table) {
            $table->engine = 'InnoDB';
			$table->string('id_potongan_biaya', 40)->primary();
			$table->float('total_potongan', 10, 0)->nullable();
			$table->dateTime('tanggal_potongan')->nullable();
			$table->string('keterangan')->nullable();
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
