<?php

use App\Http\Controllers\Sekretariat\DataDokumen\DokumenDibagikanController;
use App\Http\Controllers\Sekretariat\DataDokumen\InputDokumenController;
use App\Http\Controllers\Sekretariat\DataSekretariat\DataKategoriController;
use App\Http\Controllers\Sekretariat\DataSekretariat\DataLokerAlmariController;
use App\Http\Controllers\Sekretariat\DataSekretariat\DataPemilikController;
use App\Http\Controllers\Sekretariat\DataSekretariat\DataSubKategoriController;
use App\Http\Controllers\Sekretariat\Laporan\WaliKelasController;
use App\Http\Controllers\Sekretariat\ManajemenFile\DataFileController;
use App\Http\Controllers\Sekretariat\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Sekretariat\WelcomeController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('sekretariat')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);
        Route::get('biodata', [ \App\Http\Controllers\Administrator\WelcomeController::class, 'viewBiodata']);

        Route::prefix('data-sekretariat')->group(function () {
            // MENU Data Loker Almari
            Route::get('data-loker-almari', [DataLokerAlmariController::class, 'viewDataLokerAlmari']);
            Route::get('data-loker-almari/add', [DataLokerAlmariController::class, 'addDataLokerAlmari']);
            Route::get('data-loker-almari/edit/{id}', [DataLokerAlmariController::class, 'editDataLokerAlmari']);
            Route::get('data-loker-almari/datatables', [DataLokerAlmariController::class, 'datatablesDataLokerAlmari']);

            //action data loker almari
            Route::post('action-data-loker-almari/{mode}/{id}', [DataLokerAlmariController::class, 'actionDataLokerAlmari']);

            //MENU Data Pemilik
            Route::get('data-pemilik', [DataPemilikController::class, 'viewDataPemilik']);
            Route::get('data-pemilik/add', [DataPemilikController::class, 'addDataPemilik']);
            Route::get('data-pemilik/edit/{id}', [DataPemilikController::class, 'editDataPemilik']);
            Route::get('data-pemilik/datatables', [DataPemilikController::class, 'datatablesDataPemilik']);

            //action data pemilik
            Route::post('action-data-pemilik/{mode}/{id}', [DataPemilikController::class, 'actionDataPemilik']);

            //Menu Data Kategori
            Route::get('data-kategori', [DataKategoriController::class, 'viewDataKategori']);
            Route::get('data-kategori/add', [DataKategoriController::class, 'addDataKategori']);
            Route::get('data-kategori/edit/{id}', [DataKategoriController::class, 'editDataKategori']);
            Route::get('data-kategori/datatables', [DataKategoriController::class, 'datatablesDataKategori']);

            //action data kategori
            Route::post('action-data-kategori/{mode}/{id}', [DataKategoriController::class, 'actionDataKategori']);

            //Menu Data Sub-Kategori
            Route::get('data-sub-kategori', [DataSubKategoriController::class, 'viewDataSubKategori']);
            Route::get('data-sub-kategori/add', [DataSubKategoriController::class, 'addDataSubKategori']);
            Route::get('data-sub-kategori/edit/{id}', [DataSubKategoriController::class, 'editDataSubKategori']);
            Route::get('data-sub-kategori/datatables', [DataSubKategoriController::class, 'datatablesDataSubKategori']);

            //action data sub-kategori
            Route::post('action-data-sub-kategori/{mode}/{id}', [DataSubKategoriController::class, 'actionDataSubKategori']);
        });

        Route::prefix('data-dokumen')->group(function () {
            // MENU Input Dokumen
            Route::get('input-dokumen', [InputDokumenController::class, 'viewInputDokumen']);
            Route::get('input-dokumen/add', [InputDokumenController::class, 'manageInputDokumen']);
            Route::get('input-dokumen/edit/{id}', [InputDokumenController::class, 'manageInputDokumen']);
            Route::get('input-dokumen/upload/{id}', [InputDokumenController::class, 'uploadInputDokumen']);
            Route::get('input-dokumen/datatables', [InputDokumenController::class, 'datatablesInputDokumen']);

            //action input dokumen
            Route::post('action-input-dokumen/{mode}/{id}', [InputDokumenController::class, 'actionInputDokumen']);

            //ajax subkategori
            Route::post('sub-kategori', [InputDokumenController::class, 'ajaxGetSubkategori']);

            Route::prefix('dokumen-dibagikan')->group(function () {
                Route::get('/', [DokumenDibagikanController::class, 'viewDokumenDibagikan']);
            });
        });

        Route::prefix('kegiatan-harian')->group(function () {

            Route::prefix('mengisi-form-kesehatan')->group(function () {
                // MENU Mengisi form kesehatan
                Route::get('/', [FormKesehatanController::class, 'viewFormKesehatan']);
                Route::get('add', [FormKesehatanController::class, 'viewAddFormKesehatan']);
                Route::get('detail/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

                Route::post('action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
                Route::post('datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
            });
        });

        Route::prefix('manajemen-file')->group(function () {

            Route::prefix('data-kategori')->group(function () {
                Route::get('/', [DataKategoriController::class, 'viewDataKategori']);
                Route::get('/add', [DataKategoriController::class, 'addDataKategori']);
                Route::get('/datatables', [DataKategoriController::class, 'datatablesCategoryfile']);
                Route::get('/edit/{id}', [DataKategoriController::class, 'editDataKategori']);

                //action input data kategori
                Route::post('action-data-kategori/{mode}/{id}', [DataKategoriController::class, 'actionDataKategori']);
            });
            Route::prefix('data-sub-kategori')->group(function () {
                Route::get('/', [SubDataKategoriController::class, 'viewSubDataKategori']);
                Route::get('/add', [SubDataKategoriController::class, 'addSubDataKategori']);
                Route::get('/datatables', [SubDataKategoriController::class, 'datatablesSubCategoryfile']);
                Route::get('/edit/{id}', [SubDataKategoriController::class, 'editSubDataKategori']);

                //action input sub data kategori
                Route::post('action-data-sub-kategori/{mode}/{id}', [SubDataKategoriController::class, 'actionSubDataKategori']);
            });
            Route::prefix('data-file')->group(function () {
                Route::get('/', [DataFileController::class, 'viewDataFile']);
                Route::get('add', [DataFileController::class, 'addDataFile']);
                Route::get('category/{category_file_id}', [DataFileController::class, 'viewDataFileCategory']);
                Route::get('sub-category/{sub_category_file_id}', [DataFileController::class, 'viewDataFileSubCategory']);
                Route::get('dropdown-category', [DataFileController::class, 'dropdownCategory']);
                Route::post('action-data-file/{mode}/{id}', [DataFileController::class, 'actionDataFile']);
                Route::get('download/{id}', [DataFileController::class, 'downloadDataFile']);
            });
        });

        Route::prefix('laporan')->group(function () {
            Route::prefix('wali-kelas')->group(function () {
                Route::get('/', [WaliKelasController::class, 'viewWaliKelas']);
                Route::get('datatables', [WaliKelasController::class, 'datatablesWaliKelas']);
                Route::get('detail/{id}', [WaliKelasController::class, 'detailWaliKelas']);
                Route::get('detail-datatable/{id}', [WaliKelasController::class, 'detailDataTable']);
            });
        });
    });
});
