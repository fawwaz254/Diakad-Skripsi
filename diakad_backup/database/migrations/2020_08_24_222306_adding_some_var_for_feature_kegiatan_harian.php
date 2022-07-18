<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingSomeVarForFeatureKegiatanHarian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengisian_jawaban', function (Blueprint $table) {
            $table->string('id_pengisian_kegiatan_harian', 40)->after('id_pengisian_jawaban')->comment('FK: pengisian_kegiatan_harian.id_pengisian_kegiatan_harian');
        });

        Schema::table('kegiatan_harian_pertanyaan', function (Blueprint $table) {
            $table->integer('show_order')->after('id_kegiatan_harian_kategori');
        });

        Schema::table('kegiatan_harian_jawaban', function (Blueprint $table) {
            $table->integer('show_order')->after('id_kegiatan_harian_pertanyaan');
            $table->string('placeholder', 256)->after('warna_keadaan')->nullable()->comment('Digunakan apabila ada pilihan jawaban yang mengandung isi text');
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
