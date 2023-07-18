<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomponenKpiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komponen_kpi', function (Blueprint $table) {
            $table->string('id_komponen_kpi', 40)->primary();
            $table->string('id_subkategori_kpi', 40);
            $table->string('nm_komponen', 256)->nullable();
            $table->string('deskripsi_komponen', 256)->nullable();
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
        Schema::dropIfExists('komponen_kpi');
    }
}
