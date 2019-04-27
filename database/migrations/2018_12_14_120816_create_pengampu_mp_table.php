<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePengampuMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pengampu_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pengampu_mp', 40)->primary();
			$table->string('id_kelas_mp', 40)->comment('FK: kelas_mp.id_kelas_mp');
			$table->string('id_guru', 40)->comment('FK: guru.id_guru');
			$table->boolean('pjmp_pengampu_mp')->nullable()->comment('1 = PJMP; 2 = Anggota;');
			$table->boolean('pjmp_uts')->nullable()->comment('1 = PJMP untuk UTS; 0 = Bukan PJMP untuk UTS;');
			$table->boolean('pjmp_uas')->nullable()->comment('1 = PJMP untuk UAS; 0 = Bukan PJMP untuk UAS;');
			$table->string('nomor_sk_mengajar', 64)->nullable();
			$table->date('tgl_sk_mengajar')->nullable();
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
		Schema::drop('pengampu_mp');
	}

}
