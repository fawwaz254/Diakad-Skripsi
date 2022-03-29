<?php
// ROLE SISWA
Route::group(array('middleware' => ['token_staff']), function () {
	Route::group(array('prefix' => 'siswa'), function () {
		Route::get('welcome', 'Siswa\WelcomeController@indexWelcome');

		/** ==== MODUL MANAJEMEN FILE ==== **/
		// url: /siswa/manajemen-file
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

		Route::group(array('prefix' => 'e-learning'), function () {

			Route::group(array('prefix' => 'materi-ajar'), function () {

				Route::get('/', 'Siswa\Elearning\MateriAjarController@viewMateriAjar');
				Route::get('datatables', 'Siswa\Elearning\MateriAjarController@datatablesMateriAjar');
				Route::get('detail/{id}', 'Siswa\Elearning\MateriAjarController@viewDetailMateriAjar');
			});
		});

		Route::group(array('prefix' => 'bursa-kerja'), function () {

			Route::group(array('prefix' => 'bkk'), function () {

				Route::get('/', 'Alumni\BursaKerja\BKKController@viewBkk');
				Route::get('detail/{id}', 'Alumni\BursaKerja\BKKController@viewDetailBkk');
				Route::get('datatables', 'Alumni\BursaKerja\BKKController@showDatatablesBkk');
			});
		});

		/** ==== MODUL DATA PRIBADI ==== **/
		Route::group(array('prefix' => 'data-pribadi'), function () {

			Route::get('data-siswa', 'Siswa\DataPribadi\DataSiswaController@viewDataSiswa');
			Route::get('data-siswa/view-print-siswa/{nis_nama_siswa}', 'Siswa\DataPribadi\DataSiswaController@viewPrintSiswa');
			Route::post('data-siswa/{id}', 'Siswa\DataPribadi\DataSiswaController@actionUpdateSiswa');
		});

		/** ==== MODUL SKPI ==== **/
		Route::group(array('prefix' => 'skpi'), function () {

			Route::group(array('prefix' => 'data-kegiatan-siswa'), function () {

				Route::get('/', 'Siswa\SKPI\DataKegiatanSiswaController@viewDataKegiatanSiswa');
				Route::get('add', 'Siswa\SKPI\DataKegiatanSiswaController@viewAddDataKegiatanSiswa');
				Route::get('edit/{id}', 'Siswa\SKPI\DataKegiatanSiswaController@viewEditDataKegiatanSiswa');
				Route::post('action/{mode}/{id}', 'Siswa\SKPI\DataKegiatanSiswaController@actionDataKegiatanSiswa');

				Route::get('datatables', 'Siswa\SKPI\DataKegiatanSiswaController@datatablesDataKegiatanSiswa');
			});

			Route::group(array('prefix' => 'data-prestasi-siswa'), function () {

				Route::get('/', 'Siswa\SKPI\DataPrestasiSiswaController@viewDataPrestasiSiswa');
				Route::get('add', 'Siswa\SKPI\DataPrestasiSiswaController@viewAddDataPrestasiSiswa');
				Route::get('edit/{id}', 'Siswa\SKPI\DataPrestasiSiswaController@viewEditDataPrestasiSiswa');
				Route::post('action/{mode}/{id}', 'Siswa\SKPI\DataPrestasiSiswaController@actionDataPrestasiSiswa');

				Route::get('datatables', 'Siswa\SKPI\DataPrestasiSiswaController@datatablesDataPrestasiSiswa');
			});
		});

		/** ==== MODUL AKADEMIK ==== **/
		Route::group(array('prefix' => 'akademik'), function () {

			// MENU Kalender Akademik
			Route::get('kalender-akademik', 'Siswa\Akademik\KalenderAkademikController@viewKalenderAkademik');
			Route::get('kalender-akademik/datatables', 'Siswa\Akademik\KalenderAkademikController@datatablesKalenderAkademik');

			// MENU Jadwal KBM
			Route::get('jadwal-kbm', 'Siswa\Akademik\JadwalKBMController@viewJadwalKBM');
			Route::get('jadwal-kbm/datatables', 'Siswa\Akademik\JadwalKBMController@datatablesJadwalKBM');

			// MENU Jadwal Ujian
			Route::get('jadwal-ujian', 'Siswa\Akademik\JadwalUjianController@viewJadwalUjian');
			Route::get('jadwal-ujian/datatables-uts', 'Siswa\Akademik\JadwalUjianController@datatablesJadwalUTS');
			Route::get('jadwal-ujian/datatables-uas', 'Siswa\Akademik\JadwalUjianController@datatablesJadwalUAS');

			Route::group(array('prefix' => 'jadwal-kelas-daring'), function () {
				Route::get('/', 'Siswa\Akademik\JadwalKelasDaringController@viewJadwalKelasDaring');
				Route::post('datatables', 'Siswa\Akademik\JadwalKelasDaringController@datatablesJadwalKelasDaring');
				Route::get('{id}', 'Siswa\Akademik\JadwalKelasDaringController@viewDetailJadwalKelasDaring');
				Route::get('download/{id}', 'Siswa\Akademik\JadwalKelasDaringController@downloadMateri');
				Route::post('upload-tugas/{id}', 'Siswa\Akademik\JadwalKelasDaringController@uploadTugas');
			});

			// MENU Magang
			Route::get('magang', 'Siswa\Akademik\MagangController@viewMagang');
			Route::get('magang/datatables', 'Siswa\Akademik\MagangController@datatablesMagang');

			Route::group(array('prefix' => 'lihat-absensi'), function () {
				Route::get('/', 'Siswa\Akademik\LihatAbsensiController@viewLihatAbsensi');
				Route::get('/{id_bulan}/{tahun}', 'Siswa\Akademik\LihatAbsensiController@viewLihatAbsensi');
			});
		});

		/** ==== MODUL KEUANGAN ==== **/
		Route::group(array('prefix' => 'keuangan'), function () {

			// MENU Tagihan
			Route::get('tagihan', 'Siswa\Keuangan\TagihanController@viewTagihan');
			Route::get('tagihan/datatables', 'Siswa\Keuangan\TagihanController@datatablesTagihan');

			Route::post('tagihan/generate', 'Siswa\Keuangan\TagihanController@actionGenerate');

			// MENU Riwayat Bayar
			Route::get('riwayat-bayar', 'Siswa\Keuangan\RiwayatBayarController@viewRiwayatBayar');
			Route::get('riwayat-bayar/datatables', 'Siswa\Keuangan\RiwayatBayarController@datatablesRiwayatBayar');
		});

		/** ==== MODUL PELANGGARAN ==== **/
		Route::group(array('prefix' => 'pelanggaran'), function () {

			// MENU Jadwal Ujian
			Route::get('riwayat-pelanggaran', 'Siswa\Pelanggaran\RiwayatPelanggaranController@viewRiwayatPelanggaran');
			Route::get('riwayat-pelanggaran/datatables-non-kbm', 'Siswa\Pelanggaran\RiwayatPelanggaranController@datatablesPelanggaranNonKBM');
			Route::get('riwayat-pelanggaran/datatables-kbm', 'Siswa\Pelanggaran\RiwayatPelanggaranController@datatablesPelanggaranKBM');
		});

		/** ==== MODUL KESISWAAN ==== **/
		Route::group(array('prefix' => 'kesiswaan'), function () {

			//MENU Prestasi
			Route::get('prestasi', 'Siswa\Kesiswaan\PrestasiController@viewPrestasi');
			Route::get('prestasi/datatables', 'Siswa\Kesiswaan\PrestasiController@datatablesPrestasi');

			//MENU Prestasi
			Route::get('beasiswa', 'Siswa\Kesiswaan\BeasiswaController@viewBeasiswa');
			Route::get('beasiswa/datatables', 'Siswa\Kesiswaan\BeasiswaController@datatablesBeasiswa');

			Route::group(array('prefix' => 'absensi-ekskul'), function () {
				Route::get('/', 'Siswa\Kesiswaan\AbsensiEkskulController@viewAbsensiEkskul');
				Route::get('detail/{id_semester}/{id_ekskul}', 'Siswa\Kesiswaan\AbsensiEkskulController@viewDetailAbsensiEkskul');
			});

			Route::group(array('prefix' => 'nilai-ekskul'), function () {
				Route::get('/', 'Siswa\Kesiswaan\NilaiEkskulController@viewNilaiEkskul');
				Route::get('detail/{id_semester}/{id_ekskul}', 'Siswa\Kesiswaan\NilaiEkskulController@viewDetailNilaiEkskul');
			});
		});

		/** ==== MODUL SARANA PRASARANA ==== **/
		Route::group(array('prefix' => 'sarpras'), function () {
			// MENU Komplain Inventaris/Sarpras
			Route::get('komplain-sarpras', 'Siswa\Sarpras\KomplainSarprasController@viewKomplainSarpras');

			Route::post('post-view-ruangan-sarpras', 'Siswa\Sarpras\KomplainSarprasController@actionViewRuanganKomplainSarpras');
			Route::get('komplain-sarpras/ruangan-sarpras/view-ruangan/{id_ruangan}', 'Siswa\Sarpras\KomplainSarprasController@viewRuanganKomplainSarpras');
			Route::get('komplain-sarpras/ruangan-sarpras/datatables/{id_ruangan}', 'Siswa\Sarpras\KomplainSarprasController@datatablesRuanganKomplainSarpras');
			Route::get('komplain-sarpras/ruangan-sarpras/add/{id_ruangan}', 'Siswa\Sarpras\KomplainSarprasController@addRuanganKomplainSarpras');
			Route::get('komplain-sarpras/ruangan-sarpras/edit/{id_ruangan}/{id}', 'Siswa\Sarpras\KomplainSarprasController@editRuanganKomplainSarpras');

			Route::post('post-view-bukualat-sarpras', 'Siswa\Sarpras\KomplainSarprasController@actionViewBukualatKomplainSarpras');
			Route::get('komplain-sarpras/bukualat-sarpras/view-bukualat/{id_buku_alat}', 'Siswa\Sarpras\KomplainSarprasController@viewBukualatKomplainSarpras');
			Route::get('komplain-sarpras/bukualat-sarpras/datatables/{id_buku_alat}', 'Siswa\Sarpras\KomplainSarprasController@datatablesBukualatKomplainSarpras');
			Route::get('komplain-sarpras/bukualat-sarpras/add/{id_buku_alat}', 'Siswa\Sarpras\KomplainSarprasController@addBukualatKomplainSarpras');
			Route::get('komplain-sarpras/bukualat-sarpras/edit/{id_buku_alat}/{id}', 'Siswa\Sarpras\KomplainSarprasController@editBukualatKomplainSarpras');

			Route::post('action-komplain-sarpras/{mode}/{id}', 'Siswa\Sarpras\KomplainSarprasController@actionKomplainSarpras');
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

		Route::group(array('prefix' => 'kesekretariatan'), function () {

			Route::group(array('prefix' => 'dokumen'), function () {
				Route::get('/', 'Guru\Kesekretariatan\DokumenController@viewDokumen');
				Route::get('detail/{id}', 'Guru\Kesekretariatan\DokumenController@viewDetailDokumen');

				Route::post('datatables', 'Guru\Kesekretariatan\DokumenController@datatablesDokumen');
			});
		});
	});
});
