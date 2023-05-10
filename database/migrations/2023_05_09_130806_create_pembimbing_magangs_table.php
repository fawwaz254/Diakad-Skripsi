<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembimbingMagangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('pembimbing_magang', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pembimbing_magang', 40)->primary();
            $table->string('id_periode_magang', 40);
            $table->string('id_rekanan_magang', 40);
            $table->string('id_pengguna', 40);
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
