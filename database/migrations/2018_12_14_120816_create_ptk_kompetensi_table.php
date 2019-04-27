<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkKompetensiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_kompetensi', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_kompetensi', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('bidang_studi_kompetensi', 64)->nullable();
			$table->integer('urutan_kompetensi')->nullable()->comment('Urutan bidang studi yang diajarkan oleh PTK. Kolom ini untuk menentukan bidang studi utama atau bukan, tingkat prioritas, maupun tingkat kompetensi penguasaan PTK terhadap bidang studi terkait. Isikan angka 1 pada kolom Urutan apabila mata pelajaran terkait adalah mata pelajaran utama yang diajarkan oleh PTK');
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
		Schema::drop('ptk_kompetensi');
	}

}
