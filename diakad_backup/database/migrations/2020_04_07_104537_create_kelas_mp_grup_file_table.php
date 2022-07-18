<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKelasMpGrupFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas_mp_grup_file', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kelas_mp_grup_file', 40)->primary();
            $table->string('id_kelas_mp_grup_materi', 40)->nullable()->comment('FK: kelas_mp_grup_materi.id_kelas_mp_grup_materi');
            $table->string('nm_kelas_mp_grup_file', 2048)->nullable()->comment('inputan user');
            $table->string('path_kelas_mp_grup_file', 2048)->nullable()->comment('path penyimpanan file');
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
        Schema::dropIfExists('kelas_mp_grup_file');
    }
}
