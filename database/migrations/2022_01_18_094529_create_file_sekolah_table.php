<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileSekolahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_sekolah', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_file_sekolah', 40)->primary()->comment('[prefix].strtotime(now).uniqid()');
            $table->string('nama_file', 255)->nullable();
            $table->string('link_gdrive', 255)->nullable();
            $table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah');
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
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
        Schema::dropIfExists('file_sekolah');
    }
}
