<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePembayaranTunggakan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_tunggakan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pembayaran_tunggakan', 40)->primary();
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa, untuk keperluan apabila pembayarannya mencantumkan siswa')->nullable();
			$table->string('id_semester_mulai', 40)->comment('FK: semester.id_semester');
			$table->string('id_semester_selesai', 40)->comment('FK: semester.id_semester');
			$table->float('besar_pembayaran', 10, 0)->nullable();
			$table->dateTime('tgl_pembayaran')->nullable();
			$table->string('keterangan', 128)->nullable();
			$table->timestamps();
			$table->string('created_by', 40)->nullable();
			$table->string('updated_by', 40)->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
