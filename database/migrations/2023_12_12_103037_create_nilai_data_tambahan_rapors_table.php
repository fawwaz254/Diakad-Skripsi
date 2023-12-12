<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiDataTambahanRaporsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_tambahan_rapor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_nilai_tambahan_rapor', 40)->primary();
            $table->string('id_tambahan_rapor', 40);
            $table->string('id_siswa', 40);
            $table->string('id_semester', 40);
            $table->string('nilai', 128);
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
        // Schema::dropIfExists('nilai_tambahan_rapors');
    }
}
