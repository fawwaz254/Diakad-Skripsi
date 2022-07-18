<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdGuruEntryPresensiHarian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presensi_harian', function (Blueprint $table) {
            $table->string('id_guru_entry', 40)->after('id_siswa_entry')->nullable()->comment('FK: guru.id_guru (null apabila presensi oleh sekretaris kelas)');
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
