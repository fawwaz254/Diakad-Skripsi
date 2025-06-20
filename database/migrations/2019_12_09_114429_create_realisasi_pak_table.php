<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRealisasiPakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realisasi_pak', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_realisasi_pak', 40)->primary();
            $table->string('id_semester_realisasi_pak', 40)->comment('FK: semester.id_semester');
            $table->string('id_pak', 40)->comment('FK: pak.id_pak');
            $table->string('id_unit_kerja', 40)->comment('FK: unit_kerja.id_unit_kerja');
            $table->string('id_rpb_sarpras', 40)->nullable()->comment('FK: rpb_sarpras.id_rpb_sarpras, diisi ketika realisasi PAK berasal dari rencana pengadaan barang');
            $table->string('nm_realisasi_pak', 1024)->nullable();
            $table->string('id_ket_subkategori_rapb', 40)->nullable()->comment('FK: ket_subkategori_rapb.id_ket_subkategori_rapb, diisi ketika dibutuhkan keterangan tambahan subkategori rapb');
            $table->boolean('termin_dana_realisasi_pak')->nullable()->default(1);
            $table->boolean('is_hutang_realisasi_pak')->nullable()->default(0)->comment('0 = Realisasi PAK Sudah Lunas/Realisasi PAK Tanpa Termin; 1 = Masih Terdapat Hutang Realisasi PAK;');
            $table->decimal('dana_realisasi_pak', 10, 0)->nullable();
            $table->date('tgl_realisasi_pak')->nullable();
            $table->string('id_pengguna_cek_keuangan', 40)->nullable()->comment('FK: pengguna.id_pengguna, staf keuangan yg melakukan cek Realisasi PAK');
            $table->string('id_pengguna_kepala_keuangan', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala keuangan yg melakukan approve Realisasi PAK');
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
        Schema::dropIfExists('realisasi_pak');
    }
}
