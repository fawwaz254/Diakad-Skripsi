<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePresensiEkskulTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('presensi_ekskul', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_presensi_ekskul', 40)->primary();
			$table->string('id_ekskul', 40)->comment('FK: ekskul.id_ekskul');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->boolean('pertemuan_ke')->nullable();
			$table->string('materi_ekskul', 128)->nullable();
			$table->string('waktu_mulai', 5)->nullable();
			$table->string('waktu_selesai', 5)->nullable();
			$table->timestamp('tgl_entry')->nullable()->comment('sama seperti created_at');
			$table->decimal('persentase_presensi_ekskul', 10, 0)->nullable();
			$table->string('keterangan_presensi_ekskul', 128)->nullable();
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
		Schema::drop('presensi_ekskul');
	}

}
