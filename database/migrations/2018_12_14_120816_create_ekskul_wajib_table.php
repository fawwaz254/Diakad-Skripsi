<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEkskulWajibTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ekskul_wajib', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ekskul_wajib', 40)->primary();
			$table->string('id_ekskul', 40)->comment('FK: ekskul.id_ekskul');
			$table->boolean('tingkat_kelas')->nullable();
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
		Schema::drop('ekskul_wajib');
	}

}
