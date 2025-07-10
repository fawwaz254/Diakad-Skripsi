<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceTyninttointThnMasukSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            DB::statement('ALTER TABLE siswa ALTER COLUMN thn_masuk_siswa TYPE integer USING thn_masuk_siswa::integer');
            DB::statement('ALTER TABLE siswa ALTER COLUMN thn_masuk_siswa DROP NOT NULL');
            DB::statement("COMMENT ON COLUMN siswa.thn_masuk_siswa IS 'Tahun Masuk Siswa Di Sekolah'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswa', function ($table) {
            $table->dropColumn('thn_masuk_siswa');
        });
    }
}
