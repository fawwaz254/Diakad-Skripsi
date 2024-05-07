<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePredikatRaporPendukungTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predikat_rapor_pendukung', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_predikat_rapor_pendukung', 40)->primary();
            $table->string('id_indikator_rapor_pendukung', 40)->nullable()->comment('FK: indikator_rapor_pendukung.id_indikator_rapor_pendukung');
            $table->string('id_kelas', 40)->nullable()->comment('FK: kelas.id_kelas');
            $table->string('id_siswa', 40)->nullable()->comment('FK: siswa.id_siswa');
            $table->string('nilai')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
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
        Schema::dropIfExists('predikat_rapor_pendukung');
    }
}
