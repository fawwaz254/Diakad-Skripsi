<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRapbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapb', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_rapb', 40)->primary();
            $table->string('id_semester_mulai', 40)->comment('FK: semester.id_semester');
            $table->string('id_semester_selesai', 40)->comment('FK: semester.id_semester');
            $table->string('id_subkategori_rapb', 40)->comment('FK: subkategori_rapb.id_subkategori_rapb');
            $table->string('id_unit_kerja', 40)->comment('FK: unit_kerja.id_unit_kerja');
            $table->decimal('dana_perkiraan_rapb', 10, 0)->nullable();
            $table->date('tgl_rapb')->nullable();
            $table->boolean('prioritas_rapb')->nullable()->comment('1 = Rendah; 2 = Sedang; 3 = Tinggi;');
            $table->string('id_pengguna_kepala_unit', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala unit yg melakukan approve rapb');
            $table->string('id_pengguna_kepala_keuangan', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala keuangan yg melakukan approve rapb');
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
        Schema::dropIfExists('rapb');
    }
}
