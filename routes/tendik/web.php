<?php
// ROLE TENAGA PENDIDIK
Route::group(array('middleware'=> ['token_staff']), function () {
    Route::group(array('prefix' => 'tendik'), function () {
        Route::get('welcome', 'Tendik\WelcomeController@indexWelcome');

        /** ==== MODUL BIODATA ==== **/
        Route::group(array('prefix' => 'biodata'), function () {
            Route::get('data-pribadi', 'Tendik\Biodata\DataPribadiController@viewDataPribadi');
             Route::post('action-input-tendik/{mode}/{id}', 'Tendik\Biodata\DataPribadiController@actionInputTendik');
        });


        /** ==== MODUL GURU PIKET ==== **/
        Route::group(array('prefix' => 'guru-piket'), function () {
            // MENU Monitoring kelas kosong
            Route::get('monitoring-kelas-kosong', 'Guru\GuruPiket\MonitoringKelasKosongController@viewMonitoringKelasKosong');
            Route::get('monitoring-kelas-kosong/datatables', 'Guru\GuruPiket\MonitoringKelasKosongController@datatablesMonitoringKelasKosong');

            // MENU Monitoring kelas kosong
            Route::get('rekap-monitoring-kelas-kosong', 'Guru\GuruPiket\MonitoringKelasKosongController@viewRekapMonitoringKelasKosong');
            Route::get('rekap-monitoring-kelas-kosong/datatables', 'Guru\GuruPiket\MonitoringKelasKosongController@datatablesRekapMonitoringKelasKosong');

            // MENU Absensi Harian Siswa
            Route::get('absensi-harian-siswa', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewManageAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}/{id_presensi_harian}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewManageAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/detail/{id_semester}/{id_kelas}/{tahun}/{id_bulan}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewDetailAbsensiHarianSiswa');
            
            Route::post('absensi-harian-siswa/datatables/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@datatablesAbsensiHarianSiswa');
            Route::post('absensi-harian-siswa/datatables-detail/{id_semester}/{id_kelas}/{id_presensi_harian}', 'Guru\GuruPiket\AbsensiHarianSiswaController@datatablesKelasAbsensiHariSiswa');
            Route::post('absensi-harian-siswa/action/{mode}', 'Guru\GuruPiket\AbsensiHarianSiswaController@actionAbsensiHarianSiswa');
            Route::post('absensi-harian-siswa/action/{mode}/{id}', 'Guru\GuruPiket\AbsensiHarianSiswaController@actionAbsensiHarianSiswa');

            // MENU Input Pelanggaran Siswa Non-KBM
            Route::get('input-pelanggaran', 'Guru\GuruPiket\InputPelanggaranController@viewInputPelanggaran');
            Route::get('input-pelanggaran/datatables', 'Guru\GuruPiket\InputPelanggaranController@datatablesInputPelanggaran');
            Route::get('input-pelanggaran/add', 'Guru\GuruPiket\InputPelanggaranController@addInputPelanggaran');
            Route::get('input-pelanggaran/edit/{id}', 'Guru\GuruPiket\InputPelanggaranController@editInputPelanggaran');

            Route::post('action-input-pelanggaran/{mode}/{id}', 'Guru\GuruPiket\InputPelanggaranController@actionInputPelanggaran');

            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', 'Guru\GuruPiket\InputPelanggaranController@ajaxGetSiswaByKelas');

            // MENU Rekap Kesehatan Siswa
            Route::get('rekap-kesehatan', 'Guru\GuruPiket\RekapKesehatanController@viewRekapKesehatan');
            Route::get('rekap-kesehatan/user/{id}/{date}', 'Guru\WaliKelas\RekapKesehatanController@viewRekapKesehatanSiswa');
            Route::get('rekap-kesehatan/detail/form/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');
            
            Route::get('rekap-kesehatan/{id}', 'Guru\GuruPiket\RekapKesehatanController@viewDetailRekapKesehatan');
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}', 'Guru\GuruPiket\RekapKesehatanController@viewDetailRekapKesehatan');
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}/download', 'Guru\GuruPiket\RekapKesehatanController@downloadDetailRekapKesehatan');
            
                
            Route::post('rekap-kesehatan/action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
            Route::post('rekap-kesehatan/datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');

            // MENU Rekap Absen Tanpa Jadwal
            Route::get('rekap-absen-tanpa-jadwal', 'Guru\GuruPiket\RekapAbsenTanpaJadwalController@viewRekapAbsenTanpaJadwal');
            Route::post('post-get-kbm-by-kelas', 'Guru\GuruPiket\RekapAbsenTanpaJadwalController@actionGetKBMByKelas');

            Route::post('post-kbm-rekap-absen-tanpa-jadwal', 'Guru\GuruPiket\RekapAbsenTanpaJadwalController@actionViewKBMRekapAbsenTanpaJadwal');
            Route::get('rekap-absen-tanpa-jadwal/view-kbm/{id_kelas_mp}', 'Guru\GuruPiket\RekapAbsenTanpaJadwalController@viewKBMRekapAbsenTanpaJadwal');

            Route::get('rekap-absen-tanpa-jadwal/print/{id_kelas_mp}', 'Guru\GuruPiket\RekapAbsenTanpaJadwalController@printKBMRekapAbsenTanpaJadwal');
        });

        Route::group(array('prefix' => 'kegiatan-harian'), function () {
        
            Route::group(array('prefix' => 'mengisi-form-kesehatan'), function () {
                // MENU Mengisi form kesehatan
                Route::get('/', 'Tendik\KegiatanHarian\FormKesehatanController@viewFormKesehatan');
                Route::get('add', 'Tendik\KegiatanHarian\FormKesehatanController@viewAddFormKesehatan');
                Route::get('detail/form/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');
                
                Route::post('action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
                Route::post('datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');
            });
        });

        Route::group(['prefix' => 'laporan'], function(){

            Route::group(array('prefix' => 'kerja-harian'), function () {
                Route::get('/', 'Guru\Laporan\KerjaHarianController@viewKerjaHarian');
                Route::get('datatables', 'Guru\Laporan\KerjaHarianController@datatablesKerjaHarian');
                Route::get('add', 'Guru\Laporan\KerjaHarianController@addKerjaHarian');
                Route::get('edit/{id}', 'Guru\Laporan\KerjaHarianController@editKerjaHarian');
                Route::post('action-kerja-harian/{mode}/{id}', 'Guru\Laporan\KerjaHarianController@actionKerjaHarian');
            });

        });

//penambahan absensi
Route::group(['prefix' => 'absensi'], function(){

    Route::group(array('prefix' => 'histori-absensi'), function () {
        
        Route::get('/', 'Guru\Absensi\HistoriAbsensiController@viewHistoriAbsensi');
        Route::get('/{start_date}/{end_date}', 'Guru\Absensi\HistoriAbsensiController@viewHistoriAbsensi');
     

    });

});



        Route::group(array('prefix' => 'kesekretariatan'), function () {

            Route::group(array('prefix' => 'upload-dokumen'), function () {
                Route::get('/', 'Guru\Kesekretariatan\DokumenController@manageInputDokumen');
                Route::get('edit/{id}', 'Guru\Kesekretariatan\DokumenController@manageInputDokumen');
                Route::get('upload/{id}', 'Guru\Kesekretariatan\DokumenController@uploadInputDokumen');
            });

            // action upload dokumen
            Route::post('action-upload-dokumen/{mode}/{id}', 'Guru\Kesekretariatan\DokumenController@actionUploadDokumen');

            // ajax sub kategori
            Route::post('sub-kategori', 'Guru\Kesekretariatan\DokumenController@ajaxGetSubkategori');
        
            Route::group(array('prefix' => 'dokumen'), function () {
                Route::get('/', 'Guru\Kesekretariatan\DokumenController@viewDokumen');
                Route::get('detail/{id}', 'Guru\Kesekretariatan\DokumenController@viewDetailDokumen');              
                Route::post('datatables', 'Guru\Kesekretariatan\DokumenController@datatablesDokumen');
            });
        });
    });
});
