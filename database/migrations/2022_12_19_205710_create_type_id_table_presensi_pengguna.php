<?php

use App\Models\PresensiPengguna;
use App\Models\Sekolah;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeIdTablePresensiPengguna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sekolah =  Sekolah::first();
        if ($sekolah->nm_singkat_sekolah == "smkypm2" || $sekolah->nm_singkat_sekolah == "smkypm3taman") {

            Schema::table('presensi_pengguna', function ($table) {
                $table->integer('id_presensi_pengguna', true)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
