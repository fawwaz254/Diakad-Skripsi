<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAndAddIdSemesterTableTutupBukuBulananKas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutup_buku_bulanan_kas', function (Blueprint $table) {

            $table->dropColumn('id_semester');

            $table->string('id_semester_mulai', 40)->after('id_tutup_buku_bulanan_kas')->comment('FK: semester.id_semester');
            $table->string('id_semester_selesai', 40)->after('id_semester_mulai')->comment('FK: semester.id_semester');
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
