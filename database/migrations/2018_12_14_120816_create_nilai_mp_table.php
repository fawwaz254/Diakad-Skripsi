<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNilaiMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nilai_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_nilai_mp', 40)->primary();
			$table->string('id_pengambilan_mp', 40)->comment('FK: pengambilan_mp.id_pengambilan_mp');
			$table->string('id_komponen_mp', 40)->comment('FK: komponen_mp.id_komponen_mp');
			$table->decimal('besar_nilai_mp', 10, 0)->nullable();
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
		Schema::drop('nilai_mp');
	}

}
