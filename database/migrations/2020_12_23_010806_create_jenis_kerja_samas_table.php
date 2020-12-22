<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJenisKerjaSamasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_kerjasama', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_jenis_kerjasama', 40)->primary();
            $table->string('nm_jenis_kerjasama');
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->string('deleted_by', 40)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_kerjasama');
    }
}
