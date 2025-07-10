<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableForKegiatanHarian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_harian', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kegiatan_harian', 40)->primary();
            $table->string('nm_kegiatan_harian', 1024)->nullable();
            $table->boolean('is_aktif')->nullable();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });

        Schema::create('kegiatan_harian_kategori', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kegiatan_harian_kategori', 40)->primary();
            $table->string('id_kegiatan_harian', 40)->comment('FK: kegiatan_harian.id_kegiatan_harian');
            $table->string('nm_kegiatan_harian_kategori', 1024)->nullable();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });

        Schema::create('kegiatan_harian_pertanyaan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kegiatan_harian_pertanyaan', 40)->primary();
            $table->string('id_kegiatan_harian_kategori', 40)->comment('FK: kegiatan_harian_kategori.id_kegiatan_harian_kategori');
            $table->string('isi_pertanyaan', 1024)->nullable();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });

        Schema::create('kegiatan_harian_jawaban', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kegiatan_harian_jawaban', 40)->primary();
            $table->string('id_kegiatan_harian_pertanyaan', 40)->comment('FK: kegiatan_harian_pertanyaan.id_kegiatan_harian_pertanyaan');
            $table->decimal('bobot_jawaban', 10)->nullable();
            $table->string('warna_keadaan', 32)->nullable();
            $table->string('isi_jawaban', 1024)->nullable();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });

        Schema::create('pengisian_kegiatan_harian', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pengisian_kegiatan_harian', 40)->primary();
            $table->string('id_pengguna_pengisi', 40)->comment('FK: pengguna.id_pengguna');
            $table->boolean('status_join_table')->nullable();
            $table->boolean('status_pengisian')->comment('0: Belum selesai; 1: Keadaan normal; 2: Keadaan Warning')->default('0');
            $table->string('warna_keadaan', 32)->nullable();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });

        Schema::create('pengisian_jawaban', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pengisian_jawaban', 40)->primary();
            $table->string('id_kegiatan_harian_pertanyaan', 40)->comment('FK: kegiatan_harian_pertanyaan.id_kegiatan_harian_pertanyaan');
            $table->string('id_kegiatan_harian_jawaban', 40)->comment('FK: kegiatan_harian_jawaban.id_kegiatan_harian_jawaban');
            $table->string('isi_jawaban_text', 1024)->nullable();
            $table->decimal('bobot_jawaban', 10)->nullable();
            $table->string('warna_keadaan', 32)->nullable();
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
        //
    }
}
