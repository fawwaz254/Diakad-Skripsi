<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullableIdRpbSarprasHabisPakaiOnRealisasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('realisasi', function (Blueprint $table) {
            $table->string('id_rpb_sarpras_habis_pakai', 40)->after('id_rpb_sarpras')->nullable()->comment('FK: rpb_sarpras_habis_pakai.id_rpb_sarpras_habis_pakai, diisi ketika realisasi berasal dari rencana pengadaan barang habis pakai')->change();
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
