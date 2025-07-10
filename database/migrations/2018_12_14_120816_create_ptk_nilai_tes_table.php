<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkNilaiTesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_nilai_tes', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_nilai_tes', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->boolean('jenis_tes')->nullable()->comment('1 = TOEFL; 2 = TOEIC; 3 = UKBI; 4 = TPA; 5 = UKG; 6 = Lainnya;');
			$table->string('nm_tes', 64)->nullable();
			$table->string('penyelenggara_tes', 64)->nullable();
			$table->integer('tahun_tes')->nullable();
			$table->decimal('nilai_skor_tes', 10, 0)->nullable();
			$table->string('nomor_peserta_tes', 64)->nullable();
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
		Schema::drop('ptk_nilai_tes');
	}

}
