<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePredikatKPISTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predikat_kpi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_predikat_kpi', 40)->primary();
            $table->string('id_point_kpi', 40);
            $table->string('id_kelas', 40);
            $table->string('id_siswa', 40);
            $table->string('predikat', 40)->nullable();
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
    { }
}
