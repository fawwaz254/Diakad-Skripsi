<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiRaporsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_rapor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_nilai_rapor', 40)->primary();
            $table->string('id_rapor', 40);
            $table->string('id_komponen_jenis_rapor', 40);
            $table->string('id_siswa', 40);
            $table->string('nilai', 40)->nullable();
            $table->string('keterangan', 256)->nullable();
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
        // Schema::dropIfExists('nilai_rapors');
    }
}
