<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class SettingRaporSisipan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = new Setting;
        $setting->key_setting = 'mode_rapor_sisipan';
        $setting->value = '0';
        $setting->keterangan = '0 = hanya uts, 1 = (2 x RT2 SMT)+(STS), 2 = T + 2F + 2 PTS,';
        $setting->save();
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
