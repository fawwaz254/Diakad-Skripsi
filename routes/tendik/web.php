<?php
// ROLE TENAGA PENDIDIK
Route::group(array('middleware'=> ['token_staff']), function () {
    Route::group(array('prefix' => 'tendik'), function () {
        Route::get('welcome', 'Tendik\WelcomeController@indexWelcome');

        /** ==== MODUL GURU PIKET ==== **/
        Route::group(array('prefix' => 'guru-piket'), function () {
            // MENU Monitoring kelas kosong
            Route::get('monitoring-kelas-kosong', 'Guru\GuruPiket\MonitoringKelasKosongController@viewMonitoringKelasKosong');
            Route::get('monitoring-kelas-kosong/datatables', 'Guru\GuruPiket\MonitoringKelasKosongController@datatablesMonitoringKelasKosong');

            // MENU Absensi Harian Siswa
            Route::get('absensi-harian-siswa', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewManageAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}/{id_presensi_harian}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewManageAbsensiHarianSiswa');
            
            Route::post('absensi-harian-siswa/datatables/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@datatablesAbsensiHarianSiswa');
            Route::post('absensi-harian-siswa/datatables-detail/{id_semester}/{id_kelas}/{id_presensi_harian}', 'Guru\GuruPiket\AbsensiHarianSiswaController@datatablesKelasAbsensiHariSiswa');
            Route::post('absensi-harian-siswa/action/{mode}', 'Guru\GuruPiket\AbsensiHarianSiswaController@actionAbsensiHarianSiswa');
        });
    });
});
