<?php
// ROLE KEUANGAN
Route::group(array('middleware'=> ['token_staff']), function () {
    Route::group(array('prefix' => 'keuangan'), function () {
        Route::get('welcome', 'Keuangan\WelcomeController@indexWelcome');
        // Test Push

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

            // MENU Data Biaya Internal
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
            Route::get('biaya-sekolah', 'Keuangan\DataKeuangan\BiayaSekolahController@viewBiayaSekolah');
            Route::get('biaya-sekolah/datatables', 'Keuangan\DataKeuangan\BiayaSekolahController@datatablesBiayaSekolah');
            Route::get('biaya-sekolah/add', 'Keuangan\DataKeuangan\BiayaSekolahController@addBiayaSekolah');
            Route::get('biaya-sekolah/edit/{id}', 'Keuangan\DataKeuangan\BiayaSekolahController@editBiayaSekolah');
            Route::get('biaya-sekolah/copy', 'Keuangan\DataKeuangan\BiayaSekolahController@copyBiayaSekolah');

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

            Route::post('action-pembayaran-siswa/{mode}/{id}', 'Keuangan\Utility\PembayaranSiswaController@actionPembayaranSiswa');

            //MENU Pembayaran By Kelas
            // url: /keuangan/utility/pembayaran-by-kelas
            Route::get('pembayaran-by-kelas', 'Keuangan\Utility\PembayaranByKelasController@viewPembayaranByKelas');
            Route::post('post-view-pembayaran-by-kelas', 'Keuangan\Utility\PembayaranByKelasController@actionViewPembayaranByKelas');
            Route::get('pembayaran-by-kelas/view-detail/{id_semester}/{id_kelas}', 'Keuangan\Utility\PembayaranByKelasController@viewDetailPembayaranByKelas');
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
                Route::get('cari', 'Keuangan\SIM\SppController@viewMenuCari');
                Route::post('cari/datatables', 'Keuangan\SIM\SppController@datatablesMenuCari');

                Route::get('pembayaran', 'Keuangan\SIM\SppController@viewMenuPembayaran');
                Route::get('pembayaran/{id_semester}/{id_kelas}', 'Keuangan\SIM\SppController@viewMenuPembayaran');

                Route::get('pemasukan', 'Keuangan\SIM\SppController@viewMenuPemasukan');
                Route::get('pemasukan/{tahun_akademik_semester}/{id_bulan}', 'Keuangan\SIM\SppController@viewMenuPemasukan');

                Route::get('penerimaan', 'Keuangan\SIM\SppController@viewMenuPenerimaan');
            });

            // MENU PENGELUARAN
            Route::group(array('prefix' => 'pengeluaran'), function () {
                Route::get('/', 'Keuangan\SIM\PengeluaranController@viewMenuPengeluaran');
            });
        });

        /** ==== MODUL LAPORAN KEUNGAN ==== **/
        // url: /keuangan/laporan-keuangan
        Route::group(array('prefix' => 'laporan-keuangan'), function () {
            // MENU Cetak Laporan
            Route::group(array('prefix' => 'pembayaran-siswa'), function () {
                Route::get('/', 'Keuangan\LaporanKeuangan\PembayaranSiswaController@viewPembayaranSiswa');
                Route::get('datatables', 'Keuangan\LaporanKeuangan\PembayaranSiswaController@datatablesPembayaranSiswa');
            });
        });
    });
});
