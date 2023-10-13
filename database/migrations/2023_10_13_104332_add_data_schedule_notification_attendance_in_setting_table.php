<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataScheduleNotificationAttendanceInSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::updateOrCreate(
            ['key_setting' => 'jadwal_jam_notif_kehadiran_siswa'],
            [
                'value' => '11:00',
                'keterangan' => 'Jadwal notif kehadiran siswa'
            ]
        );

        Setting::updateOrCreate(
            ['key_setting' => 'jadwal_jam_notif_pembayaran_spp'],
            [
                'value' => '15:00',
                'keterangan' => 'Jadwal notif pembayaran SPP siswa'
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
