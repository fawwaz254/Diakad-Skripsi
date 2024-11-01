<?php

use App\Http\Controllers\Akademik\RaporSisipan\CetakRaporController;
use App\Http\Controllers\Guru\Kesekretariatan\DokumenController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\WaliMurid\Akademik\LihatNilaiWaliController;
use App\Http\Controllers\WaliMurid\Akademik\AbsensiController;
use App\Http\Controllers\WaliMurid\Akademik\JadwalKBMController;
use App\Http\Controllers\WaliMurid\Akademik\JadwalUjianController;
use App\Http\Controllers\WaliMurid\Akademik\KalenderAkademikController;
use App\Http\Controllers\WaliMurid\Akademik\MagangController;
use App\Http\Controllers\WaliMurid\Akademik\RaporController;
use App\Http\Controllers\WaliMurid\Kesiswaan\AbsensiEkskulController;
use App\Http\Controllers\WaliMurid\Kesiswaan\AbsensiKehadiranSiswaController;
use App\Http\Controllers\WaliMurid\Kesiswaan\AbsensiPKLController;
use App\Http\Controllers\WaliMurid\Kesiswaan\BeasiswaController;
use App\Http\Controllers\WaliMurid\Kesiswaan\NilaiEkskulController;
use App\Http\Controllers\WaliMurid\Kesiswaan\PrestasiController;
use App\Http\Controllers\WaliMurid\Keuangan\RiwayatBayarController;
use App\Http\Controllers\WaliMurid\Keuangan\TagihanController;
use App\Http\Controllers\WaliMurid\Pelanggaran\RiwayatPelanggaranController;
use App\Http\Controllers\WaliMurid\RaporSisipan\RaporSisipanSTSController;
use App\Http\Controllers\WaliMurid\WelcomeController;

Route::middleware(['token_staff'])->group(function () {

    Route::prefix('wali-murid')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);

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

        /** ==== MODUL AKADEMIK ==== **/
        Route::prefix('akademik')->group(function () {
            Route::get('rapor-sisipan/cetak-rapor/print/{thn_akademik_semester}/{id_kelas}', [CetakRaporController::class, 'printCetakRapor']);

            // MENU Kalender Akademik
            Route::get('kalender-akademik', [KalenderAkademikController::class, 'viewKalenderAkademik']);
            Route::get('kalender-akademik/datatables', [KalenderAkademikController::class, 'datatablesKalenderAkademik']);
            //Menu Lihat Absensi
            Route::prefix('lihat-absensi')->group(function () {
                Route::get('/', [AbsensiController::class, 'viewLihatAbsensi']);
                Route::get('/{id_bulan}/{tahun}', [AbsensiController::class, 'viewLihatAbsensi']);
            });

            // MENU Jadwal KBM
            Route::get('jadwal-kbm', [JadwalKBMController::class, 'viewJadwalKBM']);
            Route::get('jadwal-kbm/datatables', [JadwalKBMController::class, 'datatablesJadwalKBM']);

            // MENU Lihat Nilai KBM
            Route::get('lihat-nilai-kbm', [LihatNilaiWaliController::class, 'index']);

            // MENU Jadwal Ujian
            Route::get('jadwal-ujian', [JadwalUjianController::class, 'viewJadwalUjian']);
            Route::get('jadwal-ujian/datatables-uts', [JadwalUjianController::class, 'datatablesJadwalUTS']);
            Route::get('jadwal-ujian/datatables-uas', [JadwalUjianController::class, 'datatablesJadwalUAS']);

            // MENU Magang
            Route::get('magang', [MagangController::class, 'viewMagang']);
            Route::get('magang/datatables', [MagangController::class, 'datatablesMagang']);

            Route::get('rapor-sisipan', [RaporController::class, 'viewSisipan']);
        });

        Route::prefix('keuangan')->group(function () {
            // MENU Tagihan
            Route::get('tagihan', [TagihanController::class, 'viewTagihan']);
            Route::get('tagihan/datatables', [TagihanController::class, 'datatablesTagihan']);

            Route::post('tagihan/generate', [TagihanController::class, 'actionGenerate']);

            // MENU Riwayat Bayar
            Route::get('riwayat-bayar', [RiwayatBayarController::class, 'viewRiwayatBayar']);
            Route::get('riwayat-bayar/datatables', [RiwayatBayarController::class, 'datatablesRiwayatBayar']);
        });

        Route::prefix('kesiswaan')->group(function () {
            //MENU Prestasi
            Route::get('prestasi', [PrestasiController::class, 'viewPrestasi']);
            Route::get('prestasi/datatables', [PrestasiController::class, 'datatablesPrestasi']);

            //MENU Beasiswa
            Route::get('beasiswa', [BeasiswaController::class, 'viewBeasiswa']);
            Route::get('beasiswa/datatables', [BeasiswaController::class, 'datatablesBeasiswa']);

            // MENU ABSENSI KEHADIRAN
            Route::get('/absensi-kehadiran', [AbsensiKehadiranSiswaController::class, 'viewAbsensiKehadiran']);
            Route::get('/absensi-kehadiran/{start_date}/{end_date}', [AbsensiKehadiranSiswaController::class, 'viewAbsensiKehadiran']);

            Route::prefix('absensi-ekskul')->group(function () {
                Route::get('/', [AbsensiEkskulController::class, 'viewAbsensiEkskul']);
                Route::get('detail/{id_semester}/{id_ekskul}', [AbsensiEkskulController::class, 'viewDetailAbsensiEkskul']);
            });
            Route::prefix('absensi-magang')->group(function () {
                Route::get('/', [AbsensiPKLController::class, 'viewAbsensiMagang']);
                Route::get('/detail/{id_rekanan}/{id_periode}/{date}', [AbsensiPKLController::class, 'viewDetailRekapAbsensiMagang']);
                Route::get('/print/{id_siswa}', [AbsensiPKLController::class, 'printRekapPresensiMagang']);
            });

            Route::prefix('nilai-ekskul')->group(function () {
                Route::get('/', [NilaiEkskulController::class, 'viewNilaiEkskul']);
                Route::get('detail/{id_semester}/{id_ekskul}', [NilaiEkskulController::class, 'viewDetailNilaiEkskul']);
            });
        });

        Route::prefix('pelanggaran')->group(function () {

            Route::get('riwayat-pelanggaran', [RiwayatPelanggaranController::class, 'viewRiwayatPelanggaran']);
            Route::get('riwayat-pelanggaran/datatables-non-kbm', [RiwayatPelanggaranController::class, 'datatablesPelanggaranNonKBM']);
            Route::get('riwayat-pelanggaran/datatables-kbm', [RiwayatPelanggaranController::class, 'datatablesPelanggaranKBM']);
        });

        Route::prefix('kesekretariatan')->group(function () {

            Route::prefix('dokumen')->group(function () {
                Route::get('/', [DokumenController::class, 'viewDokumen']);
                Route::get('detail/{id}', [DokumenController::class, 'viewDetailDokumen']);
                Route::post('datatables', [DokumenController::class, 'datatablesDokumen']);
            });
        });

        Route::prefix('rapor-sisipan')->group(function () {

            Route::prefix('nilai-sts')->group(function () {
                Route::get('/', [RaporSisipanSTSController::class, 'viewRaporSisipanSTS']);
                Route::post('datatables', [RaporSisipanSTSController::class, 'datatablesRaporSisipan']);
                Route::get('/cetak/{id_semester}', [RaporSisipanSTSController::class, 'cetakRaporSisipanSTS']);
            });
        });
    });
});
