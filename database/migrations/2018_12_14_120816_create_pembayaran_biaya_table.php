<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePembayaranBiayaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pembayaran_biaya', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pembayaran_biaya', 40)->primary();
			$table->string('id_tagihan_biaya', 40)->comment('FK: tagihan_biaya.id_tagihan_biaya');
			$table->string('id_staff_bayar', 40)->comment('FK: staff.id_staff &gt; staff yg melakukan input pembayaran');
			$table->string('id_semester_bayar', 40)->comment('FK: semester.id_semester &gt; dibayarkan pada semester berapa');
			$table->decimal('besar_pembayaran', 10, 0)->nullable();
			$table->timestamp('tgl_pembayaran')->nullable();
			$table->integer('id_bank')->nullable()->comment('FK: bank.id_bank &amp;gt; optional apabila dibayarkan lewat bank');
			$table->boolean('id_bank_via')->nullable()->comment('FK: bank_via.id_bank_via &gt; optional apabila dibayarkan lewat bank');
			$table->string('nomor_transaksi', 64)->nullable();
			$table->string('keterangan', 128)->nullable();
			$table->boolean('is_tarik')->nullable()->comment('diisi khusus pembayaran via bank > 0 = belum ditarik; 1 = sudah ditarik;');
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
		Schema::drop('pembayaran_biaya');
	}

}
