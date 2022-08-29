<?php

// ROLE ALUMNI

use App\Http\Controllers\Guru\Biodata\DataKegiatanController;
use App\Http\Controllers\Guru\Biodata\DataPrestasiController;
use App\Http\Controllers\Guru\GuruPiket\RekapKesehatanController as GuruPiketRekapKesehatanController;
use App\Http\Controllers\Guru\WaliKelas\RekapKesehatanController as WaliKelasRekapKesehatanController;
use App\Http\Controllers\Humas\Absensi\HistoriAbsensiController;
use App\Http\Controllers\Humas\Alumni\TracerAlumniController;
use App\Http\Controllers\Humas\BursaKerja\LowonganKerjaController;
use App\Http\Controllers\Humas\KegiatanHarian\InputKegiatanController;
use App\Http\Controllers\Humas\KegiatanHarian\InputPertanyaanController;
use App\Http\Controllers\Humas\KegiatanHarian\RekapKesehatanController;
use App\Http\Controllers\Humas\Kerjasama\BerkasKerjasamaController;
use App\Http\Controllers\Humas\Kerjasama\InstansiController;
use App\Http\Controllers\Humas\Kerjasama\JenisKerjaSamaController;
use App\Http\Controllers\Humas\Kerjasama\KerjasamaController;
use App\Http\Controllers\Humas\MagangSiswa\ApproveSiswaMagangController;
use App\Http\Controllers\Humas\MagangSiswa\InputNilaiMagangController;
use App\Http\Controllers\Humas\MagangSiswa\KomponenNilaiMagangController;
use App\Http\Controllers\Humas\MagangSiswa\LaporanMagangController;
use App\Http\Controllers\Humas\MagangSiswa\MagangSiswaController;
use App\Http\Controllers\Humas\MagangSiswa\PengajuanMagangController;
use App\Http\Controllers\Humas\MagangSiswa\PengajuanSiswaMagangController;
use App\Http\Controllers\Humas\MagangSiswa\PeriodeMagangController;
use App\Http\Controllers\Humas\MagangSiswa\RekananMagangController;
use App\Http\Controllers\Humas\ManajemenHariLibur\ManajemenHariLiburController;
use App\Http\Controllers\Humas\ShiftPengguna\ShiftPenggunaController;
use App\Http\Controllers\Humas\ShiftPengguna\ShiftPenggunaMasterController;
use App\Http\Controllers\Humas\WelcomeController;
use App\Http\Controllers\Kesiswaan\Laporan\WaliKelasController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;

Route::middleware(['token_staff'])->group(function () {

	Route::prefix('humas')->group(function () {
		Route::get('welcome', [WelcomeController::class, 'indexWelcome']);

		Route::prefix('manajemen-file')->group(function () {
			// MENU Data Kategori
			Route::prefix('data-kategori')->group(function () {
				Route::get('/', [DataKategoriController::class, 'viewDataKategori']);
				Route::get('/datatables', [DataKategoriController::class, 'datatablesCategoryfile']);
			});

			// MENU Data Sub Kategori 
			Route::group(array('prefix' => 'data-sub-kategori'), function () {
				Route::get('/', 'ManajemenFile\SubDataKategoriController@viewSubDataKategori');
				Route::get('/add', 'ManajemenFile\SubDataKategoriController@addSubDataKategori');
				Route::get('/datatables', 'ManajemenFile\SubDataKategoriController@datatablesSubCategoryfile');
				Route::get('/edit/{id}', 'ManajemenFile\SubDataKategoriController@editSubDataKategori');

				//action input sub data kategori
				Route::post('action-data-sub-kategori/{mode}/{id}', [SubDataKategoriController::class, 'actionSubDataKategori']);
			});
			Route::prefix('data-file')->group(function () {
				Route::get('/', [DataFileController::class, 'viewDataFile']);
				Route::get('add', [DataFileController::class, 'addDataFile']);
				Route::get('category/{category_file_id}', [DataFileController::class, 'viewDataFileCategory']);
				Route::get('dropdown-category', [DataFileController::class, 'dropdownCategory']);
				Route::get('sub-category/{sub_category_file_id}', [DataFileController::class, 'viewDataFileSubCategory']);

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

			Route::prefix('data-prestasi')->group(function () {
				Route::get('/', [DataPrestasiController::class, 'viewDataPrestasi']);
				Route::get('add', [DataPrestasiController::class, 'viewAddDataPrestasi']);
				Route::get('edit/{id}', [DataPrestasiController::class, 'viewEditDataPrestasi']);
				Route::post('action/{mode}/{id}', [DataPrestasiController::class, 'actionDataPrestasi']);
				Route::get('datatables', [DataPrestasiController::class, 'datatablesDataPrestasi']);
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

		Route::prefix('absensi')->group(function () {
			Route::prefix('shift_pengguna')->group(function () {
				Route::get('/managementShift', [ShiftPenggunaMasterController::class, 'viewShiftPenggunaManagement']);
				Route::post('/addShiftMaster', [ShiftPenggunaMasterController::class, 'storeShiftMaster']);
				Route::post('/managementShift/{id}/delete', [ShiftPenggunaMasterController::class, 'destroyShiftMaster']);

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
				Route::get('export-laravel/{date}', 'Humas\Absensi\HistoriAbsensiController@export_excel_day');
				// Route::get('/export-excel/{id_pengguna}/{start_date}/{end_date}/{role}', 'Humas\Absensi\HistoriAbsensiController@export_excel');
				Route::get('/', 'Humas\Absensi\HistoriAbsensiController@viewHistoriAbsensi');
				Route::get('/{date}', 'Humas\Absensi\HistoriAbsensiController@viewHistoriAbsensi');
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

		Route::prefix('kegiatan-harian')->group(function () {

			Route::prefix('input-kegiatan')->group(function () {
				Route::get('/', [InputKegiatanController::class, 'viewInputKegiatan']);
				Route::get('add', [InputKegiatanController::class, 'viewAddEditInputKegiatan']);
				Route::get('edit/{id}', [InputKegiatanController::class, 'viewAddEditInputKegiatan']);
				Route::post('datatables', [InputKegiatanController::class, 'showDatatablesInputKegiatan']);
				Route::post('action/{mode}', [InputKegiatanController::class, 'actionInputKegiatan']);

				Route::prefix('kategori-pertanyaan')->group(function () {
					Route::get('detail/{id1}', [InputKegiatanController::class, 'viewInputKategoriPertanyaan']);
					Route::get('{id1}/add', [InputKegiatanController::class, 'viewAddEditInputKategoriPertanyaan']);
					Route::get('{id1}/edit/{id2}', [InputKegiatanController::class, 'viewAddEditInputKategoriPertanyaan']);

					Route::post('{id1}/datatables', [InputKegiatanController::class, 'showDatatablesInputKategoriPertanyaan']);
					Route::post('{id1}/action/{mode}', [InputKegiatanController::class, 'actionInputKategoriPertanyaan']);
				});
			});
			Route::prefix('input-pertanyaan')->group(function () {
				Route::get('/', [InputPertanyaanController::class, 'viewInputPertanyaan']);
				Route::get('add', [InputPertanyaanController::class, 'viewAddEditInputPertanyaan']);
				Route::get('edit/{id}', [InputPertanyaanController::class, 'viewAddEditInputPertanyaan']);

				Route::post('datatables', [InputPertanyaanController::class, 'showDatatablesInputPertanyaan']);
				Route::post('action/{mode}', [InputPertanyaanController::class, 'actionInputPertanyaan']);

				Route::prefix('jawaban')->group(function () {
					Route::get('detail/{id1}', [InputPertanyaanController::class, 'viewInputJawaban']);
					Route::get('{id1}/add', [InputPertanyaanController::class, 'viewAddEditInputJawaban']);
					Route::get('{id1}/edit/{id2}', [InputPertanyaanController::class, 'viewAddEditInputJawaban']);

					Route::post('{id1}/datatables', [InputPertanyaanController::class, 'showDatatablesInputJawaban']);
					Route::post('{id1}/action/{mode}', [InputPertanyaanController::class, 'actionInputJawaban']);
				});
			});
			Route::get('rekap-kesehatan', [RekapKesehatanController::class, 'viewRekapFormKesehatan']);
			Route::get('rekap-kesehatan/{bulan}/{tahun}', [RekapKesehatanController::class, 'viewRekapFormKesehatan']);
			Route::get('rekap-kesehatan/{bulan}/{tahun}/download', [RekapKesehatanController::class, 'downloadRekapFormKesehatan']);
			Route::get('rekap-kesehatan/detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);
			Route::get('rekap-kesehatan/user/{id}/{date}', [WaliKelasRekapKesehatanController::class, 'viewRekapKesehatanSiswa']);

			Route::post('rekap-kesehatan/action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
			Route::post('rekap-kesehatan/datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
		});

		Route::prefix('monitoring-kesehatan')->group(function () {
			Route::get('rekap-kesehatan', [GuruPiketRekapKesehatanController::class,  'viewRekapKesehatan']);
			Route::get('rekap-kesehatan/user/{id}/{date}', [WaliKelasRekapKesehatanController::class, 'viewRekapKesehatanSiswa']);
			Route::get('rekap-kesehatan/detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

			Route::get('rekap-kesehatan/{id}', [GuruPiketRekapKesehatanController::class, 'viewDetailRekapKesehatan']);
			Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}', [GuruPiketRekapKesehatanController::class, 'viewDetailRekapKesehatan']);
			Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}/download', [GuruPiketRekapKesehatanController::class, 'downloadDetailRekapKesehatan']);


			Route::post('rekap-kesehatan/action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
			Route::post('rekap-kesehatan/datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
		});

		Route::prefix('magang-siswa')->group(function () {
			// Menu nama magang
			Route::get('nama-magang', [MagangSiswaController::class, 'viewMagangSiswa']);
			Route::get('nama-magang/datatables', [MagangSiswaController::class, 'datatablesMagangSiswa']);
			Route::get('nama-magang/add', [MagangSiswaController::class, 'addMagangSiswa']);
			Route::get('nama-magang/edit/{id}', [MagangSiswaController::class, 'editMagangSiswa']);

			Route::post('action-nama-magang/{mode}/{id}', [MagangSiswaController::class, 'actionMagang']);

			// Menu Laporan Magang
			Route::get('laporan-magang', [LaporanMagangController::class, 'viewLaporanMagang']);
			Route::get('laporan-magang/datatables', [LaporanMagangController::class, 'datatablesLaporanMagang']);
			Route::get('laporan-magang/print/{id_rekanan_magang}/{id_periode_magang}', [LaporanMagangController::class, 'printLaporanMagang']);
			Route::get('laporan-magang/input/{id_rekanan_magang}/{id_periode_magang}', [LaporanMagangController::class, 'viewInputLaporanMagang']);
			Route::get('laporan-magang/edit/{id_rekanan_magang}/{id_periode_magang}', [LaporanMagangController::class, 'editInputLaporanMagang']);
			Route::get('laporan-magang/open/{id_rekanan_magang}/{id_periode_magang}', [LaporanMagangController::class, 'openLink']);
			Route::get('laporan-magang/delete/{id_rekanan_magang}/{id_periode_magang}', [LaporanMagangController::class, 'actionDeleteLaporanLinkMagang']);

			Route::post('input/action-laporan-input-siswa/{mode}/{id}', [LaporanMagangController::class, 'actionInputLaporanMagang']);

			//MENU Periode Magang
			Route::get('periode-magang', [PeriodeMagangController::class, 'viewPeriodeMagang']);
			Route::get('periode-magang/datatables', [PeriodeMagangController::class, 'datatablesPeriodeMagang']);
			Route::get('periode-magang/add', [PeriodeMagangController::class, 'addPeriodeMagang']);
			Route::get('periode-magang/edit/{id}', [PeriodeMagangController::class, 'editPeriodeMagang']);

			Route::post('action-periode-magang/{mode}/{id}', [PeriodeMagangController::class, 'actionPeriodeMagang']);

			//MENU Rekanan Magang
			Route::get('rekanan-magang', [RekananMagangController::class, 'viewRekananMagang']);
			Route::get('rekanan-magang/datatables', [RekananMagangController::class, 'datatablesRekananMagang']);
			Route::get('rekanan-magang/add', [RekananMagangController::class, 'addRekananMagang']);
			Route::get('rekanan-magang/edit/{id}', [RekananMagangController::class, 'editRekananMagang']);
			Route::get('rekanan-magang/import-excel', [RekananMagangController::class, 'importExcel']);
			Route::post('rekanan-magang/import-excel', [RekananMagangController::class, 'importExcelAction']);

			Route::post('action-rekanan-magang/{mode}/{id}', [RekananMagangController::class, 'actionRekananMagang']);

			// Menu Pengajuan Magang
			Route::get('pengajuan-magang', [PengajuanMagangController::class, 'viewPengajuanMagang']);
			Route::get('pengajuan-magang/import-excel', [PengajuanMagangController::class, 'importExcel']);
			Route::post('pengajuan-magang/import-excel', [PengajuanMagangController::class, 'importExcelAction']);
			Route::get('pengajuan-magang/datatables', [PengajuanMagangController::class, 'datatablesPengajuanMagang']);
			Route::get('pengajuan-magang/add/{id_rekanan_magang}/{id_periode_magang}', [PengajuanMagangController::class, 'addPengajuanMagang']);
			Route::get('pengajuan-magang/datatables-list-siswa/{id_rekanan_magang}/{id_periode_magang}', [PengajuanMagangController::class, 'datatablesListSiswa']);
			Route::post('pengajuan-magang/action-pengajuan-magang', [PengajuanMagangController::class, 'actionPengajuanMagang']);

			//MENU Pengajuan Siswa Magang
			Route::get('pengajuan-siswa-magang', [PengajuanSiswaMagangController::class, 'viewPengajuanSiswaMagang']);
			Route::post('post-view-pengajuan-magang', [PengajuanSiswaMagangController::class, 'actionViewDetailPengajuanMagang']);
			Route::get('pengajuan-siswa-magang/view-detail/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', [PengajuanSiswaMagangController::class, 'viewDetailPengajuanMagang']);
			Route::get('pengajuan-siswa-magang/datatables/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', [PengajuanSiswaMagangController::class, 'datatablesPengajuanMagang']);
			Route::get('pengajuan-siswa-magang/cancel/{id}', [PengajuanSiswaMagangController::class, 'cancelPengajuanMagang']);

			Route::post('action-pengajuan-siswa-magang/{mode}/{id}/{id_siswa}/{id_periode_magang}/{id_rekanan_magang}', [PengajuanSiswaMagangController::class, 'actionPengajuanMagang']);

			//MENU Approve Siswa Magang
			Route::get('approve-siswa-magang', [ApproveSiswaMagangController::class, 'viewApproveSiswaMagang']);
			Route::post('post-view-approve-siswa-magang', [ApproveSiswaMagangController::class, 'actionViewDetailApproveSiswaMagang']);
			Route::get('approve-siswa-magang/view-detail/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', [ApproveSiswaMagangController::class, 'viewDetailApproveSiswaMagang']);
			Route::get('approve-siswa-magang/datatables/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', [ApproveSiswaMagangController::class, 'datatablesApproveSiswaMagang']);

			Route::post('action-approve-siswa-magang/{mode}/{id}/{id_siswa}/{id_periode_magang/{id_rekanan_magang}', [ApproveSiswaMagangController::class, 'actionApproveSiswaMagang']);

			//MENU Komponen Nilai Magang
			Route::get('komponen-nilai-magang', [KomponenNilaiMagangController::class, 'viewKomponenNilaiMagang']);
			Route::post('post-view-komponen-nilai-magang', [KomponenNilaiMagangController::class, 'actionViewKelasKomponenNilaiMagang']);
			Route::get('komponen-nilai-magang/view-periode/{id_periode_magang}', [KomponenNilaiMagangController::class, 'viewKelasKomponenNilaiMagang']);
			Route::get('komponen-nilai-magang/datatables/{id_periode_magang}', [KomponenNilaiMagangController::class, 'datatablesKomponenNilaiMagang']);
			Route::get('komponen-nilai-magang/add/{id_periode_magang}', [KomponenNilaiMagangController::class, 'addKomponenNilai']);
			Route::get('komponen-nilai-magang/edit/{id_periode_magang}/{id}', [KomponenNilaiMagangController::class, 'editKomponenNilai']);

			Route::post('action-komponen-nilai-magang/{mode}/{id}', [KomponenNilaiMagangController::class, 'actionKomponenNilaiMagang']);

			//MENU Input Nilai
			Route::get('input-nilai-magang', [InputNilaiMagangController::class, 'viewPeriodeMagang']);
			Route::post('post-view-input-nilai-magang', [InputNilaiMagangController::class, 'actionViewKomponenInputNilaiMagang']);
			Route::get('input-nilai-magang/view-komponen/{id_periode_magang}', [InputNilaiMagangController::class, 'viewKomponenInputNilaiMagang']);
			Route::get('input-nilai-magang/datatables/{id_periode_magang}', [InputNilaiMagangController::class, 'datatablesKomponenNilaiMagang']);

			Route::post('action-input-nilai-magang/{mode}/{id}', [InputNilaiMagangController::class, 'actionInputNilaiMagang']);
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

			});
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

		/** === MODUL MAGANG KERJASAMA === **/
		Route::namespace('Humas\Kerjasama')->prefix('kerjasama')->group(function () {
			Route::get('/list', [KerjasamaController::class, 'index']);
			Route::get('/add', [KerjasamaController::class, 'create']);
			Route::get('/edit/{kerjasama}', [KerjasamaController::class, 'edit']);
			Route::post('/store', [KerjasamaController::class, 'store']);
			Route::post('/update/{kerjasama}', [KerjasamaController::class, 'update']);
			Route::post('/delete/{kerjasama}', [KerjasamaController::class, 'destroy']);
			Route::post('/datatables', [KerjasamaController::class, 'renderDatatables']);

			Route::prefix('instansi')->group(function () {
				Route::get('/', [InstansiController::class, 'index']);
				Route::get('/add', [InstansiController::class, 'create']);
				Route::get('/edit/{instansi}', [InstansiController::class, 'edit']);
				Route::post('/store', [InstansiController::class, 'store']);
				Route::post('/update/{instansi}', [InstansiController::class, 'update']);
				Route::post('/delete/{instansi}', [InstansiController::class, 'destroy']);
				Route::post('/datatables', [InstansiController::class, 'renderDatatables']);
			});

			Route::prefix('jenis')->group(function () {
				Route::get('/', [JenisKerjaSamaController::class, 'index']);
				Route::get('/add', [JenisKerjaSamaController::class, 'create']);
				Route::get('/edit/{jenisKerjasama}', [JenisKerjaSamaController::class, 'edit']);
				Route::post('/store', [JenisKerjaSamaController::class, 'store']);
				Route::post('/update/{jenisKerjasama}', [JenisKerjaSamaController::class, 'update']);
				Route::post('/delete/{jenisKerjasama}', [JenisKerjaSamaController::class, 'destroy']);
				Route::post('/datatables', [JenisKerjaSamaController::class, 'renderDatatables']);
			});
			Route::prefix('berkas')->group(function () {
				Route::get('/', [BerkasKerjasamaController::class, 'index']);
				Route::get('/add/{kerjasama}', [BerkasKerjasamaController::class, 'create']);
				Route::post('/store', [BerkasKerjasamaController::class, 'store']);
				Route::post('/delete/{berkasKerjasama}', [BerkasKerjasamaController::class, 'destroy']);
				Route::post('/datatables', [BerkasKerjasamaController::class, 'renderDatatables']);
			});
		});
	});
});
