<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIjazahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ijazah', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_ijazah', 40)->primary();
            $table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
            $table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah');
            $table->dateTime('tgl_pengambilan_ijazah')->nullable();
            $table->string('penerima_ijazah', 128)->nullable()->comment('Nama penerima ijazah (jika diwakilkan)');
            $table->string('id_pemberi_ijazah', 40)->nullable()->comment('FK: pengguna.id_pengguna');
            $table->text('catatan_ijazah')->nullable();
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
        Schema::dropIfExists('ijazah');
    }
}
