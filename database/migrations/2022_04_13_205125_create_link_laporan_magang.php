<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkLaporanMagang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_laporan_magang', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('link_laporan_magang_id', 40)->primary();
            $table->string('id_rekanan_magang', 40);
            $table->string('id_periode_magang', 40);
            $table->string('link_google_drive', 150);

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
        Schema::dropIfExists('link_laporan_magang');
    }
}
