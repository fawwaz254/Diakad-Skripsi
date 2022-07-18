<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkTugasTambahanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_tugas_tambahan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_tugas_tambahan', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('nm_jabatan_tambahan', 64)->nullable();
			$table->string('nomor_sk_tambahan', 64)->nullable();
			$table->date('tgl_mulai_tambahan')->nullable();
			$table->date('tgl_selesai_tambahan')->nullable();
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
		Schema::drop('ptk_tugas_tambahan');
	}

}
