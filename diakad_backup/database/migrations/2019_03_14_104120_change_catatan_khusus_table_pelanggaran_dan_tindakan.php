<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCatatanKhususTablePelanggaranDanTindakan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggaran_siswa', function (Blueprint $table) {
            $table->text('catatan_pelanggaran_khusus')->nullable()->comment('private konseling dari individu BK (tidak dapat diakses oleh aktor yg lain)')->change();
        });

        Schema::table('tindakan_pelanggaran', function (Blueprint $table) {
            $table->text('catatan_tindakan_pelanggaran_khusus')->nullable()->comment('private konseling dari individu BK (tidak dapat diakses oleh aktor yg lain)')->change();
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
