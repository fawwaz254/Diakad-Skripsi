<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePeraturanNilaiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('peraturan_nilai', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_peraturan_nilai', 40)->primary();
			$table->string('id_standar_nilai', 40)->comment('FK: standar_nilai.id_standar_nilai');
			$table->decimal('nilai_kkm', 10, 0)->nullable()->comment('khusus is_mata_pelajaran = 1');
			$table->decimal('nilai_min_peraturan_nilai', 10, 0)->nullable();
			$table->decimal('nilai_max_peraturan_nilai', 10, 0)->nullable();
			$table->boolean('is_mata_pelajaran')->nullable()->comment('0 = untuk umum (magang, ekskul, dll); 1 = untuk mapel;');
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
		Schema::drop('peraturan_nilai');
	}

}
