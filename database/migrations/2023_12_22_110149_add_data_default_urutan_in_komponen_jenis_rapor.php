<?php

use App\Models\KomponenJenisRapor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataDefaultUrutanInKomponenJenisRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $komponen_jenis_rapors = KomponenJenisRapor::get();
        foreach ($komponen_jenis_rapors as $komponen_jenis_rapor) {
            $komponen_jenis_rapor->urutan = '1';
            $komponen_jenis_rapor->save();
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
