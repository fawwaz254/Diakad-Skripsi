<?php
// ROLE AKADEMIK
Route::group(array('middleware' => ['token_staff']), function () {
    Route::group(array('prefix' => 'akademik'), function () {
        Route::get('welcome', 'Akademik\WelcomeController@indexWelcome');

        //modul MGMP
        Route::group(array('prefix' => 'mpmp'), function () {
            Route::group(array('prefix' => 'jenis-mgmp'), function () {
                Route::get('/','Akademik\MGMP\JenisMGMPcontroller@viewDataJenis');
                Route::get('/datatables', 'Akademik\MGMP\JenisMGMPcontroller@datatablesjenis');
                Route::get('/add', 'Akademik\MGMP\JenisMGMPcontroller@addDataJenis');
                Route::post('action-data-kategori/{mode}/{id}', 'Akademik\MGMP\JenisMGMPcontroller@actionDataJenis');
            });

            Route::group(array('prefix' => 'data-kategori-mapel'), function () {
                Route::get('/', 'Akademik\MGMP\DataKategoriMGMPController@viewDataKategori');
                Route::get('/datatables', 'Akademik\MGMP\DataKategoriMGMPController@datatablesCategoryfile');
                Route::get('/add', 'Akademik\MGMP\DataKategoriMGMPController@addDataKategori');
                Route::get('/edit/{category_file_id}', 'Akademik\MGMP\DataKategoriMGMPController@editDataKategori');
                Route::post('action-data-kategori/{mode}/{id}', 'Akademik\MGMP\DataKategoriMGMPController@actionDataKategori');
            });

            Route::group(array('prefix' => 'laporan-mgmp'), function () {
                Route::get('/', 'Akademik\MGMP\DataKategoriMGMPController@viewLaporanAllMGMP');
                Route::get('/datatables', 'Akademik\MGMP\DataKategoriMGMPController@datatablesKerjaHarianAllMGMP');
                Route::get('preview-file/{id}', 'Akademik\MGMP\DataKategoriMGMPController@previewFile');
            });

        });


        /** ==== MODUL MANAJEMEN FILE ==== **/
        // url: /akademik/manajemen-file
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
                Route::get('download/{id}', 'ManajemenFile\DataFileController@downloadDataFile');
            });
        });

        /** ==== MODUL DATA AKADEMIK ==== **/
        Route::group(array('prefix' => 'data-akademik'), function () {
            // MENU Kurikulum
            Route::get('kurikulum', 'Akademik\DataAkademik\KurikulumController@viewKurikulum');
            Route::get('kurikulum/datatables', 'Akademik\DataAkademik\KurikulumController@datatablesKurikulum');
            Route::get('kurikulum/add', 'Akademik\DataAkademik\KurikulumController@addKurikulum');
            Route::get('kurikulum/edit/{id}', 'Akademik\DataAkademik\KurikulumController@editKurikulum');

            Route::post('action-kurikulum/{mode}/{id}', 'Akademik\DataAkademik\KurikulumController@actionKurikulum');


            // MENU Aktivasi Kurikulum
            Route::get('aktivasi-kurikulum', 'Akademik\DataAkademik\AktivasiKurikulumController@viewAktivasiKurikulum');
            Route::get('aktivasi-kurikulum/datatables', 'Akademik\DataAkademik\AktivasiKurikulumController@datatablesAktivasiKurikulum');
            Route::get('aktivasi-kurikulum/aktivasi/{id}', 'Akademik\DataAkademik\AktivasiKurikulumController@aktivasiKurikulum');

            Route::post('action-aktivasi-kurikulum/{mode}/{id}', 'Akademik\DataAkademik\AktivasiKurikulumController@actionAktivasiKurikulum');

            // MENU Data Mata Pelajaran
            Route::get('mata-pelajaran', 'Akademik\DataAkademik\MataPelajaranController@viewMataPelajaran');
            Route::get('mata-pelajaran/datatables', 'Akademik\DataAkademik\MataPelajaranController@datatablesMataPelajaran');
            Route::get('mata-pelajaran/add', 'Akademik\DataAkademik\MataPelajaranController@addMataPelajaran');
            Route::get('mata-pelajaran/edit/{id}', 'Akademik\DataAkademik\MataPelajaranController@editMataPelajaran');

            Route::post('action-mata-pelajaran/{mode}/{id}', 'Akademik\DataAkademik\MataPelajaranController@actionMataPelajaran');


            //MENU Data Jenis Mata Pelajaran
            Route::get('jenis-mata-pelajaran', 'Akademik\DataAkademik\DataJenisMataPelajaranController@viewDataJenisMataPelajaran');
            Route::get('jenis-mata-pelajaran/datatables', 'Akademik\DataAkademik\DataJenisMataPelajaranController@datatablesJenisMataPelajaran');
            Route::get('jenis-mata-pelajaran/add', 'Akademik\DataAkademik\DataJenisMataPelajaranController@addJenisMataPelajaran');
            Route::get('jenis-mata-pelajaran/edit/{id}', 'Akademik\DataAkademik\DataJenisMataPelajaranController@editJenisMataPelajaran');

            Route::post('action-jenis-mata-pelajaran/{mode}/{id}', 'Akademik\DataAkademik\DataJenisMataPelajaranController@actionJenisMataPelajaran');

            //MENU Setup Mapel Kurikulum
            Route::get('setup-mp-kurikulum', 'Akademik\DataAkademik\SetupMapelKurikulumController@viewSetupMapelKurikulum');
            Route::post('post-cari-kurikulum', 'Akademik\DataAkademik\SetupMapelKurikulumController@actionCariKurikulum');
            Route::get('setup-mp-kurikulum/view-mapel-kurikulum/{id}', 'Akademik\DataAkademik\SetupMapelKurikulumController@viewKurikulumMataPelajaran');
            Route::get('setup-mp-kurikulum/datatables/{id}', 'Akademik\DataAkademik\SetupMapelKurikulumController@datatablesSetupMapelKurikulum');
            Route::get('setup-mp-kurikulum/add/{id}', 'Akademik\DataAkademik\SetupMapelKurikulumController@addMapelKurikulum');
            Route::get('setup-mp-kurikulum/datatables-mapel/{id}', 'Akademik\DataAkademik\SetupMapelKurikulumController@datatablesaddMapelKurikulum');

            Route::post('action-setup-mp-kurikulum/{mode}/{id}', 'Akademik\DataAkademik\SetupMapelKurikulumController@actionJenisMataPelajaran');
        });

        Route::group(array('prefix' => 'kegiatan-harian'), function () {

            Route::group(array('prefix' => 'mengisi-form-kesehatan'), function () {
                // MENU Mengisi form kesehatan
                Route::get('/', 'Tendik\KegiatanHarian\FormKesehatanController@viewFormKesehatan');
                Route::get('add', 'Tendik\KegiatanHarian\FormKesehatanController@viewAddFormKesehatan');
                Route::get('detail/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');

                Route::post('action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
                Route::post('datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');
            });
        });

        // MODUL AKTIVITAS SEMESTER
        Route::group(array('prefix' => 'aktivitas-semester'), function () {
            // MENU Usulan Mata Ajar
            Route::get('usulan-mata-ajar', 'Akademik\AktivitasSemester\UsulanMataAjarController@viewUsulanMataAjar');
            Route::post('post-usulan-mata-ajar', 'Akademik\AktivitasSemester\UsulanMataAjarController@actionViewUsulanMataAjar');
            Route::get('usulan-mata-ajar/view-semester-usulan-mata-ajar/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@viewSemesterUsulanMataAjar');
            Route::get('usulan-mata-ajar/tambah-mata-ajar/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@viewTambahMataAjar');
            Route::get('usulan-mata-ajar/datatables/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@datatablesUsulanMataAjar');
            Route::get('usulan-mata-ajar/datatablesMapel/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@datatablesMataPelajaran');
            Route::get('usulan-mata-ajar/add/{id_semester}/{id_mata_pelajaran}', 'Akademik\AktivitasSemester\UsulanMataAjarController@addUsulanMataAjar');
            Route::get('usulan-mata-ajar/edit/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@editUsulanMataAjar');
            Route::get('usulan-mata-ajar/copy/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@copyUsulanMataAjar');
            Route::get('usulan-mata-ajar/copy-semester-lain/{id_semester}', 'Akademik\AktivitasSemester\UsulanMataAjarController@copyJadwalSemesterLain');

            Route::post('usulan-mata-ajar/hapus-jadwal/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@hapusJadwal');

            Route::post('usulan-mata-ajar/cek-jadwal-crash/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@cekJadwalCrash');
            Route::post('action-usulan-mata-ajar/{mode}/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@actionUsulanMataAjar');

            // MENU Monitoring Kelas
            Route::get('monitoring-kelas', 'Akademik\AktivitasSemester\MonitoringKelasController@viewMonitoringKelas');
            Route::post('post-monitoring-kelas', 'Akademik\AktivitasSemester\MonitoringKelasController@actionViewMonitoringKelas');
            Route::get('monitoring-kelas/view-semester-monitoring-kelas/{id}', 'Akademik\AktivitasSemester\MonitoringKelasController@viewSemesterMonitoringKelas');
            Route::get('monitoring-kelas/datatables/{id}', 'Akademik\AktivitasSemester\MonitoringKelasController@datatablesMonitoringKelas');
            Route::get('monitoring-kelas/view-daftar-siswa/{id}', 'Akademik\AktivitasSemester\MonitoringKelasController@viewDaftarSiswa');
            Route::get('monitoring-kelas/datatables-daftar-siswa/{id}', 'Akademik\AktivitasSemester\MonitoringKelasController@datatablesDaftarSiswa');

            //MENU Plotting Mapel Siswa
            Route::get('plotting-mapel-siswa', 'Akademik\AktivitasSemester\PlottingMapelSiswaController@viewPlottingMapelSiswa');
            Route::post('post-plotting-mapel-siswa', 'Akademik\AktivitasSemester\PlottingMapelSiswaController@actionViewPlottingMapelSiswa');
            Route::get('plotting-mapel-siswa/view-kelas-plotting/{id_semester}/{angkatan}', 'Akademik\AktivitasSemester\PlottingMapelSiswaController@viewKelasPlottingMapelSiswa');
            Route::get('plotting-mapel-siswa/datatables/{id_semester}/{angkatan}', 'Akademik\AktivitasSemester\PlottingMapelSiswaController@datatablesPlottingMapelSiswa');
            Route::get('plotting-mapel-siswa/view-mapel-plotting/{id_semester}/{angkatan}/{id_jurusan}', 'Akademik\AktivitasSemester\PlottingMapelSiswaController@viewMapelPlottingMapelSiswa');
            Route::get('plotting-mapel-siswa/datatables-mapel/{id_semester}/{angkatan}/{tingkat}', 'Akademik\AktivitasSemester\PlottingMapelSiswaController@datatablesMataPelajaran');
            Route::get('plotting-mapel-siswa/datatables-siswa/{angkatan}/{id_kelas}', 'Akademik\AktivitasSemester\PlottingMapelSiswaController@datatablesSiswa');
            Route::post('post-daftar-plotting-mapel-siswa', 'Akademik\AktivitasSemester\PlottingMapelSiswaController@actionViewDaftarPlottingMapelSiswa');
            Route::get('plotting-mapel-siswa/view-daftar-kelas-plotting/{id_semester}/{angkatan}/{id_kelas}', 'Akademik\AktivitasSemester\PlottingMapelSiswaController@viewDaftarKelasPlottingMapelSiswa');

            Route::post('action-plotting-mapel-siswa/{mode}', 'Akademik\AktivitasSemester\PlottingMapelSiswaController@actionPlottingMapelSiswa');

            //MENU Hapus Plotting Mapel Siswa
            Route::get('hapus-plotting-mapel-siswa', 'Akademik\AktivitasSemester\HapusPlottingMapelSiswaController@viewHapusPlottingMapelSiswa');
            Route::post('post-hapus-plotting-mapel-siswa', 'Akademik\AktivitasSemester\HapusPlottingMapelSiswaController@actionViewHapusPlottingMapelSiswa');
            Route::get('hapus-plotting-mapel-siswa/view-semester-hapus-plotting-mapel-siswa/{id}', 'Akademik\AktivitasSemester\HapusPlottingMapelSiswaController@viewSemesterHapusPlottingMapelSiswa');
            Route::get('hapus-plotting-mapel-siswa/view-detail-hapus-plotting-mapel-siswa/{id}', 'Akademik\AktivitasSemester\HapusPlottingMapelSiswaController@viewDetailHapusPlottingMapelSiswa');
            Route::get('hapus-plotting-mapel-siswa/datatables/{id}', 'Akademik\AktivitasSemester\HapusPlottingMapelSiswaController@datatablesHapusPlottingMapelSiswa');

            Route::post('action-hapus-plotting-mapel-siswa', 'Akademik\AktivitasSemester\HapusPlottingMapelSiswaController@actionHapusPlottingMapelSiswa');

            //MENU CARI SISWA
            Route::get('cari-siswa', 'Akademik\AktivitasSemester\CariSiswaController@viewCariSiswa');
            Route::post('post-view-cari-siswa', 'Akademik\AktivitasSemester\CariSiswaController@actionViewCariSiswa');
            Route::get('cari-siswa/view-detail/{nis_nama_siswa}', 'Akademik\AktivitasSemester\CariSiswaController@viewDetailCariSiswa');
            Route::get('cari-siswa/datatables/{nis_nama_siswa}', 'Akademik\AktivitasSemester\CariSiswaController@datatablesCariSiswa');
            Route::get('cari-siswa/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', 'Akademik\AktivitasSemester\CariSiswaController@viewDetailSiswaCariSiswa');

            //MENU Input Nilai
            Route::get('input-nilai', 'Akademik\AktivitasSemester\InputNilaiController@viewInputNilai');
            Route::post('post-view-input-nilai', 'Akademik\AktivitasSemester\InputNilaiController@actionInputNilai');
            Route::get('input-nilai/view-guru-input-nilai/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@viewGuruInputNilai');
            Route::post('post-view-komponen-nilai', 'Akademik\AktivitasSemester\InputNilaiController@actionViewKelasKomponenNilai');
            // view komponen
            Route::get('input-nilai/view-kelas/{id_kelas_mp}/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@viewKelasKomponenNilai');
            Route::get('input-nilai/datatables/{id_kelas_mp}', 'Akademik\AktivitasSemester\InputNilaiController@datatablesKomponenNilai');
            // view add komponen
            Route::get('input-nilai/add/{id_kelas_mp}/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@addKomponenNilai');
            // view edit komponen
            Route::get('input-nilai/edit/{id_kelas_mp}/{id_pengguna}/{id_semester}/{id}', 'Akademik\AktivitasSemester\InputNilaiController@editKomponenNilai');
            Route::get('input-nilai/datatables-mapel/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@datatablesMataPelajaran');
            // input nilai mapel
            Route::get('input-nilai/nilai-mapel/{id_kelas_mp}/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@viewSiswaPerMapel');

            // view subkomponen
            Route::get('input-nilai/view-sub-komponen/{id_komponen_mp}/{id_kelas_mp}/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@viewKelasSubKomponenNilai');
            Route::get('input-nilai/datatables-subkomponen/{id_komponen_mp}', 'Akademik\AktivitasSemester\InputNilaiController@datatablesSubKomponenNilai');
            // view add subkomponen
            Route::get('input-nilai/add-sub-komponen/{id_komponen_mp}/{id_kelas_mp}/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@addSubKomponenNilai');
            // view edit subkomponen
            Route::get('input-nilai/edit-sub-komponen/{id_komponen_mp}/{id_kelas_mp}/{id_pengguna}/{id_semester}/{id}', 'Akademik\AktivitasSemester\InputNilaiController@editSubKomponenNilai');

            Route::post('action-komponen-nilai/{mode}/{id}', 'Akademik\AktivitasSemester\InputNilaiController@actionKomponenNilai');
            Route::post('action-subkomponen-nilai/{mode}/{id?}', 'Akademik\AktivitasSemester\InputNilaiController@actionSubKomponenNilai');
        });

        Route::group(array('prefix' => 'ujian'), function () {
            //UTS
            Route::get('ujian-uts-reguler-online', 'Akademik\Ujian\UjianUTSController@viewUjianUts');
            Route::get('ujian-uts-reguler-online/datatables/{online}', 'Akademik\Ujian\UjianUTSController@datatablesUjianUts');
            Route::get('ujian-uts-reguler-online/datatablesMapel/{online}', 'Akademik\Ujian\UjianUTSController@datatablesDaftarMataPelajaran');
            Route::get('ujian-uts-reguler-online/datatablesSiswa/{id}', 'Akademik\Ujian\UjianUTSController@datatablesDaftarSiswa');
            Route::get('ujian-uts-reguler-online/add/{id}', 'Akademik\Ujian\UjianUTSController@addUjianUts');
            Route::get('ujian-uts-reguler-online/addUjian/{online}/{id_kelas_mp}', 'Akademik\Ujian\UjianUTSController@addDataUjianUts');
            Route::get('ujian-uts-reguler-online/edit/{id_ujian_mp}', 'Akademik\Ujian\UjianUTSController@editDataUjianUts');
            Route::get('ujian-uts-reguler-online/assign/{id}', 'Akademik\Ujian\UjianUTSController@assignUjianUts');

            Route::post('action-ujian-uts/{mode}/{id}', 'Akademik\Ujian\UjianUTSController@actionUjianUts');

            //UAS
            Route::get('ujian-uas-reguler-online', 'Akademik\Ujian\UjianUASController@viewUjianUas');
            Route::get('ujian-uas-reguler-online/datatables/{online}', 'Akademik\Ujian\UjianUASController@datatablesUjianUas');
            Route::get('ujian-uas-reguler-online/datatablesMapel/{online}', 'Akademik\Ujian\UjianUASController@datatablesDaftarMataPelajaran');
            Route::get('ujian-uas-reguler-online/datatablesSiswa/{id}', 'Akademik\Ujian\UjianUASController@datatablesDaftarSiswa');
            Route::get('ujian-uas-reguler-online/add/{id}', 'Akademik\Ujian\UjianUASController@addUjianUas');
            // ini yg salah
            Route::get('ujian-uas-reguler-online/addUjian/{online}/{id_kelas_mp}', 'Akademik\Ujian\UjianUASController@addDataUjianUas');
            Route::get('ujian-uas-reguler-online/edit/{id_ujian_mp}', 'Akademik\Ujian\UjianUASController@editDataUjianUas');
            Route::get('ujian-uas-reguler-online/assign/{id}', 'Akademik\Ujian\UjianUASController@assignUjianUas');

            Route::post('action-ujian-uas/{mode}/{id}', 'Akademik\Ujian\UjianUASController@actionUjianUas');

            //TRY OUT
            Route::get('try-out-reguler-online', 'Akademik\Ujian\TryOutController@viewTryOut');
            Route::get('try-out-reguler-online/datatables/{online}', 'Akademik\Ujian\TryOutController@datatablesTryOut');
            Route::get('try-out-reguler-online/datatablesMapel/{online}', 'Akademik\Ujian\TryOutController@datatablesDaftarMataPelajaran');
            Route::get('try-out-reguler-online/datatablesSiswa/{id}', 'Akademik\Ujian\TryOutController@datatablesDaftarSiswa');
            Route::get('try-out-reguler-online/add/{id}', 'Akademik\Ujian\TryOutController@addTryOut');
            Route::get('try-out-reguler-online/addUjian/{online}/{id_kelas_mp}', 'Akademik\Ujian\TryOutController@addDataTryOut');
            Route::get('try-out-reguler-online/edit/{id_ujian_mp}', 'Akademik\Ujian\TryOutController@editDataTryOut');
            Route::get('try-out-reguler-online/assign/{id}', 'Akademik\Ujian\TryOutController@assignTryOut');

            Route::post('action-try-out/{mode}/{id}', 'Akademik\Ujian\TryOutController@actionTryOut');
        });

        // MODUL PRESENSI
        Route::group(array('prefix' => 'presensi'), function () {
            // MENU Cetak Presensi KBM
            Route::get('cetak-presensi-kbm', 'Akademik\Presensi\CetakPresensiKBMController@viewCetakPresensiKBM');
            Route::post('post-cetak-presensi-kbm', 'Akademik\Presensi\CetakPresensiKBMController@actionviewCetakPresensiKBM');
            Route::get('cetak-presensi-kbm/view-semester-cetak-presensi-kbm/{id}', 'Akademik\Presensi\CetakPresensiKBMController@viewSemesterCetakPresensiKBM');
            Route::get('cetak-presensi-kbm/datatables/{id}', 'Akademik\Presensi\CetakPresensiKBMController@datatablesCetakPresensiKBM');
            Route::get('cetak-presensi-kbm/print/{id}', 'Akademik\Presensi\CetakPresensiKBMController@printCetakPresensiKBM');

            // MENU Cetak Presensi UTS
            Route::get('cetak-presensi-uts', 'Akademik\Presensi\CetakPresensiUTSController@viewCetakPresensiUTS');
            Route::post('post-cetak-presensi-uts', 'Akademik\Presensi\CetakPresensiUTSController@actionviewCetakPresensiUTS');
            Route::get('cetak-presensi-uts/view-semester-cetak-presensi-uts/{id}', 'Akademik\Presensi\CetakPresensiUTSController@viewSemesterCetakPresensiUTS');
            Route::get('cetak-presensi-uts/datatables/{id}', 'Akademik\Presensi\CetakPresensiUTSController@datatablesCetakPresensiUTS');
            Route::get('cetak-presensi-uts/print/{id}/{pengampu}', 'Akademik\Presensi\CetakPresensiUTSController@printCetakPresensiUTS');

            // MENU Cetak Presensi UAS
            Route::get('cetak-presensi-uas', 'Akademik\Presensi\CetakPresensiUASController@viewCetakPresensiUAS');
            Route::post('post-cetak-presensi-uas', 'Akademik\Presensi\CetakPresensiUASController@actionviewCetakPresensiUAS');
            Route::get('cetak-presensi-uas/view-semester-cetak-presensi-uas/{id}', 'Akademik\Presensi\CetakPresensiUASController@viewSemesterCetakPresensiUAS');
            Route::get('cetak-presensi-uas/datatables/{id}', 'Akademik\Presensi\CetakPresensiUASController@datatablesCetakPresensiUAS');
            Route::get('cetak-presensi-uas/print/{id}/{pengampu}', 'Akademik\Presensi\CetakPresensiUASController@printCetakPresensiUAS');
        });

        // MODUL MONITORING

        Route::group(array('prefix' => 'guru-piket'), function () {
            Route::get('absensi-harian-siswa', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewManageAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}/{id_presensi_harian}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewManageAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/detail/{id_semester}/{id_kelas}/{tahun}/{id_bulan}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewDetailAbsensiHarianSiswa');

            Route::post('absensi-harian-siswa/datatables/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@datatablesAbsensiHarianSiswa');
            Route::post('absensi-harian-siswa/datatables-detail/{id_semester}/{id_kelas}/{id_presensi_harian}', 'Guru\GuruPiket\AbsensiHarianSiswaController@datatablesKelasAbsensiHariSiswa');
            Route::post('absensi-harian-siswa/action/{mode}', 'Guru\GuruPiket\AbsensiHarianSiswaController@actionAbsensiHarianSiswa');
            Route::post('absensi-harian-siswa/action/{mode}/{id}', 'Guru\GuruPiket\AbsensiHarianSiswaController@actionAbsensiHarianSiswa');

            Route::get('monitoring-kelas-kosong/datatables', 'Guru\GuruPiket\MonitoringKelasKosongController@datatablesMonitoringKelasKosong');
            Route::get('rekap-monitoring-kelas-kosong/datatables', 'Guru\GuruPiket\MonitoringKelasKosongController@datatablesRekapMonitoringKelasKosong');
        });

        Route::group(array('prefix' => 'monitoring'), function () {

            Route::get('monitoring-presensi', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewAbsensiHarianSiswa');

            Route::get('monitoring-kelas-kosong', 'Guru\GuruPiket\MonitoringKelasKosongController@viewMonitoringKelasKosong');
            Route::get('rekap-monitoring-kelas-kosong', 'Guru\GuruPiket\MonitoringKelasKosongController@viewRekapMonitoringKelasKosong');
        });

        // MODUL KELAS DARING
        Route::group(array('prefix' => 'kelas-daring'), function () {
            // MENU Setting Toleransi Keterlambatan
            Route::get('setting-toleransi', 'Akademik\KelasDaring\SettingToleransiController@viewSettingToleransi');
            Route::post('post-setting-toleransi', 'Akademik\KelasDaring\SettingToleransiController@actionSettingToleransi');

            // MENU Setting Pengampu
            Route::get('setting-pengampu', 'Akademik\KelasDaring\SettingPengampuController@viewSettingPengampu');
            Route::post('setting-pengampu/datatables', 'Akademik\KelasDaring\SettingPengampuController@datatablesSettingPengampu');

            Route::get('setting-pengampu/guru/{id_guru}', 'Akademik\KelasDaring\SettingPengampuController@viewGuruSettingPengampu');
            Route::post('setting-pengampu/guru/datatables', 'Akademik\KelasDaring\SettingPengampuController@datatablesGuruSettingPengampu');

            Route::post('setting-pengampu/guru/action/{mode}', 'Akademik\KelasDaring\SettingPengampuController@actionSettingPengampu');

            Route::group(array('prefix' => 'jadwal-kelas'), function () {
                Route::get('/', 'Guru\KelasDaring\SettingKelasDaringController@viewKelasDaring');
                Route::get('add', 'Guru\KelasDaring\SettingKelasDaringController@viewAddKelasDaring');
                Route::get('edit/{id}', 'Guru\KelasDaring\SettingKelasDaringController@viewEditKelasDaring');

                Route::get('materi/edit/{id_kelas_mp_grup}/{id}', 'Guru\KelasDaring\SettingKelasDaringController@viewEditMateriKelasDaring');
                Route::post('materi/action/{mode}', 'Guru\KelasDaring\SettingKelasDaringController@actionEditMateriKelasDaring');

                Route::post('datatables', 'Guru\KelasDaring\SettingKelasDaringController@datatablesKelasDaring');
                Route::post('save', 'Guru\KelasDaring\SettingKelasDaringController@actionAddKelasDaring');

                Route::group(array('prefix' => 'data-kelas'), function () {
                    Route::get('/{id}', 'Guru\KelasDaring\SettingKelasDaringController@viewKelasMpKelasDaring');

                    Route::post('datatables', 'Guru\KelasDaring\SettingKelasDaringController@datatablesKelasMpKelasDaring');
                    Route::post('action/{mode}', 'Guru\KelasDaring\SettingKelasDaringController@actionKelasMpKelasDaring');
                });

                Route::group(array('prefix' => 'data-jadwal'), function () {
                    Route::get('/{id}', 'Guru\KelasDaring\SettingKelasDaringController@viewPresensiMpKelasDaring');
                    Route::get('add/{id_kelas_mp_grup}', 'Guru\KelasDaring\SettingKelasDaringController@viewAddPresensiMpKelasDaring');
                    Route::get('edit/{id_kelas_mp_grup}/{id}', 'Guru\KelasDaring\SettingKelasDaringController@viewEditPresensiMpKelasDaring');

                    Route::post('datatables', 'Guru\KelasDaring\SettingKelasDaringController@datatablesPresensiMpKelasDaring');
                    Route::post('action/{mode}', 'Guru\KelasDaring\SettingKelasDaringController@actionPresensiMpKelasDaring');

                    Route::post('action-delete/{id}', 'Guru\KelasDaring\SettingKelasDaringController@actionDeletePresensiMpKelasDaring');
                });
            });
        });

        Route::group(array('prefix' => 'laporan'), function () {

            Route::group(array('prefix' => 'wali-kelas'), function () {
                Route::get('/', 'Kesiswaan\Laporan\WaliKelasController@viewWaliKelas');
                Route::get('datatables', 'Kesiswaan\Laporan\WaliKelasController@datatablesWaliKelas');
                Route::get('add', 'Kesiswaan\Laporan\WaliKelasController@addWaliKelas');
                Route::get('edit/{id}', 'Kesiswaan\Laporan\WaliKelasController@editWaliKelas');
                Route::get('detail/{id}', 'Kesiswaan\Laporan\WaliKelasController@detailWaliKelas');
                Route::get('detail-ajax/{id}', 'Kesiswaan\Laporan\WaliKelasController@detailAjaxWaliKelas');
                Route::get('detail-datatable/{id}', 'Kesiswaan\Laporan\WaliKelasController@detailDataTable');
                Route::post('action-detail-wali-kelas', 'Kesiswaan\Laporan\WaliKelasController@actionDetailWaliKelas');
                Route::post('action-wali-kelas/{mode}/{id}', 'Kesiswaan\Laporan\WaliKelasController@actionWaliKelas');
            });
        });
    });
});
