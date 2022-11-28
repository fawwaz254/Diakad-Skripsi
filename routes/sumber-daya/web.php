<?php

use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\SumberDaya\DataSumberDaya\StatusAktifGuruController;
use App\Http\Controllers\SumberDaya\DataSumberDaya\StatusAktifTendikController;
use App\Http\Controllers\SumberDaya\DataSumberDaya\UnitKerjaController;
use App\Http\Controllers\SumberDaya\DataSumberDaya\UpdateFotoUnitKerjaController;
use App\Http\Controllers\SumberDaya\Guru\InputGuruController;
use App\Http\Controllers\SumberDaya\Guru\SettingGuruPiketController;
use App\Http\Controllers\SumberDaya\Guru\UploadDataGuruController;
use App\Http\Controllers\SumberDaya\Tendik\InputTendikController;
use App\Http\Controllers\SumberDaya\Tendik\UploadDataTendikController;
use App\Http\Controllers\SumberDaya\WelcomeController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;

Route::middleware(['token_staff'])->group(function () {

    Route::prefix('sumber-daya')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);
        Route::get('biodata', [ \App\Http\Controllers\Administrator\WelcomeController::class, 'viewBiodata']);

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
        Route::prefix('data-sumber-daya')->group(function () {
            // MENU Data Unit Kerja
            Route::get('unit-kerja', [UnitKerjaController::class, 'viewUnitKerja']);
            Route::get('unit-kerja/datatables', [UnitKerjaController::class, 'datatablesUnitKerja']);
            Route::get('unit-kerja/add', [UnitKerjaController::class, 'addUnitKerja']);
            Route::get('unit-kerja/edit/{id}', [UnitKerjaController::class, 'editUnitKerja']);

            Route::post('action-unit-kerja/{mode}/{id}', [UnitKerjaController::class, 'actionUnitKerja']);


            //Menu Upload Foto

            Route::get('update-foto', [UpdateFotoUnitKerjaController::class, 'viewUpdateFoto']);
            Route::get('update-foto/batch', [UpdateFotoUnitKerjaController::class, 'viewBatchUpdateFoto']);
            Route::post('post-view-update-foto', [UpdateFotoUnitKerjaController::class, 'actionViewUpdateFoto']);
            Route::get('update-foto/view-detail-update-foto/{unit_kerja}/{status_join_table}', [UpdateFotoUnitKerjaController::class, 'viewDetailUpdateFoto']);
            Route::get('update-foto/datatables/{unit_kerja}/{status_join_table}', [UpdateFotoUnitKerjaController::class, 'datatablesUpdateFoto']);
            Route::get('update-foto/upload/{id_pengguna}', [UpdateFotoUnitKerjaController::class, 'viewUpload']);
            Route::post('action-update-foto/{mode}/{id}', [UpdateFotoUnitKerjaController::class, 'actionUpdateFoto']);
            Route::post('action-batch-upload-foto', [UpdateFotoUnitKerjaController::class, 'actionBatchUploadFoto']);


            // MENU Data Jabatan Pegawai
            // TABEL DIHAPUS
            /*Route::get('jabatan-pegawai', [JabatanPegawaiController::class, 'viewJabatanPegawai']);
            Route::get('jabatan-pegawai/datatables', [JabatanPegawaiController::class, 'datatablesJabatanPegawai']);
            Route::get('jabatan-pegawai/add', [JabatanPegawaiController::class, 'addJabatanPegawai']);
            Route::get('jabatan-pegawai/edit/{id}', [JabatanPegawaiController::class, 'editJabatanPegawai']);

            Route::post('action-jabatan-pegawai/{mode}/{id}', [JabatanPegawaiController::class, 'actionJabatanPegawai']);*/

            // MENU Data Status Aktif Guru
            Route::get('status-aktif-guru', [StatusAktifGuruController::class, 'viewStatusAktifGuru']);
            Route::get('status-aktif-guru/datatables', [StatusAktifGuruController::class, 'datatablesStatusAktifGuru']);
            Route::get('status-aktif-guru/add', [StatusAktifGuruController::class, 'addStatusAktifGuru']);
            Route::get('status-aktif-guru/edit/{id}', [StatusAktifGuruController::class, 'editStatusAktifGuru']);

            Route::post('action-status-aktif-guru/{mode}/{id}', [StatusAktifGuruController::class, 'actionStatusAktifGuru']);

            // MENU Data Status Aktif Tendik
            Route::get('status-aktif-tendik', [StatusAktifTendikController::class, 'viewStatusAktifTendik']);
            Route::get('status-aktif-tendik/datatables', [StatusAktifTendikController::class, 'datatablesStatusAktifTendik']);
            Route::get('status-aktif-tendik/add', [StatusAktifTendikController::class, 'addStatusAktifTendik']);
            Route::get('status-aktif-tendik/edit/{id}', [StatusAktifTendikController::class, 'editStatusAktifTendik']);

            Route::post('action-status-aktif-tendik/{mode}/{id}', [StatusAktifTendikController::class, 'actionStatusAktifTendik']);
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
        Route::prefix('guru')->group(function () {
            // MENU Input Guru Baru
            Route::get('input-guru', [InputGuruController::class, 'viewInputGuru']);
            Route::get('input-guru/datatables', [InputGuruController::class, 'datatablesInputGuru']);
            Route::get('input-guru/add', [InputGuruController::class, 'addInputGuru']);
            Route::get('input-guru/edit/{id}', [InputGuruController::class, 'editInputGuru']);

            Route::post('action-input-guru/{mode}/{id}', [InputGuruController::class, 'actionInputGuru']);

            Route::get('input-guru/get-kota/{id}', [InputGuruController::class, 'getKota']);

            //MENU Upload Guru
            Route::get('upload-data-guru', [UploadDataGuruController::class, 'viewUploadDataGuru']);
            Route::get('/download-file-excel', [UploadDataGuruController::class, 'downloadFileExcel'])->name('guru/download-file-excel');
            Route::post('post-file-excel', [UploadDataGuruController::class, 'uploadFileExcel']);

            //MENU Setting Guru Piket
            Route::get('setting-guru-piket', [SettingGuruPiketController::class, 'viewSettingGuruPiket']);
            Route::get('setting-guru-piket/datatables', [SettingGuruPiketController::class, 'datatablesSettingGuruPiket']);
            Route::get('setting-guru-piket/add', [SettingGuruPiketController::class, 'addSettingGuruPiket']);
            Route::get('setting-guru-piket/edit/{id}', [SettingGuruPiketController::class, 'editSettingGuruPiket']);
            Route::get('setting-guru-piket/datatablesGuru', [SettingGuruPiketController::class, 'datatablesAddGuruPiket']);

            Route::post('action-setting-guru-piket/{mode}/{id}', [SettingGuruPiketController::class, 'actionSettingGuruPiket']);
        });

        Route::prefix('tendik')->group(function () {
            Route::get('input-tendik', [InputTendikController::class, 'viewInputTendik']);
            Route::get('input-tendik/datatables', [InputTendikController::class, 'datatablesInputTendik']);
            Route::get('input-tendik/add', [InputTendikController::class, 'addInputTendik']);
            Route::get('input-tendik/edit/{id}', [InputTendikController::class, 'editInputTendik']);

            Route::post('action-input-tendik/{mode}/{id}', [InputTendikController::class, 'actionInputTendik']);

            Route::get('upload-data-tendik', [UploadDataTendikController::class, 'viewUploadDataTendik']);
            Route::get('/download-file-excel', [UploadDataTendikController::class, 'downloadFileExcel'])->name('tendik/download-file-excel');
            Route::post('post-file-excel', [UploadDataTendikController::class, 'uploadFileExcel']);
        });
    });
});
