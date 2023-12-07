<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomponenJenisRaporTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komponen_jenis_rapor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_komponen_jenis_rapor', 40)->primary();
            $table->string('id_jenis_rapor', 40);
            $table->string('nm_komponen_jenis_rapor', 128);
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
        // Schema::dropIfExists('komponen_jenis_rapor');
    }
}
