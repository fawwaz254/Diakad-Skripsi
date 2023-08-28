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
                'value' => '*Notifikasi Kehadiran Siswa Harian*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami dengan senang hati memberitahukan bahwa siswa/siswi Anda, *{{STUDENT_NAME}}*, telah tiba di {{SCHOOL_NAME}} pada:\n\nHari/Tanggal: {{DATE}}\nJam Check-In: {{CHECK_IN}}\n\nKami menghargai kehadiran *{{STUDENT_NAME}}* di sekolah dan berharap hari yang produktif dan bermakna untuknya. Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan atau perlu informasi lebih lanjut.\n\nTerima kasih atas kerjasama Anda.\n\n\nSalam,\n*Kesiswaan {{SCHOOL_NAME}}*',
                'keterangan' => 'template pesan notifikasi kehadiran siswa'
            ]
        );

        Setting::updateOrCreate(
            ['key_setting' => 'template_notif_pembayaran_spp'],
            [
                'value' => '*Notifikasi Pembayaran SPP*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami ingin menginformasikan bahwa pembayaran SPP untuk siswa/siswi Anda, *{{STUDENT_NAME}}*, di {{SCHOOL_NAME}} telah berhasil diterima:\n\nBulan Pembayaran: {{PAYMENT_MONTH}}\nJumlah Pembayaran: Rp. {{PAYMENT_AMOUNT}},\nTanggal Pembayaran: {{PAYMENT_DATE}},\n\nKami mengucapkan terima kasih atas kesediaan Anda untuk memenuhi kewajiban pembayaran SPP. Dukungan Anda membantu menjaga kualitas pendidikan di *{{SCHOOL_NAME}}*. Jika Anda memiliki pertanyaan terkait pembayaran atau informasi lainnya, jangan ragu untuk menghubungi kami.\n\nTerima kasih atas perhatian dan kerjasama Anda.\n\n\nSalam,\n*Keuangan {{SCHOOL_NAME}}*',
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
