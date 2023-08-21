<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\Administrator\WelcomeController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Administrator\Device\FingerprintController;
use App\Http\Controllers\Administrator\PengelolaanAkun\GuruController;
use App\Http\Controllers\Administrator\PengelolaanAkun\SiswaController;
use App\Http\Controllers\Administrator\PengelolaanAkun\TendikController;
use App\Http\Controllers\Administrator\PengelolaanAkun\PencarianController;
use App\Http\Controllers\Administrator\JurnalPimpinan\JurnalPimpinanController;
use App\Http\Controllers\Administrator\ManajemenMenu\SettingDashboardController;
use App\Http\Controllers\Administrator\JurnalPimpinan\JenisKategoriJurnalPimpinanController;
use App\Http\Controllers\administrator\Notification\NotificationController;
use App\Http\Controllers\FeaturemenuController;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('administrator')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);
        Route::view('/whatsapp-notification/scan', 'iframe-whatsapp');

        Route::get('/report-pimpinan', [ReportController::class, 'viewAllDiakad'])->name('report.pimpinan');
        Route::get('/report-wali-kelas', [ReportController::class, 'viewReportWaliKelas'])->name('report.walikelas');
        Route::get('/report-guru', [ReportController::class, 'viewReportGuru'])->name('report.guru');

        Route::prefix('device')->group(function () {
            Route::prefix('fingerprint')->group(function () {
                Route::get('/', [FingerprintController::class, 'indexList']);
                Route::get('/datatables', [FingerprintController::class, 'commonList']);
            });
            Route::prefix('fingerprintRealTime')->group(function () {
                // Route::get('/', [FingerprintController::class, 'indexList']);
                // Route::get('/datatables', [FingerprintController::class, 'commonList']);
            });
        });

        Route::prefix('notification')->group(function () {
            // Route::get('/', [NotificationController::class, 'indexList']);
            // Route::get('/datatables', [NotificationController::class, 'commonList']);
            Route::get('/send', [NotificationController::class, 'send']);
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

        Route::prefix('pengelolaan-akun')->group(function () {
            Route::get('pencarian', [PencarianController::class, 'viewPencarian']);
            Route::post('post-view-pencarian', [PencarianController::class, 'actionViewPencarian']);
            Route::get('pencarian/view-detail/{username_nama_cari}', [PencarianController::class, 'viewDetailPencarian']);
            Route::get('pencarian/datatables/{username_nama_cari}', [PencarianController::class, 'datatablesPencarian']);
            Route::get('pencarian/view-detail-pengguna/{id_pengguna}/{username_nama_cari}', [PencarianController::class, 'viewDetailPenggunaPencarian']);
            Route::get('pencarian/datatables-role/{id_pengguna}', [PencarianController::class, 'datatablesRolePencarian']);
            Route::get('pencarian/add-role-pengguna/{id_pengguna}/{username_nama_cari}', [PencarianController::class, 'addRolePenggunaPencarian']);

            Route::post('action-pencarian/{mode}/{id}', [PencarianController::class, 'actionPencarian']);

            Route::post('reset-some-password', [PencarianController::class, 'resetPasswordCollection']);

            // MENU Tenaga Pendidik
            // url: /administrator/pengelolaan-akun/tendik
            Route::get('tendik', [TendikController::class, 'viewDetailTendik']);
            Route::post('post-view-tendik', [TendikController::class, 'actionViewTendik']);
            Route::get('tendik/view-detail/{id_role}', [TendikController::class, 'viewDetailTendik']);
            Route::get('tendik/datatables/{id_role}', [TendikController::class, 'datatablesTendik']);

            // MENU Guru
            // url: /administrator/pengelolaan-akun/guru
            Route::get('guru', [GuruController::class, 'viewDetailGuru']);
            Route::post('post-view-guru', [GuruController::class, 'actionViewGuru']);
            Route::get('guru/view-detail/{id_role}', [GuruController::class, 'viewDetailGuru']);
            Route::get('guru/datatables/{id_role}', [GuruController::class, 'datatablesGuru']);

            // MENU Siswa
            // url: /administrator/pengelolaan-akun/siswa
            Route::get('siswa', [SiswaController::class, 'viewSiswa']);
            Route::post('post-view-siswa', [SiswaController::class, 'actionViewSiswa']);
            Route::get('siswa/view-detail/{id_kelas}', [SiswaController::class, 'viewDetailSiswa']);
            Route::get('siswa/datatables/{id_kelas}', [SiswaController::class, 'datatablesSiswa']);
        });

        //Jurnal pimpinan
        Route::prefix('jurnal-pimpinan')->group(function () {
            Route::prefix('tambah-jurnal-pimpinan')->group(function () {
                Route::get('/', [JurnalPimpinanController::class, 'viewSettingJurnalPimpinan']);
                Route::get('datatables', [JurnalPimpinanController::class, 'datatablesSettingJurnalPimpinan']);
                Route::get('add', [JurnalPimpinanController::class, 'addSettingJurnalPimpinan']);
                Route::get('edit/{id}', [JurnalPimpinanController::class, 'editSettingJurnalPimpinan']);
                Route::get('datatablesJurnalPimpinan', [JurnalPimpinanController::class, 'datatablesAddJurnalPimpinan']);

                Route::post('action-setting-jurnal-pimpinan/{mode}/{id}', [JurnalPimpinanController::class, 'actionSettingJurnalPimpinan']);
            });
            Route::prefix('jenis-jurnal-pimpinan')->group(function () {
                Route::get('/', [JenisKategoriJurnalPimpinanController::class, 'viewDataJenis']);
                Route::get('/datatables', [JenisKategoriJurnalPimpinanController::class, 'datatablesjenis']);
                // Route::get('/add', [JenisKategoriJurnalPimpinanController::class, 'addDataJenis']);
                Route::post('action-data-kategori/{mode}/{id}', [JenisKategoriJurnalPimpinanController::class, 'actionDataJenis']);
                Route::get('/import-excel', [JenisKategoriJurnalPimpinanController::class, 'importExcel']);
                Route::post('/import-excel', [JenisKategoriJurnalPimpinanController::class, 'importExcelAction']);
            });

            Route::group(array('prefix' => 'laporan-jurnal-pimpinan'), function () {
                Route::get('/', [JurnalPimpinanController::class, 'viewLaporanAllJurnalPimpinan']);
                Route::get('/datatables', [JurnalPimpinanController::class, 'datatablesLaporanJurnalPimpinan']);
                Route::get('preview-file/{id}', [JurnalPimpinanController::class, 'previewFile']);
                Route::get('download-file/{id}', [JurnalPimpinanController::class, 'downloadFile']);
            });
        });

        Route::prefix('manajemen-menu')->group(function () {
            // MENU Setting Dashboard
            // url: /administrator/manajemen-menu/setting-dashboard
            Route::get('setting-dashboard', [SettingDashboardController::class, 'viewSettingDashboard']);
            Route::post('post-view-setting-dashboard', [SettingDashboardController::class, 'actionViewSettingDashboard']);
            Route::get('setting-dashboard/view-detail/{id_role}', [SettingDashboardController::class, 'viewDetailSettingDashboard']);
            Route::post('setting-dashboard', [SettingDashboardController::class, 'actionSettingDashboard']);
            Route::get('setting-feature-guru', [FeaturemenuController::class, 'index']);
            Route::post('action-setting-feature-guru', [FeaturemenuController::class, 'save']);
        });
    });
});
