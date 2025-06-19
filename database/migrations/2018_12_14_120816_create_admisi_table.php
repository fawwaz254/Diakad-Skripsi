<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdmisiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admisi', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_admisi', 40)->primary();
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->string('id_status_pengguna', 40)->comment('FK: status_pengguna.id_status_pengguna');
			$table->string('id_jalur', 40)->nullable()->comment('FK: jalur.id_jalur &gt; diisi ketika admisi masuk');
			$table->decimal('ips', 10, 0)->nullable()->comment('diisi ketika admisi aktif di tengah KBM');
			$table->decimal('ipk', 10, 0)->nullable()->comment('diisi ketika admisi aktif di tengah KBM');
			$table->string('id_pengajuan_wisuda', 40)->nullable()->comment('FK: pengajuan_wisuda.id_pengajuan_wisuda &gt; diisi ketika admisi lulus &gt; isian lain (no_ijasah, no_sk_kelulusan,dll ada di tabel pengajuan_wisuda)');
			$table->timestamp('tgl_keluar')->nullable()->comment('diisi ketika admisi keluar (bukan lulus)');
			$table->string('alasan_keluar', 256)->nullable()->comment('diisi ketika admisi keluar (bukan lulus)');
			$table->string('kode_sekolah_asal', 64)->nullable()->comment('diisi ketika siswa masuk jalur transfer');
			$table->string('nm_sekolah_asal', 128)->nullable()->comment('diisi ketika siswa masuk jalur transfer');
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
		Schema::drop('admisi');
	}

}
