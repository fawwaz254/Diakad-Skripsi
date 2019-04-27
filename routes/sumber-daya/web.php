<?php
// ROLE SUMBER DAYA
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'sumber-daya'), function() {
        Route::get('welcome', 'SumberDaya\WelcomeController@indexWelcome');

        /** ==== MODUL DATA SUMBER DAYA ==== **/
        Route::group(array('prefix' => 'data-sumber-daya'), function() {
            // MENU Data Unit Kerja
            Route::get('unit-kerja', 'SumberDaya\DataSumberDaya\UnitKerjaController@viewUnitKerja');
            Route::get('unit-kerja/datatables', 'SumberDaya\DataSumberDaya\UnitKerjaController@datatablesUnitKerja');
            Route::get('unit-kerja/add', 'SumberDaya\DataSumberDaya\UnitKerjaController@addUnitKerja');
            Route::get('unit-kerja/edit/{id}', 'SumberDaya\DataSumberDaya\UnitKerjaController@editUnitKerja');

            Route::post('action-unit-kerja/{mode}/{id}', 'SumberDaya\DataSumberDaya\UnitKerjaController@actionUnitKerja');

            // MENU Data Jabatan Pegawai
            // TABEL DIHAPUS
            /*Route::get('jabatan-pegawai', 'SumberDaya\DataSumberDaya\JabatanPegawaiController@viewJabatanPegawai');
            Route::get('jabatan-pegawai/datatables', 'SumberDaya\DataSumberDaya\JabatanPegawaiController@datatablesJabatanPegawai');
            Route::get('jabatan-pegawai/add', 'SumberDaya\DataSumberDaya\JabatanPegawaiController@addJabatanPegawai');
            Route::get('jabatan-pegawai/edit/{id}', 'SumberDaya\DataSumberDaya\JabatanPegawaiController@editJabatanPegawai');

            Route::post('action-jabatan-pegawai/{mode}/{id}', 'SumberDaya\DataSumberDaya\JabatanPegawaiController@actionJabatanPegawai');*/

            // MENU Data Status Aktif Guru
            Route::get('status-aktif-guru', 'SumberDaya\DataSumberDaya\StatusAktifGuruController@viewStatusAktifGuru');
            Route::get('status-aktif-guru/datatables', 'SumberDaya\DataSumberDaya\StatusAktifGuruController@datatablesStatusAktifGuru');
            Route::get('status-aktif-guru/add', 'SumberDaya\DataSumberDaya\StatusAktifGuruController@addStatusAktifGuru');
            Route::get('status-aktif-guru/edit/{id}', 'SumberDaya\DataSumberDaya\StatusAktifGuruController@editStatusAktifGuru');

            Route::post('action-status-aktif-guru/{mode}/{id}', 'SumberDaya\DataSumberDaya\StatusAktifGuruController@actionStatusAktifGuru');

            // MENU Data Status Aktif Tendik
            Route::get('status-aktif-tendik', 'SumberDaya\DataSumberDaya\StatusAktifTendikController@viewStatusAktifTendik');
            Route::get('status-aktif-tendik/datatables', 'SumberDaya\DataSumberDaya\StatusAktifTendikController@datatablesStatusAktifTendik');
            Route::get('status-aktif-tendik/add', 'SumberDaya\DataSumberDaya\StatusAktifTendikController@addStatusAktifTendik');
            Route::get('status-aktif-tendik/edit/{id}', 'SumberDaya\DataSumberDaya\StatusAktifTendikController@editStatusAktifTendik');

            Route::post('action-status-aktif-tendik/{mode}/{id}', 'SumberDaya\DataSumberDaya\StatusAktifTendikController@actionStatusAktifTendik');
        });

        /** ==== MODUL DATA GURU ==== **/
        Route::group(array('prefix' => 'guru'), function() {
            // MENU Input Guru Baru
            Route::get('input-guru', 'SumberDaya\Guru\InputGuruController@viewInputGuru');
            Route::get('input-guru/datatables', 'SumberDaya\Guru\InputGuruController@datatablesInputGuru');
            Route::get('input-guru/add', 'SumberDaya\Guru\InputGuruController@addInputGuru');
            Route::get('input-guru/edit/{id}', 'SumberDaya\Guru\InputGuruController@editInputGuru');

            Route::post('action-input-guru/{mode}/{id}', 'SumberDaya\Guru\InputGuruController@actionInputGuru');

            Route::get('input-guru/get-kota/{id}', 'SumberDaya\Guru\InputGuruController@getKota');


            //MENU Upload Guru
            Route::get('upload-data-guru', 'SumberDaya\Guru\UploadDataGuruController@viewUploadDataGuru');
            Route::get('/download-file-excel', 'SumberDaya\Guru\UploadDataGuruController@downloadFileExcel')->name('guru/download-file-excel');
            Route::post('post-file-excel', 'SumberDaya\Guru\UploadDataGuruController@uploadFileExcel');

            //MENU Setting Guru Piket
            Route::get('setting-guru-piket', 'SumberDaya\Guru\SettingGuruPiketController@viewSettingGuruPiket');
            Route::get('setting-guru-piket/datatables', 'SumberDaya\Guru\SettingGuruPiketController@datatablesSettingGuruPiket');
            Route::get('setting-guru-piket/add', 'SumberDaya\Guru\SettingGuruPiketController@addSettingGuruPiket');
            Route::get('setting-guru-piket/edit/{id}', 'SumberDaya\Guru\SettingGuruPiketController@editSettingGuruPiket');
            Route::get('setting-guru-piket/datatablesGuru', 'SumberDaya\Guru\SettingGuruPiketController@datatablesAddGuruPiket');

            Route::post('action-setting-guru-piket/{mode}/{id}', 'SumberDaya\Guru\SettingGuruPiketController@actionSettingGuruPiket');
        });

        /** ==== MODUL DATA TENAGA PENDIDIK ==== **/
        Route::group(array('prefix' => 'tendik'), function() {
            // MENU Input Tendik Baru
            Route::get('input-tendik', 'SumberDaya\Tendik\InputTendikController@viewInputTendik');
            Route::get('input-tendik/datatables', 'SumberDaya\Tendik\InputTendikController@datatablesInputTendik');
            Route::get('input-tendik/add', 'SumberDaya\Tendik\InputTendikController@addInputTendik');
            Route::get('input-tendik/edit/{id}', 'SumberDaya\Tendik\InputTendikController@editInputTendik');

            Route::post('action-input-tendik/{mode}/{id}', 'SumberDaya\Tendik\InputTendikController@actionInputTendik');

            Route::get('upload-data-tendik', 'SumberDaya\Tendik\UploadDataTendikController@viewUploadDataTendik');
            Route::get('/download-file-excel', 'SumberDaya\Tendik\UploadDataTendikController@downloadFileExcel')->name('tendik/download-file-excel');
            Route::post('post-file-excel', 'SumberDaya\Tendik\UploadDataTendikController@uploadFileExcel');
        });
    });
});