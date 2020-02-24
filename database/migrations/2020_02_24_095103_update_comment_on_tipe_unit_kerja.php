<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCommentOnTipeUnitKerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unit_kerja', function (Blueprint $table) {
            $table->string('tipe_unit_kerja', 64)->nullable()->comment('PIMPINAN, KEUANGAN, SARPRAS, REKTORAT, FAKULTAS, PRODI')->change();
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
