<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePresensiMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('presensi_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_presensi_mp', 40)->primary();
			$table->string('id_kelas_mp', 40)->comment('FK: kelas_mp.id_kelas_mp');
			$table->boolean('pertemuan_ke')->nullable();
			$table->text('uraian_materi', 65535)->nullable();
			$table->string('waktu_mulai', 5)->nullable();
			$table->string('waktu_selesai', 5)->nullable();
			$table->string('id_guru_pengganti', 40)->nullable()->comment('FK: guru.id_guru &gt; diisi oleh guru piket ketika guru pjmp tidak hadir');
			$table->string('alasan_tidak_hadir', 256)->nullable()->comment('diisi oleh guru piket ketika guru pjmp tidak hadir');
			$table->timestamp('tgl_entry')->nullable();
			$table->decimal('persentase_presensi_mp', 10, 0)->nullable();
			$table->string('keterangan', 256)->nullable();
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
		Schema::drop('presensi_mp');
	}

}
