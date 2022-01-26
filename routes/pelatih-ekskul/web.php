<?php
// ROLE PELATIH EKSKUL
Route::group(array('middleware' => ['token_staff']), function () {
    Route::group(array('prefix' => 'pelatih-ekskul'), function () {
        Route::get('welcome', 'PelatihEkskul\WelcomeController@indexWelcome');

        /** ==== MODUL MANAJEMEN FILE ==== **/
        // url: /pelatih-ekskul/manajemen-file
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
                Route::get('sub-category/{sub_category_file_id}', 'ManajemenFile\DataFileController@viewDataFileSubCategory');

                Route::post('action-data-file/{mode}/{id}', 'ManajemenFile\DataFileController@actionDataFile');
            });
        });

        /** ==== MODUL ABSENSI EKSKUL ==== **/
        Route::group(array('prefix' => 'absensi-ekskul'), function () {
            // MENU Input Absensi Ekskul
            Route::get('input-absensi-ekskul', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@viewInputAbsensiEkskul');
            Route::get('input-absensi-ekskul/{id_semester}/{id_ekskul}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@viewInputAbsensiEkskul');
            Route::get('input-absensi-ekskul/manage/{id_semester}/{id_ekskul}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@viewManageInputAbsensiEkskul');
            Route::get('input-absensi-ekskul/manage/{id_semester}/{id_ekskul}/{id_presensi_ekskul}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@viewManageInputAbsensiEkskul');
            Route::get('input-absensi-ekskul/detail/{id_ekskul}/{id_kelas}/{tahun}/{id_bulan}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@viewDetailInputAbsensiEkskul');

            Route::post('input-absensi-ekskul/datatables/{id_semester}/{id_ekskul}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@datatablesInputAbsensiEkskul');
            Route::post('input-absensi-ekskul/datatables-detail/{id_semester}/{id_ekskul}/{id_presensi_ekskul}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@datatablesSiswaInputAbsensiEkskul');
            Route::post('input-absensi-ekskul/action/{mode}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@actionInputAbsensiEkskul');
            Route::post('input-absensi-ekskul/action/{mode}/{id}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@actionInputAbsensiEkskul');

            // MENU Rekap Absensi Ekskul
            Route::get('rekap-absensi-ekskul', 'PelatihEkskul\AbsensiEkskul\RekapAbsensiEkskulController@viewRekapAbsensiEkskul');
            Route::get('rekap-absensi-ekskul/detail/{id_semester}/{id_ekskul}', 'PelatihEkskul\AbsensiEkskul\RekapAbsensiEkskulController@viewDetailRekapAbsensiEkskul');
            Route::get('rekap-absensi-ekskul/print/{id_semester}/{id_ekskul}', 'PelatihEkskul\AbsensiEkskul\RekapAbsensiEkskulController@printRekapAbsensiEkskul');
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
