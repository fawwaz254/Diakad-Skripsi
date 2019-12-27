<?php
// ROLE ORANG TUA
Route::group(array('middleware'=> ['token_staff']), function () {
    Route::group(array('prefix' => 'wali-murid'), function () {
        Route::get('welcome', 'WaliMurid\WelcomeController@indexWelcome');

        /** ==== MODUL AKADEMIK ==== **/
        Route::group(array('prefix' => 'akademik'), function () {

            // MENU Kalender Akademik
            Route::get('kalender-akademik', 'WaliMurid\Akademik\KalenderAkademikController@viewKalenderAkademik');
            Route::get('kalender-akademik/datatables', 'WaliMurid\Akademik\KalenderAkademikController@datatablesKalenderAkademik');

            // MENU Jadwal KBM
            Route::get('jadwal-kbm', 'WaliMurid\Akademik\JadwalKBMController@viewJadwalKBM');
            Route::get('jadwal-kbm/datatables', 'WaliMurid\Akademik\JadwalKBMController@datatablesJadwalKBM');

            // MENU Jadwal Ujian
            Route::get('jadwal-ujian', 'WaliMurid\Akademik\JadwalUjianController@viewJadwalUjian');
            Route::get('jadwal-ujian/datatables-uts', 'WaliMurid\Akademik\JadwalUjianController@datatablesJadwalUTS');
            Route::get('jadwal-ujian/datatables-uas', 'WaliMurid\Akademik\JadwalUjianController@datatablesJadwalUAS');

            // MENU Magang
            Route::get('magang', 'WaliMurid\Akademik\MagangController@viewMagang');
            Route::get('magang/datatables', 'WaliMurid\Akademik\MagangController@datatablesMagang');
        });

        /** ==== MODUL KEUANGAN ==== **/
        Route::group(array('prefix' => 'keuangan'), function () {

            // MENU Tagihan
            Route::get('tagihan', 'WaliMurid\Keuangan\TagihanController@viewTagihan');
            Route::get('tagihan/datatables', 'WaliMurid\Keuangan\TagihanController@datatablesTagihan');

            // MENU Riwayat Bayar
            Route::get('riwayat-bayar', 'WaliMurid\Keuangan\RiwayatBayarController@viewRiwayatBayar');
            Route::get('riwayat-bayar/datatables', 'WaliMurid\Keuangan\RiwayatBayarController@datatablesRiwayatBayar');
        });

        /** ==== MODUL PELANGGARAN ==== **/
        Route::group(array('prefix' => 'pelanggaran'), function () {

            // MENU Jadwal Ujian
            Route::get('riwayat-pelanggaran', 'WaliMurid\Pelanggaran\RiwayatPelanggaranController@viewRiwayatPelanggaran');
            Route::get('riwayat-pelanggaran/datatables-non-kbm', 'WaliMurid\Pelanggaran\RiwayatPelanggaranController@datatablesPelanggaranNonKBM');
            Route::get('riwayat-pelanggaran/datatables-kbm', 'WaliMurid\Pelanggaran\RiwayatPelanggaranController@datatablesPelanggaranKBM');
        });
    });
});
