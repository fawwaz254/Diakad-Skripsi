<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemasukanBiayaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemasukan_biaya', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pemasukan_biaya', 40)->primary();
            $table->string('id_pemasukan_biaya_subkategori', 40)->comment('FK: pemasukan_biaya_subkategori.id_pemasukan_biaya_subkategori');
            $table->string('id_semester', 40)->comment('FK: semester.id_semester');
            $table->string('id_staff', 40)->comment('FK: staff.id_staff (staff yg melakukan input pemasukan)');
            $table->date('tgl_pemasukan_biaya')->nullable();
            $table->decimal('besar_pemasukan_biaya', 10, 0)->nullable();
            $table->boolean('is_upload_file')->nullable()->comment('0 = tidak upload file; 1 = ada upload file;');
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
        Schema::dropIfExists('pemasukan_biaya');
    }
}
