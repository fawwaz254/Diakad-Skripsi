<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdSubkategoriPelanggaranTablePelanggaranSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggaran_siswa', function (Blueprint $table) {
            $table->string('id_subkategori_pelanggaran', 40)->after('id_guru_input')->nullable()->comment('FK: subkategori_pelanggaran.id_subkategori_pelanggaran');
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
