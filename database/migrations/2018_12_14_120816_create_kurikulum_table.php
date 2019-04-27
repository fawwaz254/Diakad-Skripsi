<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKurikulumTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kurikulum', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_kurikulum', 40)->primary();
			$table->string('id_jurusan', 40)->comment('FK: jurusan.id_jurusan');
			$table->string('id_semester_mulai', 40)->comment('FK: semester.id_semester');
			$table->string('nm_kurikulum', 64)->nullable();
			$table->integer('tahun_kurikulum')->nullable();
			$table->string('nomor_sk_kurikulum', 64)->nullable();
			$table->string('keterangan_kurikulum', 128)->nullable();
			$table->date('berlaku_mulai')->nullable();
			$table->date('berlaku_sampai')->nullable();
			$table->boolean('is_aktif')->nullable()->comment('0 = tidak aktif; 1 = aktif;');
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
		Schema::drop('kurikulum');
	}

}
