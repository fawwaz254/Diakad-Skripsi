<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePenerimaanSyaratTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('penerimaan_syarat', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_penerimaan_syarat', 40)->primary();
			$table->string('id_penerimaan', 40)->comment('FK: penerimaan.id_penerimaan');
			$table->string('id_jurusan', 40)->nullable()->comment('FK: jurusan.id_jurusan (diisi apabila ada syarat khusus untuk jurusan)');
			$table->string('nm_penerimaan_syarat', 128)->nullable();
			$table->boolean('is_wajib')->nullable()->comment('0 = syarat tidak wajib; 1 = syarat wajib;');
			$table->boolean('urutan')->nullable()->comment('urutan tampilan di aplikasi ppdb online');
			$table->boolean('is_upload_file')->nullable()->comment('0 = tidak perlu upload file; 1 = harus upload file;');
			$table->string('keterangan_penerimaan_syarat', 128)->nullable();
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
		Schema::drop('penerimaan_syarat');
	}

}
