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
            $table->string('id_nilai_kpi')->primary();
            $table->string('id_kpi');
            $table->string('id_komponen');
            $table->string('id_siswa');
            $table->string('nilai_komponen')->nullable();
            $table->string('deskripsi_nilai')->nullable();
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
