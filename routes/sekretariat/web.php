<?php
// ROLE SEKRETARIAT
Route::group(array('middleware' => ['token_staff']), function () {
    Route::group(array('prefix' => 'sekretariat'), function () {

        Route::get('welcome', 'Sekretariat\WelcomeController@indexWelcome');

        Route::group(array('prefix' => 'data-sekretariat'), function () {

            // MENU Data Loker Almari
            Route::get('data-loker-almari', 'Sekretariat\DataSekretariat\DataLokerAlmariController@viewDataLokerAlmari');
            Route::get('data-loker-almari/add', 'Sekretariat\DataSekretariat\DataLokerAlmariController@addDataLokerAlmari');
            Route::get('data-loker-almari/edit/{id}', 'Sekretariat\DataSekretariat\DataLokerAlmariController@editDataLokerAlmari');
            Route::get('data-loker-almari/datatables', 'Sekretariat\DataSekretariat\DataLokerAlmariController@datatablesDataLokerAlmari');

            //action data loker almari
            Route::post('action-data-loker-almari/{mode}/{id}', 'Sekretariat\DataSekretariat\DataLokerAlmariController@actionDataLokerAlmari');

            //MENU Data Pemilik
            Route::get('data-pemilik', 'Sekretariat\DataSekretariat\DataPemilikController@viewDataPemilik');
            Route::get('data-pemilik/add', 'Sekretariat\DataSekretariat\DataPemilikController@addDataPemilik');
            Route::get('data-pemilik/edit/{id}', 'Sekretariat\DataSekretariat\DataPemilikController@editDataPemilik');
            Route::get('data-pemilik/datatables', 'Sekretariat\DataSekretariat\DataPemilikController@datatablesDataPemilik');

            //action data pemilik
            Route::post('action-data-pemilik/{mode}/{id}', 'Sekretariat\DataSekretariat\DataPemilikController@actionDataPemilik');

            //Menu Data Kategori
            Route::get('data-kategori', 'Sekretariat\DataSekretariat\DataKategoriController@viewDataKategori');
            Route::get('data-kategori/add', 'Sekretariat\DataSekretariat\DataKategoriController@addDataKategori');
            Route::get('data-kategori/edit/{id}', 'Sekretariat\DataSekretariat\DataKategoriController@editDataKategori');
            Route::get('data-kategori/datatables', 'Sekretariat\DataSekretariat\DataKategoriController@datatablesDataKategori');

            //action data kategori
            Route::post('action-data-kategori/{mode}/{id}', 'Sekretariat\DataSekretariat\DataKategoriController@actionDataKategori');

            //Menu Data Sub-Kategori
            Route::get('data-sub-kategori', 'Sekretariat\DataSekretariat\DataSubKategoriController@viewDataSubKategori');
            Route::get('data-sub-kategori/add', 'Sekretariat\DataSekretariat\DataSubKategoriController@addDataSubKategori');
            Route::get('data-sub-kategori/edit/{id}', 'Sekretariat\DataSekretariat\DataSubKategoriController@editDataSubKategori');
            Route::get('data-sub-kategori/datatables', 'Sekretariat\DataSekretariat\DataSubKategoriController@datatablesDataSubKategori');

            //action data sub-kategori
            Route::post('action-data-sub-kategori/{mode}/{id}', 'Sekretariat\DataSekretariat\DataSubKategoriController@actionDataSubKategori');
        });

        Route::group(array('prefix' => 'data-dokumen'), function () {
            // MENU Input Dokumen
            Route::get('input-dokumen', 'Sekretariat\DataDokumen\InputDokumenController@viewInputDokumen');
            Route::get('input-dokumen/add', 'Sekretariat\DataDokumen\InputDokumenController@manageInputDokumen');
            Route::get('input-dokumen/edit/{id}', 'Sekretariat\DataDokumen\InputDokumenController@manageInputDokumen');
            Route::get('input-dokumen/upload/{id}', 'Sekretariat\DataDokumen\InputDokumenController@uploadInputDokumen');
            Route::get('input-dokumen/datatables', 'Sekretariat\DataDokumen\InputDokumenController@datatablesInputDokumen');

            //action input dokumen
            Route::post('action-input-dokumen/{mode}/{id}', 'Sekretariat\DataDokumen\InputDokumenController@actionInputDokumen');

            //ajax subkategori
            Route::post('sub-kategori', 'Sekretariat\DataDokumen\InputDokumenController@ajaxGetSubkategori');

            Route::group(array('prefix' => 'dokumen-dibagikan'), function () {
                Route::get('/', 'Sekretariat\DataDokumen\DokumenDibagikanController@viewDokumenDibagikan');
            });
        });

        Route::group(array('prefix' => 'kegiatan-harian'), function () {

            Route::group(array('prefix' => 'mengisi-form-kesehatan'), function () {
                // MENU Mengisi form kesehatan
                Route::get('/', 'Tendik\KegiatanHarian\FormKesehatanController@viewFormKesehatan');
                Route::get('add', 'Tendik\KegiatanHarian\FormKesehatanController@viewAddFormKesehatan');
                Route::get('detail/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');

                Route::post('action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
                Route::post('datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');
            });
        });


        Route::group(array('prefix' => 'manajemen-file'), function () {

            Route::group(array('prefix' => 'data-kategori'), function () {
                Route::get('/', 'Sekretariat\ManajemenFile\DataKategoriController@viewDataKategori');
                Route::get('/add', 'Sekretariat\ManajemenFile\DataKategoriController@addDataKategori');
                Route::get('/datatables', 'Sekretariat\ManajemenFile\DataKategoriController@datatablesCategoryfile');
                Route::get('/edit/{id}', 'Sekretariat\ManajemenFile\DataKategoriController@editDataKategori');

                //action input data kategori
                Route::post('action-data-kategori/{mode}/{id}', 'Sekretariat\ManajemenFile\DataKategoriController@actionDataKategori');
            });

            Route::group(array('prefix' => 'data-sub-kategori'), function () {
                Route::get('/', 'Sekretariat\ManajemenFile\SubDataKategoriController@viewSubDataKategori');
                Route::get('/add', 'Sekretariat\ManajemenFile\SubDataKategoriController@addSubDataKategori');
                Route::get('/datatables', 'Sekretariat\ManajemenFile\SubDataKategoriController@datatablesSubCategoryfile');
                Route::get('/edit/{id}', 'Sekretariat\ManajemenFile\SubDataKategoriController@editSubDataKategori');

                //action input sub data kategori
                Route::post('action-data-sub-kategori/{mode}/{id}', 'Sekretariat\ManajemenFile\SubDataKategoriController@actionSubDataKategori');
            });

            Route::group(array('prefix' => 'data-file'), function () {

                Route::get('/', 'Sekretariat\ManajemenFile\DataFileController@viewDataFile');
                Route::get('add', 'Sekretariat\ManajemenFile\DataFileController@addDataFile');
                Route::get('category/{category_file_id}', 'Sekretariat\ManajemenFile\DataFileController@viewDataFileCategory');
                Route::get('sub-category/{sub_category_file_id}', 'Sekretariat\ManajemenFile\DataFileController@viewDataFileSubCategory');
                Route::post('action-data-file/{mode}/{id}', 'Sekretariat\ManajemenFile\DataFileController@actionDataFile');
            });
        });

        Route::group(array('prefix' => 'laporan'), function () {

            Route::group(array('prefix' => 'wali-kelas'), function () {

                Route::get('/', 'Sekretariat\Laporan\WaliKelasController@viewWaliKelas');
                Route::get('datatables', 'Sekretariat\Laporan\WaliKelasController@datatablesWaliKelas');
                Route::get('detail/{id}', 'Sekretariat\Laporan\WaliKelasController@detailWaliKelas');
                Route::get('detail-datatable/{id}', 'Sekretariat\Laporan\WaliKelasController@detailDataTable');
            });
        });
    });
});
