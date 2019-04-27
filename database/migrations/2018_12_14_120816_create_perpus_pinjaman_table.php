<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePerpusPinjamanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('perpus_pinjaman', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_perpus_pinjaman', 40)->primary();
			$table->string('id_perpus_buku', 40)->comment('FK: perpus_buku.id_perpus_buku');
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_staff_pinjaman', 40)->comment('FK: staff.id_staff (staff yg meminjamkan)');
			$table->dateTime('tgl_pinjaman')->nullable();
			$table->float('lama_pinjaman', 10, 0)->nullable()->comment('dalam hari');
			$table->string('keterangan_pinjaman', 128)->nullable();
			$table->date('tgl_deadline_kembali')->nullable();
			$table->string('id_staff_kembali', 40)->nullable()->comment('FK: staff.id_staff (staff yg menerima saat siswa mengembalikan)');
			$table->date('tgl_kembali')->nullable();
			$table->string('keterangan_kembali', 128)->nullable();
			$table->float('denda_kembali', 10, 0)->nullable()->comment('diisi apabila ada denda yg dikenakan kepada siswa');
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
		Schema::drop('perpus_pinjaman');
	}

}
