<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaporanWaliKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('laporan_wali_kelas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_laporan_wali_kelas', 40)->primary();
            $table->string('id_semester', 40)->comment('FK: semester.id_semester');
            $table->tinyInteger('id_bulan')->nullable()->comment('1 = Januari, 2 = Februari, dst');
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
        Schema::dropIfExists('laporan_wali_kelas');
    }
}
