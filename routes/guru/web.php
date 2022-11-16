<?php

use App\Http\Controllers\Guru\WelcomeController;
use App\Http\Controllers\Guru\Tutorial\VideoController;
use App\Http\Controllers\Guru\Jadwal\JadwalKBMController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\Guru\ELearningSoal\SoalController;
use App\Http\Controllers\Guru\Jadwal\InputJadwalController;
use App\Http\Controllers\Guru\Jadwal\JadwalUjianController;
use App\Http\Controllers\Guru\Biodata\DataPribadiController;
use App\Http\Controllers\Guru\Laporan\KerjaHarianController;
use App\Http\Controllers\Guru\Presensi\RekapAbsenController;
use App\Http\Controllers\Guru\WaliKelas\HomeVisitController;
use App\Http\Controllers\Guru\Biodata\DataKegiatanController;
use App\Http\Controllers\Guru\Biodata\DataPrestasiController;
use App\Http\Controllers\Guru\Penilaian\InputNilaiController;
use App\Http\Controllers\Guru\Penilaian\RekapNilaiController;
use App\Http\Controllers\Guru\Presensi\AbsensiSiswaController;
use App\Http\Controllers\Guru\Absensi\HistoriAbsensiController;
use App\Http\Controllers\Guru\WaliKelas\RekapNomorHpController;
use App\Http\Controllers\Guru\ELearningSoal\HasilTestController;
use App\Http\Controllers\Guru\ELearningSoal\PaketSoalController;
use App\Http\Controllers\Guru\Jadwal\KalenderAkademikController;
use App\Http\Controllers\Guru\Kesekretariatan\DokumenController;
use App\Http\Controllers\Guru\Penilaian\KomponenNilaiController;
use App\Http\Controllers\Guru\Sarpras\KomplainSarprasController;
use App\Http\Controllers\Guru\KelasDaring\LaporanAbsenController;
use App\Http\Controllers\Guru\WaliKelas\RekapKesehatanController;
use App\Http\Controllers\Keuangan\SIM\PembayaranOnlineController;
use App\Http\Controllers\Guru\Jadwal\SetJadwalKelasGuruController;
use App\Http\Controllers\Guru\RaporSisipan\RaporSisipanController;
use App\Http\Controllers\Guru\WaliKelas\InventarisKelasController;
use App\Http\Controllers\Guru\KelasDaring\MengajarDaringController;
use App\Http\Controllers\Guru\Presensi\AbsensiTanpaJadwalController;
use App\Http\Controllers\Guru\WaliKelas\RekapAbsensiKelasController;
use App\Http\Controllers\Guru\GuruPiket\AbsensiHarianSiswaController;
use App\Http\Controllers\Guru\RewardSiswa\InputRewardSiswaController;
use App\Http\Controllers\Guru\WaliKelas\RekapKeuanganKelasController;
use App\Http\Controllers\Humas\Absensi\HistoriAbsensiSiswaController;
use App\Http\Controllers\Guru\ELearning\ManajemenMateriAjarController;
use App\Http\Controllers\Guru\JurnalPimpinan\JurnalPimpinanController;
use App\Http\Controllers\Guru\KelasDaring\SettingKelasDaringController;
use App\Http\Controllers\Guru\PembinaEkskul\InputNilaiEkskulController;
use App\Http\Controllers\Guru\PembinaEkskul\RekapNilaiEkskulController;
use App\Http\Controllers\Guru\Presensi\RekapAbsenTanpaJadwalController;
use App\Http\Controllers\Guru\RaporSisipan\RaporSisipanAkhirController;
use App\Http\Controllers\Guru\WaliKelas\ApprovePrestasiSiswaController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;
use App\Http\Controllers\Guru\GuruPiket\MonitoringKelasKosongController;
use App\Http\Controllers\Guru\WaliKelas\RekapPelanggaranKelasController;
use App\Http\Controllers\Guru\WaliKelas\TracerAlumniWaliKelasController;
use App\Http\Controllers\Guru\PembinaEkskul\InputAbsensiEkskulController;
use App\Http\Controllers\Guru\PembinaEkskul\RekapAbsensiEkskulController;
use App\Http\Controllers\Guru\RaporSisipan\RaporTengahSemesterController;
use App\Http\Controllers\Guru\PelanggaranSiswa\InputPelanggaranController;
use App\Http\Controllers\Guru\PembinaEkskul\KomponenNilaiEkskulController;
use App\Http\Controllers\Guru\WaliKelas\RekapAbsensiKelasDaringController;
use App\Http\Controllers\Guru\RaporSisipan\InputNilaiRaporSisipanController;
use App\Http\Controllers\Akademik\AktivitasSemester\SetJadwalKelasController;
use App\Http\Controllers\Akademik\RaporSisipan\CetakRaporController;
use App\Http\Controllers\Guru\RaporSisipan\InputNilaiRaporSisipanAkhirController;
use App\Http\Controllers\Guru\LaporanKerjaHarianMGMP\LaporanKerjaHarianController;
use App\Http\Controllers\Guru\GuruPiket\RekapKesehatanController as GuruPiketRekapKesehatanController;
use App\Http\Controllers\Guru\GuruPiket\InputPelanggaranController as GuruPiketInputPelanggaranController;
use App\Http\Controllers\Guru\WaliKelas\InputPelanggaranController as WaliKelasInputPelanggaranController;
use App\Http\Controllers\Guru\GuruPiket\RekapAbsenTanpaJadwalController as GuruPiketRekapAbsenTanpaJadwalController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormLainnyaController;

// ROLE GURU

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('guru')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);

        Route::prefix('mgmp')->group(function () {

            Route::prefix('laporan-harian-mgmp')->group(function () {
                Route::get('/', [LaporanKerjaHarianController::class, 'viewLaporanHarianMGMP']);
                Route::get('add', [LaporanKerjaHarianController::class, 'addLaporanHarianMGMP']);
                Route::post('action-kerja-harian/{mode}/{id}', [LaporanKerjaHarianController::class, 'actionLaporanHarianMGMP']);
                Route::get('datatables', [LaporanKerjaHarianController::class, 'datatablesKerjaHarianMGMP']);
                Route::get('edit/{id}', [LaporanKerjaHarianController::class, 'editKerjaHarian']);
                Route::get('preview-file/{id}/{no}', [LaporanKerjaHarianController::class, 'previewFile']);
                Route::get('download-file/{id}', [LaporanKerjaHarianController::class, 'downloadFile']);
            });
            Route::prefix('laporan-kelompok-mgmp')->group(function () {
                Route::get('/', [LaporanKerjaHarianController::class, 'viewLaporanKelompokMGMP']);
                Route::get('datatables', [LaporanKerjaHarianController::class, 'datatablesKerjaHarianKelompokMGMP']);
                Route::get('/detail/{id}', [LaporanKerjaHarianController::class, 'detailLaporanKelompokMGMP']);
                Route::get('/detail/datatables/{id}', [LaporanKerjaHarianController::class, 'datatablesDetailKerjaHarianKelompokMGMP']);
                Route::get('preview-file/{id}/{no}', [LaporanKerjaHarianController::class, 'previewFile']);
                Route::get('download-file/{id}', [LaporanKerjaHarianController::class, 'downloadFile']);
            });
        });

        Route::prefix('jurnal-pimpinan')->group(function () {
            Route::prefix('laporan-jurnal-pimpinan')->group(function () {
                Route::get('/', [JurnalPimpinanController::class, 'viewLaporanJurnalPimpinan']);
                Route::get('add', [JurnalPimpinanController::class, 'addLaporanHarianJurnalPimpinan']);
                Route::post('action-kerja-harian/{mode}/{id}', [JurnalPimpinanController::class, 'actionLaporanJurnalPimpinan']);
                Route::get('datatables', [JurnalPimpinanController::class, 'datatablesJurnalPimpinan']);
                Route::get('edit/{id}', [JurnalPimpinanController::class, 'editLaporanJurnalPimpinan']);
                Route::get('preview-file/{id}', [JurnalPimpinanController::class, 'previewFile']);
                Route::get('download-file/{id}', [JurnalPimpinanController::class, 'downloadFile']);
            });
        });

        /** ==== MODUL MANAJEMEN FILE ==== **/
        // url: /guru/manajemen-file
        Route::prefix('manajemen-file')->group(function () {
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

        Route::prefix('biodata')->group(function () {
            Route::get('data-pribadi', [DataPribadiController::class, 'viewDataPribadi']);
            Route::post('action-data-pribadi', [DataPribadiController::class, 'actionSaveDataPribadi']);

            Route::prefix('data-kegiatan')->group(function () {
                Route::get('/', [DataKegiatanController::class, 'viewDataKegiatan']);
                Route::get('add', [DataKegiatanController::class, 'viewAddDataKegiatan']);
                Route::get('edit/{id}', [DataKegiatanController::class, 'viewEditDataKegiatan']);
                Route::post('action/{mode}/{id}', [DataKegiatanController::class, 'actionDataKegiatan']);

                Route::get('datatables', [DataKegiatanController::class, 'datatablesDataKegiatan']);
            });

            Route::prefix('data-prestasi')->group(function () {
                Route::get('/', [DataPrestasiController::class, 'viewDataPrestasi']);
                Route::get('add', [DataPrestasiController::class, 'viewAddDataPrestasi']);
                Route::get('edit/{id}', [DataPrestasiController::class, 'viewEditDataPrestasi']);
                Route::post('action/{mode}/{id}', [DataPrestasiController::class, 'actionDataPrestasi']);

                Route::get('datatables', [DataPrestasiController::class, 'datatablesDataPrestasi']);
            });
        });


        /** ==== MODUL E-Learning ==== **/
        Route::prefix('e-learning')->group(function () {

            Route::prefix('manajemen-materi-ajar')->group(function () {
                Route::get('/', [ManajemenMateriAjarController::class, 'viewManajemenMateriAjar']);
                Route::get('datatables', [ManajemenMateriAjarController::class, 'datatablesManajemenMateriAjar']);
                Route::get('add', [ManajemenMateriAjarController::class, 'addManajemenMateriAjar']);
                Route::get('edit/{id}', [ManajemenMateriAjarController::class, 'editManajemenMateriAjar']);
                Route::get('get-kelas/{id_jurusan}', [ManajemenMateriAjarController::class, 'getKelas']);
                Route::get('view/{id}', [ManajemenMateriAjarController::class, 'listViewManajemenMateriAjar']);
                Route::get('view/datatables/{id}', [ManajemenMateriAjarController::class, 'datatablesViewManajemenMateriAjar']);
                Route::post('deleteItem/{id_materi_ajar_file}', [ManajemenMateriAjarController::class, 'deleteItem']);
            });
            Route::post('action-manajemen-materi-ajar/{mode}/{id}', [ManajemenMateriAjarController::class, 'actionManajemenMateriAjar']);
        });

        Route::prefix('e-learning-soal')->group(function () {

            Route::prefix('soal')->group(function () {
                Route::get('/', [SoalController::class, 'indexList']);
                // Route::get('uploadImage', [SoalController::class,'uploadImageCkeditor']);
                Route::get('new/{tipe_soal}', [SoalController::class, 'indexNew']);
                Route::get('kategori', [SoalController::class, 'addKategori']);
                Route::post('kategori', [SoalController::class, 'actionKategori']);
                Route::get('kategori/table', [SoalController::class, 'commonListKategori']);
                Route::post('kategori/delete', [SoalController::class, 'actionDeleteKategori']);
                Route::post('new', [SoalController::class, 'actionSave']);
                Route::post('/table', [SoalController::class, 'commonList']);
                Route::get('edit/{id}', [SoalController::class, 'indexManage']);
                Route::get('test/{id}', [SoalController::class, 'indexTest']);
                Route::get('detail/{id}', [SoalController::class, 'indexOrder']);
                // Route::post('order/save', [QuestionController::class, 'actionOrderSave']);
                Route::post('/delete', [SoalController::class, 'actionDelete']);
            });
            Route::prefix('paket-soal')->group(function () {
                Route::get('/', [PaketSoalController::class, 'indexList']);
                Route::get('manage', [PaketSoalController::class, 'indexManage']);
                Route::get('manage/{id}', [PaketSoalController::class, 'indexManage']);
                Route::post('table', [PaketSoalController::class, 'commonList']);
                Route::post('/', [PaketSoalController::class, 'actionSave']);
                Route::post('delete', [PaketSoalController::class, 'actionDelete']);
                Route::get('detail/{id}', [PaketSoalController::class, 'indexDetail']);
                Route::get('test/{id}', [PaketSoalController::class, 'indexTest']);
                // Route::post('detail/table', [PaketSoalController::class,'detailList']);
                Route::post('detail/table/{id}/{tipe}', [PaketSoalController::class, 'detailList']);
                Route::post('detail/add', [PaketSoalController::class, 'actionDetailAdd']);
                Route::post('detail/delete', [PaketSoalController::class, 'actionDetailDelete']);
            });
            Route::prefix('hasil-test')->group(function () {
                Route::get('/', [HasilTestController::class, 'indexList']);
                Route::get('koreksi/{id_test}/{id_pengguna}', [HasilTestController::class, 'indexKoreksi']);
                Route::get('koreksi/{id_paket_soal}/{id_test}/{id_pengguna}', [HasilTestController::class, 'indexKoreksi']);
                Route::post('koreksi', [HasilTestController::class, 'actionKoreksiHasilTest']);
                Route::post('table', [HasilTestController::class, 'commonList']);
                Route::get('detail/{id}', [HasilTestController::class, 'indexDetail']);
                Route::post('detail/table/{id}', [HasilTestController::class, 'detailList']);
            });
        });

        Route::prefix('absensi')->group(function () {
            Route::prefix('histori-absensi')->group(function () {
                Route::get('/', [HistoriAbsensiController::class, 'viewHistoriAbsensi']);
                Route::get('/{start_date}/{end_date}', [HistoriAbsensiController::class, 'viewHistoriAbsensi']);
            });

            Route::prefix('histori-absensi-siswa')->group(function () {
                Route::get('/', [HistoriAbsensiSiswaController::class, 'viewHistoriAbsensiSiswa']);
                // Route::get('get-kelas/{id_jurusan}', [HistoriAbsensiSiswaController::class, 'getKelas']);
                Route::post('/', [HistoriAbsensiSiswaController::class, 'actionDetailHistoriAbsensiSiswa']);
                Route::get('/detail/{kelas}/{date}/{status}', [HistoriAbsensiSiswaController::class, 'viewDetailHistoriAbsensiSiswa']);
                // Route::get('/details/{kelas}/{date}', [HistoriAbsensiSiswaController::class, 'viewDetailsHistoriAbsensiSiswa']);
                Route::get('export-laravel-mount/{kelas}/{date}', [HistoriAbsensiSiswaController::class, 'export_excel_mount']);
                Route::get('export-laravel-week/{kelas}/{date}', [HistoriAbsensiSiswaController::class, 'export_excel_week']);
                Route::get('export-laravel/{kelas}/{date}', [HistoriAbsensiSiswaController::class, 'export_excel_day']);
                //buat izin / sakit
                Route::get('/{id_pengguna}/{kelas}/{date}/add', [HistoriAbsensiSiswaController::class, 'createHistoriAbsensi']);
                Route::post('/{id_pengguna}/{kelas}/{date}/add', [HistoriAbsensiSiswaController::class, 'storeHistoriAbsensi']);
                Route::get('/{id_presensi_pengguna}/{kelas}/{date}/edit', [HistoriAbsensiSiswaController::class, 'editHistoriAbsensi']);
                Route::post('/{id_presensi_pengguna}/{kelas}/{date}/edit', [HistoriAbsensiSiswaController::class, 'updateHistoriAbsensi']);
                Route::post('/{id_presensi_pengguna}/delete', [HistoriAbsensiSiswaController::class, 'destroyHistoriAbsensi']);
            });
        });

        Route::prefix('tutorial')->group(function () {
            Route::prefix('video')->group(function () {
                Route::get('/', [VideoController::class, 'viewVideo']);
                Route::get('/modul/{id_modul}', [VideoController::class, 'viewVideoModul']);
                Route::get('/menu/{id_menu}', [VideoController::class, 'viewVideoMenu']);
            });
        });

        Route::prefix('jadwal')->group(function () {
            Route::get('kalender-akademik', [KalenderAkademikController::class, 'viewKalenderAkademik']);
            Route::get('kalender-akademik/datatables', [KalenderAkademikController::class, 'datatablesKalenderAkademik']);

            Route::get('jadwal-kbm', [JadwalKBMController::class, 'viewJadwalKBM']);
            Route::get('jadwal-kbm/datatables', [JadwalKBMController::class, 'datatablesJadwalKBM']);

            Route::get('jadwal-ujian', [JadwalUjianController::class, 'viewJadwalUjian']);
            Route::get('jadwal-ujian/datatables-uts', [JadwalUjianController::class, 'datatablesJadwalUTS']);
            Route::get('jadwal-ujian/datatables-uas', [JadwalUjianController::class, 'datatablesJadwalUAS']);

            /*Route::get('usulan-mata-ajar/view-semester-usulan-mata-ajar/{id}', [UsulanMataAjarController::class, 'viewSemesterUsulanMataAjar']);
            Route::get('usulan-mata-ajar/datatables/{id}', [UsulanMataAjarController::class, 'datatablesUsulanMataAjar']);
            Route::get('usulan-mata-ajar/edit/{id}', [UsulanMataAjarController::class, 'editUsulanMataAjar']);

            Route::post('action-usulan-mata-ajar/{mode}/{id}', [UsulanMataAjarController::class, 'actionUsulanMataAjar']);*/
            // MENU Input Jadwal
            Route::get('input-jadwal', [InputJadwalController::class, 'viewInputJadwal']);
            Route::get('input-jadwal/datatables/{id}', [InputJadwalController::class, 'datatablesInputJadwal']);
            Route::get('input-jadwal/edit/{id}', [InputJadwalController::class, 'editInputJadwal']);

            Route::post('action-input-jadwal/{mode}/{id}', [InputJadwalController::class, 'actionInputJadwal']);

            //menu set jadwal kelas
            Route::get('set-jadwal-kelas', [SetJadwalKelasGuruController::class, 'viewSetJadwalKelas']);
            Route::post('set-jadwal-kelas', [SetJadwalKelasGuruController::class, 'actionSetJadwalKelas']);
            Route::get('set-jadwal-kelas/view-tambah-jadwal-kelas/{id_kelas}/{id_semester}', [SetJadwalKelasGuruController::class, 'viewTambahJadwalKelas']);
            Route::post('action-set-jadwal-kelas/{mode}/{id}', [SetJadwalKelasGuruController::class, 'actionTambahJadwalKelas']);
        });

        // Route::prefix('aktivitas-semester')->group(function () {
        //     Route::get('set-jadwal-kelas', [SetJadwalKelasController::class, 'viewSetJadwalKelas']);
        //     Route::post('set-jadwal-kelas', [SetJadwalKelasController::class, 'actionSetJadwalKelas']);
        //     Route::get('set-jadwal-kelas/view-tambah-jadwal-kelas/{id_kelas}/{id_semester}', [SetJadwalKelasController::class, 'viewTambahJadwalKelas']);
        //     Route::post('action-set-jadwal-kelas/{mode}/{id}', [SetJadwalKelasController::class, 'actionTambahJadwalKelas']);
        // });


        Route::prefix('presensi')->group(function () {
            // MENU Absensi Siswa
            Route::get('absensi-siswa', [AbsensiSiswaController::class, 'viewAbsensiSiswa']);

            Route::post('post-kbm-absensi-siswa', [AbsensiSiswaController::class, 'actionViewKBMAbsensiSiswa']);
            Route::get('absensi-siswa/view-kbm/{id_kelas_mp}/{pertemuan_ke}', [AbsensiSiswaController::class, 'viewKBMAbsensiSiswa']);
            Route::get('absensi-siswa/datatables-kbm/{id_kelas_mp}/{pertemuan_ke}', [AbsensiSiswaController::class, 'datatablesKBMAbsensiSiswa']);

            Route::post('post-uts-absensi-siswa', [AbsensiSiswaController::class, 'actionViewUTSAbsensiSiswa']);
            Route::get('absensi-siswa/view-uts/{id_ujian_mp}', [AbsensiSiswaController::class, 'viewUTSAbsensiSiswa']);
            Route::get('absensi-siswa/datatables-uts/{id_ujian_mp}', [AbsensiSiswaController::class, 'datatablesUTSAbsensiSiswa']);

            Route::post('post-uas-absensi-siswa', [AbsensiSiswaController::class, 'actionViewUASAbsensiSiswa']);
            Route::get('absensi-siswa/view-uas/{id_ujian_mp}', [AbsensiSiswaController::class, 'viewUASAbsensiSiswa']);
            Route::get('absensi-siswa/datatables-uas/{id_ujian_mp}', [AbsensiSiswaController::class, 'datatablesUASAbsensiSiswa']);

            Route::post('action-absensi-siswa/{mode}/{id}', [AbsensiSiswaController::class, 'actionAbsensiSiswa']);
            Route::post('action-absensi-siswa/{mode}/{id}/{pertemuan_ke}', [AbsensiSiswaController::class, 'actionAbsensiSiswa']);

            // AJAX GET PERTEMUAN BY KELAS_MP
            Route::post('pertemuan-byjadwalkelasmp', [AbsensiSiswaController::class, 'ajaxGetPertemuanByJadwalKelasMp']);

            // MENU Rekap Absen
            Route::get('rekap-absen', [RekapAbsenController::class, 'viewRekapAbsen']);
            Route::post('post-kbm-rekap-absen', [RekapAbsenController::class, 'actionViewKBMRekapAbsen']);
            Route::get('rekap-absen/view-kbm/{id_jadwal_kelas_mp}', [RekapAbsenController::class, 'viewKBMRekapAbsen']);

            Route::get('rekap-absen/print/{id_jadwal_kelas_mp}', [RekapAbsenController::class, 'printKBMRekapAbsen']);

            // MENU Absensi Tanpa Jadwal
            Route::get('absensi-tanpa-jadwal', [AbsensiTanpaJadwalController::class, 'viewAbsensiTanpaJadwal']);
            Route::get('absensi-tanpa-jadwal/view-kbm/{id_guru}/{id_mata_pelajaran}/{id_kelas}/{opsi}', [AbsensiTanpaJadwalController::class, 'viewKBMAbsensiTanpaJadwal']);
            Route::get('absensi-tanpa-jadwal/datatables-kbm/{id_guru}/{id_mata_pelajaran}/{id_kelas}', [AbsensiTanpaJadwalController::class, 'datatablesKBMAbsensiTanpaJadwal']);

            Route::post('absensi-tanpa-jadwal/view-kbm', [AbsensiTanpaJadwalController::class, 'actionViewKBMAbsensiTanpaJadwal']);
            Route::post('action-absensi-tanpa-jadwal/{mode}/{id_guru}/{id_mata_pelajaran}/{id_kelas}', [AbsensiTanpaJadwalController::class, 'actionAbsensiTanpaJadwal']);

            // MENU Rekap Absen Tanpa Jadwal
            Route::get('rekap-absen-tanpa-jadwal', [RekapAbsenTanpaJadwalController::class, 'viewRekapAbsenTanpaJadwal']);
            Route::post('post-kbm-rekap-absen-tanpa-jadwal', [RekapAbsenTanpaJadwalController::class, 'actionViewKBMRekapAbsenTanpaJadwal']);
            Route::get('rekap-absen-tanpa-jadwal/view-kbm/{id_kelas_mp}', [RekapAbsenTanpaJadwalController::class, 'viewKBMRekapAbsenTanpaJadwal']);

            Route::get('rekap-absen-tanpa-jadwal/print/{id_kelas_mp}', [RekapAbsenTanpaJadwalController::class, 'printKBMRekapAbsenTanpaJadwal']);
        });

        Route::prefix('penilaian')->group(function () {
            // MENU Komponen Nilai
            Route::get('komponen-nilai', [KomponenNilaiController::class, 'viewKomponenNilai']);
            Route::post('post-view-komponen-nilai', [KomponenNilaiController::class, 'actionViewKelasKomponenNilai']);
            Route::get('komponen-nilai/view-kelas/{id_kelas_mp}', [KomponenNilaiController::class, 'viewKelasKomponenNilai']);
            Route::get('komponen-nilai/datatables/{id_kelas_mp}', [KomponenNilaiController::class, 'datatablesKomponenNilai']);
            Route::get('komponen-nilai/add/{id_kelas_mp}', [KomponenNilaiController::class, 'addKomponenNilai']);
            Route::get('komponen-nilai/edit/{id_kelas_mp}/{id}', [KomponenNilaiController::class, 'editKomponenNilai']);

            Route::post('action-komponen-nilai/{mode}/{id}', [KomponenNilaiController::class, 'actionKomponenNilai']);

            // MENU Sub Komponen Nilai
            Route::get('komponen-nilai/view-sub-komponen/{id_kelas_mp}/{id_komponen_mp}', [KomponenNilaiController::class, 'viewKelasSubKomponenNilai']);
            Route::get('komponen-nilai/datatables-subkomponen/{id_komponen_mp}', [KomponenNilaiController::class, 'datatablesSubKomponenNilai']);
            Route::get('komponen-nilai/add-sub-komponen/{id_kelas_mp}/{id_komponen_mp}', [KomponenNilaiController::class, 'addSubKomponenNilai']);
            Route::get('komponen-nilai/edit-sub-komponen/{id_kelas_mp}/{id_komponen_mp}/{id}', [KomponenNilaiController::class, 'editSubKomponenNilai']);

            Route::post('action-subkomponen-nilai/{mode}/{id?}', [KomponenNilaiController::class, 'actionSubKomponenNilai']);

            // MENU Input Nilai KBM/Try Out
            Route::get('input-nilai', [InputNilaiController::class, 'viewInputNilai']);
            Route::post('post-view-input-nilai', [InputNilaiController::class, 'actionViewKelasInputNilai']);
            Route::get('input-nilai/view-kelas/{id_kelas_mp}', [InputNilaiController::class, 'viewKelasInputNilai']);
            Route::get('input-nilai/datatables/{id_kelas_mp}', [InputNilaiController::class, 'datatablesInputNilai']);
            /*
            Route::post('post-view-input-tryout', [InputNilaiController::class, 'actionViewKelasInputTryOut']);
            Route::get('input-tryout/view-kelas/{id_kelas_mp}', [InputNilaiController::class, 'viewKelasInputTryOut']);
            Route::get('input-tryout/datatables/{id_kelas_mp}', [InputNilaiController::class, 'datatablesInputTryOut']);
             */

            Route::post('action-input-nilai/{mode}/{id}', [InputNilaiController::class, 'actionInputNilai']);

            // MENU Rekap Nilai
            Route::get('rekap-nilai', [RekapNilaiController::class, 'viewRekapNilai']);
            Route::get('rekap-nilai/detail/{id_kelas_mp}', [RekapNilaiController::class, 'viewDetailRekapNilai']);
            Route::get('rekap-nilai/print/{id_kelas_mp}', [RekapNilaiController::class, 'printRekapNilai']);
        });

        /** ==== MODUL PELANGGARAN SISWA ==== **/
        // alurnya berbeda dengan input pelanggaran yg lain (merujuk ke presensi_mp)
        Route::prefix('pelanggaran-siswa')->group(function () {
            // MENU Input Pelanggaran Siswa
            Route::get('input-pelanggaran-mp', [InputPelanggaranController::class, 'viewInputPelanggaran']);

            Route::post('post-input-pelanggaran-mp', [InputPelanggaranController::class, 'actionViewKBMInputPelanggaran']);
            Route::get('input-pelanggaran-mp/view-kbm/{id_jadwal_kelas_mp}/{pertemuan_ke}', [InputPelanggaranController::class, 'viewKBMInputPelanggaran']);
            Route::get('input-pelanggaran-mp/datatables/{id_presensi_mp}', [InputPelanggaranController::class, 'datatablesInputPelanggaran']);
            Route::get('input-pelanggaran-mp/add/{id_presensi_mp}/{id_siswa}', [InputPelanggaranController::class, 'addInputPelanggaran']);
            Route::get('input-pelanggaran-mp/edit/{id}', [InputPelanggaranController::class, 'editInputPelanggaran']);

            Route::post('action-input-pelanggaran-mp/{mode}/{id}', [InputPelanggaranController::class, 'actionInputPelanggaran']);
            Route::post('pertemuan-byjadwalkelasmp', [InputPelanggaranController::class, 'ajaxGetPertemuanByJadwalKelasMp']);

            Route::get('rekap-input-pelanggaran-mp', [InputPelanggaranController::class, 'viewRekapInputPelanggaran']);
            Route::get('rekap-input-pelanggaran-mp/datatables', [InputPelanggaranController::class, 'datatablesRekapInputPelanggaran']);

            Route::post('subkategori-bykategori', [InputPelanggaranController::class, 'ajaxGetSubkategoriByKategori']);

            // MENU Input Pelanggaran Siswa Non-KBM
            Route::get('input-pelanggaran', [GuruPiketInputPelanggaranController::class, 'viewInputPelanggaran']);
            Route::get('input-pelanggaran/datatables', [GuruPiketInputPelanggaranController::class, 'datatablesInputPelanggaran']);
            Route::get('input-pelanggaran/add', [GuruPiketInputPelanggaranController::class, 'addInputPelanggaran']);
            Route::get('input-pelanggaran/edit/{id}', [GuruPiketInputPelanggaranController::class, 'editInputPelanggaran']);

            Route::post('action-input-pelanggaran/{mode}/{id}', [GuruPiketInputPelanggaranController::class, 'actionInputPelanggaran']);

            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', [GuruPiketInputPelanggaranController::class, 'ajaxGetSiswaByKelas']);
        });

        Route::prefix('reward-siswa')->group(function () {
            Route::get('input-reward-siswa', [InputRewardSiswaController::class, 'viewInputRewardSiswa']);
            Route::post('post-input-reward-siswa', [InputRewardSiswaController::class, 'actionViewInputRewardSiswa']);
            Route::get('input-reward-siswa/view-kelas/{id_kelas}', [InputRewardSiswaController::class, 'viewKelasInputRewardSiswa']);
            Route::get('input-reward-siswa/add/{id_siswa}', [InputRewardSiswaController::class, 'addInputRewardSiswa']);
            Route::get('input-reward-siswa/edit/{id}', [InputRewardSiswaController::class, 'editInputRewardSiswa']);

            Route::get('input-reward-siswa/datatables/{id_kelas}', [InputRewardSiswaController::class, 'datatablesInputRewardSiswa']);
            Route::post('action-input-reward-siswa/{mode}/{id}', [InputRewardSiswaController::class, 'actionInputRewardSiswa']);

            Route::get('rekap-input-reward-siswa', [InputRewardSiswaController::class, 'viewRekapInputRewardSiswa']);
            Route::get('rekap-input-reward-siswa/datatables', [InputRewardSiswaController::class, 'datatablesRekapInputRewardSiswa']);
        });

        Route::prefix('sarpras')->group(function () {
            Route::get('komplain-sarpras', [KomplainSarprasController::class, 'viewKomplainSarpras']);

            Route::post('post-view-ruangan-sarpras', [KomplainSarprasController::class, 'actionViewRuanganKomplainSarpras']);
            Route::get('komplain-sarpras/ruangan-sarpras/view-ruangan/{id_ruangan}', [KomplainSarprasController::class, 'viewRuanganKomplainSarpras']);
            Route::get('komplain-sarpras/ruangan-sarpras/datatables/{id_ruangan}', [KomplainSarprasController::class, 'datatablesRuanganKomplainSarpras']);
            Route::get('komplain-sarpras/ruangan-sarpras/add/{id_ruangan}', [KomplainSarprasController::class, 'addRuanganKomplainSarpras']);
            Route::get('komplain-sarpras/ruangan-sarpras/edit/{id_ruangan}/{id}', [KomplainSarprasController::class, 'editRuanganKomplainSarpras']);

            Route::post('post-view-bukualat-sarpras', [KomplainSarprasController::class, 'actionViewBukualatKomplainSarpras']);
            Route::get('komplain-sarpras/bukualat-sarpras/view-bukualat/{id_buku_alat}', [KomplainSarprasController::class, 'viewBukualatKomplainSarpras']);
            Route::get('komplain-sarpras/bukualat-sarpras/datatables/{id_buku_alat}', [KomplainSarprasController::class, 'datatablesBukualatKomplainSarpras']);
            Route::get('komplain-sarpras/bukualat-sarpras/add/{id_buku_alat}', [KomplainSarprasController::class, 'addBukualatKomplainSarpras']);
            Route::get('komplain-sarpras/bukualat-sarpras/edit/{id_buku_alat}/{id}', [KomplainSarprasController::class, 'editBukualatKomplainSarpras']);

            Route::post('action-komplain-sarpras/{mode}/{id}', [KomplainSarprasController::class, 'actionKomplainSarpras']);
        });

        Route::prefix('guru-piket')->group(function () {
            // MENU Monitoring kelas kosong
            Route::get('monitoring-kelas-kosong', [MonitoringKelasKosongController::class, 'viewMonitoringKelasKosong']);
            Route::get('monitoring-kelas-kosong/datatables', [MonitoringKelasKosongController::class, 'datatablesMonitoringKelasKosong']);

            // MENU Monitoring kelas kosong
            Route::get('rekap-monitoring-kelas-kosong', [MonitoringKelasKosongController::class, 'viewRekapMonitoringKelasKosong']);
            Route::get('rekap-monitoring-kelas-kosong/datatables', [MonitoringKelasKosongController::class, 'datatablesRekapMonitoringKelasKosong']);

            // MENU Absensi Harian Siswa
            Route::get('absensi-harian-siswa', [AbsensiHarianSiswaController::class, 'viewAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/{id_semester}/{id_kelas}', [AbsensiHarianSiswaController::class, 'viewAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}', [AbsensiHarianSiswaController::class, 'viewManageAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}/{id_presensi_harian}', [AbsensiHarianSiswaController::class, 'viewManageAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/detail/{id_semester}/{id_kelas}/{tahun}/{id_bulan}', [AbsensiHarianSiswaController::class, 'viewDetailAbsensiHarianSiswa']);
            Route::get('absensi-harian-siswa/print-detail/{id_semester}/{id_kelas}/{id_pengguna}/{id_bulan}/{tahun}', [AbsensiHarianSiswaController::class, 'printDetailAbsensiHarianSiswa']);

            Route::post('absensi-harian-siswa/datatables/{id_semester}/{id_kelas}', [AbsensiHarianSiswaController::class, 'datatablesAbsensiHarianSiswa']);
            Route::post('absensi-harian-siswa/datatables-detail/{id_semester}/{id_kelas}/{id_presensi_harian}', [AbsensiHarianSiswaController::class, 'datatablesKelasAbsensiHariSiswa']);
            Route::post('absensi-harian-siswa/action/{mode}', [AbsensiHarianSiswaController::class, 'actionAbsensiHarianSiswa']);
            Route::post('absensi-harian-siswa/action/{mode}/{id}', [AbsensiHarianSiswaController::class, 'actionAbsensiHarianSiswa']);

            // MENU Input Pelanggaran Siswa Non-KBM
            Route::get('input-pelanggaran', [GuruPiketInputPelanggaranController::class, 'viewInputPelanggaran']);
            Route::get('input-pelanggaran/datatables', [GuruPiketInputPelanggaranController::class, 'datatablesInputPelanggaran']);
            Route::get('input-pelanggaran/add', [GuruPiketInputPelanggaranController::class, 'addInputPelanggaran']);
            Route::get('input-pelanggaran/edit/{id}', [GuruPiketInputPelanggaranController::class, 'editInputPelanggaran']);

            Route::post('action-input-pelanggaran/{mode}/{id}', [GuruPiketInputPelanggaranController::class, 'actionInputPelanggaran']);

            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', [GuruPiketInputPelanggaranController::class, 'ajaxGetSiswaByKelas']);

            // MENU Rekap Kesehatan Siswa
            Route::get('rekap-kesehatan', [GuruPiketRekapKesehatanController::class, 'viewRekapKesehatan']);
            Route::get('rekap-kesehatan/user/{id}/{date}', [RekapKesehatanController::class, 'viewRekapKesehatanSiswa']);
            Route::get('rekap-kesehatan/detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

            Route::get('rekap-kesehatan/{id}', [GuruPiketRekapKesehatanController::class, 'viewDetailRekapKesehatan']);
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}', [GuruPiketRekapKesehatanController::class, 'viewDetailRekapKesehatan']);
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}/download', [GuruPiketRekapKesehatanController::class, 'downloadDetailRekapKesehatan']);

            Route::post('rekap-kesehatan/action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
            Route::post('rekap-kesehatan/datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);

            // MENU Rekap Absen Tanpa Jadwal
            Route::get('rekap-absen-tanpa-jadwal', [GuruPiketRekapAbsenTanpaJadwalController::class, 'viewRekapAbsenTanpaJadwal']);
            Route::post('post-get-kbm-by-kelas', [GuruPiketRekapAbsenTanpaJadwalController::class, 'actionGetKBMByKelas']);

            Route::post('post-kbm-rekap-absen-tanpa-jadwal', [GuruPiketRekapAbsenTanpaJadwalController::class, 'actionViewKBMRekapAbsenTanpaJadwal']);
            Route::get('rekap-absen-tanpa-jadwal/view-kbm/{id_kelas_mp}', [GuruPiketRekapAbsenTanpaJadwalController::class, 'viewKBMRekapAbsenTanpaJadwal']);

            Route::get('rekap-absen-tanpa-jadwal/print/{id_kelas_mp}', [GuruPiketRekapAbsenTanpaJadwalController::class, 'printKBMRekapAbsenTanpaJadwal']);
        });

        Route::prefix('wali-kelas')->group(function () {
            // MENU Data Inventaris Kelas/Sarana
            Route::get('inventaris-kelas', [InventarisKelasController::class, 'viewInventarisKelas']);
            Route::get('inventaris-kelas/datatables', [InventarisKelasController::class, 'datatablesInventarisKelas']);

            // MENU Input Pelanggaran Siswa
            Route::get('input-pelanggaran', [WaliKelasInputPelanggaranController::class, 'viewInputPelanggaran']);
            Route::get('input-pelanggaran/datatables', [WaliKelasInputPelanggaranController::class, 'datatablesInputPelanggaran']);
            Route::get('input-pelanggaran/add', [WaliKelasInputPelanggaranController::class, 'addInputPelanggaran']);
            Route::get('input-pelanggaran/edit/{id}', [WaliKelasInputPelanggaranController::class, 'editInputPelanggaran']);

            Route::post('action-input-pelanggaran/{mode}/{id}', [WaliKelasInputPelanggaranController::class, 'actionInputPelanggaran']);

            // MENU Rekap Absensi Kelas
            Route::get('rekap-absensi-kelas', [RekapAbsensiKelasController::class, 'viewRekapAbsensiKelas']);
            Route::post('post-rekap-absensi-kelas', [RekapAbsensiKelasController::class, 'actionViewRekapAbsensiKelas']);
            Route::get('rekap-absensi-kelas/rekap-absensi-kelas-siswa/{id_jadwal_kelas_mp}', [RekapAbsensiKelasController::class, 'viewRekapAbsensiKelasSiswa']);

            Route::get('rekap-absensi-kelas-daring', [RekapAbsensiKelasDaringController::class, 'viewRekapAbsensiKelasDaring']);
            Route::post('post-rekap-absensi-kelas-daring', [RekapAbsensiKelasDaringController::class, 'actionViewRekapAbsensiKelasDaring']);
            Route::get('rekap-absensi-kelas-daring/view/{id_kelas_mp_grup}', [RekapAbsensiKelasDaringController::class, 'viewDetailRekapAbsensiKelasDaring']);

            // MENU Rekap Keuangan Kelas
            Route::get('rekap-keuangan-kelas', [RekapKeuanganKelasController::class, 'viewRekapKeuanganKelas']);
            Route::get('rekap-keuangan-kelas/print/{id_semester}/{id_kelas}', [RekapKeuanganKelasController::class, 'printRekapKeuanganKelas']);

            // MENU Rekap Pelanggaran Kelas
            Route::get('rekap-pelanggaran-kelas', [RekapPelanggaranKelasController::class, 'viewRekapPelanggaranKelas']);
            Route::get('rekap-pelanggaran-kelas/datatables-belum-nonkbm', [RekapPelanggaranKelasController::class, 'datatablesBelumTindakanNonKBM']);
            Route::get('rekap-pelanggaran-kelas/datatables-belum-kbm', [RekapPelanggaranKelasController::class, 'datatablesBelumTindakanKBM']);
            Route::get('rekap-pelanggaran-kelas/datatables-sudah', [RekapPelanggaranKelasController::class, 'datatablesSudahTindakan']);

            // MENU Home Visit
            Route::get('home-visit', [HomeVisitController::class, 'viewHomeVisit']);
            Route::get('home-visit/datatables', [HomeVisitController::class, 'datatablesHomeVisit']);
            Route::get('home-visit/add', [HomeVisitController::class, 'addHomeVisit']);
            Route::get('home-visit/edit/{id}', [HomeVisitController::class, 'editHomeVisit']);

            Route::post('action-home-visit/{mode}/{id}', [HomeVisitController::class, 'actionHomeVisit']);

            // AJAX GET SUBKATEGORI PELANGGARAN BY KATEGORI
            Route::post('subkategori-bykategori', [WaliKelasInputPelanggaranController::class, 'ajaxGetSubkategoriByKategori']);

            // MENU PEMBAYARAN ONLINE
            Route::prefix('pembayaran-online')->group(function () {
                Route::get('/', [PembayaranOnlineController::class, 'viewIndex']);
                Route::get('add', [PembayaranOnlineController::class, 'viewAdd']);

                Route::post('datatables', [PembayaranOnlineController::class, 'datatables']);
                Route::post('tagihan/datatables/{id}', [PembayaranOnlineController::class, 'datatablesTagihan']);
                Route::post('save', [PembayaranOnlineController::class, 'actionSave']);

                Route::post('siswa-bykelas', [PembayaranOnlineController::class, 'ajaxGetSiswaByKelas']);
            });

            Route::get('rekap-kesehatan', [RekapKesehatanController::class, 'viewRekapFormKesehatan']);
            Route::get('rekap-kesehatan/user/{id}/{date}', [RekapKesehatanController::class, 'viewRekapKesehatanSiswa']);
            Route::get('rekap-kesehatan/detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

            Route::post('rekap-kesehatan/action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
            Route::post('rekap-kesehatan/datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);

            Route::get('rekap-kesehatan/{bulan}/{tahun}', [RekapKesehatanController::class, 'viewRekapFormKesehatan']);
            Route::get('rekap-kesehatan/{bulan}/{tahun}/download', [RekapKesehatanController::class, 'downloadRekapKesehatan']);

            Route::get('approve-prestasi-siswa', [ApprovePrestasiSiswaController::class, 'viewApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/datatables', [ApprovePrestasiSiswaController::class, 'datatablesApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/{id}', [ApprovePrestasiSiswaController::class, 'viewDetailPrestasiSiswa']);
            Route::get('approve-prestasi-siswa/prestasi/datatables/{id}', [ApprovePrestasiSiswaController::class, 'datatablesPrestasiApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/print/skpi/{id}', [ApprovePrestasiSiswaController::class, 'PrintSkpi']);
            Route::get('approve-prestasi-siswa/kegiatan/datatables/{id}', [ApprovePrestasiSiswaController::class, 'datatablesKegiatanApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/informasi-tambahan/datatables/{id}', [ApprovePrestasiSiswaController::class, 'datatablesInformasiTambahan']);
            Route::post('approve-prestasi-siswa/{data}/{id}', [ApprovePrestasiSiswaController::class, 'actionApprovePrestasiSiswa']);
            Route::post('reject-prestasi-siswa/{data}/{id}', [ApprovePrestasiSiswaController::class, 'actionRejectPrestasiSiswa']);

            Route::get('edit-prestasi-siswa/{id}', [ApprovePrestasiSiswaController::class, 'editPrestasiSiswa']);
            Route::get('edit-kegiatan-siswa/{id}', [ApprovePrestasiSiswaController::class, 'editKegiatanSiswa']);

            Route::post('action-edit-prestasi-siswa/{id}', [ApprovePrestasiSiswaController::class, 'actionEditPrestasiSiswa']);
            Route::post('action-edit-kegiatan-siswa/{id}', [ApprovePrestasiSiswaController::class, 'actionEditKegiatanSiswa']);

            //Menu Tracer Alumni
            Route::get('tracer-alumni', [TracerAlumniWaliKelasController::class, 'cetakTracerAlumniWaliKelas']);
            Route::post('tracer-alumni', [TracerAlumniWaliKelasController::class, 'changeTracerAlumniWaliKelas']);
            Route::get('tracer-alumni/{id_kelas}/{tahun_lulus}', [TracerAlumniWaliKelasController::class, 'cetakTracerAlumniWaliKelas']);
            Route::get('tracer-alumni/datatables/{id_kelas}/{tahun_lulus}', [TracerAlumniWaliKelasController::class, 'datatablesCetakTracerAlumniWaliKelas']);
            Route::get('tracer-alumni/export-alumni/{id_kelas}/{tahun_lulus}', [TracerAlumniWaliKelasController::class, 'exportAlumnniWaliKelas']);

            // Route::get('approve-prestasi-siswa', [ApprovePrestasiSiswaController::class, 'viewApprovePrestasiSiswa']);
            // Route::get('approve-prestasi-siswa/datatables', [ApprovePrestasiSiswaController::class, 'datatablesApprovePrestasiSiswa']);
            // Route::get('approve-prestasi-siswa/{id}', [ApprovePrestasiSiswaController::class, 'viewDetailPrestasiSiswa']);
            // Route::get('approve-prestasi-siswa/prestasi/datatables/{id}', [ApprovePrestasiSiswaController::class, 'datatablesPrestasiApprovePrestasiSiswa']);
            // Route::get('approve-prestasi-siswa/kegiatan/datatables/{id}', [ApprovePrestasiSiswaController::class, 'datatablesKegiatanApprovePrestasiSiswa']);

            // Route::post('approve-prestasi-siswa/{data}/{id}', [ApprovePrestasiSiswaController::class, 'actionApprovePrestasiSiswa']);
            // Route::get('approve-prestasi-siswa/print-skpi/{id}', [ApprovePrestasiSiswaController::class, 'PrintSkpi']);
            //Menu Cek Nomor HP Siswa

            Route::get('rekap-nomor-hp', [RekapNomorHpController::class, 'viewRekapNomorHp']);
            Route::get('rekap-nomor-hp/datatables', [RekapNomorHpController::class, 'datatablesRekapNomorHp']);

            Route::prefix('cetak-rapor-siswa')->group(function () {
                Route::get('/', [CetakRaporController::class, 'viewCetakRaporWaliKelas']);
                Route::get('print/{id_kelas}', [CetakRaporController::class, 'printCetakRapor']);
            });
        });

        // Modul Rapor Sisipan
        Route::prefix('rapor-sisipan')->group(function () {
            Route::prefix('daftar-nilai-sts')->group(function () {
                Route::get('/', [RaporSisipanController::class, 'viewDaftarNilaiSTS']);
                Route::get('datatables', [RaporSisipanController::class, 'datatablesDaftarNilaiSTS']);
                Route::get('add', [RaporSisipanController::class, 'addDaftarNilaiSTS']);
                Route::post('action-daftar-nilai-sts/{mode}/{id}', [RaporSisipanController::class, 'actionDaftarNilaiSTS']);
                Route::get('excel/{id}', [RaporSisipanController::class, 'excelDaftarNilaiSTS']);
                Route::get('importExcel', [RaporSisipanController::class, 'imporExcelSTS']);
                Route::post('importExcel', [RaporSisipanController::class, 'uploadRaporSisipanSTS']);
                Route::get('pdf/{id}', [RaporSisipanController::class, 'pdfDaftarNilaiSTS']);
                Route::get('nilai/{id}', [InputNilaiRaporSisipanController::class, 'viewKomponenInputNilai']);
                Route::post('jurusan', [RaporSisipanController::class, 'getDataFromJurusan']);

                // Route::get('input-nilai-magang/datatables/{id}', 'Guru\RaporSisipan\InputNilaiRaporSisipanController@datatablesKomponenNilaiMagang');
                Route::post('action-input-nilai-rapor-sisipan/{mode}/{id_rapor_sisipan}', [InputNilaiRaporSisipanController::class, 'actionInputNilai']);
            });
            Route::prefix('daftar-nilai-sas')->group(function () {
                Route::get('/', [RaporSisipanAkhirController::class, 'viewDaftarNilaiSAS']);
                Route::get('datatables', [RaporSisipanAkhirController::class, 'datatablesDaftarNilaiSAS']);
                Route::get('pdf/{id}', [RaporSisipanAkhirController::class, 'pdfDaftarNilaiSAS']);
                Route::get('nilai/{id}', [InputNilaiRaporSisipanAkhirController::class, 'viewKomponenInputNilai']);
                Route::post('action-input-nilai-rapor-sisipan/{mode}/{id_rapor_sisipan}', [InputNilaiRaporSisipanAkhirController::class, 'actionInputNilai']);
            });

            Route::prefix('rapor-tengah-semester')->group(function () {
                Route::get('/', [RaporTengahSemesterController::class, 'viewRaporTengahSemester']);
                Route::get('datatables', [RaporTengahSemesterController::class, 'datatablesRaporTengahSemester']);
                Route::get('add', [RaporTengahSemesterController::class, 'addRaporTengahSemester']);
                Route::post('action-rapor-tengah-semester/{mode}/{id}', [RaporTengahSemesterController::class, 'actionRaporTengahSemester']);
                // Route::get('excel/{id}', [RaporSisipanController::class, 'excelDaftarNilaiSTS']);
                // Route::get('importExcel', [RaporSisipanController::class, 'imporExcelSTS']);
                // Route::post('importExcel', [RaporSisipanController::class, 'uploadRaporSisipanSTS']);
                Route::get('pdf/{id}', [RaporTengahSemesterController::class, 'pdfRaporTengahSemester']);
                // Route::get('nilai/{id}', [InputNilaiRaporSisipanController::class, 'viewKomponenInputNilai']);
                // Route::post('action-input-nilai-rapor-sisipan/{mode}/{id_rapor_sisipan}', [InputNilaiRaporSisipanController::class, 'actionInputNilai']);

            });
        });

        Route::prefix('kelas-daring')->group(function () {
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
                });
            });
            Route::prefix('mengajar-daring')->group(function () {
                Route::get('/', [MengajarDaringController::class, 'viewMengajarDaring']);
                Route::post('datatables', [MengajarDaringController::class, 'datatablesMengajarDaring']);
                Route::get('{id}', [MengajarDaringController::class, 'viewDetailMengajarDaring']);
                Route::post('change-status/{id}', [MengajarDaringController::class, 'changeStatusMengajarDaring']);
            });
            Route::prefix('laporan-absen')->group(function () {
                Route::get('/', [LaporanAbsenController::class, 'viewLaporanAbsen']);
                Route::get('view/{id}', [LaporanAbsenController::class, 'viewLaporanAbsenDetail']);
                Route::post('post-laporan-absen', [LaporanAbsenController::class, 'postLaporanAbsen']);
            });
        });

        Route::prefix('kegiatan-harian')->group(function () {
            Route::prefix('mengisi-form-kesehatan')->group(function () {

                Route::get('/', [FormKesehatanController::class, 'viewFormKesehatan']);
                Route::get('add', [FormKesehatanController::class, 'viewAddFormKesehatan']);
                Route::get('detail/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

                Route::post('action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
                Route::post('datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
            });

            Route::prefix('form-lainnya')->group(function () {
                Route::get('/',[FormLainnyaController::class, 'viewListFormLainnya']);
                Route::post('datatables', [FormLainnyaController::class, 'datatablesListFormLainnya']);

                Route::get('/form/{id_form}',[FormLainnyaController::class, 'viewFormLainnya']);
                Route::post('/form/datatables/{id_form}',[FormLainnyaController::class, 'datatablesFormLainnya']);

                Route::get('/form/{id_form}/isi',[FormLainnyaController::class, 'isiFormLainnya']);
                Route::get('/form/{id_form}/detail',[FormLainnyaController::class, 'viewDetailFormLainnya']);
                Route::post('/form/action/{id_form}/{mode}',[FormLainnyaController::class, 'postIsiFormLainnya']);
            });
        });

        Route::prefix('kesekretariatan')->group(function () {

            Route::prefix('upload-dokumen')->group(function () {
                Route::get('/', [DokumenController::class, 'manageInputDokumen']);
                Route::get('edit/{id}', [DokumenController::class, 'manageInputDokumen']);
                Route::get('upload/{id}', [DokumenController::class, 'uploadInputDokumen']);
            });
            // action upload dokumen
            Route::post('action-upload-dokumen/{mode}/{id}', [DokumenController::class, 'actionUploadDokumen']);

            // ajax sub kategori
            Route::post('sub-kategori', [DokumenController::class, 'ajaxGetSubkategori']);

            Route::prefix('dokumen')->group(function () {
                Route::get('/', [DokumenController::class, 'viewDokumen']);
                Route::get('detail/{id}', [DokumenController::class, 'viewDetailDokumen']);

                Route::post('datatables', [DokumenController::class, 'datatablesDokumen']);
            });
        });
        Route::prefix('laporan')->group(function () {

            Route::prefix('kerja-harian')->group(function () {
                Route::get('/', [KerjaHarianController::class, 'viewKerjaHarian']);
                Route::get('datatables', [KerjaHarianController::class, 'datatablesKerjaHarian']);
                Route::get('add', [KerjaHarianController::class, 'addKerjaHarian']);
                Route::get('edit/{id}', [KerjaHarianController::class, 'editKerjaHarian']);
                Route::get('preview-file/{id}', [KerjaHarianController::class, 'previewFile']);
                Route::post('action-kerja-harian/{mode}/{id}', [KerjaHarianController::class, 'actionKerjaHarian']);
            });
        });

        // MODUL PEMBINA EKSKUL
        Route::prefix('pembina-ekskul')->group(function () {
            // Menu Input Absensi Ekskul
            Route::get('input-absensi-ekskul', [InputAbsensiEkskulController::class, 'viewInputAbsensiEkskul']);
            Route::get('input-absensi-ekskul/{id_semester}/{id_ekskul}', [InputAbsensiEkskulController::class, 'viewInputAbsensiEkskul']);
            Route::get('input-absensi-ekskul/manage/{id_semester}/{id_ekskul}', [InputAbsensiEkskulController::class, 'viewManageInputAbsensiEkskul']);
            Route::get('input-absensi-ekskul/manage/{id_semester}/{id_ekskul}/{id_presensi_ekskul}', [InputAbsensiEkskulController::class, 'viewManageInputAbsensiEkskul']);
            Route::get('input-absensi-ekskul/detail/{id_ekskul}/{id_kelas}/{tahun}/{id_bulan}', [InputAbsensiEkskulController::class, 'viewDetailInputAbsensiEkskul']);

            Route::post('input-absensi-ekskul/datatables/{id_semester}/{id_ekskul}', [InputAbsensiEkskulController::class, 'datatablesInputAbsensiEkskul']);
            Route::post('input-absensi-ekskul/datatables-detail/{id_semester}/{id_ekskul}/{id_presensi_ekskul}', [InputAbsensiEkskulController::class, 'datatablesSiswaInputAbsensiEkskul']);
            Route::post('input-absensi-ekskul/action/{mode}', [InputAbsensiEkskulController::class, 'actionInputAbsensiEkskul']);
            Route::post('input-absensi-ekskul/action/{mode}/{id}', [InputAbsensiEkskulController::class, 'actionInputAbsensiEkskul']);

            // Menu Rekap Absensi Ekskul
            Route::get('rekap-absensi-ekskul', [RekapAbsensiEkskulController::class, 'viewRekapAbsensiEkskul']);
            Route::get('rekap-absensi-ekskul/detail/{id_semester}/{id_ekskul}', [RekapAbsensiEkskulController::class, 'viewDetailRekapAbsensiEkskul']);
            Route::get('rekap-absensi-ekskul/print/{id_semester}/{id_ekskul}', [RekapAbsensiEkskulController::class, 'printRekapAbsensiEkskul']);
            Route::get('rekap-absensi-ekskul/print-detail/{id_semester}/{id_ekskul}/{id_siswa}', [RekapAbsensiEkskulController::class, 'printRekapAbsensiKehadiranEkskul']);

            // Menu Komponen Nilai Ekskul
            Route::get('komponen-nilai-ekskul', [KomponenNilaiEkskulController::class, 'viewKomponenNilaiEkskul']);
            Route::post('post-view-komponen-nilai', [KomponenNilaiEkskulController::class, 'postViewKomponenNilaiEkskul']);
            Route::get('komponen-nilai-ekskul/list/{id_semester}/{id_ekskul}', [KomponenNilaiEkskulController::class, 'viewListKomponenNilaiEkskul']);
            Route::get('komponen-nilai-ekskul/datatables/{id_semester}/{id_ekskul}', [KomponenNilaiEkskulController::class, 'datatablesKomponenNilaiEkskul']);
            Route::get('komponen-nilai-ekskul/add/{id_semester}/{id_ekskul}', [KomponenNilaiEkskulController::class, 'addKomponenNilaiEkskul']);
            Route::get('komponen-nilai-ekskul/edit/{id_semester}/{id_ekskul}/{id}', [KomponenNilaiEkskulController::class, 'editKomponenNilaiEkskul']);

            Route::post('action-komponen-nilai-ekskul/{mode}/{id?}', [KomponenNilaiEkskulController::class, 'actionKomponenNilaiEkskul']);

            // Menu Input Nilai Ekskul
            Route::get('input-nilai-ekskul', [InputNilaiEkskulController::class, 'viewInputNilaiEkskul']);
            Route::post('post-view-input-nilai', [InputNilaiEkskulController::class, 'postViewInputNilaiEkskul']);
            Route::get('input-nilai-ekskul/detail/{id_semester}/{id_ekskul}', [InputNilaiEkskulController::class, 'viewDetailInputNilaiEkskul']);

            Route::post('input-nilai-ekskul/save', [InputNilaiEkskulController::class, 'saveInputNilaiEkskul']);

            // Menu Rekap Nilai Ekskul
            Route::get('rekap-nilai-ekskul', [RekapNilaiEkskulController::class, 'viewRekapNilaiEkskul']);
            Route::get('rekap-nilai-ekskul/detail/{id_semester}/{id_ekskul}', [RekapNilaiEkskulController::class, 'viewDetailRekapNilaiEkskul']);
            Route::get('rekap-nilai-ekskul/print/{id_semester}/{id_ekskul}', [RekapNilaiEkskulController::class, 'printRekapNilaiEkskul']);
        });
    });
});
