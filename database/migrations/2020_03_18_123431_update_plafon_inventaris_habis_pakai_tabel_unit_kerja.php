<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePlafonInventarisHabisPakaiTabelUnitKerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unit_kerja', function (Blueprint $table) {
            $table->decimal('plafon_inventaris_habis_pakai', 10, 0)->after('nm_singkatan_unit')->comment('batas permintaan barang inventaris habis pakai')->nullable()->change();
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
