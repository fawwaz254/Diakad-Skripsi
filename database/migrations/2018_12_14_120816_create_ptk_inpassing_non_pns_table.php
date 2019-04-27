<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkInpassingNonPnsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_inpassing_non_pns', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_inpassing_non_pns', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('pangkat_golongan_inpassing', 64)->nullable();
			$table->string('nomor_sk_inpassing', 64)->nullable();
			$table->date('tgl_sk_inpassing')->nullable();
			$table->date('tgl_mulai_inpassing')->nullable();
			$table->string('angka_kridit_inpassing', 32)->nullable();
			$table->integer('masa_kerja_tahun_inpassing')->nullable();
			$table->integer('masa_kerja_bulan_inpassing')->nullable();
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
		Schema::drop('ptk_inpassing_non_pns');
	}

}
