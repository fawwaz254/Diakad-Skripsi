<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRealisasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realisasi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_realisasi', 40)->primary();
            $table->string('id_semester_realisasi', 40)->comment('FK: semester.id_semester');
            $table->string('id_rapb', 40)->comment('FK: rapb.id_rapb');
            $table->string('id_unit_kerja', 40)->comment('FK: unit_kerja.id_unit_kerja');
            $table->string('id_rpb_sarpras', 40)->nullable()->comment('FK: rpb_sarpras.id_rpb_sarpras, diisi ketika realisasi berasal dari rencana pengadaan barang');
            $table->string('nm_realisasi', 1024)->nullable();
            $table->string('id_ket_subkategori_rapb', 40)->nullable()->comment('FK: ket_subkategori_rapb.id_ket_subkategori_rapb, diisi ketika dibutuhkan keterangan tambahan subkategori rapb');
            $table->boolean('termin_dana_realisasi')->nullable()->default(1);
            $table->boolean('is_hutang_realisasi')->nullable()->default(0)->comment('0 = Realisasi Sudah Lunas/Realisasi Tanpa Termin; 1 = Masih Terdapat Hutang Realisasi;');
            $table->float('dana_realisasi', 10, 0)->nullable();
            $table->date('tgl_realisasi')->nullable();
            $table->string('id_pengguna_cek_keuangan', 40)->nullable()->comment('FK: pengguna.id_pengguna, staf keuangan yg melakukan cek Realisasi');
            $table->string('id_pengguna_kepala_keuangan', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala keuangan yg melakukan approve Realisasi');
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
        Schema::dropIfExists('realisasi');
    }
}
