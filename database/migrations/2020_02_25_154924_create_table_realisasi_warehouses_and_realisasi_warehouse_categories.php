<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRealisasiWarehousesAndRealisasiWarehouseCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realisasi_warehouses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('realisasi_warehouse_id');
            $table->string('semester_code', 16)->nullable();
            $table->string('division_name', 128)->nullable();
            $table->string('subcategory', 128)->nullable();
            $table->integer('transaction_id')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->float('transaction_nominal')->nullable();

            $table->string('id_realisasi', 40)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('realisasi_warehouse_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('realisasi_warehouse_category_id');
            $table->string('subcategory', 128)->nullable();

            $table->string('id_subkategori_rapb', 40)->nullable();
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
