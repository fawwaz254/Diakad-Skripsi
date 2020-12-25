<?php
// ROLE ALUMNI
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'humas'), function() {
			Route::get('welcome', 'Humas\WelcomeController@indexWelcome');

        /** ==== MODUL KEGIATAN HARIAN ==== **/
			Route::group(array('prefix' => 'kegiatan-harian'), function() {
			
				Route::group(array('prefix' => 'input-kegiatan'), function() {
					Route::get('/', 'Humas\KegiatanHarian\InputKegiatanController@viewInputKegiatan');
					Route::get('add', 'Humas\KegiatanHarian\InputKegiatanController@viewAddEditInputKegiatan');
					Route::get('edit/{id}', 'Humas\KegiatanHarian\InputKegiatanController@viewAddEditInputKegiatan');
					Route::post('datatables', 'Humas\KegiatanHarian\InputKegiatanController@showDatatablesInputKegiatan');
					Route::post('action/{mode}', 'Humas\KegiatanHarian\InputKegiatanController@actionInputKegiatan');
					
					Route::group(array('prefix' => 'kategori-pertanyaan'), function() {
						Route::get('detail/{id1}', 'Humas\KegiatanHarian\InputKegiatanController@viewInputKategoriPertanyaan');
						Route::get('{id1}/add', 'Humas\KegiatanHarian\InputKegiatanController@viewAddEditInputKategoriPertanyaan');
						Route::get('{id1}/edit/{id2}', 'Humas\KegiatanHarian\InputKegiatanController@viewAddEditInputKategoriPertanyaan');

						Route::post('{id1}/datatables', 'Humas\KegiatanHarian\InputKegiatanController@showDatatablesInputKategoriPertanyaan');
						Route::post('{id1}/action/{mode}', 'Humas\KegiatanHarian\InputKegiatanController@actionInputKategoriPertanyaan');
					});
				});

				Route::group(array('prefix' => 'input-pertanyaan'), function() {
					Route::get('/', 'Humas\KegiatanHarian\InputPertanyaanController@viewInputPertanyaan');
					Route::get('add', 'Humas\KegiatanHarian\InputPertanyaanController@viewAddEditInputPertanyaan');
					Route::get('edit/{id}', 'Humas\KegiatanHarian\InputPertanyaanController@viewAddEditInputPertanyaan');

					Route::post('datatables', 'Humas\KegiatanHarian\InputPertanyaanController@showDatatablesInputPertanyaan');
					Route::post('action/{mode}', 'Humas\KegiatanHarian\InputPertanyaanController@actionInputPertanyaan');

					Route::group(array('prefix' => 'jawaban'), function() {
						Route::get('detail/{id1}', 'Humas\KegiatanHarian\InputPertanyaanController@viewInputJawaban');
						Route::get('{id1}/add', 'Humas\KegiatanHarian\InputPertanyaanController@viewAddEditInputJawaban');
						Route::get('{id1}/edit/{id2}', 'Humas\KegiatanHarian\InputPertanyaanController@viewAddEditInputJawaban');
		
						Route::post('{id1}/datatables', 'Humas\KegiatanHarian\InputPertanyaanController@showDatatablesInputJawaban');
						Route::post('{id1}/action/{mode}', 'Humas\KegiatanHarian\InputPertanyaanController@actionInputJawaban');
					});

				});
				
				// Route::get('rekap-kesehatan', 'Humas\KegiatanHarian\RekapKesehatanController@viewRekapKesehatan');
				Route::get('rekap-kesehatan', 'Humas\KegiatanHarian\RekapKesehatanController@viewRekapFormKesehatan');
				Route::get('rekap-kesehatan/{bulan}', 'Humas\KegiatanHarian\RekapKesehatanController@viewRekapFormKesehatan');
				Route::get('rekap-kesehatan/{bulan}/download', 'Humas\KegiatanHarian\RekapKesehatanController@downloadRekapFormKesehatan');
				Route::get('rekap-kesehatan/detail/form/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');
				Route::get('rekap-kesehatan/user/{id}/{date}', 'Guru\WaliKelas\RekapKesehatanController@viewRekapKesehatanSiswa');
					
				Route::post('rekap-kesehatan/action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
				Route::post('rekap-kesehatan/datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');
			});
			
			// Route::get('rekap-kesehatan', 'Humas\KegiatanHarian\RekapKesehatanController@viewRekapKesehatan');
			Route::get('rekap-kesehatan', 'Humas\KegiatanHarian\RekapKesehatanController@viewRekapFormKesehatan');
			Route::get('rekap-kesehatan/{bulan}', 'Humas\KegiatanHarian\RekapKesehatanController@viewRekapFormKesehatan');
			Route::get('rekap-kesehatan/{bulan}/download', 'Humas\KegiatanHarian\RekapKesehatanController@downloadRekapFormKesehatan');
			Route::get('rekap-kesehatan/detail/form/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');
			Route::get('rekap-kesehatan/user/{id}/{date}', 'Guru\WaliKelas\RekapKesehatanController@viewRekapKesehatanSiswa');
				
			Route::post('rekap-kesehatan/action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
			Route::post('rekap-kesehatan/datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');
				/** === MODUL MAGANG SISWA === **/
				Route::group(array('prefix' => 'magang-siswa'), function() {
					// MENU Nama Magang
					Route::get('nama-magang', 'Humas\MagangSiswa\MagangSiswaController@viewMagangSiswa');
					Route::get('nama-magang/datatables', 'Humas\MagangSiswa\MagangSiswaController@datatablesMagangSiswa');
					Route::get('nama-magang/add', 'Humas\MagangSiswa\MagangSiswaController@addMagangSiswa');
					Route::get('nama-magang/edit/{id}', 'Humas\MagangSiswa\MagangSiswaController@editMagangSiswa');
		
					Route::post('action-nama-magang/{mode}/{id}', 'Humas\MagangSiswa\MagangSiswaController@actionMagang');
		
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
		
					Route::post('action-rekanan-magang/{mode}/{id}', 'Humas\MagangSiswa\RekananMagangController@actionRekananMagang');
		
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
		
				Route::namespace('Humas\Alumni')->prefix('alumni')->group(function() {
					Route::get('/', 'AlumniController@index');
					Route::get('/add', 'AlumniController@create');
					Route::get('/edit/{alumni}', 'AlumniController@show');
					Route::post('/store', 'AlumniController@store');
					Route::post('/update/{alumni}', 'AlumniController@update');
					Route::post('/delete/{alumni}', 'AlumniController@destroy');
					Route::post('/datatables', 'AlumniController@renderDatatables');
				});
		
				Route::namespace('Humas\Kerjasama')->prefix('kerjasama')->group(function() {
					Route::group(array('prefix' => 'instansi'), function() {
						Route::get('/', 'InstansiController@index');
						Route::get('/add', 'InstansiController@create');
						Route::get('/edit/{instansi}', 'InstansiController@edit');
						Route::post('/store', 'InstansiController@store');
						Route::post('/update/{instansi}', 'InstansiController@update');
						Route::post('/delete/{instansi}', 'InstansiController@destroy');
						Route::post('/datatables', 'InstansiController@renderDatatables');
					});
					
					Route::group(array('prefix' => 'jenis'), function() {
						Route::get('/', 'JenisKerjaSamaController@index');
						Route::get('/add', 'JenisKerjaSamaController@create');
						Route::get('/edit/{jenisKerjasama}', 'JenisKerjaSamaController@edit');
						Route::post('/store', 'JenisKerjaSamaController@store');
						Route::post('/update/{jenisKerjasama}', 'JenisKerjaSamaController@update');
						Route::post('/delete/{jenisKerjasama}', 'JenisKerjaSamaController@destroy');
						Route::post('/datatables', 'JenisKerjaSamaController@renderDatatables');
					});
				});
		});
});