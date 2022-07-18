<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWaliMuridTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wali_murid', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_wali_murid', 40)->primary();
			$table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna');
			$table->string('nm_wali_murid', 128)->nullable();
			$table->string('nomor_hp_wali_murid', 32)->nullable();
			$table->boolean('is_orang_tua')->nullable()->comment('0 = wali (bukan orang tua kandung); 1 = orang tua kandung;');
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
		Schema::drop('wali_murid');
	}

}
