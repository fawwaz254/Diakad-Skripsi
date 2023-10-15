<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointKPISTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_kpi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_point_kpi', 40)->primary();
            $table->string('id_kelompok_kpi', 40);
            $table->string('id_semester', 40);
            $table->string('nm_point_kpi', 128);
            $table->integer('tingkat_kelas');
            $table->integer('urutan');
            $table->string('deskripsi', 256)->nullable();
            $table->integer('jenis')->comment('0 = header sub kelompok; 1 = aktif;');
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
