<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSetTanggalCetakRaporInSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::updateOrCreate(
            ['key_setting' => 'set_tanggal_cetak_rapor_sisipan'],
            [
                'value' => '',
                'keterangan' => 'setting tanggal cetak untuk rapor sisipan'
            ]
        );

        Setting::updateOrCreate(
            ['key_setting' => 'set_tanggal_cetak_rapor_semester'],
            [
                'value' => '',
                'keterangan' => 'setting tanggal cetak untuk rapor semester'
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->delete();
        Setting::where('key_setting', 'set_tanggal_cetak_rapor_semester')->delete();
    }
}
