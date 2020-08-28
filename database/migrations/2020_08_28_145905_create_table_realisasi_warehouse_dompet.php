<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRealisasiWarehouseDompet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realisasi_warehouse_dompet', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('realisasi_warehouse_dompet_id');
            $table->string('bank_name', 256)->nullable();

            $table->string('id_dompet', 40)->nullable();
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
