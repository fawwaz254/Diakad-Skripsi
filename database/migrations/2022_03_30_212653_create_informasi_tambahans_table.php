<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformasiTambahansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informasi_tambahan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_informasi_tambahan', 40)->primary();
            $table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
            $table->string('id_kelas', 40)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
            $table->string('id_semester', 40)->comment('FK: semester.id_semester');
            $table->string('jenis_informasi_tambahan');
            $table->string('nm_informasi_tambahan', 128)->nullable();
            $table->string('nm_informasi_tambahan_eng', 128)->nullable();
            $table->string('status', 128)->nullable();
            $table->string('keterangan', 128)->nullable();
            $table->string('approved_by', 40)->nullable();
            $table->string('approved_at', 40)->nullable();
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
        Schema::dropIfExists('informasi_tambahan');
    }
}
