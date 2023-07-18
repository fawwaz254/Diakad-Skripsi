<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriKpiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_kpi', function (Blueprint $table) {
            $table->string('id_kategori_kpi', 40)->primary();
            $table->string('nm_kategori', 40)->nullable();
            $table->integer('tingkat');
            $table->string('semester', 40);
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
        Schema::dropIfExists('kategori_kpi');
    }
}
