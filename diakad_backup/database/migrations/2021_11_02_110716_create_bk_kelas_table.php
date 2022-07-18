<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBkKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bk_kelas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_bk_kelas', 40)->primary();
            $table->string('id_kelas', 40)->comment('FK:kelas.id_kelas');
            $table->string('id_guru', 40)->comment('FK:guru.id_guru');
            $table->string('id_semester', 40)->comment('FK:semester.id_semester');
            $table->boolean('is_aktif')->nullable()->comment('0 = tidak aktif; 1 = aktif;');
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
        Schema::dropIfExists('bk_kelas');
    }
}
