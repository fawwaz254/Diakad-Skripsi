<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInventarisRuanganTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventaris_ruangan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_inventaris_ruangan', 40)->primary();
			$table->string('id_ruangan', 40)->comment('FK: ruangan.id_ruangan');
			$table->string('nm_inventaris_ruangan', 64)->nullable();
			$table->integer('jumlah_inventaris_ruangan')->nullable();
			$table->integer('jumlah_kondisi_baik')->nullable();
			$table->integer('jumlah_kondisi_rusak')->nullable();
			$table->string('spesifikasi_inventaris_ruangan', 64)->nullable()->comment('Spesifikasi sarana, seperti ukuran, bahan, dan merk');
			$table->string('keterangan_inventaris_ruangan', 128)->nullable();
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
		Schema::drop('inventaris_ruangan');
	}

}
