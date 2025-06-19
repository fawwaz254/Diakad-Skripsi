<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilePenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_pengguna', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('file_pengguna_id', 40)->primary();
            $table->string('pengguna_id', 40);
            $table->string('link_file', 150);
            $table->text('judul');
            $table->text('keterangan');
            $table->integer('is_google_drive');
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
        Schema::dropIfExists('file_pengguna');
    }
}
