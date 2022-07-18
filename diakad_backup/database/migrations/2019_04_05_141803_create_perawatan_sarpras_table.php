<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerawatanSarprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perawatan_sarpras', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_perawatan_sarpras', 40)->primary();
            $table->string('id_ruangan', 40)->nullable()->comment('FK: ruangan.id_ruangan');
            $table->string('id_inventaris_ruangan', 40)->nullable()->comment('FK: inventaris_ruangan.id_inventaris_ruangan');
            $table->string('id_buku_alat', 40)->nullable()->comment('FK: buku_alat.id_buku_alat');
            $table->date('tgl_perawatan')->nullable();
            $table->string('keterangan_perawatan', 128)->nullable();
            $table->boolean('is_sudah_perawatan')->nullable()->comment('0 = belum; 1 = sudah;');
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
        Schema::dropIfExists('perawatan_sarpras');
    }
}
