<?php
// ROLE ALUMNI
Route::group(array('middleware' => ['token_staff']), function () {
    Route::group(array('prefix' => 'alumni'), function () {
        Route::get('welcome', 'Alumni\WelcomeController@indexWelcome');

       /** ==== MODUL Tracer Alumni ==== **/
       Route::group(array('prefix' => 'alumni'), function () {
        Route::group(array('prefix' => 'tracer-alumni'), function () {
            Route::get('/', 'Siswa\Alumni\TracerAlumniSiswaController@viewTracerAlumni');
            Route::get('datatables', 'Siswa\Alumni\TracerAlumniSiswaController@datatablesTracerAlumni');
            Route::get('add', 'Alumni\TracerAlumni\TracerAlumnicontroller@addTracerAlumni');
            Route::get('edit/{id}', 'Siswa\Alumni\TracerAlumniSiswaController@editTracerAlumni');
            //action arahkan ke humas
            Route::post('action/{mode}/{id}', 'Humas\Alumni\TracerAlumniController@actionTracerAlumni');
        });
    });

        /** ==== MODUL MANAJEMEN FILE ==== **/
        // url: /alumni/manajemen-file
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

 

        Route::group(array('prefix' => 'bursa-kerja'), function () {

            Route::group(array('prefix' => 'bkk'), function () {

                Route::get('/', 'Alumni\BursaKerja\BKKController@viewBkk');
                Route::get('detail/{id}', 'Alumni\BursaKerja\BKKController@viewDetailBkk');
                Route::get('datatables', 'Alumni\BursaKerja\BKKController@showDatatablesBkk');
            });
        });
    });
});
