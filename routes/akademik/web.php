<?php
// ROLE AKADEMIK
Route::group(array('middleware'=> ['token_staff']), function () {
    Route::group(array('prefix' => 'akademik'), function () {
        Route::get('welcome', 'Akademik\WelcomeController@indexWelcome');

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
            Route::get('input-nilai/view-guru-input-nilai/{id_guru}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@viewGuruInputNilai');
            Route::post('post-view-komponen-nilai', 'Akademik\AktivitasSemester\InputNilaiController@actionViewKelasKomponenNilai');
            Route::get('input-nilai/view-kelas/{id_kelas_mp}/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@viewKelasKomponenNilai');
            Route::get('input-nilai/datatables/{id_kelas_mp}', 'Akademik\AktivitasSemester\InputNilaiController@datatablesKomponenNilai');
            Route::get('input-nilai/datatables-mapel/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@datatablesMataPelajaran');
            Route::get('input-nilai/add/{id_kelas_mp}/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@addKomponenNilai');
            Route::get('input-nilai/edit/{id_kelas_mp}/{id_pengguna}/{id_semester}/{id}', 'Akademik\AktivitasSemester\InputNilaiController@editKomponenNilai');
            Route::get('input-nilai/nilai-mapel/{id_kelas_mp}/{id_pengguna}/{id_semester}', 'Akademik\AktivitasSemester\InputNilaiController@viewSiswaPerMapel');


            Route::post('action-komponen-nilai/{mode}/{id}', 'Akademik\AktivitasSemester\InputNilaiController@actionKomponenNilai');
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
    });
});
