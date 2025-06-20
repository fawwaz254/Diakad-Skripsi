<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_test', 40)->primary();
            $table->string('id_pengguna', 40)->nullable();
            $table->string('id_paket_soal', 40)->nullable();
            $table->timestamp('waktu_mulai_pengerjaan')->nullable();
            $table->timestamp('waktu_selesai_pengerjaan')->nullable();
            $table->boolean('status')->nullable()->comment("0 untuk belum mengumpulkan, 1 untuk sudah mengumpulkan");
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
        Schema::dropIfExists('jawaban_tests');
    }
}
