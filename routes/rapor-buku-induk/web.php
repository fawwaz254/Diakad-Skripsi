<?php
// ROLE ALUMNI
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'rapor-buku-induk'), function() {
        Route::get('welcome', 'RaporBukuInduk\WelcomeController@indexWelcome');

        /** ==== MODUL RAPOR ==== **/
        // url: /rapor-buku-induk/rapor
        Route::group(array('prefix' => 'rapor'), function() {
            // Menu Cari Siswa
            Route::get('cari-siswa/{nis_nama_siswa?}', 'RaporBukuInduk\Rapor\CariSiswaController@viewCariSiswa');
            Route::post('post-view-cari-siswa', 'RaporBukuInduk\Rapor\CariSiswaController@actionViewCariSiswa');
            Route::get('cari-siswa/datatables/{nis_nama_siswa}', 'RaporBukuInduk\Rapor\CariSiswaController@datatablesCariSiswa');
        });
    });
});