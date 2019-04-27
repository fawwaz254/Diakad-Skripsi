<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('setting', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('id_setting', true);
			$table->string('key_setting', 256);
			$table->text('value', 65535);
			$table->text('keterangan', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('setting');
	}

}
