<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVoucherTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('voucher', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_voucher', 40)->primary();
			$table->string('id_penerimaan', 40)->comment('FK: penerimaan.id_penerimaan');
			$table->string('id_voucher_tarif', 40)->comment('FK: voucher_tarif.id_voucher_tarif');
			$table->string('kode_voucher', 32)->nullable();
			$table->string('pin_password', 8)->nullable()->comment('pin di generate oleh sistem');
			$table->timestamp('tgl_ambil')->nullable()->comment('tanggal ambil voucher di aplikasi PPDB online');
			$table->boolean('is_aktif')->nullable()->comment('0 = sudah diambil oleh calon siswa; 1 = tersedia (belum diambil);');
			$table->timestamp('tgl_bayar')->nullable()->comment('tanggal bayar saat set bayar di aplikasi SSI');
			$table->decimal('besar_biaya', 10, 0)->nullable();
			$table->string('nomor_transaksi', 256)->nullable()->comment('diisi apabila transaksi dilakukan oleh bank');
			$table->integer('id_bank')->nullable()->comment('FK: bank.id_bank (diisi apabila transaksi dilakukan oleh bank)');
			$table->boolean('id_bank_via')->nullable()->comment('FK: bank_via.id_bank_via (diisi apabila transaksi dilakukan oleh bank)');
			$table->boolean('is_tagih_bank')->nullable()->comment('0 = sudah ditagih ke bank; 1 = tagihkan ke bank;');
			$table->string('keterangan_voucher', 128)->nullable();
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
		Schema::drop('voucher');
	}

}
