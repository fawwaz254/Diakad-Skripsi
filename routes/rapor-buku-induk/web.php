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
            Route::post('cari-siswa/print-rapor', 'RaporBukuInduk\Rapor\CariSiswaController@printRaporSiswa');
            Route::post('cari-siswa/preview-rapor', 'RaporBukuInduk\Rapor\CariSiswaController@previewRaporSiswa');
            
            // Menu Cetak By Kelas
            Route::get('cetak-by-kelas/{id_kelas?}', 'RaporBukuInduk\Rapor\CetakByKelasController@viewCetakByKelas');
            Route::post('post-view-cetak-by-kelas', 'RaporBukuInduk\Rapor\CetakByKelasController@actionViewCetakByKelas');
            Route::get('cetak-by-kelas/datatables/{id_kelas}', 'RaporBukuInduk\Rapor\CetakByKelasController@datatablesCetakByKelas');
            Route::post('cetak-by-kelas/print-rapor', 'RaporBukuInduk\Rapor\CetakByKelasController@printRaporSiswa');
            Route::post('cetak-by-kelas/preview-rapor', 'RaporBukuInduk\Rapor\CetakByKelasController@previewRaporSiswa');
        });
    });
});