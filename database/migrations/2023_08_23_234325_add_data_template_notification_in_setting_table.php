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
        $notif_kehadiran_siswa = new Setting;
        $notif_kehadiran_siswa->key_setting = 'template_notif_kehadiran_siswa';
        $notif_kehadiran_siswa->value = '*Notifikasi Kehadiran Siswa Harian*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami dengan senang hati memberitahukan bahwa siswa/siswi Anda, *{{STUDENT_NAME}}*, telah tiba di {{SCHOOL_NAME}} pada:\n\nTanggal: {{DATE}}\nJam Check-In: {{CHECK_IN}}\n\nKami menghargai kehadiran *{{STUDENT_NAME}}* di sekolah dan berharap hari yang produktif dan bermakna untuknya. Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan atau perlu informasi lebih lanjut.\n\nTerima kasih atas kerjasama Anda.\n\n\nSalam,\n*Kesiswaan {{SCHOOL_NAME}}*';
        $notif_kehadiran_siswa->keterangan = 'template pesan notifikasi kehadiran siswa';
        $notif_kehadiran_siswa->save();

        $notif_pembayaran_spp = new Setting;
        $notif_pembayaran_spp->key_setting = 'template_notif_pembayaran_spp';
        $notif_pembayaran_spp->value = '*Notifikasi Pembayaran SPP*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami ingin menginformasikan bahwa pembayaran SPP untuk siswa/siswi Anda, *{{STUDENT_NAME}}*, di *{{SCHOOL_NAME}}* telah berhasil diterima:\n\nTanggal Pembayaran: {{PAYMENT_DATE}}\nJumlah Pembayaran: Rp. {{PAYMENT_AMOUNT}}\n\nKami mengucapkan terima kasih atas kesediaan Anda untuk memenuhi kewajiban pembayaran SPP. Dukungan Anda membantu menjaga kualitas pendidikan di *{{SCHOOL_NAME}}*. Jika Anda memiliki pertanyaan terkait pembayaran atau informasi lainnya, jangan ragu untuk menghubungi kami.\n\nTerima kasih atas perhatian dan kerjasama Anda.\n\n\nSalam,\n*Keuangan {{SCHOOL_NAME}}*';
        $notif_pembayaran_spp->keterangan = 'template pesan notifikasi pembayaran SPP siswa';
        $notif_pembayaran_spp->save();
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
