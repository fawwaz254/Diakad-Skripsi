<?php

use App\Http\Controllers\Alumni\WelcomeController;
use App\Http\Controllers\Alumni\BursaKerja\BKKController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Siswa\Alumni\TracerAlumniSiswaController;
use App\Http\Controllers\Alumni\TracerAlumni\TracerAlumniController;
use App\Http\Controllers\Humas\Alumni\TracerAlumniController as TracerAlumniHumas;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('alumni')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);

        Route::prefix('alumni')->group(function () {
            Route::get('/', [TracerAlumniSiswaController::class, 'viewTracerAlumni']);

            Route::prefix('tracer-alumni')->group(function () {
                Route::get('/', [TracerAlumniSiswaController::class, 'viewTracerAlumni']);
                Route::get('datatables', [TracerAlumniSiswaController::class, 'datatablesTracerAlumni']);
                Route::get('add', [TracerAlumniController::class, 'addTracerAlumni']);
                Route::get('edit/{id}', [TracerAlumniSiswaController::class, 'editTracerAlumni']);
                //action arahkan ke tracer alumni humas
                Route::post('action/{mode}/{id}', [TracerAlumniHumas::class, 'actionTracerAlumni']);
            });
        });

        Route::prefix('manajemen-file')->group(function () {

            Route::prefix('data-kategori')->group(function () {
                Route::get('/', [DataKategoriController::class, 'viewDataKategori']);
                Route::get('/datatables', [DataKategoriController::class, 'datatablesCategoryfile']);
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
                Route::get('dropdown-category', [DataFileController::class, 'dropdownCategory']);
                Route::get('sub-category/{sub_category_file_id}', [DataFileController::class, 'viewDataFileSubCategory']);

                Route::post('action-data-file/{mode}/{id}', [DataFileController::class, 'actionDataFile']);
                Route::get('download/{id}', [DataFileController::class, 'downloadDataFile']);
            });
        });

        Route::prefix('bursa-kerja')->group(function () {

            Route::prefix('bkk')->group(function () {
                Route::get('/', [BKKController::class, 'viewBkk']);
                Route::get('detail/{id}', [BKKController::class, 'viewDetailBkk']);
                Route::get('datatables', [BKKController::class, 'showDatatablesBkk']);
            });
        });
    });
});
