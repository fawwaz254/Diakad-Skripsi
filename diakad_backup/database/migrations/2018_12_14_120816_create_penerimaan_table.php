<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePenerimaanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('penerimaan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_penerimaan', 40)->primary();
			$table->string('id_jalur', 40)->comment('FK: jalur.id_jalur');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->boolean('gelombang_penerimaan')->nullable();
			$table->integer('tahun_penerimaan')->nullable();
			$table->string('nm_penerimaan', 64)->nullable();
			$table->string('nm_semester_penerimaan', 8)->nullable()->comment('Ganjil atau Genap');
			$table->boolean('jml_pilihan_jurusan')->nullable();
			$table->date('tgl_awal_registrasi')->nullable();
			$table->date('tgl_akhir_registrasi')->nullable();
			$table->date('tgl_awal_verifikasi')->nullable();
			$table->date('tgl_akhir_verifikasi')->nullable();
			$table->date('tgl_penetapan')->nullable();
			$table->dateTime('tgl_pengumuman')->nullable();
			$table->date('tgl_awal_voucher')->nullable()->comment('tgl pembukaan voucher online');
			$table->date('tgl_akhir_voucher')->nullable()->comment('tgl penutupan voucher online');
			$table->boolean('is_pendaftaran_online')->nullable()->comment('0 = offline; 1 = online');
			$table->boolean('is_verifikasi')->nullable()->comment('0 = tanpa verifikasi; 1 = dengan verifikasi;');
			$table->boolean('is_bayar_voucher')->nullable()->comment('0 = otomatis dianggap sudah bayar; 1 = memerlukan action bayar dari staff;');
			$table->string('nomor_rekening_transfer', 32)->nullable();
			$table->boolean('jenis_penerimaan')->nullable()->comment('1 = penerimaan utk siswa baru siakad; 2 = penerimaan utk siswa lama (id_jalur dan id_semester diisi 0);');
			$table->boolean('is_aktif')->nullable()->comment('0 = tidak aktif; 1 = aktif;');
			$table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah (ada di DB lain)');
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
		Schema::drop('penerimaan');
	}

}
