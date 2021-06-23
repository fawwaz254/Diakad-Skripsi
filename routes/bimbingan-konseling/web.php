<?php
// ROLE BIMBINGAN KONSELING
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'bimbingan-konseling'), function() {
        Route::get('welcome', 'BK\WelcomeController@indexWelcome');

        /** ==== MODUL MONITORING KESEHATAN ==== **/
        Route::group(array('prefix' => 'monitoring-kesehatan'), function() {

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
            Route::get('subkategori-pelanggaran', 'BK\DataPelanggaran\SubkategoriPelanggaranController@viewSubkategoriPelanggaran');
            Route::get('subkategori-pelanggaran/datatables', 'BK\DataPelanggaran\SubkategoriPelanggaranController@datatablesSubkategoriPelanggaran');
            Route::get('subkategori-pelanggaran/add', 'BK\DataPelanggaran\SubkategoriPelanggaranController@addSubkategoriPelanggaran');
            Route::get('subkategori-pelanggaran/edit/{id}', 'BK\DataPelanggaran\SubkategoriPelanggaranController@editSubkategoriPelanggaran');

            Route::post('action-subkategori-pelanggaran/{mode}/{id}', 'BK\DataPelanggaran\SubkategoriPelanggaranController@actionSubkategoriPelanggaran');

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

            // MENU Data Jurnal Tindakan
            Route::get('jurnal-tindakan', 'BK\PenangananSiswa\JurnalTindakanController@viewJurnalTindakan');
            Route::get('jurnal-tindakan/print/{id_semester}/{id_kelas}/{id_siswa}', 'BK\PenangananSiswa\JurnalTindakanController@printJurnalTindakan');

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

            // AJAX GET SUBKATEGORI PELANGGARAN BY KATEGORI
            Route::post('subkategori-bykategori', 'BK\PenangananSiswa\InputPelanggaranController@ajaxGetSubkategoriByKategori');
            
        });

    });
});