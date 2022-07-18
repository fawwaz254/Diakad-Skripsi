<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKebutuhanKhususKelasMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kebutuhan_khusus_kelas_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_kebutuhan_khusus_kelas_mp', 40)->primary();
			$table->string('id_kelas_mp', 40)->comment('FK: kelas_mp.id_kelas_mp');
			$table->boolean('id_kebutuhan_khusus')->comment('FK: kebutuhan_khusus.id_kebutuhan_khusus');
			$table->boolean('is_aktif')->nullable()->comment('1 = Aktif; 0 = Non-Aktif;');
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
		Schema::drop('kebutuhan_khusus_kelas_mp');
	}

}
