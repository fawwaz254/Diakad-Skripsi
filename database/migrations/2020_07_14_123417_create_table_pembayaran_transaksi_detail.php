<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePembayaranTransaksiDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran_transaksi', function (Blueprint $table) {
            $table->renameColumn('id_pembayaran_transaksi', 'id_pembayaran_trs');
            $table->dropColumn('id_tagihan_biaya');
        });

        Schema::create('pembayaran_trs_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pembayaran_trs_detail', 40)->primary();
			$table->string('id_pembayaran_trs', 40)->comment('FK: pembayaran_trs.id_pembayaran_trs');
            $table->string('id_tagihan_biaya', 40)->comment('FK: tagihan_biaya.id_tagihan_biaya');
            $table->decimal('besar_pembayaran', 10, 0)->nullable();

			$table->timestamps();
			$table->string('created_by', 40)->nullable();
			$table->string('updated_by', 40)->nullable();
			$table->softDeletes();
			$table->string('deleted_by', 40)->nullable();
        });

        Schema::rename('pembayaran_transaksi', 'pembayaran_trs');
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
