<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePenetapanPenerimaanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('penetapan_penerimaan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_penetapan_penerimaan', 40)->primary();
			$table->string('id_penetapan', 40)->comment('FK: penetapan.id_penetapan');
			$table->string('id_penerimaan', 40)->comment('FK: penerimaan.id_penerimaan');
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
		Schema::drop('penetapan_penerimaan');
	}

}
