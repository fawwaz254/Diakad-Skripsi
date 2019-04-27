<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRaporKelompokMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rapor_kelompok_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_rapor_kelompok_mp', 40)->primary();
			$table->string('id_rapor_kelompok', 40)->comment('FK: rapor_kelompok.id_rapor_kelompok');
			$table->string('id_rapor_kategori', 40)->comment('FK: rapor_kategori.id_rapor_kategori');
			$table->string('id_mata_pelajaran', 40)->nullable()->comment('FK: mata_pelajaran.id_mata_pelajaran (null apabila punya subkelompok_mp)');
			$table->string('nm_rapor_kelompok_mp', 128)->nullable()->comment('diisi ketika id_mata_pelajaran = null');
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
		Schema::drop('rapor_kelompok_mp');
	}

}
