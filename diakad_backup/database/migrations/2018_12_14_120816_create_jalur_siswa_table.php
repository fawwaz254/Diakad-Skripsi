<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJalurSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jalur_siswa', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_jalur_siswa', 40)->primary();
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_jalur', 40)->comment('FK: jalur.id_jalur');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->boolean('is_jalur_aktif')->nullable()->comment('1 = jalur yg sedang aktif; 0 = jalur non-aktif;');
			$table->string('id_admisi', 40)->nullable()->comment('FK: admisi.id_admisi &gt; diisi ketika jalur masuk ');
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
		Schema::drop('jalur_siswa');
	}

}
