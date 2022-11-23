<?php

use App\Http\Controllers\Kesiswaan\Laporan\WaliKelasController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\SaranaPrasarana\DataSarprasBergerak\InventarisController as SarprasBergerakInventarisController;
use App\Http\Controllers\SaranaPrasarana\DataSarprasBukuAlat\BukuAlatController;
use App\Http\Controllers\SaranaPrasarana\DataSarprasBukuAlat\JenisBukuAlatController;
use App\Http\Controllers\SaranaPrasarana\DataSarprasGedung\GedungController;
use App\Http\Controllers\SaranaPrasarana\DataSarprasGedung\JenisGedungController;
use App\Http\Controllers\SaranaPrasarana\DataSarprasRuangan\InventarisController;
use App\Http\Controllers\SaranaPrasarana\DataSarprasRuangan\JenisRuanganController;
use App\Http\Controllers\SaranaPrasarana\DataSarprasRuangan\KondisiRuanganController;
use App\Http\Controllers\SaranaPrasarana\DataSarprasRuangan\PemilikSarprasController;
use App\Http\Controllers\SaranaPrasarana\DataSarprasRuangan\RuanganController;
use App\Http\Controllers\SaranaPrasarana\KomplainSarpras\TanggapiKomplainController;
use App\Http\Controllers\SaranaPrasarana\PerawatanSarpras\InputPerawatanRutinController;
use App\Http\Controllers\SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController;
use App\Http\Controllers\SaranaPrasarana\WelcomeController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('sarana-prasarana')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);
        Route::get('biodata', [\App\Http\Controllers\Administrator\WelcomeController::class, 'viewBiodata']);

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
        Route::prefix('data-sarpras-gedung')->group(function () {
            // MENU Data Jenis Gedung
            Route::get('jenis-gedung', [JenisGedungController::class, 'viewJenisGedung']);
            Route::get('jenis-gedung/datatables', [JenisGedungController::class, 'datatablesJenisGedung']);
            Route::get('jenis-gedung/add', [JenisGedungController::class, 'addJenisGedung']);
            Route::get('jenis-gedung/edit/{id}', [JenisGedungController::class, 'editJenisGedung']);

            Route::post('action-jenis-gedung/{mode}/{id}', [JenisGedungController::class, 'actionJenisGedung']);

            // MENU Data Gedung
            Route::get('gedung', [GedungController::class, 'viewGedung']);
            Route::get('gedung/datatables', [GedungController::class, 'datatablesGedung']);
            Route::get('gedung/add', [GedungController::class, 'addGedung']);
            Route::get('gedung/edit/{id}', [GedungController::class, 'editGedung']);

            Route::post('action-gedung/{mode}/{id}', [GedungController::class, 'actionGedung']);
        });

        Route::prefix('data-sarpras-ruangan')->group(function () {
            // MENU Data Pemilik Sarpras
            Route::get('pemilik-sarpras', [PemilikSarprasController::class, 'viewPemilikSarpras']);
            Route::get('pemilik-sarpras/datatables', [PemilikSarprasController::class, 'datatablesPemilikSarpras']);
            Route::get('pemilik-sarpras/add', [PemilikSarprasController::class, 'addPemilikSarpras']);
            Route::get('pemilik-sarpras/edit/{id}', [PemilikSarprasController::class, 'editPemilikSarpras']);
            Route::get('pemilik-sarpras/import-excel', [PemilikSarprasController::class, 'importExcel']);
            Route::post('pemilik-sarpras/import-excel', [PemilikSarprasController::class, 'importExcelAction']);

            Route::post('action-pemilik-sarpras/{mode}/{id}', [PemilikSarprasController::class, 'actionPemilikSarpras']);

            // MENU Data Jenis Ruangan
            Route::get('jenis-ruangan', [JenisRuanganController::class, 'viewJenisRuangan']);
            Route::get('jenis-ruangan/datatables', [JenisRuanganController::class, 'datatablesJenisRuangan']);
            Route::get('jenis-ruangan/add', [JenisRuanganController::class, 'addJenisRuangan']);
            Route::get('jenis-ruangan/edit/{id}', [JenisRuanganController::class, 'editJenisRuangan']);
            Route::get('jenis-ruangan/import-excel', [JenisRuanganController::class, 'importExcel']);
            Route::post('jenis-ruangan/import-excel', [JenisRuanganController::class, 'importExcelAction']);

            Route::post('action-jenis-ruangan/{mode}/{id}', [JenisRuanganController::class, 'actionJenisRuangan']);

            // MENU Data Ruangan
            Route::get('ruangan', [RuanganController::class, 'viewRuangan']);
            Route::get('ruangan/datatables', [RuanganController::class, 'datatablesRuangan']);
            Route::get('ruangan/add', [RuanganController::class, 'addRuangan']);
            Route::get('ruangan/edit/{id}', [RuanganController::class, 'editRuangan']);
            Route::get('ruangan/import-excel', [RuanganController::class, 'importExcel']);
            Route::post('ruangan/import-excel', [RuanganController::class, 'importExcelAction']);

            Route::post('action-ruangan/{mode}/{id}', [RuanganController::class, 'actionRuangan']);

            // MENU Kondisi Ruangan
            Route::get('kondisi-ruangan', [KondisiRuanganController::class, 'viewKondisiRuangan']);
            Route::get('kondisi-ruangan/datatables', [KondisiRuanganController::class, 'datatablesKondisiRuangan']);
            Route::get('kondisi-ruangan/add', [KondisiRuanganController::class, 'addKondisiRuangan']);
            Route::get('kondisi-ruangan/edit/{id}', [KondisiRuanganController::class, 'editKondisiRuangan']);
            Route::get('kondisi-ruangan/import-excel', [KondisiRuanganController::class, 'importExcel']);
            Route::post('kondisi-ruangan/import-excel', [KondisiRuanganController::class, 'importExcelAction']);

            Route::post('action-kondisi-ruangan/{mode}/{id}', [KondisiRuanganController::class, 'actionKondisiRuangan']);

            // MENU Data Inventaris
            Route::get('inventaris', [InventarisController::class, 'viewInventaris']);
            Route::get('inventaris/datatables', [InventarisController::class, 'datatablesInventaris']);
            Route::get('inventaris/add', [InventarisController::class, 'addInventaris']);
            Route::get('inventaris/edit/{id}', [InventarisController::class, 'editInventaris']);
            Route::get('inventaris/import-excel', [InventarisController::class, 'importExcel']);
            Route::post('inventaris/import-excel', [InventarisController::class, 'importExcelAction']);

            Route::post('action-inventaris/{mode}/{id}', [InventarisController::class, 'actionInventaris']);
        });

        Route::prefix('data-inventaris-bergerak')->group(function () {
            // MENU Data Inventaris
            Route::get('data-inventaris', [SarprasBergerakInventarisController::class, 'viewInventaris']);
            Route::get('inventaris/datatables', [SarprasBergerakInventarisController::class, 'datatablesInventaris']);
            Route::get('data-inventaris/add', [SarprasBergerakInventarisController::class, 'addInventaris']);
            Route::get('inventaris/edit/{id}', [SarprasBergerakInventarisController::class, 'editInventaris']);
            // Route::get('inventaris/import-excel', [SarprasBergerakInventarisController::class,'importExcel']);
            // Route::post('inventaris/import-excel', [SarprasBergerakInventarisController::class,'importExcelAction']);

            Route::post('action-inventaris/{mode}/{id}', [SarprasBergerakInventarisController::class, 'actionInventaris']);
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

        Route::prefix('data-sarpras-buku-alat')->group(function () {
            // MENU Data Jenis Buku/Alat
            Route::get('jenis-buku-alat', [JenisBukuAlatController::class, 'viewJenisBukuAlat']);
            Route::get('jenis-buku-alat/datatables', [JenisBukuAlatController::class, 'datatablesJenisBukuAlat']);
            Route::get('jenis-buku-alat/add', [JenisBukuAlatController::class, 'addJenisBukuAlat']);
            Route::get('jenis-buku-alat/edit/{id}', [JenisBukuAlatController::class, 'editJenisBukuAlat']);
            Route::get('jenis-buku-alat/import-excel', [JenisBukuAlatController::class, 'importExcel']);
            Route::post('jenis-buku-alat/import-excel', [JenisBukuAlatController::class, 'importExcelAction']);

            Route::post('action-jenis-buku-alat/{mode}/{id}', [JenisBukuAlatController::class, 'actionJenisBukuAlat']);

            // MENU Data Buku/Alat
            Route::get('buku-alat', [BukuAlatController::class, 'viewBukuAlat']);
            Route::get('buku-alat/datatables', [BukuAlatController::class, 'datatablesBukuAlat']);
            Route::get('buku-alat/add', [BukuAlatController::class, 'addBukuAlat']);
            Route::get('buku-alat/edit/{id}', [BukuAlatController::class, 'editBukuAlat']);
            Route::get('buku-alat/import-excel', [BukuAlatController::class, 'importExcel']);
            Route::post('buku-alat/import-excel', [BukuAlatController::class, 'importExcelAction']);

            Route::post('action-buku-alat/{mode}/{id}', [BukuAlatController::class, 'actionBukuAlat']);
        });

        Route::prefix('komplain-sarpras')->group(function () {
            // MENU Tanggapi Komplain
            // url: /sarana-prasarana/komplain-sarpras/tanggapi-komplain
            Route::get('tanggapi-komplain', [TanggapiKomplainController::class, 'viewTanggapiKomplain']);
            Route::get('tanggapi-komplain/datatables-belum', [TanggapiKomplainController::class, 'datatablesTanggapiKomplainBelum']);
            Route::get('tanggapi-komplain/datatables-sudah', [TanggapiKomplainController::class, 'datatablesTanggapiKomplainSudah']);
            Route::get('tanggapi-komplain/edit/{id}', [TanggapiKomplainController::class, 'editTanggapiKomplain']);

            Route::post('action-tanggapi-komplain/{mode}/{id}', [TanggapiKomplainController::class, 'actionTanggapiKomplain']);
        });

        Route::prefix('perawatan-sarpras')->group(function () {
            // MENU Input Perawatan Rutin
            // url: /sarana-prasarana/perawatan-sarpras/input-perawatan-rutin
            Route::get('input-perawatan-rutin', [InputPerawatanRutinController::class, 'viewInputPerawatanRutin']);
            Route::get('input-perawatan-rutin/datatables-belum', [InputPerawatanRutinController::class, 'datatablesInputPerawatanRutinBelum']);
            Route::get('input-perawatan-rutin/datatables-sudah', [InputPerawatanRutinController::class, 'datatablesInputPerawatanRutinSudah']);
            Route::get('input-perawatan-rutin/add', [InputPerawatanRutinController::class, 'addInputPerawatanRutin']);
            Route::get('input-perawatan-rutin/edit/{id}', [InputPerawatanRutinController::class, 'editInputPerawatanRutin']);
            // AJAX GET INVENTARIS BY RUANGAN
            Route::post('inventaris-byruangan', [InputPerawatanRutinController::class, 'ajaxGetInventarisByRuangan']);

            Route::post('action-input-perawatan-rutin/{mode}/{id}', [InputPerawatanRutinController::class, 'actionInputPerawatanRutin']);

            // MENU Pengadaan Barang/Sarpras
            // url: /sarana-prasarana/perawatan-sarpras/pengadaan-sarpras
            Route::get('pengadaan-sarpras', [PengadaanSarprasController::class, 'viewPengadaanSarpras']);
            Route::get('pengadaan-sarpras/datatables-tinggi', [PengadaanSarprasController::class, 'datatablesPengadaanSarprasTinggi']);
            Route::get('pengadaan-sarpras/datatables-sedang', [PengadaanSarprasController::class, 'datatablesPengadaanSarprasSedang']);
            Route::get('pengadaan-sarpras/datatables-rendah', [PengadaanSarprasController::class, 'datatablesPengadaanSarprasRendah']);
            Route::get('pengadaan-sarpras/add', [PengadaanSarprasController::class, 'addPengadaanSarpras']);

            // pengadaan (supplier)
            Route::get('pengadaan-sarpras/view-detail-supplier/{id_rpb_sarpras}', [PengadaanSarprasController::class, 'viewDetailSupplier']);
            Route::get('pengadaan-sarpras/datatables/{id_rpb_sarpras}', [PengadaanSarprasController::class, 'datatablesPengadaanSarprasSupplier']);
            Route::get('pengadaan-sarpras/add-supplier/{id_rpb_sarpras}', [PengadaanSarprasController::class, 'addPengadaanSarprasSupplier']);
            Route::get('pengadaan-sarpras/edit-apv-supplier/{id_rpb_sarpras}/{id_rpb_sarpras_supplier}', [PengadaanSarprasController::class, 'editApvPengadaanSarprasSupplier']);

            // AJAX GET INVENTARIS BY RUANGAN
            Route::post('inventaris-byruangan', [PengadaanSarprasController::class, 'ajaxGetInventarisByRuangan']);

            Route::post('action-apv-pengadaan/{mode}/{id}/{id_unit_kerja}', [PengadaanSarprasController::class, 'actionApvPengadaan']);
            Route::post('action-pengadaan-sarpras/{mode}/{id}', [PengadaanSarprasController::class, 'actionPengadaanSarpras']);
        });

        Route::prefix('laporan')->group(function () {

            Route::prefix('wali-kelas')->group(function () {
                Route::get('/', [WaliKelasController::class, 'viewWaliKelas']);
                Route::get('datatables', [WaliKelasController::class, 'datatablesWaliKelas']);
                Route::get('add', [WaliKelasController::class, 'addWaliKelas']);
                Route::get('edit/{id}', [WaliKelasController::class, 'editWaliKelas']);
                Route::get('detail/{id}', [WaliKelasController::class, 'detailWaliKelas']);
                Route::get('detail-ajax/{id}', [WaliKelasController::class, 'detailAjaxWaliKelas']);
                Route::get('detail-datatable/{id}', [WaliKelasController::class, 'detailDataTable']);
                Route::post('action-detail-wali-kelas', [WaliKelasController::class, 'actionDetailWaliKelas']);
                Route::post('action-wali-kelas/{mode}/{id}', [WaliKelasController::class, 'actionWaliKelas']);
            });
        });
    });
});
