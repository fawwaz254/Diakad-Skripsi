<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pendidikan\Siswa\InsertUpdateSiswaController;
use App\Http\Controllers\RaporBukuInduk\BukuInduk\CariSiswaBIController;
use App\Http\Controllers\RaporBukuInduk\BukuInduk\CetakByKelasBIController;
use App\Http\Controllers\RaporBukuInduk\Rapor\CariSiswaController;
use App\Http\Controllers\RaporBukuInduk\Rapor\CetakByKelasController;
use App\Http\Controllers\RaporBukuInduk\WelcomeController;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('rapor-buku-induk')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);
        Route::get('biodata', [\App\Http\Controllers\Administrator\WelcomeController::class, 'viewBiodata']);

        Route::prefix('rapor')->group(function () {
            // Menu Cari Siswa
            Route::get('cari-siswa/{nis_nama_siswa?}', [CariSiswaController::class, 'viewCariSiswa']);
            Route::post('post-view-cari-siswa', [CariSiswaController::class, 'actionViewCariSiswa']);
            Route::get('cari-siswa/datatables/{nis_nama_siswa}', [CariSiswaController::class, 'datatablesCariSiswa']);
            Route::post('cari-siswa/print-rapor', [CariSiswaController::class, 'printRaporSiswa']);
            Route::post('cari-siswa/preview-rapor', [CariSiswaController::class, 'previewRaporSiswa']);

            // Menu Cetak By Kelas
            Route::get('cetak-by-kelas/{id_kelas?}', [CetakByKelasController::class, 'viewCetakByKelas']);
            Route::post('post-view-cetak-by-kelas', [CetakByKelasController::class, 'actionViewCetakByKelas']);
            Route::get('cetak-by-kelas/datatables/{id_kelas}', [CetakByKelasController::class, 'datatablesCetakByKelas']);
            Route::post('cetak-by-kelas/print-rapor', [CetakByKelasController::class, 'printRaporSiswa']);
            Route::post('cetak-by-kelas/preview-rapor', [CetakByKelasController::class, 'previewRaporSiswa']);
        });

        Route::prefix('buku-induk')->group(function () {
            // Menu Cari Siswa
            Route::get('cari-siswa/{nis_nama_siswa?}', [CariSiswaBIController::class, 'viewCariSiswa']);
            Route::post('post-view-cari-siswa', [CariSiswaBIController::class, 'actionViewCariSiswa']);
            Route::get('cari-siswa/datatables/{nis_nama_siswa}', [CariSiswaBIController::class, 'datatablesCariSiswa']);
            Route::get('cari-siswa/print-rapor/{nis_siswa}', [InsertUpdateSiswaController::class, 'viewPrintSiswa']);

            // Menu Cetak By Kelas
            Route::get('cetak-by-kelas/{id_kelas?}', [CetakByKelasBIController::class, 'viewCetakByKelas']);
            Route::post('post-view-cetak-by-kelas', [CetakByKelasBIController::class, 'actionViewCetakByKelas']);
            Route::get('cetak-by-kelas/datatables/{id_kelas}', [CetakByKelasBIController::class, 'datatablesCetakByKelas']);
            Route::get('cetak-by-kelas/print-rapor/{nis_siswa}', [InsertUpdateSiswaController::class, 'viewPrintSiswa']);
            Route::get('cetak-by-kelas/cetak-all-kelas/{id_kelas}', [InsertUpdateSiswaController::class, 'viewPrintSiswaKelas']);
            // Route::post('cetak-by-kelas/preview-rapor', [CetakByKelasController::class, 'previewRaporSiswa']);
        });
    });
});
