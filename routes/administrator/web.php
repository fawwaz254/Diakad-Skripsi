<?php
// ROLE ALUMNI
Route::group(array('middleware' => ['token_staff']), function () {
	Route::group(array('prefix' => 'administrator'), function () {
		Route::get('welcome', 'Administrator\WelcomeController@indexWelcome');

		/** ==== MODUL MANAJEMEN FILE ==== **/
		// url: /administrator/manajemen-file
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
			});
		});

		/** ==== MODUL PENGELOLAAN AKUN ==== **/
		// url: /administrator/pengelolaan-akun
		Route::group(array('prefix' => 'pengelolaan-akun'), function () {

			// MENU Pencarian
			// url: /administrator/pengelolaan-akun/pencarian
			Route::get('pencarian', 'Administrator\PengelolaanAkun\PencarianController@viewPencarian');
			Route::post('post-view-pencarian', 'Administrator\PengelolaanAkun\PencarianController@actionViewPencarian');
			Route::get('pencarian/view-detail/{username_nama_cari}', 'Administrator\PengelolaanAkun\PencarianController@viewDetailPencarian');
			Route::get('pencarian/datatables/{username_nama_cari}', 'Administrator\PengelolaanAkun\PencarianController@datatablesPencarian');
			Route::get('pencarian/view-detail-pengguna/{id_pengguna}/{username_nama_cari}', 'Administrator\PengelolaanAkun\PencarianController@viewDetailPenggunaPencarian');
			Route::get('pencarian/datatables-role/{id_pengguna}', 'Administrator\PengelolaanAkun\PencarianController@datatablesRolePencarian');
			Route::get('pencarian/add-role-pengguna/{id_pengguna}/{username_nama_cari}', 'Administrator\PengelolaanAkun\PencarianController@addRolePenggunaPencarian');

			Route::post('action-pencarian/{mode}/{id}', 'Administrator\PengelolaanAkun\PencarianController@actionPencarian');

			Route::post('reset-some-password', 'Administrator\PengelolaanAkun\PencarianController@resetPasswordCollection');

			// MENU Tenaga Pendidik
			// url: /administrator/pengelolaan-akun/tendik
			Route::get('tendik', 'Administrator\PengelolaanAkun\TendikController@viewTendik');
			Route::post('post-view-tendik', 'Administrator\PengelolaanAkun\TendikController@actionViewTendik');
			Route::get('tendik/view-detail/{id_role}', 'Administrator\PengelolaanAkun\TendikController@viewDetailTendik');
			Route::get('tendik/datatables/{id_role}', 'Administrator\PengelolaanAkun\TendikController@datatablesTendik');

			// MENU Guru
			// url: /administrator/pengelolaan-akun/guru
			Route::get('guru', 'Administrator\PengelolaanAkun\GuruController@viewGuru');
			Route::post('post-view-guru', 'Administrator\PengelolaanAkun\GuruController@actionViewGuru');
			Route::get('guru/view-detail/{id_role}', 'Administrator\PengelolaanAkun\GuruController@viewDetailGuru');
			Route::get('guru/datatables/{id_role}', 'Administrator\PengelolaanAkun\GuruController@datatablesGuru');

			// MENU Siswa
			// url: /administrator/pengelolaan-akun/siswa
			Route::get('siswa', 'Administrator\PengelolaanAkun\SiswaController@viewSiswa');
			Route::post('post-view-siswa', 'Administrator\PengelolaanAkun\SiswaController@actionViewSiswa');
			Route::get('siswa/view-detail/{id_kelas}', 'Administrator\PengelolaanAkun\SiswaController@viewDetailSiswa');
			Route::get('siswa/datatables/{id_kelas}', 'Administrator\PengelolaanAkun\SiswaController@datatablesSiswa');
		});

		/** ==== MODUL MANAJEMEN MENU ==== **/
		// url: /administrator/manajemen-menu
		Route::group(array('prefix' => 'manajemen-menu'), function () {
			// MENU Setting Dashboard
			// url: /administrator/manajemen-menu/setting-dashboard
			Route::get('setting-dashboard', 'Administrator\ManajemenMenu\SettingDashboardController@viewSettingDashboard');
			Route::post('post-view-setting-dashboard', 'Administrator\ManajemenMenu\SettingDashboardController@actionViewSettingDashboard');
			Route::get('setting-dashboard/view-detail/{id_role}', 'Administrator\ManajemenMenu\SettingDashboardController@viewDetailSettingDashboard');
			Route::post('setting-dashboard', 'Administrator\ManajemenMenu\SettingDashboardController@actionSettingDashboard');
		});
	});
});
