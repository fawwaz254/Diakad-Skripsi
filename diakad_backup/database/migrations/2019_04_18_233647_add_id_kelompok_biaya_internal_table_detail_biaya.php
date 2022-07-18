<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdKelompokBiayaInternalTableDetailBiaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_biaya', function (Blueprint $table) {
            $table->string('id_kelompok_biaya_internal', 40)->after('id_biaya')->nullable()->comment('FK: id_kelompok_biaya_internal.id_kelompok_biaya_internal (opsional jika ada detail biaya internal)');
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
