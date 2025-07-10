<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensiMagangTable01062023 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensi_magang', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_presensi_magang', 40)->primary();
            $table->string('id_pembimbing_magang', 40);
            $table->timestamp('tanggal');
            $table->string('keterangan', 40)->nullable();
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
        // Schema::dropIfExists('presensi_magang_table_01062023');
    }
}
