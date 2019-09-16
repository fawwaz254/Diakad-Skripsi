<?php
// ROLE BIMBINGAN KONSELING
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'bimbingan-konseling'), function() {
        Route::get('welcome', 'BK\WelcomeController@indexWelcome');

        /** ==== MODUL DATA PELANGGARAN ==== **/
        Route::group(array('prefix' => 'data-pelanggaran'), function() {
            // MENU Kategori Pelanggaran
            // url: /bimbingan-konseling/data-pelanggaran/kategori-pelanggaran
            Route::get('kategori-pelanggaran', 'BK\DataPelanggaran\KategoriPelanggaranController@viewKategoriPelanggaran');
            Route::get('kategori-pelanggaran/datatables', 'BK\DataPelanggaran\KategoriPelanggaranController@datatablesKategoriPelanggaran');
            Route::get('kategori-pelanggaran/add', 'BK\DataPelanggaran\KategoriPelanggaranController@addKategoriPelanggaran');
            Route::get('kategori-pelanggaran/edit/{id}', 'BK\DataPelanggaran\KategoriPelanggaranController@editKategoriPelanggaran');

            Route::post('action-kategori-pelanggaran/{mode}/{id}', 'BK\DataPelanggaran\KategoriPelanggaranController@actionKategoriPelanggaran');

            // MENU Sub-Kategori Pelanggaran
            // url: /bimbingan-konseling/data-pelanggaran/subkategori-pelanggaran
            Route::get('subkategori-pelanggaran', 'BK\DataPelanggaran\SubKategoriPelanggaranController@viewSubkategoriPelanggaran');
            Route::get('subkategori-pelanggaran/datatables', 'BK\DataPelanggaran\SubKategoriPelanggaranController@datatablesSubkategoriPelanggaran');
            Route::get('subkategori-pelanggaran/add', 'BK\DataPelanggaran\SubKategoriPelanggaranController@addSubkategoriPelanggaran');
            Route::get('subkategori-pelanggaran/edit/{id}', 'BK\DataPelanggaran\SubKategoriPelanggaranController@editSubkategoriPelanggaran');

            Route::post('action-subkategori-pelanggaran/{mode}/{id}', 'BK\DataPelanggaran\SubKategoriPelanggaranController@actionSubkategoriPelanggaran');

            // MENU Kesimpulan Pelanggaran
            // url: /bimbingan-konseling/data-pelanggaran/kesimpulan-pelanggaran
            Route::get('kesimpulan-pelanggaran', 'BK\DataPelanggaran\KesimpulanPelanggaranController@viewKesimpulanPelanggaran');
            Route::get('kesimpulan-pelanggaran/datatables', 'BK\DataPelanggaran\KesimpulanPelanggaranController@datatablesKesimpulanPelanggaran');
            Route::get('kesimpulan-pelanggaran/add', 'BK\DataPelanggaran\KesimpulanPelanggaranController@addKesimpulanPelanggaran');
            Route::get('kesimpulan-pelanggaran/edit/{id}', 'BK\DataPelanggaran\KesimpulanPelanggaranController@editKesimpulanPelanggaran');

            Route::post('action-kesimpulan-pelanggaran/{mode}/{id}', 'BK\DataPelanggaran\KesimpulanPelanggaranController@actionKesimpulanPelanggaran');

        });

        /** ==== MODUL PENANGANAN SISWA ==== **/
        Route::group(array('prefix' => 'penanganan-siswa'), function() {
            // MENU Data Jenis Tindakan
            Route::get('jenis-tindakan', 'BK\PenangananSiswa\JenisTindakanController@viewJenisTindakan');
            Route::get('jenis-tindakan/datatables', 'BK\PenangananSiswa\JenisTindakanController@datatablesJenisTindakan');
            Route::get('jenis-tindakan/add', 'BK\PenangananSiswa\JenisTindakanController@addJenisTindakan');
            Route::get('jenis-tindakan/edit/{id}', 'BK\PenangananSiswa\JenisTindakanController@editJenisTindakan');

            Route::post('action-jenis-tindakan/{mode}/{id}', 'BK\PenangananSiswa\JenisTindakanController@actionJenisTindakan');

            // MENU Input Pelanggaran Siswa
            Route::get('input-pelanggaran', 'BK\PenangananSiswa\InputPelanggaranController@viewInputPelanggaran');
            Route::get('input-pelanggaran/datatables', 'BK\PenangananSiswa\InputPelanggaranController@datatablesInputPelanggaran');
            Route::get('input-pelanggaran/add', 'BK\PenangananSiswa\InputPelanggaranController@addInputPelanggaran');
            Route::get('input-pelanggaran/edit/{id}', 'BK\PenangananSiswa\InputPelanggaranController@editInputPelanggaran');

            Route::post('action-input-pelanggaran/{mode}/{id}', 'BK\PenangananSiswa\InputPelanggaranController@actionInputPelanggaran');

            // MENU Tindakan Pelanggaran
            Route::get('tindakan-pelanggaran', 'BK\PenangananSiswa\TindakanPelanggaranController@viewTindakanPelanggaran');
            Route::get('tindakan-pelanggaran/datatables-belum-nonkbm', 'BK\PenangananSiswa\TindakanPelanggaranController@datatablesBelumTindakanNonKBM');
            Route::get('tindakan-pelanggaran/datatables-belum-kbm', 'BK\PenangananSiswa\TindakanPelanggaranController@datatablesBelumTindakanKBM');
            Route::get('tindakan-pelanggaran/datatables-sudah', 'BK\PenangananSiswa\TindakanPelanggaranController@datatablesSudahTindakan');
            Route::get('tindakan-pelanggaran/add-nonkbm/{id}', 'BK\PenangananSiswa\TindakanPelanggaranController@addTindakanPelanggaranNonKBM');
            Route::get('tindakan-pelanggaran/add-kbm/{id}', 'BK\PenangananSiswa\TindakanPelanggaranController@addTindakanPelanggaranKBM');
            Route::get('tindakan-pelanggaran/edit/{id}', 'BK\PenangananSiswa\TindakanPelanggaranController@editTindakanPelanggaran');

            Route::post('action-tindakan-pelanggaran/{mode}/{id}', 'BK\PenangananSiswa\TindakanPelanggaranController@actionTindakanPelanggaran');
            
            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', 'BK\PenangananSiswa\InputPelanggaranController@ajaxGetSiswaByKelas');
            
        });

    });
});