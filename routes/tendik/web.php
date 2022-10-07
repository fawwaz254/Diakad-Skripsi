<?php

use App\Http\Controllers\Tendik\WelcomeController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\Guru\Laporan\KerjaHarianController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\Tendik\Biodata\DataPribadiController;
use App\Http\Controllers\Guru\Absensi\HistoriAbsensiController;
use App\Http\Controllers\Guru\Kesekretariatan\DokumenController;
use App\Http\Controllers\Guru\GuruPiket\RekapKesehatanController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Guru\GuruPiket\InputPelanggaranController;
use App\Http\Controllers\Guru\GuruPiket\AbsensiHarianSiswaController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;
use App\Http\Controllers\Guru\GuruPiket\MonitoringKelasKosongController;
use App\Http\Controllers\Guru\GuruPiket\RekapAbsenTanpaJadwalController;
use App\Http\Controllers\Tendik\JurnalHarian\JurnalHarianTendikController;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('tendik')->group(function () {
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

        Route::prefix('biodata')->group(function () {
            Route::get('data-pribadi', [DataPribadiController::class, 'viewDataPribadi']);
            Route::post('action-input-tendik/{mode}/{id}', [DataPribadiController::class, 'actionInputTendik']);
        });

        /** ==== Jurnal Harian ==== **/
        Route::prefix('jurnal-harian')->group(function () {
            Route::prefix('laporan-individu-jurnal-harian')->group(function () {
                Route::get('/', [JurnalHarianTendikController::class, 'viewLaporanJurnalHarian']);
                Route::get('add', [JurnalHarianTendikController::class, 'addLaporanHarianJurnalHarian']);
                Route::post('action-kerja-harian/{mode}/{id}', [JurnalHarianTendikController::class, 'actionLaporanHarianTendik']);
                Route::get('datatables', [JurnalHarianTendikController::class, 'datatablesKerjaHarianTendik']);
                Route::get('edit/{id}', [JurnalHarianTendikController::class, 'editKerjaHarian']);
                Route::get('preview-file/{id}/{no}', [JurnalHarianTendikController::class, 'previewFile']);
                Route::get('download-file/{id}', [JurnalHarianTendikController::class, 'downloadFile']);
            });
            Route::prefix('laporan-kelompok-jurnal-harian')->group(function () {
                Route::get('/', [JurnalHarianTendikController::class, 'viewLaporanKelompokKerjaHarian']);
                Route::get('datatables', [JurnalHarianTendikController::class, 'datatablesKerjaHarianKelompokTendik']);
                Route::get('/detail/{id}', [JurnalHarianTendikController::class, 'detailLaporanKelompokTendik']);
                Route::get('/detail/datatables/{id}', [JurnalHarianTendikController::class, 'datatablesDetailKerjaHarianKelompokTendik']);
                Route::get('preview-file/{id}/{no}', [JurnalHarianTendikController::class, 'previewFile']);
                Route::get('download-file/{id}', [JurnalHarianTendikController::class, 'downloadFile']);
            });
        });

        Route::prefix('guru-piket')->group(function () {
            // MENU Monitoring kelas kosong
            Route::get('monitoring-kelas-kosong', [MonitoringKelasKosongController::class, 'viewMonitoringKelasKosong']);
            Route::get('monitoring-kelas-kosong/datatables', [MonitoringKelasKosongController::class, 'datatablesMonitoringKelasKosong']);

            // MENU Monitoring kelas kosong
            Route::get('rekap-monitoring-kelas-kosong', [MonitoringKelasKosongController::class, 'viewRekapMonitoringKelasKosong']);
            Route::get('rekap-monitoring-kelas-kosong/datatables', [MonitoringKelasKosongController::class, 'datatablesRekapMonitoringKelasKosong']);

            // MENU Absensi Harian Siswa
            Route::get('absensi-harian-siswa', [AbsensiHarianSiswaController::class, 'viewAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/{id_semester}/{id_kelas}', [AbsensiHarianSiswaController::class, 'viewAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}', [AbsensiHarianSiswaController::class, 'viewManageAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}/{id_presensi_harian}', [AbsensiHarianSiswaController::class, 'viewManageAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/detail/{id_semester}/{id_kelas}/{tahun}/{id_bulan}', [AbsensiHarianSiswaController::class, 'viewDetailAbsensiHarianSiswa']);

            Route::post('absensi-harian-siswa/datatables/{id_semester}/{id_kelas}', [AbsensiHarianSiswaController::class, 'datatablesAbsensiHarianSiswa']);
            Route::post('absensi-harian-siswa/datatables-detail/{id_semester}/{id_kelas}/{id_presensi_harian}', [AbsensiHarianSiswaController::class, 'datatablesKelasAbsensiHariSiswa']);
            Route::post('absensi-harian-siswa/action/{mode}', [AbsensiHarianSiswaController::class, 'actionAbsensiHarianSiswa']);
            Route::post('absensi-harian-siswa/action/{mode}/{id}', [AbsensiHarianSiswaController::class, 'actionAbsensiHarianSiswa']);

            // MENU Input Pelanggaran Siswa Non-KBM
            Route::get('input-pelanggaran', [InputPelanggaranController::class, 'viewInputPelanggaran']);
            Route::get('input-pelanggaran/datatables', [InputPelanggaranController::class, 'datatablesInputPelanggaran']);
            Route::get('input-pelanggaran/add', [InputPelanggaranController::class, 'addInputPelanggaran']);
            Route::get('input-pelanggaran/edit/{id}', [InputPelanggaranController::class, 'editInputPelanggaran']);

            Route::post('action-input-pelanggaran/{mode}/{id}', [InputPelanggaranController::class, 'actionInputPelanggaran']);

            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', [InputPelanggaranController::class, 'ajaxGetSiswaByKelas']);

            // MENU Rekap Kesehatan Siswa
            Route::get('rekap-kesehatan', [RekapKesehatanController::class, 'viewRekapKesehatan']);
            Route::get('rekap-kesehatan/user/{id}/{date}', [RekapKesehatanController::class, 'viewRekapKesehatanSiswa']);
            Route::get('rekap-kesehatan/detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

            Route::get('rekap-kesehatan/{id}', [RekapKesehatanController::class, 'viewDetailRekapKesehatan']);
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}', [RekapKesehatanController::class, 'viewDetailRekapKesehatan']);
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}/download', [RekapKesehatanController::class, 'downloadDetailRekapKesehatan']);

            Route::post('rekap-kesehatan/action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
            Route::post('rekap-kesehatan/datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);

            // MENU Rekap Absen Tanpa Jadwal
            Route::get('rekap-absen-tanpa-jadwal', [RekapAbsenTanpaJadwalController::class, 'viewRekapAbsenTanpaJadwal']);
            Route::post('post-get-kbm-by-kelas', [RekapAbsenTanpaJadwalController::class, 'actionGetKBMByKelas']);

            Route::post('post-kbm-rekap-absen-tanpa-jadwal', [RekapAbsenTanpaJadwalController::class, 'actionViewKBMRekapAbsenTanpaJadwal']);
            Route::get('rekap-absen-tanpa-jadwal/view-kbm/{id_kelas_mp}', [RekapAbsenTanpaJadwalController::class, 'viewKBMRekapAbsenTanpaJadwal']);

            Route::get('rekap-absen-tanpa-jadwal/print/{id_kelas_mp}', [RekapAbsenTanpaJadwalController::class, 'printKBMRekapAbsenTanpaJadwal']);
        });

        Route::prefix('kegiatan-harian')->group(function () {

            Route::prefix('mengisi-form-kesehatan')->group(function () {
                // MENU Mengisi form kesehatan
                Route::get('/', [FormKesehatanController::class, 'viewFormKesehatan']);
                Route::get('add', [FormKesehatanController::class, 'viewAddFormKesehatan']);
                Route::get('detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

                Route::post('action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
                Route::post('datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
            });
        });

        Route::prefix('laporan')->group(function () {

            Route::prefix('kerja-harian')->group(function () {
                Route::get('/', [KerjaHarianController::class, 'viewKerjaHarian']);
                Route::get('datatables', [KerjaHarianController::class, 'datatablesKerjaHarian']);
                Route::get('add', [KerjaHarianController::class, 'addKerjaHarian']);
                Route::get('edit/{id}', [KerjaHarianController::class, 'editKerjaHarian']);
                Route::get('preview-file/{id}', [KerjaHarianController::class, 'previewFile']);
                Route::get('print-kerja-harian/{start_date}/{end_date}', [KerjaHarianController::class, 'printKerjaHarian']);
                Route::post('action-kerja-harian/{mode}/{id}', [KerjaHarianController::class, 'actionKerjaHarian']);
            });
        });

        Route::prefix('absensi')->group(function () {

            Route::prefix('histori-absensi')->group(function () {
                Route::get('/', [HistoriAbsensiController::class, 'viewHistoriAbsensi']);
                Route::get('/{start_date}/{end_date}', [HistoriAbsensiController::class, 'viewHistoriAbsensi']);
            });
        });

        Route::prefix('kesekretariatan')->group(function () {

            Route::prefix('upload-dokumen')->group(function () {
                Route::get('/', [DokumenController::class, 'manageInputDokumen']);
                Route::get('edit/{id}', [DokumenController::class, 'manageInputDokumen']);
                Route::get('upload/{id}', [DokumenController::class, 'uploadInputDokumen']);
            });

            // action upload dokumen
            Route::post('action-upload-dokumen/{mode}/{id}', [DokumenController::class, 'actionUploadDokumen']);

            // ajax sub kategori
            Route::post('sub-kategori', [DokumenController::class, 'ajaxGetSubkategori']);

            Route::prefix('dokumen')->group(function () {
                Route::get('/', [DokumenController::class, 'viewDokumen']);
                Route::get('detail/{id}', [DokumenController::class, 'viewDetailDokumen']);
                Route::post('datatables', [DokumenController::class, 'datatablesDokumen']);
            });
        });
    });
});
