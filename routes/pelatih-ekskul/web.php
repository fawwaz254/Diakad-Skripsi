<?php

use App\Http\Controllers\PelatihEkskul\WelcomeController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\Guru\Kesekretariatan\DokumenController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController;
use App\Http\Controllers\PelatihEkskul\AbsensiEkskul\RekapAbsensiEkskulController;
use App\Http\Controllers\Guru\PembinaEkskul\RekapAbsensiEkskulController as RekapAbsensiEkskulControllerFromGuru;
use Illuminate\Support\Facades\Route;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('pelatih-ekskul')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);

        Route::prefix('manajemen-file')->group(function () {

            Route::prefix('data-kategori')->group(function () {
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

        Route::prefix('absensi-ekskul')->group(function () {
            // MENU Input Absensi Ekskul
            Route::get('input-absensi-ekskul', [InputAbsensiEkskulController::class, 'viewInputAbsensiEkskul']);
            Route::get('input-absensi-ekskul/{id_semester}/{id_ekskul}', [InputAbsensiEkskulController::class, 'viewInputAbsensiEkskul']);
            Route::get('input-absensi-ekskul/manage/{id_semester}/{id_ekskul}', [InputAbsensiEkskulController::class, 'viewManageInputAbsensiEkskul']);

            Route::get('input-absensi-ekskul/manage/{id_semester}/{id_ekskul}/{id_presensi_ekskul}', [InputAbsensiEkskulController::class, 'viewManageInputAbsensiEkskul']);
            Route::get('input-absensi-ekskul/detail/{id_ekskul}/{id_kelas}/{tahun}/{id_bulan}', [InputAbsensiEkskulController::class, 'viewDetailInputAbsensiEkskul']);

            Route::post('input-absensi-ekskul/datatables/{id_semester}/{id_ekskul}', [InputAbsensiEkskulController::class, 'datatablesInputAbsensiEkskul']);
            Route::post('input-absensi-ekskul/datatables-detail/{id_semester}/{id_ekskul}/{id_presensi_ekskul}', [InputAbsensiEkskulController::class, 'datatablesSiswaInputAbsensiEkskul']);
            Route::post('input-absensi-ekskul/action/{mode}', [InputAbsensiEkskulController::class, 'actionInputAbsensiEkskul']);
            Route::post('input-absensi-ekskul/action/{mode}/{id}', [InputAbsensiEkskulController::class, 'actionInputAbsensiEkskul']);

            Route::get('input-absensi-ekskul/excel/{id_semester}/{id_ekskul}', [InputAbsensiEkskulController::class, 'viewExcelInputAbsensiEkskul']);
            Route::get('input-absensi-ekskul/excel/download/{id_semester}/{id_ekskul}/{day}/{start_date}/{end_date}/{jam_mulai}/{jam_akhir}', [InputAbsensiEkskulController::class, 'downloadExcelInputAbsensiEkskul']);
            Route::post('input-absensi-ekskul/excel/upload', [InputAbsensiEkskulController::class, 'uploadExcelInputAbsensiEkskul']);

            // Route::get('input-absensi-ekskul/excel/{id_semester}/{id_ekskul}', [InputAbsensiEkskulController::class, 'viewExcelInputAbsensiEkskul']);

            // MENU Rekap Absensi Ekskul
            Route::get('rekap-absensi-ekskul', [RekapAbsensiEkskulController::class, 'viewRekapAbsensiEkskul']);
            Route::get('rekap-absensi-ekskul/detail/{id_semester}/{id_ekskul}', [RekapAbsensiEkskulController::class, 'viewDetailRekapAbsensiEkskul']);
            Route::get('rekap-absensi-ekskul/print/{id_semester}/{id_ekskul}', [RekapAbsensiEkskulController::class, 'printRekapAbsensiEkskul']);
            Route::get('rekap-absensi-ekskul/print-detail/{id_semester}/{id_ekskul}/{id_siswa}', [RekapAbsensiEkskulControllerFromGuru::class, 'printRekapAbsensiKehadiranEkskul']);
        });

        Route::prefix('kesekretariatan')->group(function () {

            Route::prefix('dokumen')->group(function () {

                Route::get('/', [DokumenController::class, 'viewDokumen']);
                Route::get('detail/{id}', [DokumenController::class, 'viewDetailDokumen']);

                Route::post('datatables', [DokumenController::class, 'datatablesDokumen']);
            });
        });
    });
});
