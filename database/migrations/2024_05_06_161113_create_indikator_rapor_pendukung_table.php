<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndikatorRaporPendukungTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indikator_rapor_pendukung', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_indikator_rapor_pendukung', 40)->primary();
            $table->string('id_komponen_rapor_pendukung', 40)->nullable()->comment('FK: komponen_rapor_pendukung.id_komponen_rapor_pendukung');
            $table->string('id_semester', 40)->nullable()->comment('FK: semester.id_semester');
            $table->string('tingkat_kelas')->nullable();
            $table->string('nm_indikator')->nullable();
            $table->tinyInteger('urutan')->nullable();
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
        Schema::dropIfExists('indikator_rapor_pendukung');
    }
}
