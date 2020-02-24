<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCommentOnJenisJabatanGuruStaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->boolean('jenis_jabatan')->nullable()->comment('1 = kepala unit sarpras; 2 = kepala unit keuangan; 3 = staf unit sarpras; 4 = staf unit keuangan; 5 = staf unit sumber daya; 6 = pimpinan sarpras; 7 = pimpinan keuangan; 8 = pimpinan; 98 = kepala unit; 99 = staf unit;')->change();
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->boolean('jenis_jabatan')->nullable()->comment('1 = kepala unit sarpras; 2 = kepala unit keuangan; 3 = staf unit sarpras; 4 = staf unit keuangan; 5 = staf unit sumber daya; 6 = pimpinan sarpras; 7 = pimpinan keuangan; 8 = pimpinan; 98 = kepala unit; 99 = staf unit;')->change();
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
