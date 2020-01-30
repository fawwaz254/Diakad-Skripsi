<?php
// ROLE PELATIH EKSKUL
Route::group(array('middleware'=> ['token_staff']), function () {
    Route::group(array('prefix' => 'pelatih-ekskul'), function () {
        Route::get('welcome', 'PelatihEkskul\WelcomeController@indexWelcome');
    
        /** ==== MODUL ABSENSI EKSKUL ==== **/
        Route::group(array('prefix' => 'absensi-ekskul'), function () {
            // MENU Input Absensi Ekskul
            Route::get('input-absensi-ekskul', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@viewInputAbsensiEkskul');
            Route::get('input-absensi-ekskul/{id_semester}/{id_ekskul}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@viewInputAbsensiEkskul');
            Route::get('input-absensi-ekskul/manage/{id_semester}/{id_ekskul}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@viewManageInputAbsensiEkskul');
            Route::get('input-absensi-ekskul/manage/{id_semester}/{id_ekskul}/{id_presensi_ekskul}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@viewManageInputAbsensiEkskul');
            Route::get('input-absensi-ekskul/detail/{id_ekskul}/{id_kelas}/{tahun}/{id_bulan}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@viewDetailInputAbsensiEkskul');
            
            Route::post('input-absensi-ekskul/datatables/{id_semester}/{id_ekskul}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@datatablesInputAbsensiEkskul');
            Route::post('input-absensi-ekskul/datatables-detail/{id_semester}/{id_ekskul}/{id_presensi_ekskul}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@datatablesSiswaInputAbsensiEkskul');
            Route::post('input-absensi-ekskul/action/{mode}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@actionInputAbsensiEkskul');
            Route::post('input-absensi-ekskul/action/{mode}/{id}', 'PelatihEkskul\AbsensiEkskul\InputAbsensiEkskulController@actionInputAbsensiEkskul');
        });
    });
});
