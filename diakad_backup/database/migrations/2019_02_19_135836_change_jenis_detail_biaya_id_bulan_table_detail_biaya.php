<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeJenisDetailBiayaIdBulanTableDetailBiaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_biaya', function (Blueprint $table) {
            $table->boolean('id_jenis_detail_biaya')->after('jenis_detail_biaya')->nullable()->comment('FK: jenis_detail_biaya.id_jenis_detail_biaya');
            $table->boolean('id_bulan')->nullable()->comment('FK: bulan.id_bulan')->change();
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
