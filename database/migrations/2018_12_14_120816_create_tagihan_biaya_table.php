<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagihanBiayaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tagihan_biaya', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_tagihan_biaya', 40)->primary();
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_detail_biaya', 40)->comment('FK: detail_biaya.id_detail_biaya');
			$table->decimal('besar_biaya', 10, 0)->nullable();
			$table->decimal('denda_biaya', 10, 0)->nullable();
			$table->boolean('is_tagih')->nullable()->comment('0 = tidak ditagihkan; 1 = ditagihkan;');
			$table->string('keterangan', 128)->nullable();
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
		Schema::drop('tagihan_biaya');
	}

}
