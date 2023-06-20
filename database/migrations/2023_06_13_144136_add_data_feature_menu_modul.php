<?php

use App\Models\Featuremenu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataFeatureMenuModul extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $featuremenu = new Featuremenu;
        $featuremenu->id_modul = 8;
        $featuremenu->is_aktif = 1;
        $featuremenu->deskripsi = 'Modul biodata yang berisi data pribadi, data prestasi dan data kegiatan';
        $featuremenu->save();
        $featuremenu1 = new Featuremenu;
        $featuremenu1->id_modul = 78;
        $featuremenu1->is_aktif = 1;
        $featuremenu1->deskripsi = 'Modul kegiatan harian yang berisi tentang pengisian form Kesehatan dan form kegiatan lainnya';
        $featuremenu1->save();
        $featuremenu2 = new Featuremenu;
        $featuremenu2->id_modul = 81;
        $featuremenu2->is_aktif = 1;
        $featuremenu2->deskripsi = 'Modul kesekretariatan yang berisi upload dokumen atau sebagai loker digital Absensi';
        $featuremenu2->save();
        $featuremenu3 = new Featuremenu;
        $featuremenu3->id_modul = 113;
        $featuremenu3->is_aktif = 1;
        $featuremenu3->deskripsi = 'Modul absensi yang berisi history absensi kehadiran dan absensi kehadiran siswa';
        $featuremenu3->save();
        $featuremenu4 = new Featuremenu;
        $featuremenu4->id_modul = 107;
        $featuremenu4->is_aktif = 1;
        $featuremenu4->deskripsi = 'Modul yang berisi manajemen materi ajar guru';
        $featuremenu4->save();
        $featuremenu5 = new Featuremenu;
        $featuremenu5->id_modul = 119;
        $featuremenu5->is_aktif = 1;
        $featuremenu5->deskripsi = 'Modul yang berisi bank soal, paket soal dan hasil test';
        $featuremenu5->save();
        $featuremenu6 = new Featuremenu;
        $featuremenu6->id_modul = 128;
        $featuremenu6->is_aktif = 1;
        $featuremenu6->deskripsi = 'Modul untuk input nilai tengah semester dan input nilai akhir semester';
        $featuremenu6->save();
        $featuremenu7 = new Featuremenu;
        $featuremenu7->id_modul = 9;
        $featuremenu7->is_aktif = 1;
        $featuremenu7->deskripsi = 'Modul yang berisi calendar akademik, jadwal KBM, jadwal ujian dan set jadwal kelas guru';
        $featuremenu7->save();
        $featuremenu8 = new Featuremenu;
        $featuremenu8->id_modul = 10;
        $featuremenu8->is_aktif = 1;
        $featuremenu8->deskripsi = 'Modul yang berisi absensi siswa di kelas beserta rekap absen siswa, absensi tanpa jadwal beserta rekap absen tanpa jadwal';
        $featuremenu8->save();
        $featuremenu9 = new Featuremenu;
        $featuremenu9->id_modul = 121;
        $featuremenu9->is_aktif = 1;
        $featuremenu9->deskripsi = 'Modul yang berisi laporan kerja individu dan laporan kerja kelompok';
        $featuremenu9->save();
        $featuremenu10 = new Featuremenu;
        $featuremenu10->id_modul = 11;
        $featuremenu10->is_aktif = 1;
        $featuremenu10->deskripsi = 'Modul yang berisi komponen nilai, input nilai KBM dan rekap nilai';
        $featuremenu10->save();
        $featuremenu11 = new Featuremenu;
        $featuremenu11->id_modul = 34;
        $featuremenu11->is_aktif = 1;
        $featuremenu11->deskripsi = 'Modul yang berisi input pelanggaran siswa KBM dan Non KBM';
        $featuremenu11->save();
        $featuremenu12 = new Featuremenu;
        $featuremenu12->id_modul = 61;
        $featuremenu12->is_aktif = 1;
        $featuremenu12->deskripsi = 'Modul yang berisi komplain inventaris sarpras';
        $featuremenu12->save();
        $featuremenu13 = new Featuremenu;
        $featuremenu13->id_modul = 110;
        $featuremenu13->is_aktif = 1;
        $featuremenu13->deskripsi = 'Modul yang berisi video tutorial per modul role guru';
        $featuremenu13->save();
        $featuremenu14 = new Featuremenu;
        $featuremenu14->id_modul = 75;
        $featuremenu14->is_aktif = 1;
        $featuremenu14->deskripsi = 'Modul yang berisi Kelas Daring untuk siswa';
        $featuremenu14->save();
        $featuremenu15 = new Featuremenu;
        $featuremenu15->id_modul = 102;
        $featuremenu15->is_aktif = 1;
        $featuremenu15->deskripsi = 'Modul yang berisi laporan kerja harian guru';
        $featuremenu15->save();
        $featuremenu16 = new Featuremenu;
        $featuremenu16->id_modul = 127;
        $featuremenu16->is_aktif = 1;
        $featuremenu16->deskripsi = 'Modul untuk laporan kerja harian kepala sekolah beserta wakil kepala sekolah';
        $featuremenu16->save();
        $featuremenu17 = new Featuremenu;
        $featuremenu17->id_modul = 134;
        $featuremenu17->is_aktif = 1;
        $featuremenu17->deskripsi = 'Modul yang berisi input ketidaksesuaian SOP guru dan data ketidaksesuaian SOP pribadi';
        $featuremenu17->save();
        $featuremenu18 = new Featuremenu;
        $featuremenu18->id_modul = 35;
        $featuremenu18->is_aktif = 1;
        $featuremenu18->deskripsi = 'Modul untuk manajemen absensi, kelas dan pelanggaran siswa';
        $featuremenu18->save();
        $featuremenu19 = new Featuremenu;
        $featuremenu19->id_modul = 36;
        $featuremenu19->is_aktif = 1;
        $featuremenu19->deskripsi = 'Modul untuk manajemen siswa dalam kelas yang ditugaskan';
        $featuremenu19->save();
        $featuremenu20 = new Featuremenu;
        $featuremenu20->id_modul = 37;
        $featuremenu20->is_aktif = 1;
        $featuremenu20->deskripsi = 'Modul untuk guru yang bertanggung jawab terhadap presensi ekstrakurikuler siswa';
        $featuremenu20->save();
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
