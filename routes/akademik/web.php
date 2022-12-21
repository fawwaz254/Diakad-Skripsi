<?php

use App\Http\Controllers\Akademik\AktivitasSemester\CariSiswaController;
use App\Http\Controllers\Akademik\AktivitasSemester\HapusPlottingMapelSiswaController;
use App\Http\Controllers\Akademik\AktivitasSemester\InputNilaiController;
use App\Http\Controllers\Akademik\AktivitasSemester\MonitoringKelasController;
use App\Http\Controllers\Akademik\AktivitasSemester\PlottingMapelSiswaController;
use App\Http\Controllers\Akademik\AktivitasSemester\SetJadwalKelasController;
use App\Http\Controllers\Akademik\AktivitasSemester\UsulanMataAjarController;
use App\Http\Controllers\Akademik\DataAkademik\AktivasiKurikulumController;
use App\Http\Controllers\Akademik\DataAkademik\DataJenisMataPelajaranController;
use App\Http\Controllers\Akademik\DataAkademik\KurikulumController;
use App\Http\Controllers\Akademik\DataAkademik\MataPelajaranController;
use App\Http\Controllers\Akademik\DataAkademik\SetupMapelKurikulumController;
use App\Http\Controllers\Akademik\KelasDaring\SettingPengampuController;
use App\Http\Controllers\Akademik\KelasDaring\SettingToleransiController;
use App\Http\Controllers\Akademik\MGMP\DataKategoriMGMPController;
use App\Http\Controllers\Akademik\MGMP\JenisMGMPcontroller;
use App\Http\Controllers\Akademik\Monitoring\MonitoringPresensiGuruController;
use App\Http\Controllers\Akademik\Presensi\CetakPresensiKBMController;
use App\Http\Controllers\Akademik\Presensi\CetakPresensiUASController;
use App\Http\Controllers\Akademik\Presensi\CetakPresensiUTSController;
use App\Http\Controllers\Akademik\RaporSisipan\CetakRaporController;
use App\Http\Controllers\Akademik\RaporSisipan\KomponenNilaiController;
use App\Http\Controllers\Akademik\RaporSisipan\KomponenNilaiRaporSisipanController;
use App\Http\Controllers\Akademik\RaporSisipan\RaporSisipanController;
use App\Http\Controllers\Akademik\RaporSisipan\RaporTengahSemesterController;
use App\Http\Controllers\Akademik\Ujian\TryOutController;
use App\Http\Controllers\Akademik\Ujian\UjianUASController;
use App\Http\Controllers\Akademik\Ujian\UjianUTSController;
use App\Http\Controllers\Akademik\WelcomeController;
use App\Http\Controllers\Guru\GuruPiket\AbsensiHarianSiswaController;
use App\Http\Controllers\Guru\GuruPiket\MonitoringKelasKosongController;
use App\Http\Controllers\Guru\KelasDaring\SettingKelasDaringController;
// use App\Http\Controllers\Guru\RaporSisipan\RaporTengahSemesterController;
// use App\Http\Controllers\Guru\RaporSisipan\RaporTengahSemesterController;
use App\Http\Controllers\Kesiswaan\Laporan\WaliKelasController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;
use App\Models\RaporSisipan;

// ROLE AKADEMIK
Route::middleware(['token_staff'])->group(function () {

    Route::prefix('akademik')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);

        Route::prefix('mpmp')->group(function () {
            Route::prefix('jenis-mgmp')->group(function () {
                Route::get('/', [JenisMGMPcontroller::class, 'viewDataJenis']);
                Route::get('/datatables', [JenisMGMPcontroller::class, 'datatablesjenis']);
                Route::get('/add', [JenisMGMPcontroller::class, 'addDataJenis']);
                Route::get('/add/import-excel', [JenisMGMPcontroller::class, 'importExcel']);
                Route::post('/add/import-excel', [JenisMGMPcontroller::class, 'importExcelAction']);
                Route::post('action-data-kategori/{mode}/{id}', [JenisMGMPcontroller::class, 'actionDataJenis']);
            });
            Route::prefix('data-kategori-mapel')->group(function () {
                Route::get('/', [DataKategoriMGMPController::class, 'viewDataKategori']);
                Route::get('/datatables', [DataKategoriMGMPController::class, 'datatablesCategoryfile']);
                Route::get('/add', [DataKategoriMGMPController::class, 'addDataKategori']);
                Route::get('/edit/{category_file_id}', [DataKategoriMGMPController::class, 'editDataKategori']);
                Route::post('action-data-kategori/{mode}/{id}', [DataKategoriMGMPController::class, 'actionDataKategori']);
            });

            // Route::prefix('jenis-mgmp')->group(function () {
            //     Route::get('/', [JenisMGMPcontroller::class, 'viewDataJenis']);
            //     Route::get('/datatables', [JenisMGMPcontroller::class, 'datatablesjenis']);
            //     Route::get('/add', [JenisMGMPcontroller::class, 'addDataJenis']);
            //     Route::post('action-data-kategori/{mode}/{id}', [JenisMGMPcontroller::class, 'actionDataJenis']);
            // });

            Route::prefix('laporan-mgmp')->group(function () {
                Route::get('/', [DataKategoriMGMPController::class, 'viewLaporanAllMGMP']);
                Route::get('/datatables', [DataKategoriMGMPController::class, 'datatablesKerjaHarianAllMGMP']);
                Route::get('preview-file/{id}', [DataKategoriMGMPController::class, 'previewFile']);
                Route::get('download-file/{id}', [DataKategoriMGMPController::class, 'downloadFile']);
            });
        });

        Route::prefix('manajemen-file')->group(function () {
            Route::prefix('data-kategori')->group(function () {
                Route::get('/', [DataKategoriController::class, 'viewDataKategori']);
                Route::get('/datatables', [DataKategoriController::class, 'datatablesCategoryfile']);
            });
            Route::prefix('data-sub-kategori')->group(function () {
                Route::get('/', [SubDataKategoriController::class, 'viewSubDataKategori']);
                Route::get('/add', [SubDataKategoriController::class, 'addSubDataKategori']);
                Route::get('/datatables', [SubDataKategoriController::class, 'datatablesSubCategoryfile']);
                Route::get('/edit/{id}', [SubDataKategoriController::class, 'editSubDataKategori']);

                //action input sub data kategori
                Route::post('action-data-sub-kategori/{mode}/{id}', [SubDataKategoriController::class, 'actionSubDataKategori']);
            });
            Route::prefix('data-file')->group(function () {
                Route::get('/', [DataFileController::class, 'viewDataFile']);
                Route::get('add', [DataFileController::class, 'addDataFile']);
                Route::get('category/{category_file_id}', [DataFileController::class, 'viewDataFileCategory']);
                Route::get('dropdown-category', [DataFileController::class, 'dropdownCategory']);
                Route::get('sub-category/{sub_category_file_id}', [DataFileController::class, 'viewDataFileSubCategory']);

                Route::post('action-data-file/{mode}/{id}', [DataFileController::class, 'actionDataFile']);
                Route::get('download/{id}', [DataFileController::class, 'downloadDataFile']);
            });
        });

        Route::prefix('data-akademik')->group(function () {
            // MENU Kurikulum
            Route::get('kurikulum', [KurikulumController::class, 'viewKurikulum']);
            Route::get('kurikulum/datatables', [KurikulumController::class, 'datatablesKurikulum']);
            Route::get('kurikulum/add', [KurikulumController::class, 'addKurikulum']);
            Route::get('kurikulum/edit/{id}', [KurikulumController::class, 'editKurikulum']);

            Route::post('action-kurikulum/{mode}/{id}', [KurikulumController::class, 'actionKurikulum']);

            // MENU Aktivasi Kurikulum
            Route::get('aktivasi-kurikulum', [AktivasiKurikulumController::class, 'viewAktivasiKurikulum']);
            Route::get('aktivasi-kurikulum/datatables', [AktivasiKurikulumController::class, 'datatablesAktivasiKurikulum']);
            Route::get('aktivasi-kurikulum/aktivasi/{id}', [AktivasiKurikulumController::class, 'aktivasiKurikulum']);

            Route::post('action-aktivasi-kurikulum/{mode}/{id}', [AktivasiKurikulumController::class, 'actionAktivasiKurikulum']);

            // MENU Data Mata Pelajaran
            Route::get('mata-pelajaran', [MataPelajaranController::class, 'viewMataPelajaran']);
            Route::get('mata-pelajaran/datatables', [MataPelajaranController::class, 'datatablesMataPelajaran']);
            Route::get('mata-pelajaran/add', [MataPelajaranController::class, 'addMataPelajaran']);
            Route::get('mata-pelajaran/edit/{id}', [MataPelajaranController::class, 'editMataPelajaran']);

            Route::post('action-mata-pelajaran/{mode}/{id}', [MataPelajaranController::class, 'actionMataPelajaran']);

            //MENU Data Jenis Mata Pelajaran
            Route::get('jenis-mata-pelajaran', [DataJenisMataPelajaranController::class, 'viewDataJenisMataPelajaran']);
            Route::get('jenis-mata-pelajaran/datatables', [DataJenisMataPelajaranController::class, 'datatablesJenisMataPelajaran']);
            Route::get('jenis-mata-pelajaran/add', [DataJenisMataPelajaranController::class, 'addJenisMataPelajaran']);
            Route::get('jenis-mata-pelajaran/edit/{id}', [DataJenisMataPelajaranController::class, 'editJenisMataPelajaran']);

            Route::post('action-jenis-mata-pelajaran/{mode}/{id}', [DataJenisMataPelajaranController::class, 'actionJenisMataPelajaran']);

            //MENU Setup Mapel Kurikulum
            Route::get('setup-mp-kurikulum', [SetupMapelKurikulumController::class, 'viewSetupMapelKurikulum']);
            Route::post('post-cari-kurikulum', [SetupMapelKurikulumController::class, 'actionCariKurikulum']);
            Route::get('setup-mp-kurikulum/view-mapel-kurikulum/{id}', [SetupMapelKurikulumController::class, 'viewKurikulumMataPelajaran']);
            Route::get('setup-mp-kurikulum/datatables/{id}', [SetupMapelKurikulumController::class, 'datatablesSetupMapelKurikulum']);
            Route::get('setup-mp-kurikulum/add/{id}', [SetupMapelKurikulumController::class, 'addMapelKurikulum']);
            Route::get('setup-mp-kurikulum/datatables-mapel/{id}', [SetupMapelKurikulumController::class, 'datatablesaddMapelKurikulum']);

            Route::post('action-setup-mp-kurikulum/{mode}/{id}', [SetupMapelKurikulumController::class, 'actionJenisMataPelajaran']);
        });

        Route::prefix('kegiatan-harian')->group(function () {

            Route::prefix('mengisi-form-kesehatan')->group(function () {
                // MENU Mengisi form kesehatan
                Route::get('/', [FormKesehatanController::class, 'viewFormKesehatan']);
                Route::get('add', [FormKesehatanController::class, 'viewAddFormKesehatan']);
                Route::get('detail/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

                Route::post('action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
                Route::post('datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
            });
        });

        Route::prefix('aktivitas-semester')->group(function () {
            // MENU Usulan Mata Ajar
            Route::get('usulan-mata-ajar', [UsulanMataAjarController::class, 'viewUsulanMataAjar']);
            Route::post('post-usulan-mata-ajar', [UsulanMataAjarController::class, 'actionViewUsulanMataAjar']);
            Route::get('usulan-mata-ajar/view-semester-usulan-mata-ajar/{id}', [UsulanMataAjarController::class, 'viewSemesterUsulanMataAjar']);
            Route::get('usulan-mata-ajar/tambah-mata-ajar/{id}', [UsulanMataAjarController::class, 'viewTambahMataAjar']);
            Route::get('usulan-mata-ajar/datatables/{id}', [UsulanMataAjarController::class, 'datatablesUsulanMataAjar']);
            Route::get('usulan-mata-ajar/datatablesMapel/{id}', [UsulanMataAjarController::class, 'datatablesMataPelajaran']);
            Route::get('usulan-mata-ajar/add/{id_semester}/{id_mata_pelajaran}', [UsulanMataAjarController::class, 'addUsulanMataAjar']);
            Route::get('usulan-mata-ajar/edit/{id}', [UsulanMataAjarController::class, 'editUsulanMataAjar']);
            Route::get('usulan-mata-ajar/copy/{id}', [UsulanMataAjarController::class, 'copyUsulanMataAjar']);
            Route::get('usulan-mata-ajar/copy-semester-lain/{id_semester}', [UsulanMataAjarController::class, 'copyJadwalSemesterLain']);

            Route::post('usulan-mata-ajar/hapus-jadwal/{id}', [UsulanMataAjarController::class, 'hapusJadwal']);

            Route::post('usulan-mata-ajar/cek-jadwal-crash/{id}', [UsulanMataAjarController::class, 'cekJadwalCrash']);
            Route::post('action-usulan-mata-ajar/{mode}/{id}', [UsulanMataAjarController::class, 'actionUsulanMataAjar']);

            //menu set jadwal kelas
            Route::get('set-jadwal-kelas', [SetJadwalKelasController::class, 'viewSetJadwalKelas']);
            Route::post('set-jadwal-kelas', [SetJadwalKelasController::class, 'actionSetJadwalKelas']);
            Route::get('set-jadwal-kelas/view-tambah-jadwal-kelas/{id_kelas}/{id_semester}', [SetJadwalKelasController::class, 'viewTambahJadwalKelas']);
            Route::post('action-set-jadwal-kelas/{mode}/{id}', [SetJadwalKelasController::class, 'actionTambahJadwalKelas']);


            // MENU Monitoring Kelas
            Route::get('monitoring-kelas', [MonitoringKelasController::class, 'viewMonitoringKelas']);
            Route::post('post-monitoring-kelas', [MonitoringKelasController::class, 'actionViewMonitoringKelas']);
            Route::get('monitoring-kelas/view-semester-monitoring-kelas/{id}', [MonitoringKelasController::class, 'viewSemesterMonitoringKelas']);
            Route::get('monitoring-kelas/datatables/{id}', [MonitoringKelasController::class, 'datatablesMonitoringKelas']);
            Route::get('monitoring-kelas/view-daftar-siswa/{id}', [MonitoringKelasController::class, 'viewDaftarSiswa']);
            Route::get('monitoring-kelas/datatables-daftar-siswa/{id}', [MonitoringKelasController::class, 'datatablesDaftarSiswa']);

            //MENU Plotting Mapel Siswa
            Route::get('plotting-mapel-siswa', [PlottingMapelSiswaController::class, 'viewPlottingMapelSiswa']);
            Route::post('post-plotting-mapel-siswa', [PlottingMapelSiswaController::class, 'actionViewPlottingMapelSiswa']);
            Route::get('plotting-mapel-siswa/view-kelas-plotting/{id_semester}/{angkatan}', [PlottingMapelSiswaController::class, 'viewKelasPlottingMapelSiswa']);
            Route::get('plotting-mapel-siswa/datatables/{id_semester}/{angkatan}', [PlottingMapelSiswaController::class, 'datatablesPlottingMapelSiswa']);
            Route::get('plotting-mapel-siswa/view-mapel-plotting/{id_semester}/{angkatan}/{id_jurusan}', [PlottingMapelSiswaController::class, 'viewMapelPlottingMapelSiswa']);
            Route::get('plotting-mapel-siswa/datatables-mapel/{id_semester}/{angkatan}/{tingkat}', [PlottingMapelSiswaController::class, 'datatablesMataPelajaran']);
            Route::get('plotting-mapel-siswa/datatables-siswa/{angkatan}/{id_kelas}', [PlottingMapelSiswaController::class, 'datatablesSiswa']);
            Route::post('post-daftar-plotting-mapel-siswa', [PlottingMapelSiswaController::class, 'actionViewDaftarPlottingMapelSiswa']);
            Route::get('plotting-mapel-siswa/view-daftar-kelas-plotting/{id_semester}/{angkatan}/{id_kelas}', [PlottingMapelSiswaController::class, 'viewDaftarKelasPlottingMapelSiswa']);

            Route::post('action-plotting-mapel-siswa/{mode}', [PlottingMapelSiswaController::class, 'actionPlottingMapelSiswa']);

            //test automatik ploting
            Route::get('plotting-mapel-siswa/view-auto-plotting-mapel-siswa/{id_semester}/{angkatan}/{id_jurusan}', [PlottingMapelSiswaController::class, 'viewAutoPlottingMapelSiswa']);
            Route::get('plotting-mapel-siswa/datatables-auto-plotting-mapel-siswa/{id_semester}/{angkatan}/{id_jurusan}', [PlottingMapelSiswaController::class, 'datatablesAutoPlottingMapelSiswa']);
            Route::post('plotting-mapel-siswa/action-auto-plotting-mapel-siswa', [PlottingMapelSiswaController::class, 'actionAutoPlottingMapelSiswa']);


            //MENU Hapus Plotting Mapel Siswa
            Route::get('hapus-plotting-mapel-siswa', [HapusPlottingMapelSiswaController::class, 'viewHapusPlottingMapelSiswa']);
            Route::post('post-hapus-plotting-mapel-siswa', [HapusPlottingMapelSiswaController::class, 'actionViewHapusPlottingMapelSiswa']);
            Route::get('hapus-plotting-mapel-siswa/view-semester-hapus-plotting-mapel-siswa/{id}', [HapusPlottingMapelSiswaController::class, 'viewSemesterHapusPlottingMapelSiswa']);
            Route::get('hapus-plotting-mapel-siswa/view-detail-hapus-plotting-mapel-siswa/{id}', [HapusPlottingMapelSiswaController::class, 'viewDetailHapusPlottingMapelSiswa']);
            Route::get('hapus-plotting-mapel-siswa/datatables/{id}', [HapusPlottingMapelSiswaController::class, 'datatablesHapusPlottingMapelSiswa']);

            Route::post('action-hapus-plotting-mapel-siswa', [HapusPlottingMapelSiswaController::class, 'actionHapusPlottingMapelSiswa']);

            Route::post('action-hapus-semua-plotting-mapel-siswa', [HapusPlottingMapelSiswaController::class, 'actionHapusSemuaPlottingMapelSiswa']);

            //MENU CARI SISWA
            Route::get('cari-siswa', [CariSiswaController::class, 'viewCariSiswa']);
            Route::post('post-view-cari-siswa', [CariSiswaController::class, 'actionViewCariSiswa']);
            Route::get('cari-siswa/view-detail/{nis_nama_siswa}', [CariSiswaController::class, 'viewDetailCariSiswa']);
            Route::get('cari-siswa/datatables/{nis_nama_siswa}', [CariSiswaController::class, 'datatablesCariSiswa']);
            Route::get('cari-siswa/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', [CariSiswaController::class, 'viewDetailSiswaCariSiswa']);

            //MENU Input Nilai
            Route::get('input-nilai', [InputNilaiController::class, 'viewInputNilai']);
            Route::post('post-view-input-nilai', [InputNilaiController::class, 'actionInputNilai']);
            Route::get('input-nilai/view-guru-input-nilai/{id_pengguna}/{id_semester}', [InputNilaiController::class, 'viewGuruInputNilai']);
            Route::post('post-view-komponen-nilai', [InputNilaiController::class, 'actionViewKelasKomponenNilai']);
            // view komponen
            Route::get('input-nilai/view-kelas/{id_kelas_mp}/{id_pengguna}/{id_semester}', [InputNilaiController::class, 'viewKelasKomponenNilai']);
            Route::get('input-nilai/datatables/{id_kelas_mp}', [InputNilaiController::class, 'datatablesKomponenNilai']);
            // view add komponen
            Route::get('input-nilai/add/{id_kelas_mp}/{id_pengguna}/{id_semester}', [InputNilaiController::class, 'addKomponenNilai']);
            // view edit komponen
            Route::get('input-nilai/edit/{id_kelas_mp}/{id_pengguna}/{id_semester}/{id}', [InputNilaiController::class, 'editKomponenNilai']);
            Route::get('input-nilai/datatables-mapel/{id_pengguna}/{id_semester}', [InputNilaiController::class, 'datatablesMataPelajaran']);
            // input nilai mapel
            Route::get('input-nilai/nilai-mapel/{id_kelas_mp}/{id_pengguna}/{id_semester}', [InputNilaiController::class, 'viewSiswaPerMapel']);

            // view subkomponen
            Route::get('input-nilai/view-sub-komponen/{id_komponen_mp}/{id_kelas_mp}/{id_pengguna}/{id_semester}', [InputNilaiController::class, 'viewKelasSubKomponenNilai']);
            Route::get('input-nilai/datatables-subkomponen/{id_komponen_mp}', [InputNilaiController::class, 'datatablesSubKomponenNilai']);
            // view add subkomponen
            Route::get('input-nilai/add-sub-komponen/{id_komponen_mp}/{id_kelas_mp}/{id_pengguna}/{id_semester}', [InputNilaiController::class, 'addSubKomponenNilai']);
            // view edit subkomponen
            Route::get('input-nilai/edit-sub-komponen/{id_komponen_mp}/{id_kelas_mp}/{id_pengguna}/{id_semester}/{id}', [InputNilaiController::class, 'editSubKomponenNilai']);

            Route::post('action-komponen-nilai/{mode}/{id}', [InputNilaiController::class, 'actionKomponenNilai']);
            Route::post('action-subkomponen-nilai/{mode}/{id?}', [InputNilaiController::class, 'actionSubKomponenNilai']);
        });

        Route::prefix('ujian')->group(function () {
            //UTS
            Route::get('ujian-uts-reguler-online', [UjianUTSController::class, 'viewUjianUts']);
            Route::get('ujian-uts-reguler-online/datatables/{online}', [UjianUTSController::class, 'datatablesUjianUts']);
            Route::get('ujian-uts-reguler-online/datatablesMapel/{online}', [UjianUTSController::class, 'datatablesDaftarMataPelajaran']);
            Route::get('ujian-uts-reguler-online/datatablesSiswa/{id}', [UjianUTSController::class, 'datatablesDaftarSiswa']);
            Route::get('ujian-uts-reguler-online/add/{id}', [UjianUTSController::class, 'addUjianUts']);
            Route::get('ujian-uts-reguler-online/addUjian/{online}/{id_kelas_mp}', [UjianUTSController::class, 'addDataUjianUts']);
            Route::get('ujian-uts-reguler-online/edit/{id_ujian_mp}', [UjianUTSController::class, 'editDataUjianUts']);
            Route::get('ujian-uts-reguler-online/assign/{id}', [UjianUTSController::class, 'assignUjianUts']);

            Route::post('action-ujian-uts/{mode}/{id}', [UjianUTSController::class, 'actionUjianUts']);

            //UAS
            Route::get('ujian-uas-reguler-online', [UjianUASController::class, 'viewUjianUas']);
            Route::get('ujian-uas-reguler-online/datatables/{online}', [UjianUASController::class, 'datatablesUjianUas']);
            Route::get('ujian-uas-reguler-online/datatablesMapel/{online}', [UjianUASController::class, 'datatablesDaftarMataPelajaran']);
            Route::get('ujian-uas-reguler-online/datatablesSiswa/{id}', [UjianUASController::class, 'datatablesDaftarSiswa']);
            Route::get('ujian-uas-reguler-online/add/{id}', [UjianUASController::class, 'addUjianUas']);
            // ini yg salah
            Route::get('ujian-uas-reguler-online/addUjian/{online}/{id_kelas_mp}', [UjianUASController::class, 'addDataUjianUas']);
            Route::get('ujian-uas-reguler-online/edit/{id_ujian_mp}', [UjianUASController::class, 'editDataUjianUas']);
            Route::get('ujian-uas-reguler-online/assign/{id}', [UjianUASController::class, 'assignUjianUas']);

            Route::post('action-ujian-uas/{mode}/{id}', [UjianUASController::class, 'actionUjianUas']);

            //TRY OUT
            Route::get('try-out-reguler-online', [TryOutController::class, 'viewTryOut']);
            Route::get('try-out-reguler-online/datatables/{online}', [TryOutController::class, 'datatablesTryOut']);
            Route::get('try-out-reguler-online/datatablesMapel/{online}', [TryOutController::class, 'datatablesDaftarMataPelajaran']);
            Route::get('try-out-reguler-online/datatablesSiswa/{id}', [TryOutController::class, 'datatablesDaftarSiswa']);
            Route::get('try-out-reguler-online/add/{id}', [TryOutController::class, 'addTryOut']);
            Route::get('try-out-reguler-online/addUjian/{online}/{id_kelas_mp}', [TryOutController::class, 'addDataTryOut']);
            Route::get('try-out-reguler-online/edit/{id_ujian_mp}', [TryOutController::class, 'editDataTryOut']);
            Route::get('try-out-reguler-online/assign/{id}', [TryOutController::class, 'assignTryOut']);

            Route::post('action-try-out/{mode}/{id}', [TryOutController::class, 'actionTryOut']);
        });

        Route::prefix('presensi')->group(function () {
            // MENU Cetak Presensi KBM
            Route::get('cetak-presensi-kbm', [CetakPresensiKBMController::class, 'viewCetakPresensiKBM']);
            Route::post('post-cetak-presensi-kbm', [CetakPresensiKBMController::class, 'actionviewCetakPresensiKBM']);
            Route::get('cetak-presensi-kbm/view-semester-cetak-presensi-kbm/{id}', [CetakPresensiKBMController::class, 'viewSemesterCetakPresensiKBM']);
            Route::get('cetak-presensi-kbm/datatables/{id}', [CetakPresensiKBMController::class, 'datatablesCetakPresensiKBM']);
            Route::get('cetak-presensi-kbm/print/{id}', [CetakPresensiKBMController::class, 'printCetakPresensiKBM']);
            Route::get('cetak-rekap-presensi-kbm/print/{id}', [CetakPresensiKBMController::class, 'printCetakRekapPresensiKBM']);

            // MENU Cetak Presensi UTS
            Route::get('cetak-presensi-uts', [CetakPresensiUTSController::class, 'viewCetakPresensiUTS']);
            Route::post('post-cetak-presensi-uts', [CetakPresensiUTSController::class, 'actionviewCetakPresensiUTS']);
            Route::get('cetak-presensi-uts/view-semester-cetak-presensi-uts/{id}', [CetakPresensiUTSController::class, 'viewSemesterCetakPresensiUTS']);
            Route::get('cetak-presensi-uts/datatables/{id}', [CetakPresensiUTSController::class, 'datatablesCetakPresensiUTS']);
            Route::get('cetak-presensi-uts/print/{id}/{pengampu}', [CetakPresensiUTSController::class, 'printCetakPresensiUTS']);

            // MENU Cetak Presensi UAS
            Route::get('cetak-presensi-uas', [CetakPresensiUASController::class, 'viewCetakPresensiUAS']);
            Route::post('post-cetak-presensi-uas', [CetakPresensiUASController::class, 'actionviewCetakPresensiUAS']);
            Route::get('cetak-presensi-uas/view-semester-cetak-presensi-uas/{id}', [CetakPresensiUASController::class, 'viewSemesterCetakPresensiUAS']);
            Route::get('cetak-presensi-uas/datatables/{id}', [CetakPresensiUASController::class, 'datatablesCetakPresensiUAS']);
            Route::get('cetak-presensi-uas/print/{id}/{pengampu}', [CetakPresensiUASController::class, 'printCetakPresensiUAS']);
        });

        Route::prefix('guru-piket')->group(function () {
            Route::get('absensi-harian-siswa', [AbsensiHarianSiswaController::class, 'viewAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/{id_semester}/{id_kelas}', [AbsensiHarianSiswaController::class, 'viewAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}', [AbsensiHarianSiswaController::class, 'viewManageAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}/{id_presensi_harian}', [AbsensiHarianSiswaController::class, 'viewManageAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/detail/{id_semester}/{id_kelas}/{tahun}/{id_bulan}', [AbsensiHarianSiswaController::class, 'viewDetailAbsensiHarianSiswa']);

            Route::post('absensi-harian-siswa/datatables/{id_semester}/{id_kelas}', [AbsensiHarianSiswaController::class, 'datatablesAbsensiHarianSiswa']);
            Route::post('absensi-harian-siswa/datatables-detail/{id_semester}/{id_kelas}/{id_presensi_harian}', [AbsensiHarianSiswaController::class, 'datatablesKelasAbsensiHariSiswa']);
            Route::post('absensi-harian-siswa/action/{mode}', [AbsensiHarianSiswaController::class, 'actionAbsensiHarianSiswa']);
            Route::post('absensi-harian-siswa/action/{mode}/{id}', [AbsensiHarianSiswaController::class, 'actionAbsensiHarianSiswa']);

            Route::get('monitoring-kelas-kosong/datatables', [MonitoringKelasKosongController::class, 'datatablesMonitoringKelasKosong']);
            Route::get('rekap-monitoring-kelas-kosong/datatables', [MonitoringKelasKosongController::class, 'datatablesRekapMonitoringKelasKosong']);
        });

        // Modul Rapor Sisipan
        Route::prefix('rapor-sisipan')->group(function () {
            Route::prefix('daftar-nilai-sts')->group(function () {
                Route::get('/', [RaporSisipanController::class, 'viewDaftarNilaiSTS']);
                Route::get('datatables', [RaporSisipanController::class, 'datatablesDaftarNilaiSTS']);
                // Route::get('add', [RaporSisipanController::class, 'addDaftarNilaiSTS']);
                // Route::post('action-daftar-nilai-sts/{mode}/{id}', [RaporSisipanController::class, 'actionDaftarNilaiSTS']);
                Route::get('print/{id}', [RaporSisipanController::class, 'printDaftarNilaiSTS']);
                Route::get('pdf/{id}', [RaporSisipanController::class, 'pdfDaftarNilaiSTS']);

                // Route::get('nilai/{id}', [InputNilaiRaporSisipanController::class, 'viewKomponenInputNilai']);
                // Route::get('input-nilai-magang/datatables/{id}', '[InputNilaiRaporSisipanController::class, 'datatablesKomponenNilaiMagang');
                // Route::post('action-input-nilai-rapor-sisipan/{mode}/{id_rapor_sisipan}', '[InputNilaiRaporSisipanController::class, 'actionInputNilai');
                // Route::prefix('daftar-nilai-sas')->group(function () {
                // });
            });

            Route::prefix('nilai-rapor-tengah-semester')->group(function () {
                Route::get('/', [RaporTengahSemesterController::class, 'viewRaporTengahSemester']);
                Route::get('datatables', [RaporTengahSemesterController::class, 'datatablesDaftarNilaiSTS']);
                Route::get('pdf/{id}', [RaporTengahSemesterController::class, 'pdfDaftarNilaiSTS']);
            });

            Route::prefix('komponen-nilai')->group(function () {
                Route::get('/', [KomponenNilaiRaporSisipanController::class, 'viewKomponenNilai']);
                Route::get('datatables', [KomponenNilaiRaporSisipanController::class, 'datatablesKomponenNilai']);
                Route::get('edit/{id}', [KomponenNilaiRaporSisipanController::class, 'editKomponenNilai']);
                Route::post('edit/{id}', [KomponenNilaiRaporSisipanController::class, 'actionEditKomponenNilai']);
                // Route::get('pdf/{id}', [RaporTengahSemesterController::class, 'pdfDaftarNilaiSTS']);

            });

            Route::prefix('cetak-rapor')->group(function () {
                Route::get('/', [CetakRaporController::class, 'viewCetakRapor']);
                Route::get('datatables', [CetakRaporController::class, 'datatablesCetakRapor']);
                Route::get('print/{id_kelas}', [CetakRaporController::class, 'printCetakRapor']);
                Route::get('viewSetting', [CetakRaporController::class, 'viewSetting']);
                Route::get('viewDeskripsi', [CetakRaporController::class, 'viewDeskripsi']);
                Route::get('datatablesViewSetting', [CetakRaporController::class, 'datatablesViewSetting']);
                Route::get('datatablesViewDeskripsi', [CetakRaporController::class, 'datatablesViewDeskripsi']);
                Route::get('addSetting/{mata_pelajaran}', [CetakRaporController::class, 'addSetting']);
                Route::post('postSetting/{mata_pelajaran}', [CetakRaporController::class, 'postSetting']);
            });
        });

        Route::prefix('monitoring')->group(function () {
            Route::get('status-entri-nilai', [MonitoringKelasKosongController::class, 'viewMonitoringKelasKosong']);
            Route::get('monitoring-presensi-guru', [MonitoringPresensiGuruController::class, 'viewMonitoringPresensiGuru']);
            Route::get('monitoring-presensi-guru/{bulan}/{tahun}', [MonitoringPresensiGuruController::class, 'viewMonitoringPresensiGuru']);
            Route::get('monitoring-presensi-guru/{day}/{bulan}/{tahun}/{id_pengguna}', [MonitoringPresensiGuruController::class, 'viewDetailPresensiGuru']);
            Route::get('monitoring-presensi', [AbsensiHarianSiswaController::class, 'viewAbsensiHarianSiswa']);
            Route::get('monitoring-kelas-kosong', [MonitoringKelasKosongController::class, 'viewMonitoringKelasKosong']);
            Route::get('rekap-monitoring-kelas-kosong', [MonitoringKelasKosongController::class, 'viewRekapMonitoringKelasKosong']);
        });

        Route::prefix('kelas-daring')->group(function () {
            // MENU Setting Toleransi Keterlambatan
            Route::get('setting-toleransi', [SettingToleransiController::class, 'viewSettingToleransi']);
            Route::post('post-setting-toleransi', [SettingToleransiController::class, 'actionSettingToleransi']);

            // MENU Setting Pengampu
            Route::get('setting-pengampu', [SettingPengampuController::class, 'viewSettingPengampu']);
            Route::post('setting-pengampu/datatables', [SettingPengampuController::class, 'datatablesSettingPengampu']);

            Route::get('setting-pengampu/guru/{id_guru}', [SettingPengampuController::class, 'viewGuruSettingPengampu']);
            Route::post('setting-pengampu/guru/datatables', [SettingPengampuController::class, 'datatablesGuruSettingPengampu']);

            Route::post('setting-pengampu/guru/action/{mode}', [SettingPengampuController::class, 'actionSettingPengampu']);

            Route::prefix('jadwal-kelas')->group(function () {
                Route::get('/', [SettingKelasDaringController::class, 'viewKelasDaring']);
                Route::get('add', [SettingKelasDaringController::class, 'viewAddKelasDaring']);
                Route::get('edit/{id}', [SettingKelasDaringController::class, 'viewEditKelasDaring']);

                Route::get('materi/edit/{id_kelas_mp_grup}/{id}', [SettingKelasDaringController::class, 'viewEditMateriKelasDaring']);
                Route::post('materi/action/{mode}', [SettingKelasDaringController::class, 'actionEditMateriKelasDaring']);

                Route::post('datatables', [SettingKelasDaringController::class, 'datatablesKelasDaring']);
                Route::post('save', [SettingKelasDaringController::class, 'actionAddKelasDaring']);

                Route::prefix('data-kelas')->group(function () {
                    Route::get('/{id}', [SettingKelasDaringController::class, 'viewKelasMpKelasDaring']);

                    Route::post('datatables', [SettingKelasDaringController::class, 'datatablesKelasMpKelasDaring']);
                    Route::post('action/{mode}', [SettingKelasDaringController::class, 'actionKelasMpKelasDaring']);
                });
                Route::prefix('data-jadwal')->group(function () {
                    Route::get('/{id}', [SettingKelasDaringController::class, 'viewPresensiMpKelasDaring']);
                    Route::get('add/{id_kelas_mp_grup}', [SettingKelasDaringController::class, 'viewAddPresensiMpKelasDaring']);
                    Route::get('edit/{id_kelas_mp_grup}/{id}', [SettingKelasDaringController::class, 'viewEditPresensiMpKelasDaring']);

                    Route::post('datatables', [SettingKelasDaringController::class, 'datatablesPresensiMpKelasDaring']);
                    Route::post('action/{mode}', [SettingKelasDaringController::class, 'actionPresensiMpKelasDaring']);

                    Route::post('action-delete/{id}', [SettingKelasDaringController::class, 'actionDeletePresensiMpKelasDaring']);
                });
            });
        });

        Route::prefix('laporan')->group(function () {

            Route::prefix('wali-kelas')->group(function () {
                Route::get('/', [WaliKelasController::class, 'viewWaliKelas']);
                Route::get('datatables', [WaliKelasController::class, 'datatablesWaliKelas']);
                Route::get('add', [WaliKelasController::class, 'addWaliKelas']);
                Route::get('edit/{id}', [WaliKelasController::class, 'editWaliKelas']);
                Route::get('detail/{id}', [WaliKelasController::class, 'detailWaliKelas']);
                Route::get('detail-ajax/{id}', [WaliKelasController::class, 'detailAjaxWaliKelas']);
                Route::get('detail-datatable/{id}', [WaliKelasController::class, 'detailDataTable']);
                Route::post('action-detail-wali-kelas', [WaliKelasController::class, 'actionDetailWaliKelas']);
                Route::post('action-wali-kelas/{mode}/{id}', [WaliKelasController::class, 'actionWaliKelas']);
            });
        });
    });
});
