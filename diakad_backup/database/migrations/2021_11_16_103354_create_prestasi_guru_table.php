<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestasiGuruTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestasi_guru', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_prestasi_guru', 40)->primary();
            $table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna');
            $table->string('id_tingkat_prestasi', 40)->comment('FK: tingkat_prestasi_siswa.id_tingkat_prestasi_siswa');
            $table->tinyInteger('jenis_prestasi')->comment('1 = Sains; 2 = Seni; 3 = Olahraga; 4 = Lain-lain;');
            $table->string('jenis_lomba',20);
            $table->string('nm_prestasi',256);
            $table->string('lokasi',256);
            $table->string('penyelenggara',128);
            $table->string('peringkat',50);
            $table->date('tanggal');
            $table->string('link_sertifikat',256);
            $table->tinyInteger('status')->comment('0 = Belum diapprove; 2 = Sudah diapprove;');
            $table->text('keterangan');
            
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
        Schema::dropIfExists('prestasi_guru');
    }
}
