<?php
// ROLE KESISWAAN
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'kesiswaan'), function() {
        Route::get('welcome', 'Kesiswaan\WelcomeController@indexWelcome');

         /** ==== MODUL Ekstrakurikuler ==== **/
        Route::group(array('prefix' => 'ekstrakurikuler'), function() {

            // MENU Data Ekstrakurikuler
            Route::get('data-ekskul', 'Kesiswaan\Ekstrakurikuler\DataEkskulController@viewDataEkskul');
            Route::get('data-ekskul/datatables', 'Kesiswaan\Ekstrakurikuler\DataEkskulController@datatablesDataEkskul');
			Route::get('data-ekskul/add', 'Kesiswaan\Ekstrakurikuler\DataEkskulController@addDataEkskul');
            Route::get('data-ekskul/edit/{id}', 'Kesiswaan\Ekstrakurikuler\DataEkskulController@editDataEkskul');

            Route::post('action-data-ekskul/{mode}/{id}', 'Kesiswaan\Ekstrakurikuler\DataEkskulController@actionDataEkskul');

            //MENU Ekskul Wajib
            Route::get('ekskul-wajib', 'Kesiswaan\Ekstrakurikuler\EkskulWajibController@viewEkskulWajib');
            Route::get('ekskul-wajib/datatables', 'Kesiswaan\Ekstrakurikuler\EkskulWajibController@datatablesEkskulWajib');
			Route::get('ekskul-wajib/add', 'Kesiswaan\Ekstrakurikuler\EkskulWajibController@addEkskulWajib');
			Route::get('ekskul-wajib/edit/{id}', 'Kesiswaan\Ekstrakurikuler\EkskulWajibController@editEkskulWajib');

            Route::post('action-ekskul-wajib/{mode}/{id}', 'Kesiswaan\Ekstrakurikuler\EkskulWajibController@actionEkskulWajib');

            //MENU Setting Pelatih Ekskul
            Route::get('setting-pelatih-ekskul', 'Kesiswaan\Ekstrakurikuler\SettingPelatihEkskulController@viewSettingPelatihEkskul');
            Route::get('setting-pelatih-ekskul/datatables', 'Kesiswaan\Ekstrakurikuler\SettingPelatihEkskulController@datatablesSettingPelatihEkskul');
            Route::get('setting-pelatih-ekskul/datatables-pelatih', 'Kesiswaan\Ekstrakurikuler\SettingPelatihEkskulController@datatablesPelatihEkskul');
            Route::get('setting-pelatih-ekskul/add', 'Kesiswaan\Ekstrakurikuler\SettingPelatihEkskulController@addSettingPelatihEkskul');
            Route::get('setting-pelatih-ekskul/view-pelatih', 'Kesiswaan\Ekstrakurikuler\SettingPelatihEkskulController@viewPelatihEkskul');
            Route::get('setting-pelatih-ekskul/edit/{id}', 'Kesiswaan\Ekstrakurikuler\SettingPelatihEkskulController@editSettingPelatihEkskul');
            Route::get('setting-pelatih-ekskul/edit-status/{id}', 'Kesiswaan\Ekstrakurikuler\SettingPelatihEkskulController@editStatusSettingPelatihEkskul');
            Route::get('setting-pelatih-ekskul/assign/{id}', 'Kesiswaan\Ekstrakurikuler\SettingPelatihEkskulController@assignSettingPelatihEkskul');

            Route::post('action-setting-pelatih-ekskul/{mode}/{id}', 'Kesiswaan\Ekstrakurikuler\SettingPelatihEkskulController@actionSettingPelatihEkskul');

            //MENU Setting Pembina Ekskul
            Route::get('setting-pembina-ekskul', 'Kesiswaan\Ekstrakurikuler\SettingPembinaEkskulController@viewSettingPembinaEkskul');
            Route::get('setting-pembina-ekskul/datatables', 'Kesiswaan\Ekstrakurikuler\SettingPembinaEkskulController@datatablesSettingPembinaEkskul');
            Route::get('setting-pembina-ekskul/assign/{id}', 'Kesiswaan\Ekstrakurikuler\SettingPembinaEkskulController@assignSettingPembinaEkskul');

            Route::post('action-setting-pembina-ekskul/{mode}/{id}', 'Kesiswaan\Ekstrakurikuler\SettingPembinaEkskulController@actionSettingPembinaEkskul');

            //MENU Setting Peserta Ekskul
            Route::get('setting-peserta-ekskul', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@viewSettingPesertaEkskul');
            Route::post('post-view-setting-peserta-ekskul', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@actionViewSettingPesertaEkskul');
            Route::get('setting-peserta-ekskul/view-ekskul/{id}', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@viewEkskulSettingPesertaEkskul');
            Route::get('setting-peserta-ekskul/datatables/{id_ekskul}', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@datatablesSettingPesertaEkskul');
            Route::get('setting-peserta-ekskul/datatables-siswa/{id_kelas}', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@datatablesSiswaSettingPesertaEkskul');
            Route::get('setting-peserta-ekskul/add/{id_ekskul}', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@addSettingPesertaEkskul');
            Route::post('post-add-setting-peserta-ekskul', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@actionAddSettingPesertaEkskul');
            Route::get('setting-peserta-ekskul/view-kelas/{id_ekskul}/{id_kelas}', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@viewKelasSettingPesertaEkskul');
            Route::get('setting-peserta-ekskul/edit/{id}', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@editSettingPesertaEkskul');


            Route::post('action-setting-peserta-ekskul/{mode}/{id}', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@actionSettingPesertaEkskul');
        });
        Route::group(array('prefix' => 'penanganan-siswa'), function() {
            // MENU Data Jenis Tindakan
            Route::get('jenis-tindakan', 'Kesiswaan\PenangananSiswa\JenisTindakanController@viewJenisTindakan');
            Route::get('jenis-tindakan/datatables', 'Kesiswaan\PenangananSiswa\JenisTindakanController@datatablesJenisTindakan');
            Route::get('jenis-tindakan/add', 'Kesiswaan\PenangananSiswa\JenisTindakanController@addJenisTindakan');
            Route::get('jenis-tindakan/edit/{id}', 'Kesiswaan\PenangananSiswa\JenisTindakanController@editJenisTindakan');

            Route::post('action-jenis-tindakan/{mode}/{id}', 'Kesiswaan\PenangananSiswa\JenisTindakanController@actionJenisTindakan');

            // MENU Input Pelanggaran Siswa
            Route::get('input-pelanggaran', 'Kesiswaan\PenangananSiswa\InputPelanggaranController@viewInputPelanggaran');
            Route::get('input-pelanggaran/datatables', 'Kesiswaan\PenangananSiswa\InputPelanggaranController@datatablesInputPelanggaran');
            Route::get('input-pelanggaran/add', 'Kesiswaan\PenangananSiswa\InputPelanggaranController@addInputPelanggaran');
            Route::get('input-pelanggaran/edit/{id}', 'Kesiswaan\PenangananSiswa\InputPelanggaranController@editInputPelanggaran');

            Route::post('action-input-pelanggaran/{mode}/{id}', 'Kesiswaan\PenangananSiswa\InputPelanggaranController@actionInputPelanggaran');

            // MENU Tindakan Pelanggaran
            Route::get('tindakan-pelanggaran', 'Kesiswaan\PenangananSiswa\TindakanPelanggaranController@viewTindakanPelanggaran');
            Route::get('tindakan-pelanggaran/datatables-belum-nonkbm', 'Kesiswaan\PenangananSiswa\TindakanPelanggaranController@datatablesBelumTindakanNonKBM');
            Route::get('tindakan-pelanggaran/datatables-belum-kbm', 'Kesiswaan\PenangananSiswa\TindakanPelanggaranController@datatablesBelumTindakanKBM');
            Route::get('tindakan-pelanggaran/datatables-sudah', 'Kesiswaan\PenangananSiswa\TindakanPelanggaranController@datatablesSudahTindakan');
            Route::get('tindakan-pelanggaran/add-nonkbm/{id}', 'Kesiswaan\PenangananSiswa\TindakanPelanggaranController@addTindakanPelanggaranNonKBM');
            Route::get('tindakan-pelanggaran/add-kbm/{id}', 'Kesiswaan\PenangananSiswa\TindakanPelanggaranController@addTindakanPelanggaranKBM');
            Route::get('tindakan-pelanggaran/edit/{id}', 'Kesiswaan\PenangananSiswa\TindakanPelanggaranController@editTindakanPelanggaran');

            Route::post('action-tindakan-pelanggaran/{mode}/{id}', 'Kesiswaan\PenangananSiswa\TindakanPelanggaranController@actionTindakanPelanggaran');
            
            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', 'Kesiswaan\PenangananSiswa\InputPelanggaranController@ajaxGetSiswaByKelas');
        });

        Route::group(array('prefix' => 'siswa'), function() {
            // MENU Evaluasi Siswa
            Route::get('evaluasi-siswa', 'Kesiswaan\Siswa\EvaluasiSiswaController@viewEvaluasiSiswa');
            Route::post('post-view-evaluasi-siswa', 'Kesiswaan\Siswa\EvaluasiSiswaController@actionViewEvaluasiSiswa');
            Route::get('evaluasi-siswa/view-detail/{nis_nama_siswa}', 'Kesiswaan\Siswa\EvaluasiSiswaController@viewDetailEvaluasiSiswa');
            Route::get('evaluasi-siswa/datatables/{nis_nama_siswa}', 'Kesiswaan\Siswa\EvaluasiSiswaController@datatablesEvaluasiSiswa');
            Route::get('evaluasi-siswa/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', 'Kesiswaan\Siswa\EvaluasiSiswaController@viewDetailSiswaEvaluasiSiswa');

            //datatable evaluasi siswa detail
            Route::get('evaluasi-siswa/datatables-beasiswa/{nis_nama_siswa}', 'Kesiswaan\Siswa\EvaluasiSiswaController@datatablesBeasiswa');
            Route::get('evaluasi-siswa/datatables-prestasi/{nis_nama_siswa}', 'Kesiswaan\Siswa\EvaluasiSiswaController@datatablesPrestasi');
            Route::get('evaluasi-siswa/datatables-ekskul/{nis_nama_siswa}', 'Kesiswaan\Siswa\EvaluasiSiswaController@datatablesEkskul');

            //MENU Pembayaran
            Route::get('pembayaran', 'Kesiswaan\Siswa\PembayaranController@viewPembayaran');
            Route::post('post-view-pembayaran', 'Kesiswaan\Siswa\PembayaranController@actionViewPembayaran');
            Route::get('pembayaran/view-detail/{nis_nama_siswa}', 'Kesiswaan\Siswa\PembayaranController@viewDetailPembayaran');
            Route::get('pembayaran/datatables/{nis_nama_siswa}', 'Kesiswaan\Siswa\PembayaranController@datatablesPembayaran');
            Route::get('pembayaran/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', 'Kesiswaan\Siswa\PembayaranController@viewDetailSiswaPembayaran');
            Route::get('pembayaran/datatables-tagihan/{id_pengguna}/{nis_nama_siswa}', 'Kesiswaan\Siswa\PembayaranController@datatablesTagihanPembayaran');
            Route::get('pembayaran/datatables-riwayat-bayar/{id_pengguna}', 'Kesiswaan\Siswa\PembayaranController@datatablesRiwayatBayarSiswa');
         
            //MENU Tingkat Prestasi Siswa
            Route::get('tingkat-prestasi-siswa', 'Kesiswaan\Siswa\TingkatPrestasiSiswaController@viewTingkatPrestasiSiswa');
            Route::get('tingkat-prestasi-siswa/datatables', 'Kesiswaan\Siswa\TingkatPrestasiSiswaController@datatablesTingkatPrestasiSiswa');
            Route::get('tingkat-prestasi-siswa/add', 'Kesiswaan\Siswa\TingkatPrestasiSiswaController@addTingkatPrestasiSiswa');
            Route::get('tingkat-prestasi-siswa/edit/{id}', 'Kesiswaan\Siswa\TingkatPrestasiSiswaController@editTingkatPrestasiSiswa');

            Route::post('action-tingkat-prestasi-siswa/{mode}/{id}', 'Kesiswaan\Siswa\TingkatPrestasiSiswaController@actionTingkatPrestasiSiswa');

            //MENU Prestasi Siswa
            Route::get('prestasi-siswa', 'Kesiswaan\Siswa\PrestasiSiswaController@viewPrestasiSiswa');
            Route::get('prestasi-siswa/datatables', 'Kesiswaan\Siswa\PrestasiSiswaController@datatablesPrestasiSiswa');
            Route::get('prestasi-siswa/add', 'Kesiswaan\Siswa\PrestasiSiswaController@addPrestasiSiswa');
            Route::get('prestasi-siswa/edit/{id}', 'Kesiswaan\Siswa\PrestasiSiswaController@editPrestasiSiswa');

            Route::post('action-prestasi-siswa/{mode}/{id}', 'Kesiswaan\Siswa\PrestasiSiswaController@actionPrestasiSiswa');

            //MENU Beasiswa Siswa
            Route::get('beasiswa-siswa', 'Kesiswaan\Siswa\BeasiswaSiswaController@viewBeasiswaSiswa');
            Route::get('beasiswa-siswa/datatables', 'Kesiswaan\Siswa\BeasiswaSiswaController@datatablesBeasiswaSiswa');
            Route::get('beasiswa-siswa/add', 'Kesiswaan\Siswa\BeasiswaSiswaController@addBeasiswaSiswa');
            Route::get('beasiswa-siswa/edit/{id}', 'Kesiswaan\Siswa\BeasiswaSiswaController@editBeasiswaSiswa');

            Route::post('action-beasiswa-siswa/{mode}/{id}', 'Kesiswaan\Siswa\BeasiswaSiswaController@actionBeasiswaSiswa');

            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', 'Kesiswaan\Siswa\PrestasiSiswaController@ajaxGetSiswaByKelas');
            Route::post('siswa-bykelas', 'Kesiswaan\Siswa\BeasiswaSiswaController@ajaxGetSiswaByKelas');

            //MENU Home Visit
            Route::get('home-visit', 'Kesiswaan\Siswa\HomeVisitController@viewHomeVisit');
            Route::get('home-visit/datatables/{id}', 'Kesiswaan\Siswa\HomeVisitController@datatablesHomeVisit');
            Route::get('home-visit/edit/{id}', 'Kesiswaan\Siswa\HomeVisitController@editHomeVisit');

            Route::post('action-home-visit/{mode}/{id}', 'Kesiswaan\Siswa\HomeVisitController@actionHomeVisit');

        });
    });
});