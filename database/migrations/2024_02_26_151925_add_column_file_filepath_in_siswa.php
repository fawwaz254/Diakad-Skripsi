<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFileFilepathInSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('file_akte')->after('thn_masuk_siswa')->nullable();
            $table->string('path_file_akte')->after('file_akte')->nullable();
            $table->string('file_kk')->after('path_file_akte')->nullable();
            $table->string('path_file_kk')->after('file_kk')->nullable();
            $table->string('file_ijazah')->after('path_file_kk')->nullable();
            $table->string('path_file_ijazah')->after('file_ijazah')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            //
        });
    }
}
