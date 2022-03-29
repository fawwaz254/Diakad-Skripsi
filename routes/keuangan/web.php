<?php

use Illuminate\Support\Facades\Route;


// ROLE KEUANGAN
Route::group(array('middleware' => ['token_staff']), function () {
    Route::group(array('prefix' => 'keuangan'), function () {
        Route::get('welcome', 'Keuangan\WelcomeController@indexWelcome');
        // Test Push

        /** ==== MODUL MANAJEMEN FILE ==== **/
        // url: /keuangan/manajemen-file
        Route::group(array('prefix' => 'manajemen-file'), function () {
            // MENU Data Kategori
            Route::group(array('prefix' => 'data-kategori'), function () {
                Route::get('/', 'ManajemenFile\DataKategoriController@viewDataKategori');
                Route::get('/datatables', 'ManajemenFile\DataKategoriController@datatablesCategoryfile');
            });

            // MENU Data Sub Kategori 
            Route::group(array('prefix' => 'data-sub-kategori'), function () {
                Route::get('/', 'ManajemenFile\SubDataKategoriController@viewSubDataKategori');
                Route::get('/add', 'ManajemenFile\SubDataKategoriController@addSubDataKategori');
                Route::get('/datatables', 'ManajemenFile\SubDataKategoriController@datatablesSubCategoryfile');
                Route::get('/edit/{id}', 'ManajemenFile\SubDataKategoriController@editSubDataKategori');

                //action input sub data kategori
                Route::post('action-data-sub-kategori/{mode}/{id}', 'ManajemenFile\SubDataKategoriController@actionSubDataKategori');
            });

            // MENU Data File 
            Route::group(array('prefix' => 'data-file'), function () {

                Route::get('/', 'ManajemenFile\DataFileController@viewDataFile');
                Route::get('add', 'ManajemenFile\DataFileController@addDataFile');
                Route::get('category/{category_file_id}', 'ManajemenFile\DataFileController@viewDataFileCategory');
                Route::get('dropdown-category', 'ManajemenFile\DataFileController@dropdownCategory');
                Route::get('sub-category/{sub_category_file_id}', 'ManajemenFile\DataFileController@viewDataFileSubCategory');

                Route::post('action-data-file/{mode}/{id}', 'ManajemenFile\DataFileController@actionDataFile');
                Route::get('download/{id}', 'ManajemenFile\DataFileController@downloadDataFile');
            });
        });

        Route::group(array('prefix' => 'kegiatan-harian'), function () {

            Route::group(array('prefix' => 'mengisi-form-kesehatan'), function () {
                // MENU Mengisi form kesehatan
                Route::get('/', 'Tendik\KegiatanHarian\FormKesehatanController@viewFormKesehatan');
                Route::get('add', 'Tendik\KegiatanHarian\FormKesehatanController@viewAddFormKesehatan');
                Route::get('detail/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');

                Route::post('action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
                Route::post('datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');
            });
        });

        /** ==== MODUL DATA KEUANGAN ==== **/
        // url: /keuangan/data-keuangan
        Route::group(array('prefix' => 'data-keuangan'), function () {
            // MENU Data Nama Biaya
            // url: /keuangan/data-keuangan/nama-biaya
            Route::get('nama-biaya', 'Keuangan\DataKeuangan\NamaBiayaController@viewNamaBiaya');
            Route::get('nama-biaya/datatables', 'Keuangan\DataKeuangan\NamaBiayaController@datatablesNamaBiaya');
            Route::get('nama-biaya/add', 'Keuangan\DataKeuangan\NamaBiayaController@addNamaBiaya');
            Route::get('nama-biaya/edit/{id}', 'Keuangan\DataKeuangan\NamaBiayaController@editNamaBiaya');

            Route::post('action-nama-biaya/{mode}/{id}', 'Keuangan\DataKeuangan\NamaBiayaController@actionNamaBiaya');
            // url: /keuangan/data-keuangan/biaya-internal
            Route::get('biaya-internal', 'Keuangan\DataKeuangan\BiayaInternalController@viewBiayaInternal');
            Route::get('biaya-internal/datatables', 'Keuangan\DataKeuangan\BiayaInternalController@datatablesBiayaInternal');
            Route::get('biaya-internal/add', 'Keuangan\DataKeuangan\BiayaInternalController@addBiayaInternal');
            Route::get('biaya-internal/edit/{id}', 'Keuangan\DataKeuangan\BiayaInternalController@editBiayaInternal');

            Route::post('action-biaya-internal/{mode}/{id}', 'Keuangan\DataKeuangan\BiayaInternalController@actionBiayaInternal');

            // MENU Data Detail Biaya Internal
            // url: /keuangan/data-keuangan/detail-biaya-internal
            Route::get('detail-biaya-internal', 'Keuangan\DataKeuangan\DetailBiayaInternalController@viewDetailBiayaInternal');
            Route::get('detail-biaya-internal/datatables', 'Keuangan\DataKeuangan\DetailBiayaInternalController@datatablesDetailBiayaInternal');
            Route::get('detail-biaya-internal/add', 'Keuangan\DataKeuangan\DetailBiayaInternalController@addDetailBiayaInternal');
            Route::get('detail-biaya-internal/edit/{id}', 'Keuangan\DataKeuangan\DetailBiayaInternalController@editDetailBiayaInternal');

            Route::post('action-detail-biaya-internal/{mode}/{id}', 'Keuangan\DataKeuangan\DetailBiayaInternalController@actionDetailBiayaInternal');

            // MENU Data Kelompok Biaya
            // url: /keuangan/data-keuangan/kelompok-biaya
            Route::get('kelompok-biaya', 'Keuangan\DataKeuangan\KelompokBiayaController@viewKelompokBiaya');
            Route::get('kelompok-biaya/datatables', 'Keuangan\DataKeuangan\KelompokBiayaController@datatablesKelompokBiaya');
            Route::get('kelompok-biaya/add', 'Keuangan\DataKeuangan\KelompokBiayaController@addKelompokBiaya');
            Route::get('kelompok-biaya/edit/{id}', 'Keuangan\DataKeuangan\KelompokBiayaController@editKelompokBiaya');

            Route::post('action-kelompok-biaya/{mode}/{id}', 'Keuangan\DataKeuangan\KelompokBiayaController@actionKelompokBiaya');

            // MENU Data Biaya Sekolah
            // url: /keuangan/data-keuangan/biaya-sekolah


            Route::group(array('prefix' => 'biaya-sekolah'), function () {

                Route::get('', 'Keuangan\DataKeuangan\BiayaSekolahController@viewBiayaSekolah');
                Route::get('datatables', 'Keuangan\DataKeuangan\BiayaSekolahController@datatablesBiayaSekolah');
                Route::get('add', 'Keuangan\DataKeuangan\BiayaSekolahController@addBiayaSekolah');
                Route::get('edit/{id}', 'Keuangan\DataKeuangan\BiayaSekolahController@editBiayaSekolah');
                Route::get('copy', 'Keuangan\DataKeuangan\BiayaSekolahController@copyBiayaSekolah');

                Route::group(array('prefix' => 'detail-biaya'), function () {

                    Route::get('{id}', 'Keuangan\DataKeuangan\DetailBiayaController@viewDetailBiaya2');
                    Route::post('datatables/{id}', 'Keuangan\DataKeuangan\DetailBiayaController@datatablesDetailBiaya2');
                    Route::get('add/{id}', 'Keuangan\DataKeuangan\DetailBiayaController@addDetailBiaya2');
                    Route::get('edit/{id}', 'Keuangan\DataKeuangan\DetailBiayaController@editDetailBiaya2');
                    Route::post('action-detail-biaya/{mode}/{id}', 'Keuangan\DataKeuangan\DetailBiayaController@actionDetailBiaya2');

                    Route::group(array('prefix' => 'detail-biaya-internal'), function () {

                        Route::get('{id}', 'Keuangan\DataKeuangan\DetailBiayaInternalController@viewDetailBiayaInternal2');
                        Route::post('datatables/{id}', 'Keuangan\DataKeuangan\DetailBiayaInternalController@datatablesDetailBiayaInternal2');
                        Route::get('add/{id}', 'Keuangan\DataKeuangan\DetailBiayaInternalController@addDetailBiayaInternal2');
                        Route::get('edit/{id}', 'Keuangan\DataKeuangan\DetailBiayaInternalController@editDetailBiayaInternal2');
                        Route::post('action-detail-biaya-internal/{id_detail_biaya}/{mode}/{id}', 'Keuangan\DataKeuangan\DetailBiayaInternalController@actionDetailBiayaInternal2');
                    });
                });
            });

            Route::post('action-biaya-sekolah/{mode}/{id}', 'Keuangan\DataKeuangan\BiayaSekolahController@actionBiayaSekolah');

            // MENU Data Detail Biaya
            // url: /keuangan/data-keuangan/detail-biaya
            Route::get('detail-biaya', 'Keuangan\DataKeuangan\DetailBiayaController@viewDetailBiaya');
            Route::get('detail-biaya/datatables', 'Keuangan\DataKeuangan\DetailBiayaController@datatablesDetailBiaya');
            Route::get('detail-biaya/add', 'Keuangan\DataKeuangan\DetailBiayaController@addDetailBiaya');
            Route::get('detail-biaya/edit/{id}', 'Keuangan\DataKeuangan\DetailBiayaController@editDetailBiaya');

            Route::post('action-detail-biaya/{mode}/{id}', 'Keuangan\DataKeuangan\DetailBiayaController@actionDetailBiaya');

            // AJAX GET BULAN BY JENIS_BIAYA
            Route::post('bulan-byjenisbiaya', 'Keuangan\DataKeuangan\DetailBiayaController@ajaxGetBulanByJenisBiaya');
        });

        /** ==== MODUL UTILITY ==== **/
        // url: /keuangan/utility
        Route::group(array('prefix' => 'utility'), function () {
            // MENU Setting Biaya Siswa
            // url: /keuangan/utility/biaya-siswa
            Route::get('biaya-siswa', 'Keuangan\Utility\BiayaSiswaController@viewBiayaSiswa');
            Route::get('biaya-siswa-by-kelas', 'Keuangan\Utility\BiayaSiswaController@viewBiayaSiswaByKelas');
            Route::get('biaya-siswa/datatables-belum', 'Keuangan\Utility\BiayaSiswaController@datatablesBiayaSiswaBelum');
            Route::get('biaya-siswa/datatables-sudah', 'Keuangan\Utility\BiayaSiswaController@datatablesBiayaSiswaSudah');
            Route::get('biaya-siswa/set/{id}', 'Keuangan\Utility\BiayaSiswaController@setBiayaSiswa');
            Route::get('biaya-siswa/edit/{id}', 'Keuangan\Utility\BiayaSiswaController@editBiayaSiswa');

            Route::post('action-biaya-siswa/{mode}/{id}', 'Keuangan\Utility\BiayaSiswaController@actionBiayaSiswa');
            Route::post('action-batch-biaya-siswa/{mode}', 'Keuangan\Utility\BiayaSiswaController@actionBatchBiayaSiswa');

            // MENU Generate Tagihan Siswa
            // url: /keuangan/utility/tagihan-siswa
            Route::get('tagihan-siswa', 'Keuangan\Utility\TagihanSiswaController@viewTagihanSiswa');
            Route::post('post-view-tagihan-siswa', 'Keuangan\Utility\TagihanSiswaController@actionViewTagihanSiswa');
            Route::get('tagihan-siswa/view-detail-tagihan-siswa/{thn_masuk_siswa}/{id_semester}/{id_kelompok_biaya}/{id_jalur}/{is_insert_replace}', 'Keuangan\Utility\TagihanSiswaController@viewDetailTagihanSiswa');
            Route::get('tagihan-siswa/datatables/{thn_masuk_siswa}/{id_semester}/{id_kelompok_biaya}/{id_jalur}/{is_insert_replace}', 'Keuangan\Utility\TagihanSiswaController@datatablesTagihanSiswa');

            Route::post('action-tagihan-siswa/{mode}', 'Keuangan\Utility\TagihanSiswaController@actionTagihanSiswa');

            //MENU Pembayaran Siswa
            // url: /keuangan/utility/pembayaran-siswa
            Route::get('pembayaran-siswa', 'Keuangan\Utility\PembayaranSiswaController@viewPembayaranSiswa');
            Route::post('post-view-pembayaran-siswa', 'Keuangan\Utility\PembayaranSiswaController@actionViewPembayaranSiswa');
            Route::get('pembayaran-siswa/view-detail/{nis_nama_siswa}', 'Keuangan\Utility\PembayaranSiswaController@viewDetailPembayaranSiswa');
            Route::get('pembayaran-siswa/datatables/{nis_nama_siswa}', 'Keuangan\Utility\PembayaranSiswaController@datatablesPembayaranSiswa');
            Route::get('pembayaran-siswa/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', 'Keuangan\Utility\PembayaranSiswaController@viewDetailSiswaPembayaranSiswa');
            Route::get('pembayaran-siswa/datatables-tagihan/{id_pengguna}/{nis_nama_siswa}', 'Keuangan\Utility\PembayaranSiswaController@datatablesTagihanPembayaranSiswa');
            Route::get('pembayaran-siswa/datatables-riwayat-bayar/{id_pengguna}', 'Keuangan\Utility\PembayaranSiswaController@datatablesRiwayatBayarSiswa');
            Route::get('pembayaran-siswa/view-detail-tagihan-siswa/{id_tagihan}/{nis_nama_siswa_asli}', 'Keuangan\Utility\PembayaranSiswaController@viewDetailTagihanPembayaranSiswa');
            Route::get('pembayaran-siswa/view-diskon-tagihan-siswa/{id_tagihan}/{nis_nama_siswa_asli}', 'Keuangan\Utility\PembayaranSiswaController@viewDiskonTagihanPembayaranSiswa');

            Route::get('pembayaran-siswa/print-pembayaran/{id_pengguna}/{tgl_pembayaran}', 'Keuangan\Utility\PembayaranSiswaController@printPembayaranSiswa');
            Route::get('pembayaran-siswa/print-belum-terbayar/{id_pengguna}', 'Keuangan\Utility\PembayaranSiswaController@printBelumTerbayarPembayaranSiswa');

            Route::post('action-tagihan-siswa/delete/{id}', 'Keuangan\Utility\PembayaranSiswaController@actionDeleteTagihanSiswa');
            Route::post('action-pembayaran-siswa/{mode}/{id}', 'Keuangan\Utility\PembayaranSiswaController@actionPembayaranSiswa');
            Route::post('action-pembayaran-siswa-massal', 'Keuangan\Utility\PembayaranSiswaController@actionPembayaranSiswaMassal');

            //MENU Pembayaran By Kelas
            // url: /keuangan/utility/pembayaran-by-kelas
            Route::get('pembayaran-by-kelas', 'Keuangan\Utility\PembayaranByKelasController@viewPembayaranByKelas');
            Route::post('post-view-pembayaran-by-kelas', 'Keuangan\Utility\PembayaranByKelasController@actionViewPembayaranByKelas');
            Route::get('pembayaran-by-kelas/view-detail/{id_semester}/{id_kelas}', 'Keuangan\Utility\PembayaranByKelasController@viewDetailPembayaranByKelas');
            Route::get('pembayaran-by-kelas/print/{id_semester}/{id_kelas}', 'Keuangan\Utility\PembayaranByKelasController@printPembayaranByKelas');

            Route::group(array('prefix' => 'input-tagihan-siswa'), function () {
                Route::get('/', 'Keuangan\Utility\InputTagihanSiswaController@viewInputTagihanSiswa');
                Route::get('filter-siswa/{id}', 'Keuangan\Utility\InputTagihanSiswaController@filterSiswa');
                Route::post('add-tagihan', 'Keuangan\Utility\InputTagihanSiswaController@addTagihan');
            });
        });

        /** ==== MODUL RAPB ==== **/
        // url: /keuangan/rapb
        Route::group(array('prefix' => 'rapb'), function () {
            // MENU Kategori Penerimaan
            // url: /keuangan/rapb/kategori-penerimaan
            Route::get('kategori-penerimaan', 'Keuangan\Rapb\KategoriPenerimaanController@viewKategoriPenerimaan');
            Route::get('kategori-penerimaan/datatables', 'Keuangan\Rapb\KategoriPenerimaanController@datatablesKategoriPenerimaan');
            Route::get('kategori-penerimaan/add', 'Keuangan\Rapb\KategoriPenerimaanController@addKategoriPenerimaan');
            Route::get('kategori-penerimaan/edit/{id}', 'Keuangan\Rapb\KategoriPenerimaanController@editKategoriPenerimaan');

            Route::get('kategori-penerimaan/sub/{id_kategori}', 'Keuangan\Rapb\KategoriPenerimaanController@viewSubkategoriPenerimaan');
            Route::get('kategori-penerimaan/sub/datatables/{id_kategori}', 'Keuangan\Rapb\KategoriPenerimaanController@datatablesSubkategoriPenerimaan');
            Route::get('kategori-penerimaan/sub/add/{id_kategori}', 'Keuangan\Rapb\KategoriPenerimaanController@addSubkategoriPenerimaan');
            Route::get('kategori-penerimaan/sub/edit/{id_kategori}/{id_subkategori}', 'Keuangan\Rapb\KategoriPenerimaanController@editSubkategoriPenerimaan');

            Route::get('kategori-penerimaan/sub/ket/{id_kategori}/{id_subkategori}', 'Keuangan\Rapb\KategoriPenerimaanController@viewKetSubkategoriPenerimaan');
            Route::get('kategori-penerimaan/sub/ket/datatables/{id_kategori}/{id_subkategori}', 'Keuangan\Rapb\KategoriPenerimaanController@datatablesKetSubkategoriPenerimaan');
            Route::get('kategori-penerimaan/sub/ket/add/{id_kategori}/{id_subkategori}', 'Keuangan\Rapb\KategoriPenerimaanController@addKetSubkategoriPenerimaan');
            Route::get('kategori-penerimaan/sub/ket/edit/{id_kategori}/{id_subkategori}/{id_ket_subkategori}', 'Keuangan\Rapb\KategoriPenerimaanController@editKetSubkategoriPenerimaan');

            Route::post('action-kategori-penerimaan/{mode}/{id}', 'Keuangan\Rapb\KategoriPenerimaanController@actionKategoriPenerimaan');

            // MENU Kategori Pengeluaran
            // url: /keuangan/rapb/kategori-pengeluaran
            Route::get('kategori-pengeluaran', 'Keuangan\Rapb\KategoriPengeluaranController@viewKategoriPengeluaran');
            Route::get('kategori-pengeluaran/datatables', 'Keuangan\Rapb\KategoriPengeluaranController@datatablesKategoriPengeluaran');
            Route::get('kategori-pengeluaran/add', 'Keuangan\Rapb\KategoriPengeluaranController@addKategoriPengeluaran');
            Route::get('kategori-pengeluaran/edit/{id}', 'Keuangan\Rapb\KategoriPengeluaranController@editKategoriPengeluaran');

            Route::get('kategori-pengeluaran/sub/{id_kategori}', 'Keuangan\Rapb\KategoriPengeluaranController@viewSubkategoriPengeluaran');
            Route::get('kategori-pengeluaran/sub/datatables/{id_kategori}', 'Keuangan\Rapb\KategoriPengeluaranController@datatablesSubkategoriPengeluaran');
            Route::get('kategori-pengeluaran/sub/add/{id_kategori}', 'Keuangan\Rapb\KategoriPengeluaranController@addSubkategoriPengeluaran');
            Route::get('kategori-pengeluaran/sub/edit/{id_kategori}/{id_subkategori}', 'Keuangan\Rapb\KategoriPengeluaranController@editSubkategoriPengeluaran');

            Route::get('kategori-pengeluaran/sub/ket/{id_kategori}/{id_subkategori}', 'Keuangan\Rapb\KategoriPengeluaranController@viewKetSubkategoriPengeluaran');
            Route::get('kategori-pengeluaran/sub/ket/datatables/{id_kategori}/{id_subkategori}', 'Keuangan\Rapb\KategoriPengeluaranController@datatablesKetSubkategoriPengeluaran');
            Route::get('kategori-pengeluaran/sub/ket/add/{id_kategori}/{id_subkategori}', 'Keuangan\Rapb\KategoriPengeluaranController@addKetSubkategoriPengeluaran');
            Route::get('kategori-pengeluaran/sub/ket/edit/{id_kategori}/{id_subkategori}/{id_ket_subkategori}', 'Keuangan\Rapb\KategoriPengeluaranController@editKetSubkategoriPengeluaran');

            Route::post('action-kategori-pengeluaran/{mode}/{id}', 'Keuangan\Rapb\KategoriPengeluaranController@actionKategoriPengeluaran');

            // MENU Input RAPB
            // url: /keuangan/rapb/input-rapb
            Route::get('input-rapb', 'Keuangan\Rapb\InputRapbController@viewInputRapb');
            Route::post('post-view-input-rapb', 'Keuangan\Rapb\InputRapbController@actionViewInputRapb');
            Route::get('input-rapb/view-detail-input-rapb/{semester_mulai}/{semester_selesai}', 'Keuangan\Rapb\InputRapbController@viewDetailInputRapb');
            Route::get('input-rapb/datatables/{semester_mulai}/{semester_selesai}', 'Keuangan\Rapb\InputRapbController@datatablesInputRapb');
            Route::get('input-rapb/add/{semester_mulai}/{semester_selesai}', 'Keuangan\Rapb\InputRapbController@addInputRapb');
            Route::get('input-rapb/edit/{semester_mulai}/{semester_selesai}/{id}', 'Keuangan\Rapb\InputRapbController@editInputRapb');

            Route::post('action-input-rapb/{mode}/{id}', 'Keuangan\Rapb\InputRapbController@actionInputRapb');
            Route::post('action-apv-rapb/{mode}/{id}/{id_unit_kerja}', 'Keuangan\Rapb\InputRapbController@actionApvRapb');
            // AJAX GET SUBKATEGORI RAPB BY KATEGORI
            Route::post('kategori-byjenis', 'Keuangan\Rapb\InputRapbController@ajaxGetKategoriByJenis');
            Route::post('subkategori-bykategori', 'Keuangan\Rapb\InputRapbController@ajaxGetSubkategoriByKategori');

            // MENU Realisasi RAPB
            // url: /keuangan/rapb/realisasi-rapb
            Route::get('realisasi-rapb', 'Keuangan\Rapb\RealisasiRapbController@viewRealisasi');
            Route::post('post-view-rapb', 'Keuangan\Rapb\RealisasiRapbController@actionViewRapb');
            Route::get('realisasi-rapb/view-detail-rapb/{semester_mulai}/{semester_selesai}', 'Keuangan\Rapb\RealisasiRapbController@viewDetailRapb');
            Route::get('realisasi-rapb/datatables/rapb-tinggi/{semester_mulai}/{semester_selesai}', 'Keuangan\Rapb\RealisasiRapbController@datatablesRapbTinggi');
            Route::get('realisasi-rapb/datatables/rapb-sedang/{semester_mulai}/{semester_selesai}', 'Keuangan\Rapb\RealisasiRapbController@datatablesRapbSedang');
            Route::get('realisasi-rapb/datatables/rapb-rendah/{semester_mulai}/{semester_selesai}', 'Keuangan\Rapb\RealisasiRapbController@datatablesRapbRendah');

            Route::get('realisasi-rapb/view-detail-realisasi/{semester_mulai}/{semester_selesai}/{id_rapb}', 'Keuangan\Rapb\RealisasiRapbController@viewDetailRealisasi');
            Route::get('realisasi-rapb/datatables/{id_rapb}', 'Keuangan\Rapb\RealisasiRapbController@datatablesRealisasi');
            Route::get('realisasi-rapb/add/{semester_mulai}/{semester_selesai}/{id_rapb}', 'Keuangan\Rapb\RealisasiRapbController@addRealisasi');
            // tabel reliasasi_pembayaran
            Route::get('realisasi-rapb/view-detail-realisasi-termin/{semester_mulai}/{semester_selesai}/{id_rapb}/{id_realisasi}', 'Keuangan\Rapb\RealisasiRapbController@viewDetailRealisasiTermin');
            Route::get('realisasi-rapb/datatables-termin/{id_realisasi}', 'Keuangan\Rapb\RealisasiRapbController@datatablesRealisasiTermin');
            Route::get('realisasi-rapb/add-realisasi-termin/{semester_mulai}/{semester_selesai}/{id_rapb}/{id_realisasi}', 'Keuangan\Rapb\RealisasiRapbController@addRealisasiTermin');

            // tabel rpb_sarpras
            Route::get('realisasi-rapb/view-detail-realisasi-sarpras/{semester_mulai}/{semester_selesai}/{id_rapb}', 'Keuangan\Rapb\RealisasiRapbController@viewDetailRealisasiSarpras');
            Route::get('realisasi-rapb/datatables-sarpras', 'Keuangan\Rapb\RealisasiRapbController@datatablesRealisasiSarpras');
            Route::get('realisasi-rapb/add-realisasi-sarpras/{semester_mulai}/{semester_selesai}/{id_rapb}/{id_rpb_sarpras}', 'Keuangan\Rapb\RealisasiRapbController@addRealisasiSarpras');

            Route::post('action-apv-realisasi/{mode}/{id}', 'Keuangan\Rapb\RealisasiRapbController@actionApvRealisasi');
            Route::post('action-realisasi-rapb/{mode}/{id}', 'Keuangan\Rapb\RealisasiRapbController@actionRealisasi');

            // belum
            /*Route::get('realisasi-rapb/edit/{semester_mulai}/{semester_selesai}/{id_rapb}/{id}', 'Keuangan\Rapb\RealisasiRapbController@editRealisasi');*/
        });

        /** ==== MODUL PEMASUKAN SEKOLAH ==== **/
        // url: /keuangan/pemasukan-sekolah
        Route::group(array('prefix' => 'pemasukan-sekolah'), function () {
            // MENU Kategori Pemasukan
            // url: /keuangan/pemasukan-sekolah/kategori-pemasukan
            Route::get('kategori-pemasukan', 'Keuangan\PemasukanSekolah\KategoriPemasukanController@viewKategoriPemasukan');
            Route::get('kategori-pemasukan/datatables', 'Keuangan\PemasukanSekolah\KategoriPemasukanController@datatablesKategoriPemasukan');
            Route::get('kategori-pemasukan/add', 'Keuangan\PemasukanSekolah\KategoriPemasukanController@addKategoriPemasukan');
            Route::get('kategori-pemasukan/edit/{id}', 'Keuangan\PemasukanSekolah\KategoriPemasukanController@editKategoriPemasukan');

            Route::post('action-kategori-pemasukan/{mode}/{id}', 'Keuangan\PemasukanSekolah\KategoriPemasukanController@actionKategoriPemasukan');

            // MENU Sub-Kategori Pemasukan
            // url: /keuangan/pemasukan-sekolah/subkategori-pemasukan
            Route::get('subkategori-pemasukan', 'Keuangan\PemasukanSekolah\SubkategoriPemasukanController@viewSubkategoriPemasukan');
            Route::get('subkategori-pemasukan/datatables', 'Keuangan\PemasukanSekolah\SubkategoriPemasukanController@datatablesSubkategoriPemasukan');
            Route::get('subkategori-pemasukan/add', 'Keuangan\PemasukanSekolah\SubkategoriPemasukanController@addSubkategoriPemasukan');
            Route::get('subkategori-pemasukan/edit/{id}', 'Keuangan\PemasukanSekolah\SubkategoriPemasukanController@editSubkategoriPemasukan');

            Route::post('action-subkategori-pemasukan/{mode}/{id}', 'Keuangan\PemasukanSekolah\SubkategoriPemasukanController@actionSubkategoriPemasukan');

            // MENU Input Pemasukan
            // url: /keuangan/pemasukan-sekolah/input-pemasukan
            Route::get('input-pemasukan', 'Keuangan\PemasukanSekolah\InputPemasukanController@viewInputPemasukan');
            Route::get('input-pemasukan/datatables', 'Keuangan\PemasukanSekolah\InputPemasukanController@datatablesInputPemasukan');
            Route::get('input-pemasukan/add', 'Keuangan\PemasukanSekolah\InputPemasukanController@addInputPemasukan');
            Route::get('input-pemasukan/edit/{id}', 'Keuangan\PemasukanSekolah\InputPemasukanController@editInputPemasukan');

            Route::post('action-input-pemasukan/{mode}/{id}', 'Keuangan\PemasukanSekolah\InputPemasukanController@actionInputPemasukan');
        });

        /** ==== MODUL PENGELUARAN SEKOLAH ==== **/
        // url: /keuangan/pengeluaran-sekolah
        Route::group(array('prefix' => 'pengeluaran-sekolah'), function () {
            // MENU Kategori Pengeluaran
            // url: /keuangan/pengeluaran-sekolah/kategori-pengeluaran
            Route::get('kategori-pengeluaran', 'Keuangan\PengeluaranSekolah\KategoriPengeluaranController@viewKategoriPengeluaran');
            Route::get('kategori-pengeluaran/datatables', 'Keuangan\PengeluaranSekolah\KategoriPengeluaranController@datatablesKategoriPengeluaran');
            Route::get('kategori-pengeluaran/add', 'Keuangan\PengeluaranSekolah\KategoriPengeluaranController@addKategoriPengeluaran');
            Route::get('kategori-pengeluaran/edit/{id}', 'Keuangan\PengeluaranSekolah\KategoriPengeluaranController@editKategoriPengeluaran');

            Route::post('action-kategori-pengeluaran/{mode}/{id}', 'Keuangan\PengeluaranSekolah\KategoriPengeluaranController@actionKategoriPengeluaran');

            // MENU Sub-Kategori Pengeluaran
            // url: /keuangan/pengeluaran-sekolah/subkategori-pengeluaran
            Route::get('subkategori-pengeluaran', 'Keuangan\PengeluaranSekolah\SubkategoriPengeluaranController@viewSubkategoriPengeluaran');
            Route::get('subkategori-pengeluaran/datatables', 'Keuangan\PengeluaranSekolah\SubkategoriPengeluaranController@datatablesSubkategoriPengeluaran');
            Route::get('subkategori-pengeluaran/add', 'Keuangan\PengeluaranSekolah\SubkategoriPengeluaranController@addSubkategoriPengeluaran');
            Route::get('subkategori-pengeluaran/edit/{id}', 'Keuangan\PengeluaranSekolah\SubkategoriPengeluaranController@editSubkategoriPengeluaran');

            Route::post('action-subkategori-pengeluaran/{mode}/{id}', 'Keuangan\PengeluaranSekolah\SubkategoriPengeluaranController@actionSubkategoriPengeluaran');
            // MENU Input Pengeluaran
            // url: /keuangan/pengeluaran-sekolah/input-pengeluaran
            Route::get('input-pengeluaran', 'Keuangan\PengeluaranSekolah\InputPengeluaranController@viewInputPengeluaran');
            Route::get('input-pengeluaran/datatables', 'Keuangan\PengeluaranSekolah\InputPengeluaranController@datatablesInputPengeluaran');
            Route::get('input-pengeluaran/add', 'Keuangan\PengeluaranSekolah\InputPengeluaranController@addInputPengeluaran');
            Route::get('input-pengeluaran/edit/{id}', 'Keuangan\PengeluaranSekolah\InputPengeluaranController@editInputPengeluaran');

            Route::post('action-input-pengeluaran/{mode}/{id}', 'Keuangan\PengeluaranSekolah\InputPengeluaranController@actionInputPengeluaran');
        });

        /** ==== MODUL SIM ==== **/
        // url: /keuangan/sim
        Route::group(array('prefix' => 'sim'), function () {
            // MENU SPP
            Route::group(array('prefix' => 'spp'), function () {

                Route::get('/', 'Keuangan\SIM\SppController@viewMenuSpp');

                Route::get('input', 'Keuangan\SIM\SppController@viewMenuInput');
                Route::post('input/save', 'Keuangan\SIM\SppController@actionSaveInputPenerimaan');

                Route::get('cari', 'Keuangan\SIM\SppController@viewMenuCari');
                Route::post('cari/datatables', 'Keuangan\SIM\SppController@datatablesMenuCari');

                Route::get('pembayaran', 'Keuangan\SIM\SppController@viewMenuPembayaran');
                Route::get('pembayaran/{tahun_akademik_semester}/{id_kelas}', 'Keuangan\SIM\SppController@viewMenuPembayaran');

                Route::get('pemasukan', 'Keuangan\SIM\SppController@viewMenuPemasukan');
                Route::get('pemasukan/{tahun_akademik_semester}/{id_bulan}', 'Keuangan\SIM\SppController@viewMenuPemasukan');

                Route::get('pemasukan/{tahun_akademik_semester}/{id_bulan}/report', 'Keuangan\SIM\SppController@indexDownloadLapBulanan');
                Route::get('pemasukan/{tahun_akademik_semester}/{id_bulan}/refresh', 'Keuangan\SIM\SppController@actionRefreshLapBulanan');

                Route::get('penerimaan', 'Keuangan\SIM\SppController@viewMenuPenerimaan');
                Route::get('tunggakan', 'Keuangan\SIM\SppController@viewMenuTunggakan');
                Route::get('tunggakan/{tahun_akademik}', 'Keuangan\SIM\SppController@viewMenuTunggakan');
                Route::post('tunggakan/save', 'Keuangan\SIM\SppController@actionSaveInputTunggakan');

                Route::get('setting', 'Keuangan\SIM\SppController@viewMenuSetting');
                Route::post('setting/datatables', 'Keuangan\SIM\SppController@datatablesMenuSetting');

                Route::get('setting-non-spp', 'Keuangan\SIM\SppController@viewMenuSettingNonSpp');
                Route::post('setting-non-spp/datatables', 'Keuangan\SIM\SppController@datatablesMenuSettingNonSpp');

                Route::get('setting-saldo-kas-awal-tahun', 'Keuangan\SIM\SppController@viewMenuSettingSaldoKasAwalTahun');
                Route::get('setting-saldo-kas-awal-tahun/datatables', 'Keuangan\SIM\SppController@datatablesMenuSettingSaldoKasAwalTahun');
                Route::get('setting-saldo-kas-awal-tahun/add', 'Keuangan\SIM\SppController@addeMenuSettingSaldoKasAwalTahun');
                Route::get('setting-saldo-kas-awal-tahun/edit/{id}', 'Keuangan\SIM\SppController@editMenuSettingSaldoKasAwalTahun');
                Route::post('action-setting-saldo-kas-awal-tahun/{mode}/{id}', 'Keuangan\SIM\SppController@actionMenuSettingSaldoKasAwalTahun');

                Route::get('setting-tunggakan-tahun-lalu', 'Keuangan\SIM\SppController@viewMenuSettingTunggakanTahunLalu');
                Route::get('setting-tunggakan-tahun-lalu/datatables', 'Keuangan\SIM\SppController@datatablesMenuSettingTunggakanTahunLalu');
                Route::get('setting-tunggakan-tahun-lalu/add', 'Keuangan\SIM\SppController@addeMenuSettingTunggakanTahunLalu');
                Route::get('setting-tunggakan-tahun-lalu/edit/{id}', 'Keuangan\SIM\SppController@editMenuSettingTunggakanTahunLalu');
                Route::post('action-setting-tunggakan-tahun-lalu/{mode}/{id}', 'Keuangan\SIM\SppController@actionMenuSettingTunggakanTahunLalu');

                Route::get('edit-setting/{tahun_akademik_semester}/{id}', 'Keuangan\SIM\SppController@viewMenuEditSetting');
                Route::post('setting/save', 'Keuangan\SIM\SppController@actionMenuSettingSaveSpp');

                Route::get('edit-setting-non-spp/{tahun_akademik_semester}/{id}', 'Keuangan\SIM\SppController@viewMenuEditSettingNonSpp');
                Route::post('setting-non-spp/save', 'Keuangan\SIM\SppController@actionMenuSettingSaveNonSpp');

                Route::get('upload-pembayaran', 'Keuangan\SIM\SppController@viewMenuUpload');
                Route::post('upload-pembayaran', 'Keuangan\SIM\SppController@actionMenuUpload');
            });

            // MENU PENGELUARAN
            Route::group(array('prefix' => 'pengeluaran'), function () {
                Route::get('/', 'Keuangan\SIM\PengeluaranController@viewMenuPengeluaran');

                Route::get('input', 'Keuangan\SIM\PengeluaranController@viewMenuInput');
                Route::post('input/save', 'Keuangan\SIM\PengeluaranController@actionSaveInputPengeluaran');
                Route::get('edit/{id}', 'Keuangan\SIM\PengeluaranController@editPengeluaran');
                Route::post('delete/{id}', 'Keuangan\SIM\PengeluaranController@deletePengeluaran');

                Route::get('tampilkan', 'Keuangan\SIM\PengeluaranController@viewMenuTampilkan');
                Route::post('tampilkan/datatables', 'Keuangan\SIM\PengeluaranController@datatablesMenuTampilkan');

                Route::get('print/{id}', 'Keuangan\SIM\PengeluaranController@printKuitansiPengeluaran');

                Route::get('laporan', 'Keuangan\SIM\PengeluaranController@viewLaporanPengeluaran');
                Route::get('laporan/datatables', 'Keuangan\SIM\PengeluaranController@datatablesLaporanPengeluaran');

                Route::get('target', 'Keuangan\SIM\PengeluaranController@viewMenuTarget');
                Route::post('target/datatables', 'Keuangan\SIM\PengeluaranController@datatablesMenuTarget');
                Route::get('target/edit/{tahun}/{id}', 'Keuangan\SIM\PengeluaranController@viewMenuEditTarget');
                Route::post('target/save', 'Keuangan\SIM\PengeluaranController@actionSaveEditTarget');
            });

            // MENU PEMBAYARAN ONLINE
            Route::group(array('prefix' => 'pembayaran-online'), function () {
                Route::get('/', 'Keuangan\SIM\PembayaranOnlineController@viewIndex');
                Route::get('add', 'Keuangan\SIM\PembayaranOnlineController@viewAdd');

                Route::post('datatables', 'Keuangan\SIM\PembayaranOnlineController@datatables');
                Route::post('tagihan/datatables/{id}', 'Keuangan\SIM\PembayaranOnlineController@datatablesTagihan');
                Route::post('save', 'Keuangan\SIM\PembayaranOnlineController@actionSave');

                Route::post('siswa-bykelas', 'Keuangan\SIM\PembayaranOnlineController@ajaxGetSiswaByKelas');
            });
        });

        /** ==== MODUL LAPORAN KEUNGAN ==== **/
        // url: /keuangan/laporan-keuangan
        Route::group(array('prefix' => 'laporan-keuangan'), function () {
            // MENU Cetak Laporan
            Route::group(['prefix' => 'cetak-laporan'], function () {
                Route::get('/', 'Keuangan\LaporanKeuangan\CetakLaporanController@viewCetakLaporan');
                Route::get('print-pengeluaran/{jenis}/{start_date}/{end_date}', 'Keuangan\LaporanKeuangan\CetakLaporanController@printCetakLaporanPengeluaran');
                Route::get('print-arus-kas/{jenis}/{start_date}/{end_date}', 'Keuangan\LaporanKeuangan\CetakLaporanController@printCetakLaporanKas');
                Route::get('print-pembayaran-siswa/{jenis}/{start_date}/{end_date}', 'Keuangan\LaporanKeuangan\CetakLaporanController@printCetakLaporanPembayaranSiswa');
                Route::get('print-laporan-bulanan/{jenis}/{start_date}/{end_date}', 'Keuangan\LaporanKeuangan\CetakLaporanController@printCetakLaporanBulanan');

                Route::post('setting', 'Keuangan\LaporanKeuangan\CetakLaporanController@actionSetSettingCetak');
            });

            Route::group(array('prefix' => 'pembayaran-siswa'), function () {
                Route::get('/', 'Keuangan\LaporanKeuangan\PembayaranSiswaController@viewPembayaranSiswa');
                Route::get('datatables', 'Keuangan\LaporanKeuangan\PembayaranSiswaController@datatablesPembayaranSiswa');
                Route::get('print-simple/{start_date}/{end_date}', 'Keuangan\LaporanKeuangan\PembayaranSiswaController@printSimplePembayaranSiswa');
                Route::get('print-detail/{start_date}/{end_date}', 'Keuangan\LaporanKeuangan\PembayaranSiswaController@printDetailPembayaranSiswa');

                Route::group(array('prefix' => 'bulanan'), function () {
                    Route::get('/', 'Keuangan\LaporanKeuangan\PembayaranSiswaBulananController@viewPembayaranSiswaBulanan');
                    Route::get('/dataPembayaranSiswaBulanan', 'Keuangan\LaporanKeuangan\PembayaranSiswaBulananController@dataPembayaranSiswaBulanan');
                });

                Route::group(array('prefix' => 'tahunan'), function () {
                    Route::get('/', 'Keuangan\LaporanKeuangan\PembayaranSiswaTahunanController@viewPembayaranSiswaTahunan');
                    Route::get('data/{year}', 'Keuangan\LaporanKeuangan\PembayaranSiswaTahunanController@dataPembayaranSiswaTahunan');
                });
            });

            Route::group(array('prefix' => 'tagihan-siswa'), function () {
                Route::get('/', 'Keuangan\LaporanKeuangan\TagihanSiswaController@viewTagihanSiswa');
                Route::post('datatables', 'Keuangan\LaporanKeuangan\TagihanSiswaController@datatablesTagihanSiswa');
                Route::get('print/{tahun}/{id_kelas}/{jenis_tagihan}', 'Keuangan\LaporanKeuangan\TagihanSiswaController@printTagihanSiswa');
                Route::get('show-list-tagihan/{tahun}/{id_kelas}', 'Keuangan\LaporanKeuangan\TagihanSiswaController@showListTagihan');
            });
        });
    });
});
