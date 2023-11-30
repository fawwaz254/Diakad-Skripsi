<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataScheduleNotificationInSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::updateOrCreate(
            ['key_setting' => 'jadwal_hari_notifikasi'],
            [
                'value' => 'MONDAY|TUESDAY|WEDNESDAY|THURSDAY|FRIDAY|SATURDAY',
                'keterangan' => 'Jadwal hari notifikasi whatsapp'
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
        Schema::table('setting', function (Blueprint $table) {
            //
        });
    }
}
