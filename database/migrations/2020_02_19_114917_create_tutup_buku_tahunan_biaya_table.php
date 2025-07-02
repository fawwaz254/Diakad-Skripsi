<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTutupBukuTahunanBiayaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutup_buku_tahunan_biaya', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_tutup_buku_tahunan_biaya', 40)->primary();
            $table->string('id_semester_mulai', 40)->comment('FK: semester.id_semester');
            $table->string('id_semester_selesai', 40)->comment('FK: semester.id_semester');
            $table->decimal('jml_tunggakan_biaya', 10, 0)->nullable();
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
        Schema::dropIfExists('tutup_buku_tahunan_biaya');
    }
}
