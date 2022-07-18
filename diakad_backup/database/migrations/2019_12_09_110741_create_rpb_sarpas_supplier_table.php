<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpbSarpasSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpb_sarpas_supplier', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_rpb_sarpras_supplier', 40)->primary();
            $table->string('id_rpb_sarpras', 40)->comment('FK: rpb_sarpras.id_rpb_sarpras');
            $table->string('id_supplier', 40)->comment('FK: supplier.id_suppplier');
            $table->float('harga_supplier', 10, 0)->nullable();
            $table->float('harga_penawaran', 10, 0)->nullable();
            $table->integer('qty_penawaran')->nullable();
            $table->boolean('termin_penawaran')->nullable();
            $table->float('harga_approve_supplier', 10, 0)->nullable();
            $table->integer('qty_approve_supplier')->nullable();
            $table->boolean('termin_approve_supplier')->nullable();
            $table->boolean('is_approve')->nullable()->default(0)->comment('0 = Tidak Approve; 1 = Supplier yg dipilih utk Realisasi;');
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
        Schema::dropIfExists('rpb_sarpas_supplier');
    }
}
