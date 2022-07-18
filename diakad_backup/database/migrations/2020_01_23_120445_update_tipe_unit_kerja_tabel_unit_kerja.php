<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTipeUnitKerjaTabelUnitKerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unit_kerja', function (Blueprint $table) {
            $table->string('tipe_unit_kerja', 64)->after('deskripsi_unit_kerja')->nullable()->comment('PIMPINAN, KEUANGAN, SARPRAS')->change();
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
