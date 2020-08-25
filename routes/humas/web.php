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
		});
    });
});