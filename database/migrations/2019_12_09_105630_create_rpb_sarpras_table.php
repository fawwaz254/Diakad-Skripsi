<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpbSarprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpb_sarpras', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_rpb_sarpras', 40)->primary();
            $table->string('id_semester', 40)->comment('FK: semester.id_semester');
            $table->string('id_unit_kerja', 40)->comment('FK: unit_kerja.id_unit_kerja');
            $table->string('id_buku_alat', 40)->nullable()->comment('FK: buku_alat.id_buku_alat, sarpras yg diajukan pengadaan (salah satu)');
            $table->string('id_inventaris_ruangan', 40)->nullable()->comment('FK: inventaris_ruangan.id_inventaris_ruangan, sarpras yg diajukan pengadaan (salah satu)');
            $table->decimal('harga_satuan_rpb_sarpras', 10, 0)->nullable();
            $table->integer('qty_rpb_sarpras')->nullable();
            $table->date('tgl_rpb_sarpras')->nullable();
            $table->boolean('prioritas_rpb_sarpras')->nullable()->comment('1 = Rendah; 2 = Sedang; 3 = Tinggi;');
            $table->string('id_pengguna_kepala_unit', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala unit yg melakukan approve rpb sarpras');
            $table->string('id_pengguna_kepala_sarpras', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala sarpras yg melakukan approve rpb sarpras');
            $table->string('id_pengguna_kepala_sarpras_approve', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala sarpras yg melakukan approve pemilihan harga, qty, termin dari supplier');
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
        Schema::dropIfExists('rpb_sarpras');
    }
}
