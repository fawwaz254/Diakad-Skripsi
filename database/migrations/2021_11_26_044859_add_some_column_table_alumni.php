<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnTableAlumni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alumni_bekerja', function (Blueprint $table) {
            $table->string('kapan_mulai_bekerja',100)->nullable()->after('tahun_masuk_instansi')->comment('setelah terima ijazah / sebelum terima ijazah');
            $table->integer('lama_bekerja')->nullable()->after('kapan_mulai_bekerja')->comment('dalam bulan');
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
