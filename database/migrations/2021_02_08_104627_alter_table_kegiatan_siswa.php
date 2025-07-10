<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableKegiatanSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatan_siswa', function (Blueprint $table) {

            $table->integer('status')->default('0')->after('nm_kegiatan_scan_sertif')->comment('0 = belum diapprove, 1 = sudah diapprove');
            $table->string('approved_by', 40)->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');

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
