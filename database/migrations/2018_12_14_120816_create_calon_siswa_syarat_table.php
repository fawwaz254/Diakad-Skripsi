<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalonSiswaSyaratTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calon_siswa_syarat', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_c_siswa_syarat', 40)->primary();
			$table->string('id_c_siswa', 40)->comment('FK: calon_siswa_baru.id_c_siswa');
			$table->string('id_penerimaan_syarat', 40)->comment('FK: penerimaan_syarat.id_penerimaan_syarat');
			$table->string('nm_file_syarat', 128)->nullable()->comment('nama file di server');
			$table->string('nm_file_asli', 128)->nullable()->comment('nama file asli ketika di upload');
			$table->boolean('is_verified')->nullable()->comment('0 = Belum Terverifikasi; 1 = Sudah Terverifikasi;');
			$table->string('pesan_verifikator', 128)->nullable()->comment('keterangan dari verifikator');
			$table->timestamp('tgl_valid_syarat')->nullable()->comment('tgl disetujui verifikator');
			$table->timestamp('tgl_invalid_syarat')->nullable()->comment('tgl ditolak verifikator');
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
		Schema::drop('calon_siswa_syarat');
	}

}
