<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataTemplateNotificationInSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::updateOrCreate(
            ['key_setting' => 'template_notif_kehadiran_siswa'],
            [
                'value' => '*Notifikasi Ketidakhadiran Siswa Harian*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami dengan berat hati memberitahukan ketidakhadiran putra/putri Anda di sekolah hari ini, {{DATE}}\n\n\n',
                'keterangan' => 'template pesan notifikasi kehadiran/ketidakhadiran siswa'
            ]
        );

        Setting::updateOrCreate(
            ['key_setting' => 'template_notif_pembayaran_spp'],
            [
                'value' => '*Notifikasi Pembayaran SPP*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami ingin menginformasikan pembayaran SPP untuk putra/putri Anda hari ini, {{DATE}}\n\n\n',
                'keterangan' => 'template pesan notifikasi pembayaran SPP siswa'
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
