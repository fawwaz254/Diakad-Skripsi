<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePesanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pesan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pesan', 40)->primary();
			$table->string('id_pengguna_kirim', 40)->comment('FK: pengguna.id_pengguna');
			$table->string('id_pengguna_terima', 40)->comment('FK: pengguna.id_pengguna');
			$table->text('isi_pesan', 65535)->nullable();
			$table->string('nm_pesan_file', 128)->nullable();
			$table->boolean('is_dibaca')->nullable()->default(0)->comment('1 = sudah dibaca oleh penerima;');
			$table->boolean('is_hapus_pengirim')->nullable()->default(0)->comment('1 = dihapus oleh pengirim;');
			$table->boolean('is_hapus_penerima')->nullable()->default(0)->comment('1 = dihapus oleh penerima;');
			$table->boolean('is_tarik_pesan')->nullable()->default(0)->comment('1 = ditarik oleh pengirim (tidak tampil di keduanya)');
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
		Schema::drop('pesan');
	}

}
