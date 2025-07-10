<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePembayaranTransaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_transaksi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pembayaran_transaksi', 40)->primary();
            $table->string('id_tagihan_biaya', 40)->comment('FK: tagihan_biaya.id_tagihan_biaya');
			$table->string('nomor_transaksi', 64)->nullable();
			$table->decimal('besar_pembayaran', 10, 0)->nullable();
            $table->integer('status_pembayaran')->default(0)->comment('0: waiting for payment; 1: paid; 10: expired;');

			$table->longText('token')->nullable();
			$table->string('id_semester_bayar', 40)->comment('FK: semester.id_semester &gt; dibayarkan pada semester berapa');
            $table->timestamp('tgl_pembayaran')->nullable();
			$table->string('keterangan', 128)->nullable();
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
