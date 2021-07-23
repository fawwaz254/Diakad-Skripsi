<?php
// ROLE KESISWAAN
Route::group(array('middleware'=> ['token_staff']), function () {
    Route::group(array('prefix' => 'kesiswaan'), function () {
        Route::get('welcome', 'Kesiswaan\WelcomeController@indexWelcome');

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

        /** ==== MODUL SKPI ==== **/
        Route::group(array('prefix' => 'skpi'), function () {

            Route::get('approve-prestasi-siswa', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@viewApprovePrestasiSiswa');
            Route::get('approve-prestasi-siswa/datatables', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@datatablesApprovePrestasiSiswa');
            Route::get('approve-prestasi-siswa/{id}/{param}', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@viewDetailPrestasiSiswa');
            Route::get('approve-prestasi-siswa/print/skpi/{id}', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@PrintSkpi');
            

            Route::get('approve-prestasi-siswa/prestasi/datatables/{id}/{param}', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@datatablesPrestasiApprovePrestasiSiswa');
            Route::get('approve-prestasi-siswa/kegiatan/datatables/{id}/{param}', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@datatablesKegiatanApprovePrestasiSiswa');


            Route::post('approve-prestasi-siswa/{data}/{id}', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@actionApprovePrestasiSiswa');
            Route::post('reject-prestasi-siswa/{data}/{id}', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@actionRejectPrestasiSiswa');

            Route::get('edit-prestasi-siswa/{id}', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@editPrestasiSiswa');
            Route::get('edit-kegiatan-siswa/{id}', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@editKegiatanSiswa');

            Route::post('action-edit-prestasi-siswa/{id}', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@actionEditPrestasiSiswa');
            Route::post('action-edit-kegiatan-siswa/{id}', 'Kesiswaan\SKPI\ApprovePrestasiSiswaController@actionEditKegiatanSiswa');

        });

        /** ==== MODUL Ekstrakurikuler ==== **/
        Route::group(array('prefix' => 'ekstrakurikuler'), function () {

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
            Route::get('setting-peserta-ekskul/setting/{id_ekskul}', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@setSettingPesertaEkskul');


            Route::post('action-setting-peserta-ekskul/{mode}/{id}', 'Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController@actionSettingPesertaEkskul');
        });

        /** ==== MODUL PENANGANAN SISWA ==== **/
        Route::group(array('prefix' => 'penanganan-siswa'), function () {
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

            // AJAX GET SUBKATEGORI PELANGGARAN BY KATEGORI
            Route::post('subkategori-bykategori', 'Kesiswaan\PenangananSiswa\InputPelanggaranController@ajaxGetSubkategoriByKategori');
        });

        /* TAMBAHAN SEMENTARA */
        /** === MODUL SISWA === **/
        Route::group(array('prefix' => 'siswa'), function () {
            // MENU Data Status Siswa
            Route::get('status-siswa', 'Pendidikan\DataAkademik\StatusSiswaController@viewStatusSiswa');
            Route::get('status-siswa/datatables', 'Pendidikan\DataAkademik\StatusSiswaController@datatablesStatusSiswa');
            Route::get('status-siswa/add', 'Pendidikan\DataAkademik\StatusSiswaController@addStatusSiswa');
            Route::get('status-siswa/edit/{id}', 'Pendidikan\DataAkademik\StatusSiswaController@editStatusSiswa');

            Route::post('action-status-siswa/{mode}/{id}', 'Pendidikan\DataAkademik\StatusSiswaController@actionStatusSiswa');
            
            // MENU DATA SISWA
            Route::get('data-siswa', 'Pendidikan\Siswa\DataSiswaController@viewDataSiswa');
            Route::get('data-siswa/get-kelas/{id_jurusan}', 'Pendidikan\Siswa\DataSiswaController@getKelas');
            Route::post('post-view-data-siswa', 'Pendidikan\Siswa\DataSiswaController@actionViewDataSiswa');
            Route::get('data-siswa/view-detail-data-siswa/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', 'Pendidikan\Siswa\DataSiswaController@viewDetailDataSiswa');
            Route::get('data-siswa/datatables/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', 'Pendidikan\Siswa\DataSiswaController@datatablesDataSiswa');

            //MENU UPDATE FOTO
            Route::get('update-foto', 'Pendidikan\Siswa\UpdateFotoController@viewUpdateFoto');
            Route::get('update-foto/batch', 'Pendidikan\Siswa\UpdateFotoController@viewBatchUpdateFoto');
            Route::post('post-view-update-foto', 'Pendidikan\Siswa\UpdateFotoController@actionViewUpdateFoto');
            Route::get('update-foto/view-detail-update-foto/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', 'Pendidikan\Siswa\UpdateFotoController@viewDetailUpdateFoto');
            Route::get('update-foto/datatables/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', 'Pendidikan\Siswa\UpdateFotoController@datatablesUpdateFoto');
            Route::get('update-foto/upload/{id_pengguna}', 'Pendidikan\Siswa\UpdateFotoController@viewUpload');
            Route::post('action-update-foto/{mode}/{id}', 'Pendidikan\Siswa\UpdateFotoController@actionUpdateFoto');
            Route::post('action-batch-upload-foto', 'Pendidikan\Siswa\UpdateFotoController@actionBatchUploadFoto');

            //MENU UPLOAD DATA SISWA
            Route::get('update-data-siswa', 'Pendidikan\Siswa\UploadDataSiswaController@updateDataSiswa');
            Route::get('upload-data-siswa', 'Pendidikan\Siswa\UploadDataSiswaController@viewUploadDataSiswa');
            Route::get('/download-file-excel', 'Pendidikan\Siswa\UploadDataSiswaController@downloadFileExcel')->name('siswa/download-file-excel');
            Route::post('post-file-excel', 'Pendidikan\Siswa\UploadDataSiswaController@uploadFileExcel');

            //MENU CARI SISWA
            Route::get('cari-siswa', 'Pendidikan\Siswa\CariSiswaController@viewCariSiswa');
            Route::post('post-view-cari-siswa', 'Pendidikan\Siswa\CariSiswaController@actionViewCariSiswa');
            Route::get('cari-siswa/view-detail/{nis_nama_siswa}', 'Pendidikan\Siswa\CariSiswaController@viewDetailCariSiswa');
            Route::get('cari-siswa/datatables/{nis_nama_siswa}', 'Pendidikan\Siswa\CariSiswaController@datatablesCariSiswa');
            Route::get('cari-siswa/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', 'Pendidikan\Siswa\CariSiswaController@viewDetailSiswaCariSiswa');

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

            //MENU SISWA AKTIF
            Route::get('siswa-aktif', 'Pendidikan\Siswa\SiswaAktifController@viewSiswaAktif');

            //MENU INSERT-UPDATE SISWA
            Route::get('insert-update-siswa', 'Pendidikan\Siswa\InsertUpdateSiswaController@viewInsertUpdateSiswa');
            Route::post('post-view-update-siswa', 'Pendidikan\Siswa\InsertUpdateSiswaController@actionViewUpdateSiswa');
            Route::get('insert-update-siswa/view-detail/{nis_nama_siswa}', 'Pendidikan\Siswa\InsertUpdateSiswaController@viewDetailUpdateSiswa');
            Route::get('insert-update-siswa/view-print-siswa/{nis_nama_siswa}', 'Pendidikan\Siswa\InsertUpdateSiswaController@viewPrintSiswa');
            Route::get('insert-update-siswa/view-cari-siswa/{nis_nama_siswa}','Pendidikan\Siswa\InsertUpdateSiswaController@viewCariUpdateSiswa');
            Route::get('insert-update-siswa/datatables/{nis_nama_siswa}', 'Pendidikan\Siswa\InsertUpdateSiswaController@datatablesCariSiswa');

            Route::post('action-insert-update-siswa/{mode}/{id}', 'Pendidikan\Siswa\InsertUpdateSiswaController@actionInsertUpdateSiswa');

            //MENU Setting Wali Murid
            Route::get('setting-wali-murid', 'Pendidikan\Siswa\SettingWaliMuridController@viewSettingWaliMurid');
            Route::get('setting-wali-murid/add', 'Pendidikan\Siswa\SettingWaliMuridController@viewSettingWaliMurid');
            Route::post('post-view-setting-wali-murid', 'Pendidikan\Siswa\SettingWaliMuridController@actionViewSettingWaliMurid');
            Route::get('setting-wali-murid/view-kelas/{id_kelas}', 'Pendidikan\Siswa\SettingWaliMuridController@viewKelasWaliMurid');
            Route::get('setting-wali-murid/datatables/{id_kelas}', 'Pendidikan\Siswa\SettingWaliMuridController@datatablesWaliMurid');
            Route::get('setting-wali-murid/edit/{id}', 'Pendidikan\Siswa\SettingWaliMuridController@editWaliMurid');

            Route::get('setting-wali-murid/upload-setting-wali-murid/{id_kelas}', 'Pendidikan\Siswa\SettingWaliMuridController@viewUploadSettingWaliMurid');
            Route::get('setting-wali-murid/upload-setting-wali-murid/download/{id_kelas}', 'Pendidikan\Siswa\SettingWaliMuridController@viewDownloadSettingWaliMurid');
            Route::post('setting-wali-murid/upload/{id_kelas}', 'Pendidikan\Siswa\SettingWaliMuridController@uploadFileExcel');

            Route::post('action-setting-wali-murid/{mode}/{id}', 'Pendidikan\Siswa\SettingWaliMuridController@actionSettingWaliMurid');

            Route::get('wali-murid/get-data', 'Pendidikan\Siswa\SettingWaliMuridController@actionGetWaliMurid');

            //MENU Setting Kelas Siswa
            Route::get('setting-kelas-siswa', 'Pendidikan\Siswa\SettingKelasSiswaController@viewSettingKelasSiswa');
            Route::post('post-view-setting-kelas-siswa', 'Pendidikan\Siswa\SettingKelasSiswaController@actionViewSettingKelasSiswa');
            Route::get('setting-kelas-siswa/view-kelas/{id_kelas}', 'Pendidikan\Siswa\SettingKelasSiswaController@viewKelasSettingKelas');
            Route::get('setting-kelas-siswa/datatables/{id_kelas}', 'Pendidikan\Siswa\SettingKelasSiswaController@datatablesKelasSiswa');
            Route::get('setting-kelas-siswa/datatables-siswa', 'Pendidikan\Siswa\SettingKelasSiswaController@datatablesSiswa');
            Route::get('setting-kelas-siswa/edit/{id}', 'Pendidikan\Siswa\SettingKelasSiswaController@tambahKelasSiswa');

            Route::post('action-setting-kelas-siswa/{mode}/{id}', 'Pendidikan\Siswa\SettingKelasSiswaController@actionSettingKelasSiswa');
        });

        /** ==== MODUL DATA SISWA ==== **/
        Route::group(array('prefix' => 'data-kesiswaan'), function () {
            //MENU ADMISI SISWA
            Route::get('admisi-siswa', 'Pendidikan\Siswa\AdmisiSiswaController@viewAdmisiSiswa');
            Route::post('post-view-admisi-siswa', 'Pendidikan\Siswa\AdmisiSiswaController@actionViewAdmisiSiswa');
            Route::get('admisi-siswa/view-detail/{nis_nama_siswa}', 'Pendidikan\Siswa\AdmisiSiswaController@viewDetailAdmisiSiswa');
            Route::post('action-admisi-siswa', 'Pendidikan\Siswa\AdmisiSiswaController@actionAdmisiSiswa');
            Route::get('admisi-siswa/generate', 'Pendidikan\Siswa\AdmisiSiswaController@generateAdmisiSiswa');
            Route::post('action-generate-admisi-siswa', 'Pendidikan\Siswa\AdmisiSiswaController@actionGenerateAdmisiSiswa');
            Route::get('admisi-siswa/generate/laporan', 'Pendidikan\Siswa\AdmisiSiswaController@laporanGenerateAdmisiSiswa');
            Route::post('post-view-laporan-admisi-siswa', 'Pendidikan\Siswa\AdmisiSiswaController@actionViewLaporanAdmisiSiswa');
            Route::get('admisi-siswa/view-laporan/{id_semester}/{id_kelas}', 'Pendidikan\Siswa\AdmisiSiswaController@viewLaporanAdmisiSiswa');
            Route::get('admisi-siswa/datatables/{id_semester}/{id_kelas}', 'Pendidikan\Siswa\AdmisiSiswaController@datatablesAdmisiSiswa');


            //MENU HISTORY ADMISI SISWA
            Route::get('histori-admisi-siswa', 'Pendidikan\Siswa\HistoryAdmisiSiswaController@viewHistoryAdmisiSiswa');
            Route::post('post-view-histori-admisi-siswa', 'Pendidikan\Siswa\HistoryAdmisiSiswaController@actionViewHistoryAdmisiSiswa');
            Route::get('histori-admisi-siswa/view-detail/{nis_nama_siswa}', 'Pendidikan\Siswa\HistoryAdmisiSiswaController@viewDetailHistoryAdmisiSiswa');
            Route::get('histori-admisi-siswa/datatables/{nis_siswa}', 'Pendidikan\Siswa\HistoryAdmisiSiswaController@datatablesHistoryAdmisiSiswa');
            Route::post('action-histori-admisi-siswa/{mode}/{id}', 'Pendidikan\Siswa\HistoryAdmisiSiswaController@actionHistoryAdmisiSiswa');
         
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

            //MENU Kegiatan Siswa
            Route::get('kegiatan-siswa', 'Kesiswaan\Siswa\KegiatanSiswaController@viewKegiatanSiswa');
            Route::get('kegiatan-siswa/datatables', 'Kesiswaan\Siswa\KegiatanSiswaController@datatablesKegiatanSiswa');
            Route::get('kegiatan-siswa/add', 'Kesiswaan\Siswa\KegiatanSiswaController@addKegiatanSiswa');
            Route::get('kegiatan-siswa/edit/{id}', 'Kesiswaan\Siswa\KegiatanSiswaController@editKegiatanSiswa');

            Route::post('action-kegiatan-siswa/{mode}/{id}', 'Kesiswaan\Siswa\KegiatanSiswaController@actionKegiatanSiswa');

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
        
        /** ==== MODUL WISUDA ==== **/
        Route::group(array('prefix' => 'wisuda'), function () {
            // MENU Nama Wisuda
            Route::get('nama-wisuda', 'Pendidikan\Wisuda\WisudaController@viewWisuda');
            Route::get('nama-wisuda/datatables', 'Pendidikan\Wisuda\WisudaController@datatablesWisuda');
            Route::get('nama-wisuda/add', 'Pendidikan\Wisuda\WisudaController@addWisuda');
            Route::get('nama-wisuda/edit/{id}', 'Pendidikan\Wisuda\WisudaController@editWisuda');

            Route::post('action-nama-wisuda/{mode}/{id}', 'Pendidikan\Wisuda\WisudaController@actionWisuda');

            // MENU Periode Wisuda
            Route::get('periode-wisuda', 'Pendidikan\Wisuda\PeriodeWisudaController@viewPeriodeWisuda');
            Route::get('periode-wisuda/datatables', 'Pendidikan\Wisuda\PeriodeWisudaController@datatablesPeriodeWisuda');
            Route::get('periode-wisuda/add', 'Pendidikan\Wisuda\PeriodeWisudaController@addPeriodeWisuda');
            Route::get('periode-wisuda/edit/{id}', 'Pendidikan\Wisuda\PeriodeWisudaController@editPeriodeWisuda');

            Route::post('action-periode-wisuda/{mode}/{id}', 'Pendidikan\Wisuda\PeriodeWisudaController@actionPeriodeWisuda');

            // MENU Pengajuan Wisuda
            Route::get('pengajuan-wisuda', 'Pendidikan\Wisuda\PengajuanWisudaController@viewPengajuanWisuda');
            Route::post('post-view-pengajuan-wisuda', 'Pendidikan\Wisuda\PengajuanWisudaController@actionViewDetailPengajuanWisuda');
            Route::get('pengajuan-wisuda/view-detail/{id_periode_wisuda}/{id_kelas}', 'Pendidikan\Wisuda\PengajuanWisudaController@viewDetailPengajuanWisuda');
            Route::get('pengajuan-wisuda/datatables/{id_periode_wisuda}/{id_kelas}', 'Pendidikan\Wisuda\PengajuanWisudaController@datatablesPengajuanWisuda');
            Route::get('pengajuan-wisuda/cancel/{id}/{id_periode_wisuda}/{id_kelas}/', 'Pendidikan\Wisuda\PengajuanWisudaController@cancelPengajuanWisuda');

            Route::post('action-pengajuan-wisuda/{mode}/{id}/{id_siswa}/{id_periode_wisuda}', 'Pendidikan\Wisuda\PengajuanWisudaController@actionPengajuanWisuda');

            // MENU Entri Data Wisuda
            Route::get('entri-wisuda', 'Pendidikan\Wisuda\EntriWisudaController@viewEntriWisuda');
            Route::post('post-view-entri-wisuda', 'Pendidikan\Wisuda\EntriWisudaController@actionViewDetailEntriWisuda');
            Route::get('entri-wisuda/view-detail/{id_periode_wisuda}/{id_kelas}', 'Pendidikan\Wisuda\EntriWisudaController@viewDetailEntriWisuda');
            Route::get('entri-wisuda/datatables/{id_periode_wisuda}/{id_kelas}', 'Pendidikan\Wisuda\EntriWisudaController@datatablesEntriWisuda');
            Route::get('entri-wisuda/input/{id}/{id_periode_wisuda}/{id_kelas}/', 'Pendidikan\Wisuda\EntriWisudaController@inputEntriWisuda');

            Route::post('action-entri-wisuda/{mode}/{id}/{id_siswa}/{id_periode_wisuda}', 'Pendidikan\Wisuda\EntriWisudaController@actionEntriWisuda');

            // MENU Set Lulus Siswa ==== (BELOM SEMUA) ====
            Route::get('set-lulus', 'Pendidikan\Wisuda\SetLulusController@viewSetLulus');
            Route::post('post-view-set-lulus', 'Pendidikan\Wisuda\SetLulusController@actionViewDetailSetLulus');
            Route::get('set-lulus/view-detail/{id_periode_wisuda}/{id_kelas}', 'Pendidikan\Wisuda\SetLulusController@viewDetailSetLulus');
            Route::get('set-lulus/datatables/{id_periode_wisuda}/{id_kelas}', 'Pendidikan\Wisuda\SetLulusController@datatablesSetLulus');

            Route::post('action-set-lulus/{mode}/{id}', 'Pendidikan\Wisuda\SetLulusController@actionSetLulus');

            Route::group(array('prefix' => 'laporan-wisuda'), function () {
                 Route::get('/', 'Pendidikan\Wisuda\LaporanWisudaController@viewLaporanWisuda');
                 Route::get('print-laporan-wisuda/{id_periode}', 'Pendidikan\Wisuda\LaporanWisudaController@printLaporanWisuda');
            });
        });
        
        /** ==== MODUL PENDAFTARAN ==== **/
        Route::group(array('prefix' => 'pendaftaran'), function () {
            // MENU Data Penerimaan (ambil dari Role PPDB)
            Route::get('penerimaan', 'PPDB\Pendaftaran\PenerimaanController@viewPenerimaan');
            Route::get('penerimaan/datatables', 'PPDB\Pendaftaran\PenerimaanController@datatablesPenerimaan');
            Route::get('penerimaan/add', 'PPDB\Pendaftaran\PenerimaanController@addPenerimaan');
            Route::get('penerimaan/edit/{id}', 'PPDB\Pendaftaran\PenerimaanController@editPenerimaan');

            Route::post('action-penerimaan/{mode}/{id}', 'PPDB\Pendaftaran\PenerimaanController@actionPenerimaan');
        });
        
        /** ==== MODUL IJAZAH ==== **/
        Route::group(array('prefix' => 'ijazah'), function () {
            // MENU Data Pengambilan Ijazah
            Route::get('pengambilan-ijazah', 'Kesiswaan\Ijazah\PengambilanIjazahController@viewPengambilanIjazah');
            Route::get('pengambilan-ijazah/datatables', 'Kesiswaan\Ijazah\PengambilanIjazahController@datatablesPengambilanIjazah');
            Route::get('pengambilan-ijazah/add', 'Kesiswaan\Ijazah\PengambilanIjazahController@addPengambilanIjazah');
            Route::get('pengambilan-ijazah/edit/{id}', 'Kesiswaan\Ijazah\PengambilanIjazahController@editPengambilanIjazah');
            Route::get('pengambilan-ijazah/print/{id}', 'Kesiswaan\Ijazah\PengambilanIjazahController@printPengambilanIjazah');

            Route::post('action-pengambilan-ijazah/{mode}/{id}', 'Kesiswaan\Ijazah\PengambilanIjazahController@actionPengambilanIjazah');
        });
    });
});
