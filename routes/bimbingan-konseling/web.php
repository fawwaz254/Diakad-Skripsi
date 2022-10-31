<?php

use App\Http\Controllers\BK\Absensi\HistoriSiswaTerlambatController;
use App\Http\Controllers\BK\WelcomeController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\Guru\GuruPiket\RekapKesehatanController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\BK\PenangananSiswa\JenisTindakanController;
use App\Http\Controllers\BK\PenangananSiswa\JurnalTindakanController;
use App\Http\Controllers\Humas\Absensi\HistoriAbsensiSiswaController;
use App\Http\Controllers\BK\PenangananSiswa\InputPelanggaranController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;
use App\Http\Controllers\Keuangan\LaporanKeuangan\TagihanSiswaController;
use App\Http\Controllers\BK\DataPelanggaran\KategoriPelanggaranController;
use App\Http\Controllers\BK\PenangananSiswa\TindakanPelanggaranController;
use App\Http\Controllers\BK\DataPelanggaran\KesimpulanPelanggaranController;
use App\Http\Controllers\BK\DataPelanggaran\SubkategoriPelanggaranController;
use App\Providers\RouteServiceProvider;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('bimbingan-konseling')->group(function () {
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

        // MENU ABSENSI SISWA
        Route::prefix('absensi')->group(function () {
            Route::prefix('histori-absensi-siswa')->group(function () {
                Route::get('/', [HistoriAbsensiSiswaController::class, 'viewHistoriAbsensiSiswa']);
                // Route::get('get-kelas/{id_jurusan}', [HistoriAbsensiSiswaController::class,'getKelas']);
                Route::post('/', [HistoriAbsensiSiswaController::class, 'actionDetailHistoriAbsensiSiswa']);
                Route::get('/detail/{kelas}/{date}/{status}', [HistoriAbsensiSiswaController::class, 'viewDetailHistoriAbsensiSiswa']);
                // Route::get('/details/{kelas}/{date}', [HistoriAbsensiSiswaController::class,'viewDetailsHistoriAbsensiSiswa']);
                Route::get('export-laravel-mount/{kelas}/{date}', [HistoriAbsensiSiswaController::class, 'export_excel_mount']);
                Route::get('export-laravel-week/{kelas}/{date}', [HistoriAbsensiSiswaController::class, 'export_excel_week']);
                Route::get('export-laravel/{kelas}/{date}', [HistoriAbsensiSiswaController::class, 'export_excel_day']);
                //buat izin / sakit
                Route::get('/{id_pengguna}/{kelas}/{date}/add', [HistoriAbsensiSiswaController::class, 'createHistoriAbsensi']);
                Route::post('/{id_pengguna}/{kelas}/{date}/add', [HistoriAbsensiSiswaController::class, 'storeHistoriAbsensi']);
                Route::get('/{id_presensi_pengguna}/{kelas}/{date}/edit', [HistoriAbsensiSiswaController::class, 'editHistoriAbsensi']);
                Route::post('/{id_presensi_pengguna}/{kelas}/{date}/edit', [HistoriAbsensiSiswaController::class, 'updateHistoriAbsensi']);
                Route::post('/{id_presensi_pengguna}/delete', [HistoriAbsensiSiswaController::class, 'destroyHistoriAbsensi']);
            });

            Route::prefix('catat-siswa-terlambat')->group(function (){
                Route::get('/', [HistoriSiswaTerlambatController::class, 'viewSiswaTerlambat']);
            });
        });

        Route::prefix('monitoring-kesehatan')->group(function () {
            // MENU Rekap Kesehatan Siswa
            Route::get('rekap-kesehatan', [RekapKesehatanController::class, 'viewRekapKesehatan']);
            Route::get('rekap-kesehatan/user/{id}/{date}', [RekapKesehatanController::class, 'viewRekapKesehatanSiswa']);
            Route::get('rekap-kesehatan/detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

            Route::get('rekap-kesehatan/{id}', [RekapKesehatanController::class, 'viewDetailRekapKesehatan']);
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}', [RekapKesehatanController::class, 'viewDetailRekapKesehatan']);
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}/download', [RekapKesehatanController::class, 'downloadDetailRekapKesehatan']);


            Route::post('rekap-kesehatan/action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
            Route::post('rekap-kesehatan/datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
        });

        Route::prefix('laporan')->group(function () {

            Route::prefix('tagihan-siswa')->group(function () {
                Route::get('/', [TagihanSiswaController::class, 'viewTagihanSiswa']);
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

        Route::post('laporan-keuangan/tagihan-siswa/datatables', [TagihanSiswaController::class, 'datatablesTagihanSiswa']);
        Route::get('laporan-keuangan/tagihan-siswa/print/{tahun}/{id_kelas}/{jenis_tagihan}', [TagihanSiswaController::class, 'printTagihanSiswa']);
        Route::get('laporan-keuangan/tagihan-siswa/show-list-tagihan/{tahun}/{id_kelas}', [TagihanSiswaController::class, 'showListTagihan']);

        Route::prefix('data-pelanggaran')->group(function () {
            // MENU Kategori Pelanggaran
            // url: /bimbingan-konseling/data-pelanggaran/kategori-pelanggaran
            Route::get('kategori-pelanggaran', [KategoriPelanggaranController::class, 'viewKategoriPelanggaran']);
            Route::get('kategori-pelanggaran/datatables', [KategoriPelanggaranController::class, 'datatablesKategoriPelanggaran']);
            Route::get('kategori-pelanggaran/add', [KategoriPelanggaranController::class, 'addKategoriPelanggaran']);
            Route::get('kategori-pelanggaran/edit/{id}', [KategoriPelanggaranController::class, 'editKategoriPelanggaran']);

            Route::post('action-kategori-pelanggaran/{mode}/{id}', [KategoriPelanggaranController::class, 'actionKategoriPelanggaran']);

            // MENU Sub-Kategori Pelanggaran
            // url: /bimbingan-konseling/data-pelanggaran/subkategori-pelanggaran
            Route::get('subkategori-pelanggaran', [SubkategoriPelanggaranController::class, 'viewSubkategoriPelanggaran']);
            Route::get('subkategori-pelanggaran/datatables', [SubkategoriPelanggaranController::class, 'datatablesSubkategoriPelanggaran']);
            Route::get('subkategori-pelanggaran/add', [SubkategoriPelanggaranController::class, 'addSubkategoriPelanggaran']);
            Route::get('subkategori-pelanggaran/edit/{id}', [SubkategoriPelanggaranController::class, 'editSubkategoriPelanggaran']);

            Route::post('action-subkategori-pelanggaran/{mode}/{id}', [SubkategoriPelanggaranController::class, 'actionSubkategoriPelanggaran']);

            // MENU Kesimpulan Pelanggaran
            // url: /bimbingan-konseling/data-pelanggaran/kesimpulan-pelanggaran
            Route::get('kesimpulan-pelanggaran', [KesimpulanPelanggaranController::class, 'viewKesimpulanPelanggaran']);
            Route::get('kesimpulan-pelanggaran/datatables', [KesimpulanPelanggaranController::class, 'datatablesKesimpulanPelanggaran']);
            Route::get('kesimpulan-pelanggaran/add', [KesimpulanPelanggaranController::class, 'addKesimpulanPelanggaran']);
            Route::get('kesimpulan-pelanggaran/edit/{id}', [KesimpulanPelanggaranController::class, 'editKesimpulanPelanggaran']);

            Route::post('action-kesimpulan-pelanggaran/{mode}/{id}', [KesimpulanPelanggaranController::class, 'actionKesimpulanPelanggaran']);

            // MENU Data Jenis Tindakan
            Route::get('jenis-tindakan', [JenisTindakanController::class, 'viewJenisTindakan']);
            Route::get('jenis-tindakan/datatables', [JenisTindakanController::class, 'datatablesJenisTindakan']);
            Route::get('jenis-tindakan/add', [JenisTindakanController::class, 'addJenisTindakan']);
            Route::get('jenis-tindakan/edit/{id}', [JenisTindakanController::class, 'editJenisTindakan']);

            Route::post('action-jenis-tindakan/{mode}/{id}', [JenisTindakanController::class, 'actionJenisTindakan']);
        });

        Route::prefix('penanganan-siswa')->group(function () {
            // MENU Data Jurnal Tindakan
            Route::get('jurnal-tindakan', [JurnalTindakanController::class, 'viewJurnalTindakan']);
            Route::get('jurnal-tindakan/print/{id_semester}/{id_kelas}/{id_siswa}', [JurnalTindakanController::class, 'printJurnalTindakan']);

            // MENU Input Pelanggaran Siswa
            Route::get('input-pelanggaran', [InputPelanggaranController::class, 'viewInputPelanggaran']);
            Route::get('input-pelanggaran/datatables', [InputPelanggaranController::class, 'datatablesInputPelanggaran']);
            Route::get('input-pelanggaran/add', [InputPelanggaranController::class, 'addInputPelanggaran']);
            Route::get('input-pelanggaran/edit/{id}', [InputPelanggaranController::class, 'editInputPelanggaran']);

            Route::post('action-input-pelanggaran/{mode}/{id}', [InputPelanggaranController::class, 'actionInputPelanggaran']);

            // MENU Tindakan Pelanggaran
            Route::get('tindakan-pelanggaran', [TindakanPelanggaranController::class, 'viewTindakanPelanggaran']);
            Route::get('tindakan-pelanggaran/datatables-belum-nonkbm', [TindakanPelanggaranController::class, 'datatablesBelumTindakanNonKBM']);
            Route::get('tindakan-pelanggaran/datatables-belum-kbm', [TindakanPelanggaranController::class, 'datatablesBelumTindakanKBM']);
            Route::get('tindakan-pelanggaran/datatables-sudah', [TindakanPelanggaranController::class, 'datatablesSudahTindakan']);
            Route::get('tindakan-pelanggaran/add-nonkbm/{id}', [TindakanPelanggaranController::class, 'addTindakanPelanggaranNonKBM']);
            Route::get('tindakan-pelanggaran/add-kbm/{id}', [TindakanPelanggaranController::class, 'addTindakanPelanggaranKBM']);
            Route::get('tindakan-pelanggaran/edit/{id}', [TindakanPelanggaranController::class, 'editTindakanPelanggaran']);

            Route::post('action-tindakan-pelanggaran/{mode}/{id}', [TindakanPelanggaranController::class, 'actionTindakanPelanggaran']);

            Route::post('action-tindakan-pelanggaran-nonkbm/{id}', [TindakanPelanggaranController::class, 'deleteDatatablesBelumTindakanNonKBM']);
            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', [InputPelanggaranController::class, 'ajaxGetSiswaByKelas']);


            // AJAX GET SUBKATEGORI PELANGGARAN BY KATEGORI
            Route::post('subkategori-bykategori', [InputPelanggaranController::class, 'ajaxGetSubkategoriByKategori']);
        });
    });
});
