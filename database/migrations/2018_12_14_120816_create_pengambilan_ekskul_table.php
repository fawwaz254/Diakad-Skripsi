<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePengambilanEkskulTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pengambilan_ekskul', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pengambilan_ekskul', 40)->primary();
			$table->string('id_ekskul', 40)->comment('FK: ekskul.id_ekskul');
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->decimal('nilai_angka', 10, 0)->nullable();
			$table->string('nilai_huruf', 3)->nullable();
			$table->decimal('persentase_presensi', 10, 0)->nullable()->comment('akumulasi dari presensi_ekskul');
			$table->boolean('is_tampil')->nullable()->comment('0 = belum ditampilkan ke siswa; 1 = ditampilkan ke siswa;');
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
		Schema::drop('pengambilan_ekskul');
	}

}
