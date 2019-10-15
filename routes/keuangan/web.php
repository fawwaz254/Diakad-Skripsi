<?php
// ROLE KEUANGAN
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'keuangan'), function() {
        Route::get('welcome', 'Keuangan\WelcomeController@indexWelcome');

        /** ==== MODUL DATA KEUANGAN ==== **/
		// url: /keuangan/data-keuangan
		Route::group(array('prefix' => 'data-keuangan'), function() {
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
		Route::group(array('prefix' => 'utility'), function() {
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

		/** ==== MODUL PEMASUKAN SEKOLAH ==== **/
		// url: /keuangan/pemasukan-sekolah
		Route::group(array('prefix' => 'pemasukan-sekolah'), function() {
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
		Route::group(array('prefix' => 'pengeluaran-sekolah'), function() {
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
    });
});