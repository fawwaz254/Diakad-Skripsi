<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_supplier', 40)->primary();
            $table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna');
            $table->string('nm_supplier', 512)->nullable();
            $table->string('cp_supplier_1', 32)->nullable()->comment('Sebagai Username Login Supplier');
            $table->string('cp_supplier_2', 32)->nullable();
            $table->string('alamat_supplier', 1024)->nullable();
            $table->string('nomor_sk_kerjasama', 128)->nullable();
            $table->date('tgl_awal_kerjasama')->nullable();
            $table->date('tgl_akhir_kerjasama')->nullable();
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
        Schema::dropIfExists('supplier');
    }
}
