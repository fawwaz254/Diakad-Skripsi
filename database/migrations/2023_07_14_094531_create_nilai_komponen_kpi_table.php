<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiKomponenKpiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_komponen_kpi', function (Blueprint $table) {
            $table->string('id_nilai_kpi' , 40)->primary();
            $table->string('id_kpi', 40);
            $table->string('id_komponen', 40);
            $table->string('id_siswa', 40);
            $table->string('nilai_komponen', 256)->nullable();
            $table->string('deskripsi_nilai', 256)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_komponen_kpi');
    }
}
