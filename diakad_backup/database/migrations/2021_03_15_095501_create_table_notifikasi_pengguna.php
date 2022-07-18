<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNotifikasiPengguna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifikasi_pengguna', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_notifikasi_pengguna', 40)->primary();
			$table->string('id_pengguna', 40)->comment('Notifikasi ditujukan kepada siapa, FK: pengguna.id_pengguna');
			$table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah');
			$table->string('isi_notifikasi', 512)->nullable()->comment('Isi notifikasi');
			$table->string('link_url', 512)->nullable()->comment('Link redirect apabila notifikasi diklik (Hanya untuk website)');
            $table->tinyInteger('status')->default('1')->comment('Status dibaca, 1: belum dibaca; 0: sudah dibaca;');
			$table->timestamps();
			$table->string('created_by', 40)->nullable();
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
