<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkTunjanganTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_tunjangan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_tunjangan', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('jenis_tunjangan', 64)->nullable();
			$table->string('nm_tunjangan', 64)->nullable();
			$table->string('instansi_tunjangan', 64)->nullable();
			$table->string('nomor_sk_tunjangan', 64)->nullable();
			$table->date('tgl_sk_tunjangan')->nullable();
			$table->string('semester_tunjangan', 32)->nullable();
			$table->string('sumber_dana_tunjangan', 64)->nullable();
			$table->integer('tahun_mulai_tunjangan')->nullable();
			$table->integer('tahun_selesai_tunjangan')->nullable();
			$table->float('jumlah_dana_tunjangan', 10, 0)->nullable();
			$table->boolean('is_masih_menerima')->nullable()->comment('1 = Ya; 0 = Tidak; (apakah masih menerima tunjangan?)');
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
		Schema::drop('ptk_tunjangan');
	}

}
