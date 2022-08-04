<?php

// ROLE ALUMNI
Route::group(array('middleware' => ['token_staff']), function () {

	Route::group(array('prefix' => 'humas'), function () {
		Route::get('welcome', 'Humas\WelcomeController@indexWelcome');

		/** ==== MODUL MANAJEMEN FILE ==== **/
		// url: /humas/manajemen-file
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

		Route::group(array('prefix' => 'data-guru'), function () {

			Route::group(array('prefix' => 'data-kegiatan'), function () {

				Route::get('/', 'Guru\Biodata\DataKegiatanController@viewDataKegiatan');
				Route::get('add', 'Guru\Biodata\DataKegiatanController@viewAddDataKegiatan');
				Route::get('edit/{id}', 'Guru\Biodata\DataKegiatanController@viewEditDataKegiatan');
				Route::post('action/{mode}/{id}', 'Guru\Biodata\DataKegiatanController@actionDataKegiatan');

				Route::get('datatables', 'Guru\Biodata\DataKegiatanController@datatablesDataKegiatan');
			});

			Route::group(array('prefix' => 'data-prestasi'), function () {

				Route::get('/', 'Guru\Biodata\DataPrestasiController@viewDataPrestasi');
				Route::get('add', 'Guru\Biodata\DataPrestasiController@viewAddDataPrestasi');
				Route::get('edit/{id}', 'Guru\Biodata\DataPrestasiController@viewEditDataPrestasi');
				Route::post('action/{mode}/{id}', 'Guru\Biodata\DataPrestasiController@actionDataPrestasi');

				Route::get('datatables', 'Guru\Biodata\DataPrestasiController@datatablesDataPrestasi');
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

		// Modul Absensi

		Route::group(array('prefix' => 'absensi'), function () {

			Route::group(array('prefix' => 'shift_pengguna'), function () {

				Route::get('/managementShift', 'Humas\ShiftPengguna\ShiftPenggunaMasterController@viewShiftPenggunaManagement');
				Route::post('/addShiftMaster', 'Humas\ShiftPengguna\ShiftPenggunaMasterController@storeShiftMaster');
				Route::post('/managementShift/{id}/delete', 'Humas\ShiftPengguna\ShiftPenggunaMasterController@destroyShiftMaster');
				Route::get('/', 'Humas\ShiftPengguna\ShiftPenggunaController@viewShiftPengguna');
				Route::get('/add', 'Humas\ShiftPengguna\ShiftPenggunaController@addShiftPengguna');
				Route::post('/add', 'Humas\ShiftPengguna\ShiftPenggunaController@storeShiftPengguna');
				Route::get('/{date}', 'Humas\ShiftPengguna\ShiftPenggunaController@viewShiftPengguna');
				Route::get('/{id_shift_pengguna}/{date}/edit', 'Humas\ShiftPengguna\ShiftPenggunaController@editShiftAbsensi');
				Route::post('/{id_shift_pengguna}/{date}/edit', 'Humas\ShiftPengguna\ShiftPenggunaController@updateShiftAbsensi');
				// Route::get('/addShift', 'Humas\ShiftPengguna\ShiftPenggunaMasterController@addShiftMaster');

			});

			Route::group(array('prefix' => 'histori-absensi'), function () {
				Route::get('export-laravel-mount/{date}', 'Humas\Absensi\HistoriAbsensiController@export_excel_mount');
				Route::get('export-laravel/{date}/{unit_kerja}', 'Humas\Absensi\HistoriAbsensiController@export_excel_day');
				// Route::get('/export-excel/{id_pengguna}/{start_date}/{end_date}/{role}', 'Humas\Absensi\HistoriAbsensiController@export_excel');
				Route::get('/', 'Humas\Absensi\HistoriAbsensiController@viewHistoriAbsensi');
				Route::get('/{date}/{unit_kerja}', 'Humas\Absensi\HistoriAbsensiController@viewHistoriAbsensi');
				Route::get('/{id_pengguna}/{date}/add', 'Humas\Absensi\HistoriAbsensiController@createHistoriAbsensi');
				Route::post('/{id_pengguna}/{date}/add', 'Humas\Absensi\HistoriAbsensiController@storeHistoriAbsensi');
				Route::get('/{id_presensi_pengguna}/{date}/edit', 'Humas\Absensi\HistoriAbsensiController@editHistoriAbsensi');
				Route::post('/{id_presensi_pengguna}/{date}/edit', 'Humas\Absensi\HistoriAbsensiController@updateHistoriAbsensi');
				Route::post('/{id_presensi_pengguna}/delete', 'Humas\Absensi\HistoriAbsensiController@destroyHistoriAbsensi');
			});

			Route::group(array('prefix' => 'manajemen-hari-libur'), function () {

				Route::get('/', 'Humas\ManajemenHariLibur\ManajemenHariLiburController@viewManajemenHariLibur');
				Route::get('/{id}/edit', 'Humas\ManajemenHariLibur\ManajemenHariLiburController@editManajemenHariLibur');
				Route::post('/{id}/edit', 'Humas\ManajemenHariLibur\ManajemenHariLiburController@updateManajemenHariLibur');
				Route::get('/add', 'Humas\ManajemenHariLibur\ManajemenHariLiburController@createManajemenHariLibur');
				Route::post('/add', 'Humas\ManajemenHariLibur\ManajemenHariLiburController@storeManajemenHariLibur');
				Route::post('/{id}/delete', 'Humas\ManajemenHariLibur\ManajemenHariLiburController@destroyManajemenHariLibur');
			});

			Route::group(array('prefix' => 'histori-absensi-siswa'), function () {

				Route::get('/', 'Humas\Absensi\HistoriAbsensiSiswaController@viewHistoriAbsensiSiswa');
				// Route::get('get-kelas/{id_jurusan}', 'Humas\Absensi\HistoriAbsensiSiswaController@getKelas');
				Route::post('/', 'Humas\Absensi\HistoriAbsensiSiswaController@actionDetailHistoriAbsensiSiswa');
				Route::get('/detail/{kelas}/{date}', 'Humas\Absensi\HistoriAbsensiSiswaController@viewDetailHistoriAbsensiSiswa');
				// Route::get('/details/{kelas}/{date}', 'Humas\Absensi\HistoriAbsensiSiswaController@viewDetailsHistoriAbsensiSiswa');
				Route::get('export-laravel-mount/{kelas}/{date}', 'Humas\Absensi\HistoriAbsensiSiswaController@export_excel_mount');
				Route::get('export-laravel/{kelas}/{date}', 'Humas\Absensi\HistoriAbsensiSiswaController@export_excel_day');
				//buat izin / sakit
				Route::get('/{id_pengguna}/{kelas}/{date}/add', 'Humas\Absensi\HistoriAbsensiSiswaController@createHistoriAbsensi');
				Route::post('/{id_pengguna}/{kelas}/{date}/add', 'Humas\Absensi\HistoriAbsensiSiswaController@storeHistoriAbsensi');
				Route::get('/{id_presensi_pengguna}/{kelas}/{date}/edit', 'Humas\Absensi\HistoriAbsensiSiswaController@editHistoriAbsensi');
				Route::post('/{id_presensi_pengguna}/{kelas}/{date}/edit', 'Humas\Absensi\HistoriAbsensiSiswaController@updateHistoriAbsensi');
				Route::post('/{id_presensi_pengguna}/delete', 'Humas\Absensi\HistoriAbsensiSiswaController@destroyHistoriAbsensi');

				//buat generate shift siswa
				Route::get('/addShift/{date1}/{date2}', 'Humas\Absensi\HistoriAbsensiSiswaController@storeShiftPengguna');

			});
			// http://127.0.0.1:8000/humas/absensi/device/datatables
			Route::group(array('prefix' => 'device'), function () {
				// MENU Data Fingerprint
				Route::get('/', 'Administrator\Device\FingerprintController@indexList');
				Route::get('/datatables', 'Administrator\Device\FingerprintController@commonList');
			
			});
		});

		/** ==== MODUL BURSA KERJA ==== **/

		Route::group(array('prefix' => 'bursa-kerja'), function () {

			Route::group(array('prefix' => 'lowongan-kerja'), function () {

				Route::get('/', 'Humas\BursaKerja\LowonganKerjaController@viewLowonganKerja');
				Route::get('add', 'Humas\BursaKerja\LowonganKerjaController@viewAddEditLowonganKerja');
				Route::get('edit/{id}', 'Humas\BursaKerja\LowonganKerjaController@viewAddEditLowonganKerja');
				Route::get('datatables', 'Humas\BursaKerja\LowonganKerjaController@showDatatablesLowonganKerja');
				Route::post('action/{mode}', 'Humas\BursaKerja\LowonganKerjaController@actionLowonganKerja');
				Route::post('action/delete/{id}', 'Humas\BursaKerja\LowonganKerjaController@actionDeleteLowonganKerja');
			});
		});

		/** ==== MODUL KEGIATAN HARIAN ==== **/
		Route::group(array('prefix' => 'kegiatan-harian'), function () {

			Route::group(array('prefix' => 'input-kegiatan'), function () {
				Route::get('/', 'Humas\KegiatanHarian\InputKegiatanController@viewInputKegiatan');
				Route::get('add', 'Humas\KegiatanHarian\InputKegiatanController@viewAddEditInputKegiatan');
				Route::get('edit/{id}', 'Humas\KegiatanHarian\InputKegiatanController@viewAddEditInputKegiatan');
				Route::post('datatables', 'Humas\KegiatanHarian\InputKegiatanController@showDatatablesInputKegiatan');
				Route::post('action/{mode}', 'Humas\KegiatanHarian\InputKegiatanController@actionInputKegiatan');

				Route::group(array('prefix' => 'kategori-pertanyaan'), function () {
					Route::get('detail/{id1}', 'Humas\KegiatanHarian\InputKegiatanController@viewInputKategoriPertanyaan');
					Route::get('{id1}/add', 'Humas\KegiatanHarian\InputKegiatanController@viewAddEditInputKategoriPertanyaan');
					Route::get('{id1}/edit/{id2}', 'Humas\KegiatanHarian\InputKegiatanController@viewAddEditInputKategoriPertanyaan');

					Route::post('{id1}/datatables', 'Humas\KegiatanHarian\InputKegiatanController@showDatatablesInputKategoriPertanyaan');
					Route::post('{id1}/action/{mode}', 'Humas\KegiatanHarian\InputKegiatanController@actionInputKategoriPertanyaan');
				});
			});

			Route::group(array('prefix' => 'input-pertanyaan'), function () {
				Route::get('/', 'Humas\KegiatanHarian\InputPertanyaanController@viewInputPertanyaan');
				Route::get('add', 'Humas\KegiatanHarian\InputPertanyaanController@viewAddEditInputPertanyaan');
				Route::get('edit/{id}', 'Humas\KegiatanHarian\InputPertanyaanController@viewAddEditInputPertanyaan');

				Route::post('datatables', 'Humas\KegiatanHarian\InputPertanyaanController@showDatatablesInputPertanyaan');
				Route::post('action/{mode}', 'Humas\KegiatanHarian\InputPertanyaanController@actionInputPertanyaan');

				Route::group(array('prefix' => 'jawaban'), function () {
					Route::get('detail/{id1}', 'Humas\KegiatanHarian\InputPertanyaanController@viewInputJawaban');
					Route::get('{id1}/add', 'Humas\KegiatanHarian\InputPertanyaanController@viewAddEditInputJawaban');
					Route::get('{id1}/edit/{id2}', 'Humas\KegiatanHarian\InputPertanyaanController@viewAddEditInputJawaban');

					Route::post('{id1}/datatables', 'Humas\KegiatanHarian\InputPertanyaanController@showDatatablesInputJawaban');
					Route::post('{id1}/action/{mode}', 'Humas\KegiatanHarian\InputPertanyaanController@actionInputJawaban');
				});
			});

			// Route::get('rekap-kesehatan', 'Humas\KegiatanHarian\RekapKesehatanController@viewRekapKesehatan');
			Route::get('rekap-kesehatan', 'Humas\KegiatanHarian\RekapKesehatanController@viewRekapFormKesehatan');
			Route::get('rekap-kesehatan/{bulan}/{tahun}', 'Humas\KegiatanHarian\RekapKesehatanController@viewRekapFormKesehatan');
			Route::get('rekap-kesehatan/{bulan}/{tahun}/download', 'Humas\KegiatanHarian\RekapKesehatanController@downloadRekapFormKesehatan');
			Route::get('rekap-kesehatan/detail/form/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');
			Route::get('rekap-kesehatan/user/{id}/{date}', 'Guru\WaliKelas\RekapKesehatanController@viewRekapKesehatanSiswa');

			Route::post('rekap-kesehatan/action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
			Route::post('rekap-kesehatan/datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');
		});

		/** ==== MODUL MONITORING KESEHATAN ==== **/
		Route::group(array('prefix' => 'monitoring-kesehatan'), function () {

			// MENU Rekap Kesehatan Siswa
			Route::get('rekap-kesehatan', 'Guru\GuruPiket\RekapKesehatanController@viewRekapKesehatan');
			Route::get('rekap-kesehatan/user/{id}/{date}', 'Guru\WaliKelas\RekapKesehatanController@viewRekapKesehatanSiswa');
			Route::get('rekap-kesehatan/detail/form/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');

			Route::get('rekap-kesehatan/{id}', 'Guru\GuruPiket\RekapKesehatanController@viewDetailRekapKesehatan');
			Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}', 'Guru\GuruPiket\RekapKesehatanController@viewDetailRekapKesehatan');
			Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}/download', 'Guru\GuruPiket\RekapKesehatanController@downloadDetailRekapKesehatan');


			Route::post('rekap-kesehatan/action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
			Route::post('rekap-kesehatan/datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');
		});

		/** === MODUL MAGANG SISWA === **/
		Route::group(array('prefix' => 'magang-siswa'), function () {
			// MENU Nama Magang
			Route::get('nama-magang', 'Humas\MagangSiswa\MagangSiswaController@viewMagangSiswa');
			Route::get('nama-magang/datatables', 'Humas\MagangSiswa\MagangSiswaController@datatablesMagangSiswa');
			Route::get('nama-magang/add', 'Humas\MagangSiswa\MagangSiswaController@addMagangSiswa');
			Route::get('nama-magang/edit/{id}', 'Humas\MagangSiswa\MagangSiswaController@editMagangSiswa');

			Route::post('action-nama-magang/{mode}/{id}', 'Humas\MagangSiswa\MagangSiswaController@actionMagang');

			// Menu Laporan Magang
			Route::get('laporan-magang', 'Humas\MagangSiswa\LaporanMagangController@viewLaporanMagang');
			Route::get('laporan-magang/datatables', 'Humas\MagangSiswa\LaporanMagangController@datatablesLaporanMagang');
			Route::get('laporan-magang/print/{id_rekanan_magang}/{id_periode_magang}', 'Humas\MagangSiswa\LaporanMagangController@printLaporanMagang');
			Route::get('laporan-magang/input/{id_rekanan_magang}/{id_periode_magang}', 'Humas\MagangSiswa\LaporanMagangController@viewInputLaporanMagang');
			Route::get('laporan-magang/edit/{id_rekanan_magang}/{id_periode_magang}', 'Humas\MagangSiswa\LaporanMagangController@editInputLaporanMagang');
			Route::get('laporan-magang/open/{id_rekanan_magang}/{id_periode_magang}', 'Humas\MagangSiswa\LaporanMagangController@openLink');
			Route::get('laporan-magang/delete/{id_rekanan_magang}/{id_periode_magang}', 'Humas\MagangSiswa\LaporanMagangController@actionDeleteLaporanLinkMagang');

			Route::post('input/action-laporan-input-siswa/{mode}/{id}', 'Humas\MagangSiswa\LaporanMagangController@actionInputLaporanMagang');


			//MENU Periode Magang
			Route::get('periode-magang', 'Humas\MagangSiswa\PeriodeMagangController@viewPeriodeMagang');
			Route::get('periode-magang/datatables', 'Humas\MagangSiswa\PeriodeMagangController@datatablesPeriodeMagang');
			Route::get('periode-magang/add', 'Humas\MagangSiswa\PeriodeMagangController@addPeriodeMagang');
			Route::get('periode-magang/edit/{id}', 'Humas\MagangSiswa\PeriodeMagangController@editPeriodeMagang');

			Route::post('action-periode-magang/{mode}/{id}', 'Humas\MagangSiswa\PeriodeMagangController@actionPeriodeMagang');

			//MENU Rekanan Magang
			Route::get('rekanan-magang', 'Humas\MagangSiswa\RekananMagangController@viewRekananMagang');
			Route::get('rekanan-magang/datatables', 'Humas\MagangSiswa\RekananMagangController@datatablesRekananMagang');
			Route::get('rekanan-magang/add', 'Humas\MagangSiswa\RekananMagangController@addRekananMagang');
			Route::get('rekanan-magang/edit/{id}', 'Humas\MagangSiswa\RekananMagangController@editRekananMagang');
			Route::get('rekanan-magang/import-excel', 'Humas\MagangSiswa\RekananMagangController@importExcel');
			Route::post('rekanan-magang/import-excel', 'Humas\MagangSiswa\RekananMagangController@importExcelAction');

			Route::post('action-rekanan-magang/{mode}/{id}', 'Humas\MagangSiswa\RekananMagangController@actionRekananMagang');

			// Menu Pengajuan Magang
			Route::get('pengajuan-magang', 'Humas\MagangSiswa\PengajuanMagangController@viewPengajuanMagang');
			Route::get('pengajuan-magang/import-excel', 'Humas\MagangSiswa\PengajuanMagangController@importExcel');
			Route::post('pengajuan-magang/import-excel', 'Humas\MagangSiswa\PengajuanMagangController@importExcelAction');
			Route::get('pengajuan-magang/datatables', 'Humas\MagangSiswa\PengajuanMagangController@datatablesPengajuanMagang');
			Route::get('pengajuan-magang/add/{id_rekanan_magang}/{id_periode_magang}', 'Humas\MagangSiswa\PengajuanMagangController@addPengajuanMagang');
			Route::get('pengajuan-magang/datatables-list-siswa/{id_rekanan_magang}/{id_periode_magang}', 'Humas\MagangSiswa\PengajuanMagangController@datatablesListSiswa');
			Route::post('pengajuan-magang/action-pengajuan-magang', 'Humas\MagangSiswa\PengajuanMagangController@actionPengajuanMagang');

			//MENU Pengajuan Siswa Magang
			Route::get('pengajuan-siswa-magang', 'Humas\MagangSiswa\PengajuanSiswaMagangController@viewPengajuanSiswaMagang');
			Route::post('post-view-pengajuan-magang', 'Humas\MagangSiswa\PengajuanSiswaMagangController@actionViewDetailPengajuanMagang');
			Route::get('pengajuan-siswa-magang/view-detail/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', 'Humas\MagangSiswa\PengajuanSiswaMagangController@viewDetailPengajuanMagang');
			Route::get('pengajuan-siswa-magang/datatables/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', 'Humas\MagangSiswa\PengajuanSiswaMagangController@datatablesPengajuanMagang');
			Route::get('pengajuan-siswa-magang/cancel/{id}', 'Humas\MagangSiswa\PengajuanSiswaMagangController@cancelPengajuanMagang');

			Route::post('action-pengajuan-siswa-magang/{mode}/{id}/{id_siswa}/{id_periode_magang}/{id_rekanan_magang}', 'Humas\MagangSiswa\PengajuanSiswaMagangController@actionPengajuanMagang');

			//MENU Approve Siswa Magang
			Route::get('approve-siswa-magang', 'Humas\MagangSiswa\ApproveSiswaMagangController@viewApproveSiswaMagang');
			Route::post('post-view-approve-siswa-magang', 'Humas\MagangSiswa\ApproveSiswaMagangController@actionViewDetailApproveSiswaMagang');
			Route::get('approve-siswa-magang/view-detail/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', 'Humas\MagangSiswa\ApproveSiswaMagangController@viewDetailApproveSiswaMagang');
			Route::get('approve-siswa-magang/datatables/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', 'Humas\MagangSiswa\ApproveSiswaMagangController@datatablesApproveSiswaMagang');

			Route::post('action-approve-siswa-magang/{mode}/{id}/{id_siswa}/{id_periode_magang/{id_rekanan_magang}', 'Humas\MagangSiswa\ApproveSiswaMagangController@actionApproveSiswaMagang');


			//MENU Komponen Nilai Magang
			Route::get('komponen-nilai-magang', 'Humas\MagangSiswa\KomponenNilaiMagangController@viewKomponenNilaiMagang');
			Route::post('post-view-komponen-nilai-magang', 'Humas\MagangSiswa\KomponenNilaiMagangController@actionViewKelasKomponenNilaiMagang');
			Route::get('komponen-nilai-magang/view-periode/{id_periode_magang}', 'Humas\MagangSiswa\KomponenNilaiMagangController@viewKelasKomponenNilaiMagang');
			Route::get('komponen-nilai-magang/datatables/{id_periode_magang}', 'Humas\MagangSiswa\KomponenNilaiMagangController@datatablesKomponenNilaiMagang');
			Route::get('komponen-nilai-magang/add/{id_periode_magang}', 'Humas\MagangSiswa\KomponenNilaiMagangController@addKomponenNilai');
			Route::get('komponen-nilai-magang/edit/{id_periode_magang}/{id}', 'Humas\MagangSiswa\KomponenNilaiMagangController@editKomponenNilai');

			Route::post('action-komponen-nilai-magang/{mode}/{id}', 'Humas\MagangSiswa\KomponenNilaiMagangController@actionKomponenNilaiMagang');

			//MENU Input Nilai
			Route::get('input-nilai-magang', 'Humas\MagangSiswa\InputNilaiMagangController@viewPeriodeMagang');
			Route::post('post-view-input-nilai-magang', 'Humas\MagangSiswa\InputNilaiMagangController@actionViewKomponenInputNilaiMagang');
			Route::get('input-nilai-magang/view-komponen/{id_periode_magang}', 'Humas\MagangSiswa\InputNilaiMagangController@viewKomponenInputNilaiMagang');
			Route::get('input-nilai-magang/datatables/{id_periode_magang}', 'Humas\MagangSiswa\InputNilaiMagangController@datatablesKomponenNilaiMagang');

			Route::post('action-input-nilai-magang/{mode}/{id}', 'Humas\MagangSiswa\InputNilaiMagangController@actionInputNilaiMagang');
		});

		/** === MODUL MAGANG ALUMNI === **/
		Route::namespace('Humas\Alumni')->prefix('alumni')->group(function () {

			// Route::get('/tracer-alumni', 'AlumniController@index');
			// Route::get('/tambah-alumni', 'AlumniController@create');
			// Route::get('/edit/{alumni}', 'AlumniController@show');
			// Route::post('/store', 'AlumniController@store');
			// Route::post('/update/{alumni}', 'AlumniController@update');
			// Route::post('/delete/{alumni}', 'AlumniController@destroy');
			// Route::post('/datatables', 'AlumniController@renderDatatables');

			Route::group(array('prefix' => 'tracer-alumni'), function () {
				Route::get('/', 'TracerAlumniController@viewTracerAlumni');
				Route::get('datatables', 'TracerAlumniController@datatablesTracerAlumni');
				// Route::get('datatables2', 'TracerAlumniController@datatablesTracerAlumni');
				Route::get('add', 'TracerAlumniController@addTracerAlumni');
				Route::get('edit/{id}', 'TracerAlumniController@editTracerAlumni');
				Route::post('action/{mode}/{id}', 'TracerAlumniController@actionTracerAlumni');
				Route::get('cetak','TracerAlumniController@cetakTracerAlumni');
				Route::get('cetak2','TracerAlumniController@cetakTracerAlumni2');
				Route::post('cetak','TracerAlumniController@changeTracerAlumni');
				Route::post('cetak2','TracerAlumniController@changeTracerAlumni2');
				Route::get('cetak/{id_kelas}/{tahun}','TracerAlumniController@cetakTracerAlumni');
				Route::get('cetak2/{id_kelas}/{tahun}','TracerAlumniController@cetakTracerAlumni2');
				Route::get('cetak/datatables/{id_kelas}/{tahun}','TracerAlumniController@datatablesCetakTracerAlumni');
				// Route::get('cetak2/datatables/{id_kelas}/{tahun}','TracerAlumniController@datatablesCetakTracerAlumni2');
				Route::get('export-alumni/{id_kelas}/{tahun}','TracerAlumniController@exportAlumnni');
				Route::get('export-alumni2/{id_kelas}/{tahun}','TracerAlumniController@exportAlumnni2');
				// Route untuk import Excel
				Route::get('excel', 'TracerAlumniController@excelTracerAlumni');
				Route::post('post-file-excel','TracerAlumniController@uploadFileExcel');
				Route::get('download-file-excel','TracerAlumniController@downloadFileExcel')->name('tracer-alumni-smp/download-file-excel');
			});
		});

		Route::group(array('prefix' => 'laporan'), function () {

			Route::group(array('prefix' => 'wali-kelas'), function () {
				Route::get('/', 'Kesiswaan\Laporan\WaliKelasController@viewWaliKelas');
				Route::get('datatables', 'Kesiswaan\Laporan\WaliKelasController@datatablesWaliKelas');
				Route::get('add', 'Kesiswaan\Laporan\WaliKelasController@addWaliKelas');
				Route::get('edit/{id}', 'Kesiswaan\Laporan\WaliKelasController@editWaliKelas');
				Route::get('detail/{id}', 'Kesiswaan\Laporan\WaliKelasController@detailWaliKelas');
				Route::get('detail-ajax/{id}', 'Kesiswaan\Laporan\WaliKelasController@detailAjaxWaliKelas');
				Route::get('detail-datatable/{id}', 'Kesiswaan\Laporan\WaliKelasController@detailDataTable');
				Route::post('action-detail-wali-kelas', 'Kesiswaan\Laporan\WaliKelasController@actionDetailWaliKelas');
				Route::post('action-wali-kelas/{mode}/{id}', 'Kesiswaan\Laporan\WaliKelasController@actionWaliKelas');
			});
		});

		/** === MODUL MAGANG KERJASAMA === **/
		Route::namespace('Humas\Kerjasama')->prefix('kerjasama')->group(function () {
			Route::get('/list', 'KerjasamaController@index');
			Route::get('/add', 'KerjasamaController@create');
			Route::get('/edit/{kerjasama}', 'KerjasamaController@edit');
			Route::post('/store', 'KerjasamaController@store');
			Route::post('/update/{kerjasama}', 'KerjasamaController@update');
			Route::post('/delete/{kerjasama}', 'KerjasamaController@destroy');
			Route::post('/datatables', 'KerjasamaController@renderDatatables');

			Route::group(array('prefix' => 'instansi'), function () {
				Route::get('/', 'InstansiController@index');
				Route::get('/add', 'InstansiController@create');
				Route::get('/edit/{instansi}', 'InstansiController@edit');
				Route::post('/store', 'InstansiController@store');
				Route::post('/update/{instansi}', 'InstansiController@update');
				Route::post('/delete/{instansi}', 'InstansiController@destroy');
				Route::post('/datatables', 'InstansiController@renderDatatables');
			});

			Route::group(array('prefix' => 'jenis'), function () {
				Route::get('/', 'JenisKerjaSamaController@index');
				Route::get('/add', 'JenisKerjaSamaController@create');
				Route::get('/edit/{jenisKerjasama}', 'JenisKerjaSamaController@edit');
				Route::post('/store', 'JenisKerjaSamaController@store');
				Route::post('/update/{jenisKerjasama}', 'JenisKerjaSamaController@update');
				Route::post('/delete/{jenisKerjasama}', 'JenisKerjaSamaController@destroy');
				Route::post('/datatables', 'JenisKerjaSamaController@renderDatatables');
			});

			Route::group(array('prefix' => 'berkas'), function () {
				Route::get('/', 'BerkasKerjasamaController@index');
				Route::get('/add/{kerjasama}', 'BerkasKerjasamaController@create');
				Route::post('/store', 'BerkasKerjasamaController@store');
				Route::post('/delete/{berkasKerjasama}', 'BerkasKerjasamaController@destroy');
				Route::post('/datatables', 'BerkasKerjasamaController@renderDatatables');
			});
		});
	});
});
