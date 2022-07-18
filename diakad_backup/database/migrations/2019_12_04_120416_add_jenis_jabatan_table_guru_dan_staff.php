<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJenisJabatanTableGuruDanStaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->boolean('jenis_jabatan')->after('nip_guru')->nullable()->comment('1 = kepala unit sarpras; 2 = kepala unit keuangan; 3 = staf unit sarpras; 4 = staf unit keuangan; 5 = staf unit sumber daya; 98 = kepala unit; 99 = staf uit;');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->boolean('jenis_jabatan')->after('nip_staff')->nullable()->comment('1 = kepala unit sarpras; 2 = kepala unit keuangan; 3 = staf unit sarpras; 4 = staf unit keuangan; 5 = staf unit sumber daya; 98 = kepala unit; 99 = staf uit;');
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
