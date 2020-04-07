<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKelasMpGrupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas_mp_grup', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kelas_mp_grup', 40)->primary();
            $table->string('nm_kelas_mp_grup', 1024)->nullable();
            $table->string('keterangan_kelas_mp_grup', 2048)->nullable();
            $table->string('id_sekolah', 40)->nullable()->comment('FK: sekolah.id_sekolah');
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
        Schema::dropIfExists('kelas_mp_grup');
    }
}
