<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKelasMpGrupDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas_mp_grup_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kelas_mp_grup_detail', 40)->primary();
            $table->string('id_kelas_mp_grup', 40)->comment('FK: kelas_mp_grup.id_kelas_mp_grup');
            $table->string('id_kelas_mp', 40)->comment('FK: kelas_mp.id_kelas_mp');
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
        Schema::dropIfExists('kelas_mp_grup_detail');
    }
}
