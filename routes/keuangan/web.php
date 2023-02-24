<?php

use App\Http\Controllers\Keuangan\DataKeuangan\BiayaInternalController;
use App\Http\Controllers\Keuangan\DataKeuangan\BiayaSekolahController;
use App\Http\Controllers\Keuangan\DataKeuangan\DetailBiayaController;
use App\Http\Controllers\Keuangan\DataKeuangan\DetailBiayaInternalController;
use App\Http\Controllers\Keuangan\DataKeuangan\KelompokBiayaController;
use App\Http\Controllers\Keuangan\DataKeuangan\NamaBiayaController;
use App\Http\Controllers\Keuangan\LaporanKeuangan\CetakLaporanController;
use App\Http\Controllers\Keuangan\LaporanKeuangan\PembayaranSiswaBulananController;
use App\Http\Controllers\Keuangan\LaporanKeuangan\PembayaranSiswaController as LaporanKeuanganPembayaranSiswaController;
use App\Http\Controllers\Keuangan\LaporanKeuangan\PembayaranSiswaTahunanController;
use App\Http\Controllers\Keuangan\LaporanKeuangan\TagihanSiswaController as LaporanKeuanganTagihanSiswaController;
use App\Http\Controllers\Keuangan\PemasukanSekolah\InputPemasukanController;
use App\Http\Controllers\Keuangan\PemasukanSekolah\KategoriPemasukanController;
use App\Http\Controllers\Keuangan\PemasukanSekolah\SubkategoriPemasukanController;
use App\Http\Controllers\Keuangan\PengeluaranSekolah\InputPengeluaranController;
use App\Http\Controllers\Keuangan\PengeluaranSekolah\KategoriPengeluaranController;
use App\Http\Controllers\Keuangan\PengeluaranSekolah\SubkategoriPengeluaranController;
use App\Http\Controllers\Keuangan\Rapb\InputRapbController;
use App\Http\Controllers\Keuangan\Rapb\KategoriPenerimaanController;
use App\Http\Controllers\Keuangan\Rapb\KategoriPengeluaranController as RapbKategoriPengeluaranController;
use App\Http\Controllers\Keuangan\Rapb\RealisasiRapbController;
use App\Http\Controllers\Keuangan\SIM\PembayaranOnlineController;
use App\Http\Controllers\Keuangan\SIM\PengeluaranController;
use App\Http\Controllers\Keuangan\SIM\SppController;
use App\Http\Controllers\Keuangan\Utility\BiayaSiswaController;
use App\Http\Controllers\Keuangan\Utility\InputTagihanSiswaController;
use App\Http\Controllers\Keuangan\Utility\PembayaranByKelasController;
use App\Http\Controllers\Keuangan\Utility\PembayaranSiswaController;
use App\Http\Controllers\Keuangan\Utility\TagihanSiswaController;
use App\Http\Controllers\Keuangan\WelcomeController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('keuangan')->group(function () {
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

        Route::prefix('data-keuangan')->group(function () {
            // MENU Data Nama Biaya
            // url: /keuangan/data-keuangan/nama-biaya
            Route::get('nama-biaya', [NamaBiayaController::class, 'viewNamaBiaya']);
            Route::get('nama-biaya/datatables', [NamaBiayaController::class, 'datatablesNamaBiaya']);
            Route::get('nama-biaya/add', [NamaBiayaController::class, 'addNamaBiaya']);
            Route::get('nama-biaya/edit/{id}', [NamaBiayaController::class, 'editNamaBiaya']);

            Route::post('action-nama-biaya/{mode}/{id}', [NamaBiayaController::class, 'actionNamaBiaya']);
            // url: /keuangan/data-keuangan/biaya-internal
            Route::get('biaya-internal', [BiayaInternalController::class, 'viewBiayaInternal']);
            Route::get('biaya-internal/datatables', [BiayaInternalController::class, 'datatablesBiayaInternal']);
            Route::get('biaya-internal/add', [BiayaInternalController::class, 'addBiayaInternal']);
            Route::get('biaya-internal/edit/{id}', [BiayaInternalController::class, 'editBiayaInternal']);

            Route::post('action-biaya-internal/{mode}/{id}', [BiayaInternalController::class, 'actionBiayaInternal']);

            // MENU Data Detail Biaya Internal
            // url: /keuangan/data-keuangan/detail-biaya-internal
            Route::get('detail-biaya-internal', [DetailBiayaInternalController::class, 'viewDetailBiayaInternal']);
            Route::get('detail-biaya-internal/datatables', [DetailBiayaInternalController::class, 'datatablesDetailBiayaInternal']);
            Route::get('detail-biaya-internal/add', [DetailBiayaInternalController::class, 'addDetailBiayaInternal']);
            Route::get('detail-biaya-internal/edit/{id}', [DetailBiayaInternalController::class, 'editDetailBiayaInternal']);

            Route::post('action-detail-biaya-internal/{mode}/{id}', [DetailBiayaInternalController::class, 'actionDetailBiayaInternal']);

            // MENU Data Kelompok Biaya
            // url: /keuangan/data-keuangan/kelompok-biaya
            Route::get('kelompok-biaya', [KelompokBiayaController::class, 'viewKelompokBiaya']);
            Route::get('kelompok-biaya/datatables', [KelompokBiayaController::class, 'datatablesKelompokBiaya']);
            Route::get('kelompok-biaya/add', [KelompokBiayaController::class, 'addKelompokBiaya']);
            Route::get('kelompok-biaya/edit/{id}', [KelompokBiayaController::class, 'editKelompokBiaya']);

            Route::post('action-kelompok-biaya/{mode}/{id}', [KelompokBiayaController::class, 'actionKelompokBiaya']);

            Route::prefix('biaya-sekolah')->group(function () {

                Route::get('', [BiayaSekolahController::class, 'viewBiayaSekolah']);
                Route::get('datatables', [BiayaSekolahController::class, 'datatablesBiayaSekolah']);
                Route::get('add', [BiayaSekolahController::class, 'addBiayaSekolah']);
                Route::get('edit/{id}', [BiayaSekolahController::class, 'editBiayaSekolah']);
                Route::get('copy', [BiayaSekolahController::class, 'copyBiayaSekolah']);
                Route::prefix('detail-biaya')->group(function () {

                    Route::get('{id}', [DetailBiayaController::class, 'viewDetailBiaya2']);
                    Route::post('datatables/{id}', [DetailBiayaController::class, 'datatablesDetailBiaya2']);
                    Route::get('add/{id}', [DetailBiayaController::class, 'addDetailBiaya2']);
                    Route::get('edit/{id}', [DetailBiayaController::class, 'editDetailBiaya2']);
                    Route::post('action-detail-biaya/{mode}/{id}', [DetailBiayaController::class, 'actionDetailBiaya2']);
                    Route::prefix('detail-biaya-internal')->group(function () {

                        Route::get('{id}', [DetailBiayaInternalController::class, 'viewDetailBiayaInternal2']);
                        Route::post('datatables/{id}', [DetailBiayaInternalController::class, 'datatablesDetailBiayaInternal2']);
                        Route::get('add/{id}', [DetailBiayaInternalController::class, 'addDetailBiayaInternal2']);
                        Route::get('edit/{id}', [DetailBiayaInternalController::class, 'editDetailBiayaInternal2']);
                        Route::post('action-detail-biaya-internal/{id_detail_biaya}/{mode}/{id}', [DetailBiayaInternalController::class, 'actionDetailBiayaInternal2']);
                    });
                });
            });

            Route::post('action-biaya-sekolah/{mode}/{id}', [BiayaSekolahController::class, 'actionBiayaSekolah']);

            // MENU Data Detail Biaya
            // url: /keuangan/data-keuangan/detail-biaya
            Route::get('detail-biaya', [DetailBiayaController::class, 'viewDetailBiaya']);
            Route::get('detail-biaya/datatables', [DetailBiayaController::class, 'datatablesDetailBiaya']);
            Route::get('detail-biaya/add', [DetailBiayaController::class, 'addDetailBiaya']);
            Route::get('detail-biaya/edit/{id}', [DetailBiayaController::class, 'editDetailBiaya']);

            Route::post('action-detail-biaya/{mode}/{id}', [DetailBiayaController::class, 'actionDetailBiaya']);

            // AJAX GET BULAN BY JENIS_BIAYA
            Route::post('bulan-byjenisbiaya', [DetailBiayaController::class, 'ajaxGetBulanByJenisBiaya']);
        });

        Route::prefix('utility')->group(function () {
            // MENU Setting Biaya Siswa
            // url: /keuangan/utility/biaya-siswa
            Route::get('biaya-siswa', [BiayaSiswaController::class, 'viewBiayaSiswa']);
            Route::get('biaya-siswa-by-kelas', [BiayaSiswaController::class, 'viewBiayaSiswaByKelas']);
            Route::get('biaya-siswa/datatables-belum', [BiayaSiswaController::class, 'datatablesBiayaSiswaBelum']);
            Route::get('biaya-siswa/datatables-sudah', [BiayaSiswaController::class, 'datatablesBiayaSiswaSudah']);
            Route::get('biaya-siswa/set/{id}', [BiayaSiswaController::class, 'setBiayaSiswa']);
            Route::get('biaya-siswa/edit/{id}', [BiayaSiswaController::class, 'editBiayaSiswa']);

            Route::post('action-biaya-siswa/{mode}/{id}', [BiayaSiswaController::class, 'actionBiayaSiswa']);
            Route::post('action-batch-biaya-siswa/{mode}', [BiayaSiswaController::class, 'actionBatchBiayaSiswa']);

            // MENU Generate Tagihan Siswa
            // url: /keuangan/utility/tagihan-siswa
            Route::get('tagihan-siswa', [TagihanSiswaController::class, 'viewTagihanSiswa']);
            Route::post('post-view-tagihan-siswa', [TagihanSiswaController::class, 'actionViewTagihanSiswa']);
            Route::get('tagihan-siswa/view-detail-tagihan-siswa/{thn_masuk_siswa}/{id_semester}/{id_kelompok_biaya}/{id_jalur}/{is_insert_replace}', [TagihanSiswaController::class, 'viewDetailTagihanSiswa']);
            Route::get('tagihan-siswa/datatables/{thn_masuk_siswa}/{id_semester}/{id_kelompok_biaya}/{id_jalur}/{is_insert_replace}', [TagihanSiswaController::class, 'datatablesTagihanSiswa']);

            Route::post('action-tagihan-siswa/{mode}', [TagihanSiswaController::class, 'actionTagihanSiswa']);

            //MENU Pembayaran Siswa
            // url: /keuangan/utility/pembayaran-siswa
            Route::get('pembayaran-siswa', [PembayaranSiswaController::class, 'viewPembayaranSiswa']);
            Route::post('post-view-pembayaran-siswa', [PembayaranSiswaController::class, 'actionViewPembayaranSiswa']);
            Route::get('pembayaran-siswa/view-detail/{nis_nama_siswa}', [PembayaranSiswaController::class, 'viewDetailPembayaranSiswa']);
            Route::get('pembayaran-siswa/datatables/{nis_nama_siswa}', [PembayaranSiswaController::class, 'datatablesPembayaranSiswa']);
            Route::get('pembayaran-siswa/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', [PembayaranSiswaController::class, 'viewDetailSiswaPembayaranSiswa']);
            Route::get('pembayaran-siswa/datatables-tagihan/{id_pengguna}/{nis_nama_siswa}', [PembayaranSiswaController::class, 'datatablesTagihanPembayaranSiswa']);
            Route::get('pembayaran-siswa/datatables-riwayat-bayar/{id_pengguna}', [PembayaranSiswaController::class, 'datatablesRiwayatBayarSiswa']);
            Route::get('pembayaran-siswa/view-detail-tagihan-siswa/{id_tagihan}/{nis_nama_siswa_asli}', [PembayaranSiswaController::class, 'viewDetailTagihanPembayaranSiswa']);
            Route::get('pembayaran-siswa/view-diskon-tagihan-siswa/{id_tagihan}/{nis_nama_siswa_asli}', [PembayaranSiswaController::class, 'viewDiskonTagihanPembayaranSiswa']);

            Route::get('pembayaran-siswa/print-pembayaran/{id_pengguna}/{tgl_pembayaran}', [PembayaranSiswaController::class, 'printPembayaranSiswa']);
            Route::get('pembayaran-siswa/print-belum-terbayar/{id_pengguna}', [PembayaranSiswaController::class, 'printBelumTerbayarPembayaranSiswa']);

            Route::post('action-tagihan-siswa/delete/{id}', [PembayaranSiswaController::class, 'actionDeleteTagihanSiswa']);
            Route::post('action-pembayaran-siswa/{mode}/{id}', [PembayaranSiswaController::class, 'actionPembayaranSiswa']);
            Route::post('action-pembayaran-siswa-massal', [PembayaranSiswaController::class, 'actionPembayaranSiswaMassal']);

            //MENU Pembayaran By Kelas
            // url: /keuangan/utility/pembayaran-by-kelas
            Route::get('pembayaran-by-kelas', [PembayaranByKelasController::class, 'viewPembayaranByKelas']);
            Route::post('post-view-pembayaran-by-kelas', [PembayaranByKelasController::class, 'actionViewPembayaranByKelas']);
            Route::get('pembayaran-by-kelas/view-detail/{id_semester}/{id_kelas}', [PembayaranByKelasController::class, 'viewDetailPembayaranByKelas']);
            Route::get('pembayaran-by-kelas/print/{id_semester}/{id_kelas}', [PembayaranByKelasController::class, 'printPembayaranByKelas']);

            Route::prefix('input-tagihan-siswa')->group(function () {
                Route::get('/', [InputTagihanSiswaController::class, 'viewInputTagihanSiswa']);
                Route::get('filter-siswa/{id}', [InputTagihanSiswaController::class, 'filterSiswa']);
                Route::post('add-tagihan', [InputTagihanSiswaController::class, 'addTagihan']);
            });
        });

        Route::prefix('rapb')->group(function () {
            // MENU Kategori Penerimaan
            // url: /keuangan/rapb/kategori-penerimaan
            Route::get('kategori-penerimaan', [KategoriPenerimaanController::class, 'viewKategoriPenerimaan']);
            Route::get('kategori-penerimaan/datatables', [KategoriPenerimaanController::class, 'datatablesKategoriPenerimaan']);
            Route::get('kategori-penerimaan/add', [KategoriPenerimaanController::class, 'addKategoriPenerimaan']);
            Route::get('kategori-penerimaan/edit/{id}', [KategoriPenerimaanController::class, 'editKategoriPenerimaan']);

            Route::get('kategori-penerimaan/sub/{id_kategori}', [KategoriPenerimaanController::class, 'viewSubkategoriPenerimaan']);
            Route::get('kategori-penerimaan/sub/datatables/{id_kategori}', [KategoriPenerimaanController::class, 'datatablesSubkategoriPenerimaan']);
            Route::get('kategori-penerimaan/sub/add/{id_kategori}', [KategoriPenerimaanController::class, 'addSubkategoriPenerimaan']);
            Route::get('kategori-penerimaan/sub/edit/{id_kategori}/{id_subkategori}', [KategoriPenerimaanController::class, 'editSubkategoriPenerimaan']);

            Route::get('kategori-penerimaan/sub/ket/{id_kategori}/{id_subkategori}', [KategoriPenerimaanController::class, 'viewKetSubkategoriPenerimaan']);
            Route::get('kategori-penerimaan/sub/ket/datatables/{id_kategori}/{id_subkategori}', [KategoriPenerimaanController::class, 'datatablesKetSubkategoriPenerimaan']);
            Route::get('kategori-penerimaan/sub/ket/add/{id_kategori}/{id_subkategori}', [KategoriPenerimaanController::class, 'addKetSubkategoriPenerimaan']);
            Route::get('kategori-penerimaan/sub/ket/edit/{id_kategori}/{id_subkategori}/{id_ket_subkategori}', [KategoriPenerimaanController::class, 'editKetSubkategoriPenerimaan']);

            Route::post('action-kategori-penerimaan/{mode}/{id}', [KategoriPenerimaanController::class, 'actionKategoriPenerimaan']);

            // MENU Kategori Pengeluaran
            // url: /keuangan/rapb/kategori-pengeluaran
            Route::get('kategori-pengeluaran', [RapbKategoriPengeluaranController::class, 'viewKategoriPengeluaran']);
            Route::get('kategori-pengeluaran/datatables', [RapbKategoriPengeluaranController::class, 'datatablesKategoriPengeluaran']);
            Route::get('kategori-pengeluaran/add', [RapbKategoriPengeluaranController::class, 'addKategoriPengeluaran']);
            Route::get('kategori-pengeluaran/edit/{id}', [RapbKategoriPengeluaranController::class, 'editKategoriPengeluaran']);

            Route::get('kategori-pengeluaran/sub/{id_kategori}', [RapbKategoriPengeluaranController::class, 'viewSubkategoriPengeluaran']);
            Route::get('kategori-pengeluaran/sub/datatables/{id_kategori}', [RapbKategoriPengeluaranController::class, 'datatablesSubkategoriPengeluaran']);
            Route::get('kategori-pengeluaran/sub/add/{id_kategori}', [RapbKategoriPengeluaranController::class, 'addSubkategoriPengeluaran']);
            Route::get('kategori-pengeluaran/sub/edit/{id_kategori}/{id_subkategori}', [RapbKategoriPengeluaranController::class, 'editSubkategoriPengeluaran']);

            Route::get('kategori-pengeluaran/sub/ket/{id_kategori}/{id_subkategori}', [RapbKategoriPengeluaranController::class, 'viewKetSubkategoriPengeluaran']);
            Route::get('kategori-pengeluaran/sub/ket/datatables/{id_kategori}/{id_subkategori}', [RapbKategoriPengeluaranController::class, 'datatablesKetSubkategoriPengeluaran']);
            Route::get('kategori-pengeluaran/sub/ket/add/{id_kategori}/{id_subkategori}', [RapbKategoriPengeluaranController::class, 'addKetSubkategoriPengeluaran']);
            Route::get('kategori-pengeluaran/sub/ket/edit/{id_kategori}/{id_subkategori}/{id_ket_subkategori}', [RapbKategoriPengeluaranController::class, 'editKetSubkategoriPengeluaran']);

            Route::post('action-kategori-pengeluaran/{mode}/{id}', [RapbKategoriPengeluaranController::class, 'actionKategoriPengeluaran']);

            // MENU Input RAPB
            // url: /keuangan/rapb/input-rapb
            Route::get('input-rapb', [InputRapbController::class, 'viewInputRapb']);
            Route::post('post-view-input-rapb', [InputRapbController::class, 'actionViewInputRapb']);
            Route::get('input-rapb/view-detail-input-rapb/{semester_mulai}/{semester_selesai}', [InputRapbController::class, 'viewDetailInputRapb']);
            Route::get('input-rapb/datatables/{semester_mulai}/{semester_selesai}', [InputRapbController::class, 'datatablesInputRapb']);
            Route::get('input-rapb/add/{semester_mulai}/{semester_selesai}', [InputRapbController::class, 'addInputRapb']);
            Route::get('input-rapb/edit/{semester_mulai}/{semester_selesai}/{id}', [InputRapbController::class, 'editInputRapb']);

            Route::post('action-input-rapb/{mode}/{id}', [InputRapbController::class, 'actionInputRapb']);
            Route::post('action-apv-rapb/{mode}/{id}/{id_unit_kerja}', [InputRapbController::class, 'actionApvRapb']);
            // AJAX GET SUBKATEGORI RAPB BY KATEGORI
            Route::post('kategori-byjenis', [InputRapbController::class, 'ajaxGetKategoriByJenis']);
            Route::post('subkategori-bykategori', [InputRapbController::class, 'ajaxGetSubkategoriByKategori']);

            // MENU Realisasi RAPB
            // url: /keuangan/rapb/realisasi-rapb
            Route::get('realisasi-rapb', [RealisasiRapbController::class, 'viewRealisasi']);
            Route::post('post-view-rapb', [RealisasiRapbController::class, 'actionViewRapb']);
            Route::get('realisasi-rapb/view-detail-rapb/{semester_mulai}/{semester_selesai}', [RealisasiRapbController::class, 'viewDetailRapb']);
            Route::get('realisasi-rapb/datatables/rapb-tinggi/{semester_mulai}/{semester_selesai}', [RealisasiRapbController::class, 'datatablesRapbTinggi']);
            Route::get('realisasi-rapb/datatables/rapb-sedang/{semester_mulai}/{semester_selesai}', [RealisasiRapbController::class, 'datatablesRapbSedang']);
            Route::get('realisasi-rapb/datatables/rapb-rendah/{semester_mulai}/{semester_selesai}', [RealisasiRapbController::class, 'datatablesRapbRendah']);

            Route::get('realisasi-rapb/view-detail-realisasi/{semester_mulai}/{semester_selesai}/{id_rapb}', [RealisasiRapbController::class, 'viewDetailRealisasi']);
            Route::get('realisasi-rapb/datatables/{id_rapb}', [RealisasiRapbController::class, 'datatablesRealisasi']);
            Route::get('realisasi-rapb/add/{semester_mulai}/{semester_selesai}/{id_rapb}', [RealisasiRapbController::class, 'addRealisasi']);
            // tabel reliasasi_pembayaran
            Route::get('realisasi-rapb/view-detail-realisasi-termin/{semester_mulai}/{semester_selesai}/{id_rapb}/{id_realisasi}', [RealisasiRapbController::class, 'viewDetailRealisasiTermin']);
            Route::get('realisasi-rapb/datatables-termin/{id_realisasi}', [RealisasiRapbController::class, 'datatablesRealisasiTermin']);
            Route::get('realisasi-rapb/add-realisasi-termin/{semester_mulai}/{semester_selesai}/{id_rapb}/{id_realisasi}', [RealisasiRapbController::class, 'addRealisasiTermin']);

            // tabel rpb_sarpras
            Route::get('realisasi-rapb/view-detail-realisasi-sarpras/{semester_mulai}/{semester_selesai}/{id_rapb}', [RealisasiRapbController::class, 'viewDetailRealisasiSarpras']);
            Route::get('realisasi-rapb/datatables-sarpras', [RealisasiRapbController::class, 'datatablesRealisasiSarpras']);
            Route::get('realisasi-rapb/add-realisasi-sarpras/{semester_mulai}/{semester_selesai}/{id_rapb}/{id_rpb_sarpras}', [RealisasiRapbController::class, 'addRealisasiSarpras']);

            Route::post('action-apv-realisasi/{mode}/{id}', [RealisasiRapbController::class, 'actionApvRealisasi']);
            Route::post('action-realisasi-rapb/{mode}/{id}', [RealisasiRapbController::class, 'actionRealisasi']);

            // belum
            /*Route::get('realisasi-rapb/edit/{semester_mulai}/{semester_selesai}/{id_rapb}/{id}', [RealisasiRapbController::class, 'editRealisasi']);*/
        });

        Route::prefix('pemasukan-sekolah')->group(function () {
            // MENU Kategori Pemasukan
            // url: /keuangan/pemasukan-sekolah/kategori-pemasukan
            Route::get('kategori-pemasukan', [KategoriPemasukanController::class, 'viewKategoriPemasukan']);
            Route::get('kategori-pemasukan/datatables', [KategoriPemasukanController::class, 'datatablesKategoriPemasukan']);
            Route::get('kategori-pemasukan/add', [KategoriPemasukanController::class, 'addKategoriPemasukan']);
            Route::get('kategori-pemasukan/edit/{id}', [KategoriPemasukanController::class, 'editKategoriPemasukan']);

            Route::post('action-kategori-pemasukan/{mode}/{id}', [KategoriPemasukanController::class, 'actionKategoriPemasukan']);

            // MENU Sub-Kategori Pemasukan
            // url: /keuangan/pemasukan-sekolah/subkategori-pemasukan
            Route::get('subkategori-pemasukan', [SubkategoriPemasukanController::class, 'viewSubkategoriPemasukan']);
            Route::get('subkategori-pemasukan/datatables', [SubkategoriPemasukanController::class, 'datatablesSubkategoriPemasukan']);
            Route::get('subkategori-pemasukan/add', [SubkategoriPemasukanController::class, 'addSubkategoriPemasukan']);
            Route::get('subkategori-pemasukan/edit/{id}', [SubkategoriPemasukanController::class, 'editSubkategoriPemasukan']);

            Route::post('action-subkategori-pemasukan/{mode}/{id}', [SubkategoriPemasukanController::class, 'actionSubkategoriPemasukan']);

            // MENU Input Pemasukan
            // url: /keuangan/pemasukan-sekolah/input-pemasukan
            Route::get('input-pemasukan', [InputPemasukanController::class, 'viewInputPemasukan']);
            Route::get('input-pemasukan/datatables', [InputPemasukanController::class, 'datatablesInputPemasukan']);
            Route::get('input-pemasukan/add', [InputPemasukanController::class, 'addInputPemasukan']);
            Route::get('input-pemasukan/edit/{id}', [InputPemasukanController::class, 'editInputPemasukan']);

            Route::post('action-input-pemasukan/{mode}/{id}', [InputPemasukanController::class, 'actionInputPemasukan']);
        });

        Route::prefix('pengeluaran-sekolah')->group(function () {
            // MENU Kategori Pengeluaran
            // url: /keuangan/pengeluaran-sekolah/kategori-pengeluaran
            Route::get('kategori-pengeluaran', [KategoriPengeluaranController::class, 'viewKategoriPengeluaran']);
            Route::get('kategori-pengeluaran/datatables', [KategoriPengeluaranController::class, 'datatablesKategoriPengeluaran']);
            Route::get('kategori-pengeluaran/add', [KategoriPengeluaranController::class, 'addKategoriPengeluaran']);
            Route::get('kategori-pengeluaran/edit/{id}', [KategoriPengeluaranController::class, 'editKategoriPengeluaran']);

            Route::post('action-kategori-pengeluaran/{mode}/{id}', [KategoriPengeluaranController::class, 'actionKategoriPengeluaran']);

            // MENU Sub-Kategori Pengeluaran
            // url: /keuangan/pengeluaran-sekolah/subkategori-pengeluaran
            Route::get('subkategori-pengeluaran', [SubkategoriPengeluaranController::class, 'viewSubkategoriPengeluaran']);
            Route::get('subkategori-pengeluaran/datatables', [SubkategoriPengeluaranController::class, 'datatablesSubkategoriPengeluaran']);
            Route::get('subkategori-pengeluaran/add', [SubkategoriPengeluaranController::class, 'addSubkategoriPengeluaran']);
            Route::get('subkategori-pengeluaran/edit/{id}', [SubkategoriPengeluaranController::class, 'editSubkategoriPengeluaran']);

            Route::post('action-subkategori-pengeluaran/{mode}/{id}', [SubkategoriPengeluaranController::class, 'actionSubkategoriPengeluaran']);
            // MENU Input Pengeluaran
            // url: /keuangan/pengeluaran-sekolah/input-pengeluaran
            Route::get('input-pengeluaran', [InputPengeluaranController::class, 'viewInputPengeluaran']);
            Route::get('input-pengeluaran/datatables', [InputPengeluaranController::class, 'datatablesInputPengeluaran']);
            Route::get('input-pengeluaran/add', [InputPengeluaranController::class, 'addInputPengeluaran']);
            Route::get('input-pengeluaran/edit/{id}', [InputPengeluaranController::class, 'editInputPengeluaran']);

            Route::post('action-input-pengeluaran/{mode}/{id}', [InputPengeluaranController::class, 'actionInputPengeluaran']);
        });

        /** ==== MODUL SIM ==== **/
        // url: /keuangan/sim
        Route::prefix('sim')->group(function () {

            Route::prefix('spp')->group(function () {
                Route::get('/', [SppController::class, 'viewMenuSpp']);

                Route::get('input', [SppController::class, 'viewMenuInput']);
                Route::post('input/save', [SppController::class, 'actionSaveInputPenerimaan']);

                Route::get('cari', [SppController::class, 'viewMenuCari']);
                Route::post('cari/datatables', [SppController::class, 'datatablesMenuCari']);

                Route::get('pembayaran', [SppController::class, 'viewMenuPembayaran']);
                Route::get('pembayaran/{tahun_akademik_semester}/{id_kelas}/{waktu}', [SppController::class, 'viewMenuPembayaran']);
                Route::get('print-pembayaran/{id}', [SppController::class, 'printPembayaran']);

                Route::get('pemasukan', [SppController::class, 'viewMenuPemasukan']);
                Route::get('pemasukan/{tahun_akademik_semester}/{id_bulan}', [SppController::class, 'viewMenuPemasukan']);

                Route::get('pemasukan/{tahun_akademik_semester}/{id_bulan}/report', [SppController::class, 'indexDownloadLapBulanan']);
                Route::get('pemasukan/{tahun_akademik_semester}/{id_bulan}/refresh', [SppController::class, 'actionRefreshLapBulanan']);

                Route::get('detail-penerimaan', [SppController::class, 'viewMenuDetailPenerimaan']);
                Route::post('detail-penerimaan/datatables', [SppController::class, 'datatablesMenuDetailPenerimaan']);
                Route::get('detail-penerimaan/edit/{id}', [SppController::class, 'viewMenuInput']);
                Route::post('detail-penerimaan/delete/{id}', [SppController::class, 'actionDeletePenerimaan']);

                Route::get('penerimaan', [SppController::class, 'viewMenuPenerimaan']);
                Route::get('tunggakan', [SppController::class, 'viewMenuTunggakan']);
                Route::get('tunggakan/{tahun_akademik}', [SppController::class, 'viewMenuTunggakan']);
                Route::post('tunggakan/save', [SppController::class, 'actionSaveInputTunggakan']);

                Route::get('setting', [SppController::class, 'viewMenuSetting']);
                Route::post('setting/datatables', [SppController::class, 'datatablesMenuSetting']);

                Route::get('setting-non-spp', [SppController::class, 'viewMenuSettingNonSpp']);
                Route::post('setting-non-spp/datatables', [SppController::class, 'datatablesMenuSettingNonSpp']);

                Route::get('setting-saldo-kas-awal-tahun', [SppController::class, 'viewMenuSettingSaldoKasAwalTahun']);
                Route::get('setting-saldo-kas-awal-tahun/datatables', [SppController::class, 'datatablesMenuSettingSaldoKasAwalTahun']);
                Route::get('setting-saldo-kas-awal-tahun/add', [SppController::class, 'addeMenuSettingSaldoKasAwalTahun']);
                Route::get('setting-saldo-kas-awal-tahun/edit/{id}', [SppController::class, 'editMenuSettingSaldoKasAwalTahun']);
                Route::post('action-setting-saldo-kas-awal-tahun/{mode}/{id}', [SppController::class, 'actionMenuSettingSaldoKasAwalTahun']);

                Route::get('setting-tunggakan-tahun-lalu', [SppController::class, 'viewMenuSettingTunggakanTahunLalu']);
                Route::get('setting-tunggakan-tahun-lalu/datatables', [SppController::class, 'datatablesMenuSettingTunggakanTahunLalu']);
                Route::get('setting-tunggakan-tahun-lalu/add', [SppController::class, 'addeMenuSettingTunggakanTahunLalu']);
                Route::get('setting-tunggakan-tahun-lalu/edit/{id}', [SppController::class, 'editMenuSettingTunggakanTahunLalu']);
                Route::post('action-setting-tunggakan-tahun-lalu/{mode}/{id}', [SppController::class, 'actionMenuSettingTunggakanTahunLalu']);

                Route::get('edit-setting/{tahun_akademik_semester}/{id}', [SppController::class, 'viewMenuEditSetting']);
                Route::post('setting/save', [SppController::class, 'actionMenuSettingSaveSpp']);

                Route::get('edit-setting-non-spp/{tahun_akademik_semester}/{id}', [SppController::class, 'viewMenuEditSettingNonSpp']);
                Route::post('setting-non-spp/save', [SppController::class, 'actionMenuSettingSaveNonSpp']);

                Route::get('upload-pembayaran', [SppController::class, 'viewMenuUpload']);
                Route::post('upload-pembayaran', [SppController::class, 'actionMenuUpload']);
                Route::get('download-contoh-upload-pembayaran', [SppController::class, 'downloadContohUploadKeuangan'])->name('keuangan/download-contoh-upload-pembayaran');
            });
            Route::prefix('pengeluaran')->group(function () {
                Route::get('/', [PengeluaranController::class, 'viewMenuPengeluaran']);

                Route::get('input', [PengeluaranController::class, 'viewMenuInput']);
                Route::post('input/save', [PengeluaranController::class, 'actionSaveInputPengeluaran']);
                Route::get('edit/{id}', [PengeluaranController::class, 'editPengeluaran']);
                Route::post('delete/{id}', [PengeluaranController::class, 'deletePengeluaran']);

                Route::get('tampilkan', [PengeluaranController::class, 'viewMenuTampilkan']);
                Route::post('tampilkan/datatables', [PengeluaranController::class, 'datatablesMenuTampilkan']);

                Route::get('print/{id}', [PengeluaranController::class, 'printKuitansiPengeluaran']);

                Route::get('laporan', [PengeluaranController::class, 'viewLaporanPengeluaran']);
                Route::get('laporan/datatables', [PengeluaranController::class, 'datatablesLaporanPengeluaran']);

                Route::get('target', [PengeluaranController::class, 'viewMenuTarget']);
                Route::post('target/datatables', [PengeluaranController::class, 'datatablesMenuTarget']);
                Route::get('target/edit/{tahun}/{id}', [PengeluaranController::class, 'viewMenuEditTarget']);
                Route::post('target/save', [PengeluaranController::class, 'actionSaveEditTarget']);
            });
            Route::prefix('pembayaran-online')->group(function () {
                Route::get('/', [PembayaranOnlineController::class, 'viewIndex']);
                Route::get('add', [PembayaranOnlineController::class, 'viewAdd']);

                Route::post('datatables', [PembayaranOnlineController::class, 'datatables']);
                Route::post('tagihan/datatables/{id}', [PembayaranOnlineController::class, 'datatablesTagihan']);
                Route::post('save', [PembayaranOnlineController::class, 'actionSave']);

                Route::post('siswa-bykelas', [PembayaranOnlineController::class, 'ajaxGetSiswaByKelas']);
            });
        });

        Route::prefix('laporan-keuangan')->group(function () {

            Route::prefix('cetak-laporan')->group(function () {
                Route::get('/', [CetakLaporanController::class, 'viewCetakLaporan']);
                Route::get('print-pengeluaran/{jenis}/{start_date}/{end_date}', [CetakLaporanController::class, 'printCetakLaporanPengeluaran']);
                Route::get('print-arus-kas/{jenis}/{start_date}/{end_date}', [CetakLaporanController::class, 'printCetakLaporanKas']);
                Route::get('print-pembayaran-siswa/{jenis}/{start_date}/{end_date}', [CetakLaporanController::class, 'printCetakLaporanPembayaranSiswa']);
                Route::get('print-laporan-bulanan/{jenis}/{start_date}/{end_date}', [CetakLaporanController::class, 'printCetakLaporanBulanan']);

                Route::post('setting', [CetakLaporanController::class, 'actionSetSettingCetak']);
                Route::post('setting2', [CetakLaporanController::class, 'actionSetSettingCetak2']);
            });
            Route::prefix('pembayaran-siswa')->group(function () {
                Route::get('/', [LaporanKeuanganPembayaranSiswaController::class, 'viewPembayaranSiswa']);
                Route::get('datatables', [LaporanKeuanganPembayaranSiswaController::class, 'datatablesPembayaranSiswa']);
                Route::get('print-simple/{start_date}/{end_date}', [LaporanKeuanganPembayaranSiswaController::class, 'printSimplePembayaranSiswa']);
                Route::get('print-detail/{start_date}/{end_date}', [LaporanKeuanganPembayaranSiswaController::class, 'printDetailPembayaranSiswa']);
                Route::prefix('bulanan')->group(function () {
                    Route::get('/', [PembayaranSiswaBulananController::class, 'viewPembayaranSiswaBulanan']);
                    Route::get('/dataPembayaranSiswaBulanan', [PembayaranSiswaBulananController::class, 'dataPembayaranSiswaBulanan']);
                });
                Route::prefix('tahunan')->group(function () {
                    Route::get('/', [PembayaranSiswaTahunanController::class, 'viewPembayaranSiswaTahunan']);
                    Route::get('data/{year}', [PembayaranSiswaTahunanController::class, 'dataPembayaranSiswaTahunan']);
                });
            });

            Route::prefix('tagihan-siswa')->group(function () {
                Route::get('/', [LaporanKeuanganTagihanSiswaController::class, 'viewTagihanSiswa']);
                Route::post('datatables', [LaporanKeuanganTagihanSiswaController::class, 'datatablesTagihanSiswa']);
                Route::get('print/{tahun}/{id_kelas}/{jenis_tagihan}', [LaporanKeuanganTagihanSiswaController::class, 'printTagihanSiswa']);
                Route::get('show-list-tagihan/{tahun}/{id_kelas}', [LaporanKeuanganTagihanSiswaController::class, 'showListTagihan']);
            });
        });
    });
});
