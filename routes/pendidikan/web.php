<?php
// ROLE PENDIDIKAN
Route::group(array('middleware'=> ['token_staff']), function() {
	// url: /pendidikan
	Route::group(array('prefix' => 'pendidikan'), function() {
		Route::get('welcome', 'Pendidikan\WelcomeController@indexWelcome'); //addresbar, namafoldercontroller @ fungsi

		/** ==== MODUL DATA AKADEMIK ==== **/
		// url: /pendidikan/data-akademik
		Route::group(array('prefix' => 'data-akademik'), function() {
			// MENU Nilai Mutu
			// url: /pendidikan/data-akademik/nilai-mutu
			Route::get('nilai-mutu', 'Pendidikan\DataAkademik\NilaiMutuController@viewNilaiMutu');
			Route::get('nilai-mutu/datatables', 'Pendidikan\DataAkademik\NilaiMutuController@datatablesNilaiMutu');
			Route::get('nilai-mutu/add', 'Pendidikan\DataAkademik\NilaiMutuController@addNilaiMutu');
			Route::get('nilai-mutu/edit/{id}', 'Pendidikan\DataAkademik\NilaiMutuController@editNilaiMutu');

			Route::post('action-nilai-mutu/{mode}/{id}', 'Pendidikan\DataAkademik\NilaiMutuController@actionNilaiMutu');

			// MENU Rentang Nilai Mutu
			Route::get('rentang-nilai-mutu', 'Pendidikan\DataAkademik\RentangNilaiMutuController@viewRentangNilaiMutu');
			Route::get('rentang-nilai-mutu/datatables', 'Pendidikan\DataAkademik\RentangNilaiMutuController@datatablesRentangNilaiMutu');
			Route::get('rentang-nilai-mutu/add', 'Pendidikan\DataAkademik\RentangNilaiMutuController@addRentangNilaiMutu');
			Route::get('rentang-nilai-mutu/edit/{id}', 'Pendidikan\DataAkademik\RentangNilaiMutuController@editRentangNilaiMutu');

			Route::post('action-rentang-nilai-mutu/{mode}/{id}', 'Pendidikan\DataAkademik\RentangNilaiMutuController@actionRentangNilaiMutu');

			// MENU Jam KBM
			Route::get('jam-kbm', 'Pendidikan\DataAkademik\JamKBMController@viewJamKBM');
			Route::get('jam-kbm/datatables', 'Pendidikan\DataAkademik\JamKBMController@datatablesJamKBM');
			Route::get('jam-kbm/add', 'Pendidikan\DataAkademik\JamKBMController@addJamKBM');
			Route::get('jam-kbm/edit/{id}', 'Pendidikan\DataAkademik\JamKBMController@editJamKBM');

			Route::post('action-jam-kbm/{mode}/{id}', 'Pendidikan\DataAkademik\JamKBMController@actionJamKBM');

			// MENU Data Nama Semester
			Route::get('nama-semester', 'Pendidikan\DataAkademik\NamaSemesterController@viewNamaSemester');
			Route::get('nama-semester/datatables', 'Pendidikan\DataAkademik\NamaSemesterController@datatablesNamaSemester');
			Route::get('nama-semester/add', 'Pendidikan\DataAkademik\NamaSemesterController@addNamaSemester');
			Route::get('nama-semester/edit/{id}', 'Pendidikan\DataAkademik\NamaSemesterController@editNamaSemester');

			Route::post('action-nama-semester/{mode}/{id}', 'Pendidikan\DataAkademik\NamaSemesterController@actionNamaSemester');

			// MENU Data Status Siswa
			Route::get('status-siswa', 'Pendidikan\DataAkademik\StatusSiswaController@viewStatusSiswa');
			Route::get('status-siswa/datatables', 'Pendidikan\DataAkademik\StatusSiswaController@datatablesStatusSiswa');
			Route::get('status-siswa/add', 'Pendidikan\DataAkademik\StatusSiswaController@addStatusSiswa');
			Route::get('status-siswa/edit/{id}', 'Pendidikan\DataAkademik\StatusSiswaController@editStatusSiswa');

			Route::post('action-status-siswa/{mode}/{id}', 'Pendidikan\DataAkademik\StatusSiswaController@actionStatusSiswa');

			// MENU Data Jalur
			Route::get('jalur', 'Pendidikan\DataAkademik\JalurController@viewJalur');
			Route::get('jalur/datatables', 'Pendidikan\DataAkademik\JalurController@datatablesJalur');
			Route::get('jalur/add', 'Pendidikan\DataAkademik\JalurController@addJalur');
			Route::get('jalur/edit/{id}', 'Pendidikan\DataAkademik\JalurController@editJalur');

			Route::post('action-jalur/{mode}/{id}', 'Pendidikan\DataAkademik\JalurController@actionJalur');

			// MENU Data Kegiatan
			Route::get('kegiatan', 'Pendidikan\DataAkademik\KegiatanController@viewKegiatan');
			Route::get('kegiatan/datatables', 'Pendidikan\DataAkademik\KegiatanController@datatablesKegiatan');
			Route::get('kegiatan/add', 'Pendidikan\DataAkademik\KegiatanController@addKegiatan');
			Route::get('kegiatan/edit/{id}', 'Pendidikan\DataAkademik\KegiatanController@editKegiatan');

			Route::post('action-kegiatan/{mode}/{id}', 'Pendidikan\DataAkademik\KegiatanController@actionKegiatan');

			// MENU Kalender Akademik
			Route::get('kalender-akademik', 'Pendidikan\DataAkademik\KalenderAkademikController@viewKalenderAkademik');
			Route::post('post-view-kalender-akademik', 'Pendidikan\DataAkademik\KalenderAkademikController@actionViewSemesterKalenderAkademik');
			Route::get('kalender-akademik/view-semester/{id_semester}', 'Pendidikan\DataAkademik\KalenderAkademikController@viewSemesterKalenderAkademik');
			Route::get('kalender-akademik/datatables/{id_semester}', 'Pendidikan\DataAkademik\KalenderAkademikController@datatablesKalenderAkademik');
			Route::get('kalender-akademik/add/{id_semester}', 'Pendidikan\DataAkademik\KalenderAkademikController@addKalenderAkademik');
			Route::get('kalender-akademik/edit/{id_semester}/{id}', 'Pendidikan\DataAkademik\KalenderAkademikController@editKalenderAkademik');

			Route::post('action-kalender-akademik/{mode}/{id}', 'Pendidikan\DataAkademik\KalenderAkademikController@actionKalenderAkademik');

			// MENU Data Jurusan
			Route::get('jurusan', 'Pendidikan\DataAkademik\JurusanController@viewJurusan');
			Route::get('jurusan/datatables', 'Pendidikan\DataAkademik\JurusanController@datatablesJurusan');
			Route::get('jurusan/add', 'Pendidikan\DataAkademik\JurusanController@addJurusan');
			Route::get('jurusan/edit/{id}', 'Pendidikan\DataAkademik\JurusanController@editJurusan');

			Route::post('action-jurusan/{mode}/{id}', 'Pendidikan\DataAkademik\JurusanController@actionJurusan');
		});
		Route::group(array('prefix' => 'data-sekolah'), function() {
			//MENU Input Data Sekolah
			Route::get('input-data-sekolah', 'Pendidikan\DataSekolah\InputDataSekolahController@viewInputDataSekolah');
			Route::get('data-sekolah/get-kota/{alamat_provinsi}', 'Pendidikan\DataSekolah\InputDataSekolahController@getKota');
			
			Route::post('action-input-data-sekolah/{mode}/{id}', 'Pendidikan\DataSekolah\InputDataSekolahController@actionInputDataSekolah');


		});

		/** ==== MODUL SETTING KELAS ==== **/
		Route::group(array('prefix' => 'setting-kelas'), function() {
			// MENU Data Kelas
			Route::get('kelas', 'Pendidikan\SettingKelas\KelasController@viewKelas');
			Route::get('kelas/datatables', 'Pendidikan\SettingKelas\KelasController@datatablesKelas');
			Route::get('kelas/add', 'Pendidikan\SettingKelas\KelasController@addKelas');
			Route::get('kelas/edit/{id}', 'Pendidikan\SettingKelas\KelasController@editKelas');

			Route::post('action-kelas/{mode}/{id}', 'Pendidikan\SettingKelas\KelasController@actionKelas');

			// MENU Setting Wali Kelas
			Route::get('wali-kelas', 'Pendidikan\SettingKelas\WaliKelasController@viewWaliKelas');
			Route::post('post-view-wali-kelas', 'Pendidikan\SettingKelas\WaliKelasController@actionViewWaliKelas');
			Route::get('wali-kelas/view-kelas/{id_kelas}', 'Pendidikan\SettingKelas\WaliKelasController@viewKelasWaliKelas');
			Route::get('wali-kelas/datatables/{id_kelas}', 'Pendidikan\SettingKelas\WaliKelasController@datatablesWaliKelas');
			Route::get('wali-kelas/add/{id_kelas}', 'Pendidikan\SettingKelas\WaliKelasController@addWaliKelas');
			Route::get('wali-kelas/edit/{id_kelas}/{id_semester}/{id}', 'Pendidikan\SettingKelas\WaliKelasController@editWaliKelas');

			Route::post('action-wali-kelas/{mode}/{id}', 'Pendidikan\SettingKelas\WaliKelasController@actionWaliKelas');

			// MENU Setting Sekretaris Kelas
			Route::get('sekretaris-kelas', 'Pendidikan\SettingKelas\SekretarisKelasController@viewSekretarisKelas');
			Route::post('post-view-sekretaris-kelas', 'Pendidikan\SettingKelas\SekretarisKelasController@actionViewSekretarisKelas');
			Route::get('sekretaris-kelas/view-kelas/{id_kelas}', 'Pendidikan\SettingKelas\SekretarisKelasController@viewKelasSekretarisKelas');
			Route::get('sekretaris-kelas/datatables/{id_kelas}', 'Pendidikan\SettingKelas\SekretarisKelasController@datatablesSekretarisKelas');
			Route::get('sekretaris-kelas/add/{id_kelas}', 'Pendidikan\SettingKelas\SekretarisKelasController@addSekretarisKelas');
			Route::get('sekretaris-kelas/edit/{id_kelas}/{id_semester}/{id}', 'Pendidikan\SettingKelas\SekretarisKelasController@editSekretarisKelas');

			Route::post('action-sekretaris-kelas/{mode}/{id}', 'Pendidikan\SettingKelas\SekretarisKelasController@actionSekretarisKelas');

			// MENU Setting Ruangan Kelas
			Route::get('ruangan-kelas', 'Pendidikan\SettingKelas\RuanganKelasController@viewRuanganKelas');
			Route::post('post-view-ruangan-kelas', 'Pendidikan\SettingKelas\RuanganKelasController@actionViewRuanganKelas');
			Route::get('ruangan-kelas/view-kelas/{id_kelas}', 'Pendidikan\SettingKelas\RuanganKelasController@viewKelasRuanganKelas');
			Route::get('ruangan-kelas/datatables/{id_kelas}', 'Pendidikan\SettingKelas\RuanganKelasController@datatablesRuanganKelas');
			Route::get('ruangan-kelas/add/{id_kelas}', 'Pendidikan\SettingKelas\RuanganKelasController@addRuanganKelas');
			Route::get('ruangan-kelas/edit/{id_kelas}/{id_semester}/{id}', 'Pendidikan\SettingKelas\RuanganKelasController@editRuanganKelas');

			Route::post('action-ruangan-kelas/{mode}/{id}', 'Pendidikan\SettingKelas\RuanganKelasController@actionRuanganKelas');



		});

		/** ==== MODUL PENDAFTARAN ==== **/
		Route::group(array('prefix' => 'pendaftaran'), function() {
			// MENU Data Penerimaan (ambil dari Role PPDB)
			Route::get('penerimaan', 'PPDB\Pendaftaran\PenerimaanController@viewPenerimaan');
			Route::get('penerimaan/datatables', 'PPDB\Pendaftaran\PenerimaanController@datatablesPenerimaan');
			Route::get('penerimaan/add', 'PPDB\Pendaftaran\PenerimaanController@addPenerimaan');
			Route::get('penerimaan/edit/{id}', 'PPDB\Pendaftaran\PenerimaanController@editPenerimaan');

			Route::post('action-penerimaan/{mode}/{id}', 'PPDB\Pendaftaran\PenerimaanController@actionPenerimaan');
		});

		/** === MODUL SISWA === **/
		Route::group(array('prefix' => 'siswa'), function() {
			// MENU DATA SISWA
			Route::get('data-siswa', 'Pendidikan\Siswa\DataSiswaController@viewDataSiswa');
			Route::get('data-siswa/get-kelas/{id_jurusan}', 'Pendidikan\Siswa\DataSiswaController@getKelas');
			Route::post('post-view-data-siswa', 'Pendidikan\Siswa\DataSiswaController@actionViewDataSiswa');
			Route::get('data-siswa/view-detail-data-siswa/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', 'Pendidikan\Siswa\DataSiswaController@viewDetailDataSiswa');
			Route::get('data-siswa/datatables/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', 'Pendidikan\Siswa\DataSiswaController@datatablesDataSiswa');

			//MENU UPLOAD DATA SISWA
			Route::get('upload-data-siswa', 'Pendidikan\Siswa\UploadDataSiswaController@viewUploadDataSiswa');
			Route::get('/download-file-excel', 'Pendidikan\Siswa\UploadDataSiswaController@downloadFileExcel')->name('siswa/download-file-excel');
			Route::post('post-file-excel', 'Pendidikan\Siswa\UploadDataSiswaController@uploadFileExcel');

			//MENU CARI SISWA
			Route::get('cari-siswa', 'Pendidikan\Siswa\CariSiswaController@viewCariSiswa');
			Route::post('post-view-cari-siswa', 'Pendidikan\Siswa\CariSiswaController@actionViewCariSiswa');
			Route::get('cari-siswa/view-detail/{nis_nama_siswa}', 'Pendidikan\Siswa\CariSiswaController@viewDetailCariSiswa');
			Route::get('cari-siswa/datatables/{nis_nama_siswa}', 'Pendidikan\Siswa\CariSiswaController@datatablesCariSiswa');
			Route::get('cari-siswa/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', 'Pendidikan\Siswa\CariSiswaController@viewDetailSiswaCariSiswa');

			//MENU ADMISI SISWA
			Route::get('admisi-siswa', 'Pendidikan\Siswa\AdmisiSiswaController@viewAdmisiSiswa');
			Route::post('post-view-admisi-siswa', 'Pendidikan\Siswa\AdmisiSiswaController@actionViewAdmisiSiswa');
			Route::get('admisi-siswa/view-detail/{nis_nama_siswa}', 'Pendidikan\Siswa\AdmisiSiswaController@viewDetailAdmisiSiswa');
			Route::post('action-admisi-siswa', 'Pendidikan\Siswa\AdmisiSiswaController@actionAdmisiSiswa');
			Route::get('admisi-siswa/generate', 'Pendidikan\Siswa\AdmisiSiswaController@generateAdmisiSiswa');
			Route::post('action-generate-admisi-siswa', 'Pendidikan\Siswa\AdmisiSiswaController@actionGenerateAdmisiSiswa');
			Route::get('admisi-siswa/generate/laporan', 'Pendidikan\Siswa\AdmisiSiswaController@laporanGenerateAdmisiSiswa');
			Route::post('post-view-laporan-admisi-siswa', 'Pendidikan\Siswa\AdmisiSiswaController@actionViewLaporanAdmisiSiswa');
			Route::get('admisi-siswa/view-laporan/{id_semester}/{id_kelas}', 'Pendidikan\Siswa\AdmisiSiswaController@viewLaporanAdmisiSiswa');
			Route::get('admisi-siswa/datatables/{id_semester}/{id_kelas}', 'Pendidikan\Siswa\AdmisiSiswaController@datatablesAdmisiSiswa');


			//MENU HISTORY ADMISI SISWA
			Route::get('histori-admisi-siswa', 'Pendidikan\Siswa\HistoryAdmisiSiswaController@viewHistoryAdmisiSiswa');
			Route::post('post-view-histori-admisi-siswa', 'Pendidikan\Siswa\HistoryAdmisiSiswaController@actionViewHistoryAdmisiSiswa');
			Route::get('histori-admisi-siswa/view-detail/{nis_nama_siswa}', 'Pendidikan\Siswa\HistoryAdmisiSiswaController@viewDetailHistoryAdmisiSiswa');
			Route::get('histori-admisi-siswa/datatables/{nis_siswa}', 'Pendidikan\Siswa\HistoryAdmisiSiswaController@datatablesHistoryAdmisiSiswa');
			Route::post('action-histori-admisi-siswa/{mode}/{id}', 'Pendidikan\Siswa\HistoryAdmisiSiswaController@actionHistoryAdmisiSiswa');

			//MENU SISWA AKTIF
			// Route::get('siswa-aktif', 'Pendidikan\Siswa\SiswaAktifController@viewSiswaAktif');

			//MENU INSERT-UPDATE SISWA
			Route::get('insert-update-siswa', 'Pendidikan\Siswa\InsertUpdateSiswaController@viewInsertUpdateSiswa');
			Route::post('post-view-update-siswa', 'Pendidikan\Siswa\InsertUpdateSiswaController@actionViewUpdateSiswa');
			Route::get('insert-update-siswa/view-detail/{nis_nama_siswa}', 'Pendidikan\Siswa\InsertUpdateSiswaController@viewDetailUpdateSiswa');

			Route::post('action-insert-update-siswa/{mode}/{id}', 'Pendidikan\Siswa\InsertUpdateSiswaController@actionInsertUpdateSiswa');

			//MENU Setting Wali Murid
			Route::get('setting-wali-murid', 'Pendidikan\Siswa\SettingWaliMuridController@viewSettingWaliMurid');
			Route::post('post-view-setting-wali-murid', 'Pendidikan\Siswa\SettingWaliMuridController@actionViewSettingWaliMurid');
			Route::get('setting-wali-murid/view-kelas/{id_kelas}', 'Pendidikan\Siswa\SettingWaliMuridController@viewKelasWaliMurid');
			Route::get('setting-wali-murid/datatables/{id_kelas}', 'Pendidikan\Siswa\SettingWaliMuridController@datatablesWaliMurid');
			Route::get('setting-wali-murid/edit/{id}', 'Pendidikan\Siswa\SettingWaliMuridController@editWaliMurid');

			Route::post('action-setting-wali-murid/{mode}/{id}', 'Pendidikan\Siswa\SettingWaliMuridController@actionSettingWaliMurid');

			//MENU Setting Kelas Siswa
			Route::get('setting-kelas-siswa', 'Pendidikan\Siswa\SettingKelasSiswaController@viewSettingKelasSiswa');
			Route::post('post-view-setting-kelas-siswa', 'Pendidikan\Siswa\SettingKelasSiswaController@actionViewSettingKelasSiswa');
			Route::get('setting-kelas-siswa/view-kelas/{id_kelas}', 'Pendidikan\Siswa\SettingKelasSiswaController@viewKelasSettingKelas');
			Route::get('setting-kelas-siswa/datatables/{id_kelas}', 'Pendidikan\Siswa\SettingKelasSiswaController@datatablesKelasSiswa');
			Route::get('setting-kelas-siswa/datatables-siswa', 'Pendidikan\Siswa\SettingKelasSiswaController@datatablesSiswa');
			Route::get('setting-kelas-siswa/edit/{id}', 'Pendidikan\Siswa\SettingKelasSiswaController@tambahKelasSiswa');

			Route::post('action-setting-kelas-siswa/{mode}/{id}', 'Pendidikan\Siswa\SettingKelasSiswaController@actionSettingKelasSiswa');
		});
		/** === MODUL MAGANG SISWA === **/
		Route::group(array('prefix' => 'magang-siswa'), function() {
			// MENU Nama Magang
			Route::get('nama-magang', 'Pendidikan\MagangSiswa\MagangSiswaController@viewMagangSiswa');
			Route::get('nama-magang/datatables', 'Pendidikan\MagangSiswa\MagangSiswaController@datatablesMagangSiswa');
			Route::get('nama-magang/add', 'Pendidikan\MagangSiswa\MagangSiswaController@addMagangSiswa');
			Route::get('nama-magang/edit/{id}', 'Pendidikan\MagangSiswa\MagangSiswaController@editMagangSiswa');

			Route::post('action-nama-magang/{mode}/{id}', 'Pendidikan\MagangSiswa\MagangSiswaController@actionMagang');

			//MENU Periode Magang
			Route::get('periode-magang', 'Pendidikan\MagangSiswa\PeriodeMagangController@viewPeriodeMagang');
			Route::get('periode-magang/datatables', 'Pendidikan\MagangSiswa\PeriodeMagangController@datatablesPeriodeMagang');
			Route::get('periode-magang/add', 'Pendidikan\MagangSiswa\PeriodeMagangController@addPeriodeMagang');
			Route::get('periode-magang/edit/{id}', 'Pendidikan\MagangSiswa\PeriodeMagangController@editPeriodeMagang');

			Route::post('action-periode-magang/{mode}/{id}', 'Pendidikan\MagangSiswa\PeriodeMagangController@actionPeriodeMagang');

			//MENU Rekanan Magang
			Route::get('rekanan-magang', 'Pendidikan\MagangSiswa\RekananMagangController@viewRekananMagang');
			Route::get('rekanan-magang/datatables', 'Pendidikan\MagangSiswa\RekananMagangController@datatablesRekananMagang');
			Route::get('rekanan-magang/add', 'Pendidikan\MagangSiswa\RekananMagangController@addRekananMagang');
			Route::get('rekanan-magang/edit/{id}', 'Pendidikan\MagangSiswa\RekananMagangController@editRekananMagang');

			Route::post('action-rekanan-magang/{mode}/{id}', 'Pendidikan\MagangSiswa\RekananMagangController@actionRekananMagang');

			//MENU Pengajuan Siswa Magang
			Route::get('pengajuan-siswa-magang', 'Pendidikan\MagangSiswa\PengajuanSiswaMagangController@viewPengajuanSiswaMagang');
			Route::post('post-view-pengajuan-magang', 'Pendidikan\MagangSiswa\PengajuanSiswaMagangController@actionViewDetailPengajuanMagang');
			Route::get('pengajuan-siswa-magang/view-detail/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', 'Pendidikan\MagangSiswa\PengajuanSiswaMagangController@viewDetailPengajuanMagang');
			Route::get('pengajuan-siswa-magang/datatables/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', 'Pendidikan\MagangSiswa\PengajuanSiswaMagangController@datatablesPengajuanMagang');
			Route::get('pengajuan-siswa-magang/cancel/{id}', 'Pendidikan\MagangSiswa\PengajuanSiswaMagangController@cancelPengajuanMagang');

			Route::post('action-pengajuan-siswa-magang/{mode}/{id}/{id_siswa}/{id_periode_magang}/{id_rekanan_magang}', 'Pendidikan\MagangSiswa\PengajuanSiswaMagangController@actionPengajuanMagang');

			//MENU Approve Siswa Magang
			Route::get('approve-siswa-magang', 'Pendidikan\MagangSiswa\ApproveSiswaMagangController@viewApproveSiswaMagang');
			Route::post('post-view-approve-siswa-magang', 'Pendidikan\MagangSiswa\ApproveSiswaMagangController@actionViewDetailApproveSiswaMagang');
			Route::get('approve-siswa-magang/view-detail/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', 'Pendidikan\MagangSiswa\ApproveSiswaMagangController@viewDetailApproveSiswaMagang');
			Route::get('approve-siswa-magang/datatables/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', 'Pendidikan\MagangSiswa\ApproveSiswaMagangController@datatablesApproveSiswaMagang');

			Route::post('action-approve-siswa-magang/{mode}/{id}/{id_siswa}/{id_periode_magang/{id_rekanan_magang}', 'Pendidikan\MagangSiswa\ApproveSiswaMagangController@actionApproveSiswaMagang');


			//MENU Komponen Nilai Magang
			Route::get('komponen-nilai-magang', 'Pendidikan\MagangSiswa\KomponenNilaiMagangController@viewKomponenNilaiMagang');
			Route::post('post-view-komponen-nilai-magang', 'Pendidikan\MagangSiswa\KomponenNilaiMagangController@actionViewKelasKomponenNilaiMagang');
			Route::get('komponen-nilai-magang/view-periode/{id_periode_magang}', 'Pendidikan\MagangSiswa\KomponenNilaiMagangController@viewKelasKomponenNilaiMagang');
			Route::get('komponen-nilai-magang/datatables/{id_periode_magang}', 'Pendidikan\MagangSiswa\KomponenNilaiMagangController@datatablesKomponenNilaiMagang');
			Route::get('komponen-nilai-magang/add/{id_periode_magang}', 'Pendidikan\MagangSiswa\KomponenNilaiMagangController@addKomponenNilai');
			Route::get('komponen-nilai-magang/edit/{id_periode_magang}/{id}', 'Pendidikan\MagangSiswa\KomponenNilaiMagangController@editKomponenNilai');

			Route::post('action-komponen-nilai-magang/{mode}/{id}', 'Pendidikan\MagangSiswa\KomponenNilaiMagangController@actionKomponenNilaiMagang');

			//MENU Input Nilai
			Route::get('input-nilai-magang', 'Pendidikan\MagangSiswa\InputNilaiMagangController@viewPeriodeMagang');
			Route::post('post-view-input-nilai-magang', 'Pendidikan\MagangSiswa\InputNilaiMagangController@actionViewKomponenInputNilaiMagang');
			Route::get('input-nilai-magang/view-komponen/{id_periode_magang}', 'Pendidikan\MagangSiswa\InputNilaiMagangController@viewKomponenInputNilaiMagang');
			Route::get('input-nilai-magang/datatables/{id_periode_magang}', 'Pendidikan\MagangSiswa\InputNilaiMagangController@datatablesKomponenNilaiMagang');

			Route::post('action-input-nilai-magang/{mode}/{id}', 'Pendidikan\MagangSiswa\InputNilaiMagangController@actionInputNilaiMagang');



		});
		/** ==== MODUL WISUDA ==== **/
		Route::group(array('prefix' => 'wisuda'), function() {
			// MENU Nama Wisuda
			Route::get('nama-wisuda', 'Pendidikan\Wisuda\WisudaController@viewWisuda');
			Route::get('nama-wisuda/datatables', 'Pendidikan\Wisuda\WisudaController@datatablesWisuda');
			Route::get('nama-wisuda/add', 'Pendidikan\Wisuda\WisudaController@addWisuda');
			Route::get('nama-wisuda/edit/{id}', 'Pendidikan\Wisuda\WisudaController@editWisuda');

			Route::post('action-nama-wisuda/{mode}/{id}', 'Pendidikan\Wisuda\WisudaController@actionWisuda');

			// MENU Periode Wisuda
			Route::get('periode-wisuda', 'Pendidikan\Wisuda\PeriodeWisudaController@viewPeriodeWisuda');
			Route::get('periode-wisuda/datatables', 'Pendidikan\Wisuda\PeriodeWisudaController@datatablesPeriodeWisuda');
			Route::get('periode-wisuda/add', 'Pendidikan\Wisuda\PeriodeWisudaController@addPeriodeWisuda');
			Route::get('periode-wisuda/edit/{id}', 'Pendidikan\Wisuda\PeriodeWisudaController@editPeriodeWisuda');

			Route::post('action-periode-wisuda/{mode}/{id}', 'Pendidikan\Wisuda\PeriodeWisudaController@actionPeriodeWisuda');

			// MENU Pengajuan Wisuda
			Route::get('pengajuan-wisuda', 'Pendidikan\Wisuda\PengajuanWisudaController@viewPengajuanWisuda');
			Route::post('post-view-pengajuan-wisuda', 'Pendidikan\Wisuda\PengajuanWisudaController@actionViewDetailPengajuanWisuda');
			Route::get('pengajuan-wisuda/view-detail/{id_periode_wisuda}/{nis_nama_siswa}', 'Pendidikan\Wisuda\PengajuanWisudaController@viewDetailPengajuanWisuda');
			Route::get('pengajuan-wisuda/datatables/{id_periode_wisuda}/{nis_nama_siswa}', 'Pendidikan\Wisuda\PengajuanWisudaController@datatablesPengajuanWisuda');
			Route::get('pengajuan-wisuda/cancel/{id}/{id_periode_wisuda}/{nis_nama_siswa}/', 'Pendidikan\Wisuda\PengajuanWisudaController@cancelPengajuanWisuda');

			Route::post('action-pengajuan-wisuda/{mode}/{id}/{id_siswa}/{id_periode_wisuda}', 'Pendidikan\Wisuda\PengajuanWisudaController@actionPengajuanWisuda');

			// MENU Entri Data Wisuda
			Route::get('entri-wisuda', 'Pendidikan\Wisuda\EntriWisudaController@viewEntriWisuda');
			Route::post('post-view-entri-wisuda', 'Pendidikan\Wisuda\EntriWisudaController@actionViewDetailEntriWisuda');
			Route::get('entri-wisuda/view-detail/{id_periode_wisuda}/{nis_nama_siswa}', 'Pendidikan\Wisuda\EntriWisudaController@viewDetailEntriWisuda');
			Route::get('entri-wisuda/datatables/{id_periode_wisuda}/{nis_nama_siswa}', 'Pendidikan\Wisuda\EntriWisudaController@datatablesEntriWisuda');
			Route::get('entri-wisuda/input/{id}/{id_periode_wisuda}/{nis_nama_siswa}/', 'Pendidikan\Wisuda\EntriWisudaController@inputEntriWisuda');

			Route::post('action-entri-wisuda/{mode}/{id}/{id_siswa}/{id_periode_wisuda}', 'Pendidikan\Wisuda\EntriWisudaController@actionEntriWisuda');

			// MENU Set Lulus Siswa ==== (BELOM SEMUA) ====
			Route::get('set-lulus', 'Pendidikan\Wisuda\SetLulusController@viewSetLulus');
			Route::get('set-lulus/datatables', 'Pendidikan\Wisuda\SetLulusController@datatablesSetLulus');

			Route::post('action-set-lulus/{mode}/{id}', 'Pendidikan\Wisuda\SetLulusController@actionSetLulus');

		});

	});
});