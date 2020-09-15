<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePenggunaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pengguna', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pengguna', 40)->primary();
			$table->string('id_status_pengguna', 40)->comment('FK: status_pengguna.id_status_pengguna');
			$table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah (ada di DB lain)');
			$table->string('nm_pengguna', 256)->nullable();
			$table->string('username', 64)->nullable();
			$table->string('password', 256)->nullable();
			$table->boolean('must_change_password')->nullable()->comment('0 = tidak perlu ganti password; 1 = harus ganti password (ketika baru insert pengguna);');
			$table->boolean('status_join_table')->nullable()->comment('1 = Pegawai; 2 = Guru; 3 = Siswa; 4 = Wali Murid; 5 = Pelatih Ekskul;');
			$table->string('remember_token', 256)->nullable();
			$table->timestamps();
			$table->string('created_by', 40)->nullable();
			$table->string('updated_by', 40)->nullable();
			$table->softDeletes();
			$table->string('deleted_by', 40)->nullable();
		});
		
		Schema::table('pembayaran_transaksi', function (Blueprint $table) {
            $table->renameColumn('id_pembayaran_transaksi', 'id_pembayaran_trs');
            $table->dropColumn('id_tagihan_biaya');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pengguna');
	}

}
