<?php
// ROLE ORANG TUA
Route::group(array('middleware'=> ['token_staff']), function() {
	Route::group(array('prefix' => 'wali-murid'), function() {
		Route::get('welcome', 'WaliMurid\WelcomeController@indexWelcome');

		/** ==== MODUL AKADEMIK ==== **/
		Route::group(array('prefix' => 'akademik'), function() {

			// MENU Kalender Akademik
			Route::get('kalender-akademik', 'WaliMurid\Akademik\KalenderAkademikController@viewKalenderAkademik');
			Route::get('kalender-akademik/datatables', 'WaliMurid\Akademik\KalenderAkademikController@datatablesKalenderAkademik');



			// MENU Jadwal KBM
			Route::get('jadwal-kbm', 'WaliMurid\Akademik\JadwalKBMController@viewSiswaJadwalKBM');
			Route::post('post-view-jadwal-kbm', 'WaliMurid\Akademik\JadwalKBMController@actionViewSiswaJadwalKBM');
			Route::get('jadwal-kbm/{id_pengguna}', 'WaliMurid\Akademik\JadwalKBMController@viewJadwalKBM');
			Route::get('jadwal-kbm/datatables/{id_pengguna}', 'WaliMurid\Akademik\JadwalKBMController@datatablesJadwalKBM');

			// MENU Jadwal Ujian
			Route::get('jadwal-ujian', 'WaliMurid\Akademik\JadwalUjianController@viewSiswaJadwalUjian');
			Route::post('post-view-jadwal-ujian', 'WaliMurid\Akademik\JadwalUjianController@actionViewSiswaJadwalUjian');
			Route::get('jadwal-ujian/{id_pengguna}', 'WaliMurid\Akademik\JadwalUjianController@viewJadwalUjian');
			Route::get('jadwal-ujian/datatables-uts/{id_pengguna}', 'WaliMurid\Akademik\JadwalUjianController@datatablesJadwalUTS');
			Route::get('jadwal-ujian/datatables-uas/{id_pengguna}', 'WaliMurid\Akademik\JadwalUjianController@datatablesJadwalUAS');

			// MENU Magang
			Route::get('magang', 'WaliMurid\Akademik\MagangController@viewSiswaMagang');
			Route::post('post-view-magang', 'WaliMurid\Akademik\MagangController@actionViewSiswaMagang');
			Route::get('magang/{id_pengguna}', 'WaliMurid\Akademik\MagangController@viewMagang');
			Route::get('magang/datatables/{id_pengguna}', 'WaliMurid\Akademik\MagangController@datatablesMagang');

		});

	});
});