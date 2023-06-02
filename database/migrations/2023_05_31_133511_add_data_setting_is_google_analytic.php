<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataSettingIsGoogleAnalytic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $cek = Setting::where('key_setting', 'is_google_analytic')->first();
        if ($cek) { } else {
            $google_id = new Setting;
            $google_id->key_setting = 'is_google_analytic';
            $google_id->value = 0;
            $google_id->save();
        }
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
