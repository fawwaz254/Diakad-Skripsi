<?php
// ROLE ORANG TUA
Route::group(array('middleware' => ['token_staff']), function () {
    Route::group(array('prefix' => 'wali-murid'), function () {
        Route::get('welcome', 'WaliMurid\WelcomeController@indexWelcome');

        /** ==== MODUL MANAJEMEN FILE ==== **/
        // url: /wali-murid/manajemen-file
        Route::group(array('prefix' => 'manajemen-file'), function () {
            // MENU Data Kategori
            Route::group(array('prefix' => 'data-kategori'), function () {
                Route::get('/', 'ManajemenFile\DataKategoriController@viewDataKategori');
                Route::get('/datatables', 'ManajemenFile\DataKategoriController@datatablesCategoryfile');
            });

            // MENU Data Sub Kategori 
            Route::group(array('prefix' => 'data-sub-kategori'), function () {
                Route::get('/', 'ManajemenFile\SubDataKategoriController@viewSubDataKategori');
                Route::get('/add', 'ManajemenFile\SubDataKategoriController@addSubDataKategori');
                Route::get('/datatables', 'ManajemenFile\SubDataKategoriController@datatablesSubCategoryfile');
                Route::get('/edit/{id}', 'ManajemenFile\SubDataKategoriController@editSubDataKategori');

                //action input sub data kategori
                Route::post('action-data-sub-kategori/{mode}/{id}', 'ManajemenFile\SubDataKategoriController@actionSubDataKategori');
            });

            // MENU Data File 
            Route::group(array('prefix' => 'data-file'), function () {

                Route::get('/', 'ManajemenFile\DataFileController@viewDataFile');
                Route::get('add', 'ManajemenFile\DataFileController@addDataFile');
                Route::get('category/{category_file_id}', 'ManajemenFile\DataFileController@viewDataFileCategory');
                Route::get('dropdown-category', 'ManajemenFile\DataFileController@dropdownCategory');
                Route::get('sub-category/{sub_category_file_id}', 'ManajemenFile\DataFileController@viewDataFileSubCategory');

                Route::post('action-data-file/{mode}/{id}', 'ManajemenFile\DataFileController@actionDataFile');
                Route::get('download/{id}', 'ManajemenFile\DataFileController@downloadDataFile');
            });
        });

        /** ==== MODUL AKADEMIK ==== **/
        Route::group(array('prefix' => 'akademik'), function () {

            // MENU Kalender Akademik
            Route::get('kalender-akademik', 'WaliMurid\Akademik\KalenderAkademikController@viewKalenderAkademik');
            Route::get('kalender-akademik/datatables', 'WaliMurid\Akademik\KalenderAkademikController@datatablesKalenderAkademik');

            //Menu Lihat Absensi
            Route::group(array('prefix' => 'lihat-absensi'), function () {
				Route::get('/', 'WaliMurid\Akademik\AbsensiController@viewLihatAbsensi');
				Route::get('/{id_bulan}/{tahun}', 'WaliMurid\Akademik\AbsensiController@viewLihatAbsensi');
			});


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

            Route::post('tagihan/generate', 'WaliMurid\Keuangan\TagihanController@actionGenerate');

            // MENU Riwayat Bayar
            Route::get('riwayat-bayar', 'WaliMurid\Keuangan\RiwayatBayarController@viewRiwayatBayar');
            Route::get('riwayat-bayar/datatables', 'WaliMurid\Keuangan\RiwayatBayarController@datatablesRiwayatBayar');
        });

        /** ==== MODUL KESISWAAN ==== **/
        Route::group(array('prefix' => 'kesiswaan'), function () {
            //MENU Prestasi
            Route::get('prestasi', 'WaliMurid\Kesiswaan\PrestasiController@viewPrestasi');
            Route::get('prestasi/datatables', 'WaliMurid\Kesiswaan\PrestasiController@datatablesPrestasi');

            //MENU Beasiswa
            Route::get('beasiswa', 'WaliMurid\Kesiswaan\BeasiswaController@viewBeasiswa');
            Route::get('beasiswa/datatables', 'WaliMurid\Kesiswaan\BeasiswaController@datatablesBeasiswa');
        });

        /** ==== MODUL PELANGGARAN ==== **/
        Route::group(array('prefix' => 'pelanggaran'), function () {

            // MENU Jadwal Ujian
            Route::get('riwayat-pelanggaran', 'WaliMurid\Pelanggaran\RiwayatPelanggaranController@viewRiwayatPelanggaran');
            Route::get('riwayat-pelanggaran/datatables-non-kbm', 'WaliMurid\Pelanggaran\RiwayatPelanggaranController@datatablesPelanggaranNonKBM');
            Route::get('riwayat-pelanggaran/datatables-kbm', 'WaliMurid\Pelanggaran\RiwayatPelanggaranController@datatablesPelanggaranKBM');
        });

        Route::group(array('prefix' => 'kesekretariatan'), function () {

            Route::group(array('prefix' => 'dokumen'), function () {
                Route::get('/', 'Guru\Kesekretariatan\DokumenController@viewDokumen');
                Route::get('detail/{id}', 'Guru\Kesekretariatan\DokumenController@viewDetailDokumen');

                Route::post('datatables', 'Guru\Kesekretariatan\DokumenController@datatablesDokumen');
            });
        });
    });
});
