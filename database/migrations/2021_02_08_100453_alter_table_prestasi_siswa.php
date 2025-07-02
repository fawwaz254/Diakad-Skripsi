<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePrestasiSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestasi_siswa', function (Blueprint $table) {

            $table->integer('status')->default('0')->after('link_sertif_prestasi_siswa')->comment('0 = belum diapprove, 1 = sudah diapprove');
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
