<?php
// ROLE PENDIDIKAN

use App\Http\Controllers\Guru\GuruPiket\AbsensiHarianSiswaController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Pendidikan\DataAkademik\JalurController;
use App\Http\Controllers\Pendidikan\DataAkademik\JamKBMController;
use App\Http\Controllers\Pendidikan\DataAkademik\JurusanController;
use App\Http\Controllers\Pendidikan\DataAkademik\KalenderAkademikController;
use App\Http\Controllers\Pendidikan\DataAkademik\KegiatanController;
use App\Http\Controllers\Pendidikan\DataAkademik\NamaSemesterController;
use App\Http\Controllers\Pendidikan\DataAkademik\NilaiMutuController;
use App\Http\Controllers\Pendidikan\DataAkademik\RentangNilaiMutuController;
use App\Http\Controllers\Pendidikan\DataSekolah\InputDataSekolahController;
use App\Http\Controllers\Pendidikan\LaporanAkademik\JurnalGuruController;
use App\Http\Controllers\Pendidikan\LaporanAkademik\JurnalKelasController;
use App\Http\Controllers\Pendidikan\SettingKelas\BkKelasController;
use App\Http\Controllers\Pendidikan\SettingKelas\KelasController;
use App\Http\Controllers\Pendidikan\SettingKelas\RuanganKelasController;
use App\Http\Controllers\Pendidikan\SettingKelas\SekretarisKelasController;
use App\Http\Controllers\Pendidikan\SettingKelas\WaliKelasController;
use App\Http\Controllers\Pendidikan\Siswa\CariSiswaController;
use App\Http\Controllers\Pendidikan\Siswa\DataSiswaController;
use App\Http\Controllers\Pendidikan\Siswa\InsertUpdateSiswaController;
use App\Http\Controllers\Pendidikan\WelcomeController;
use App\Http\Controllers\Pendidikan\Wisuda\EntriWisudaController;
use App\Http\Controllers\Pendidikan\Wisuda\PengajuanWisudaController;
use App\Http\Controllers\Pendidikan\Wisuda\PeriodeWisudaController;
use App\Http\Controllers\Pendidikan\Wisuda\SetLulusController;
use App\Http\Controllers\Pendidikan\Wisuda\WisudaController;
use App\Http\Controllers\PPDB\Pendaftaran\PenerimaanController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;

Route::middleware(['token_staff'])->group(function () {
    // url: /pendidikan
    Route::prefix('pendidikan')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);
        Route::get('biodata', [\App\Http\Controllers\Administrator\WelcomeController::class, 'viewBiodata']);

        /** ==== MODUL MANAJEMEN FILE ==== **/
        // url: /pendidikan/manajemen-file
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

        /** ==== MODUL DATA AKADEMIK ==== **/
        // url: /pendidikan/data-akademik
        Route::prefix('data-akademik')->group(function () {
            // MENU Nilai Mutu
            // url: /pendidikan/data-akademik/nilai-mutu
            Route::get('nilai-mutu', [NilaiMutuController::class, 'viewNilaiMutu']);
            Route::get('nilai-mutu/datatables', [NilaiMutuController::class, 'datatablesNilaiMutu']);
            Route::get('nilai-mutu/add', [NilaiMutuController::class, 'addNilaiMutu']);
            Route::get('nilai-mutu/edit/{id}', [NilaiMutuController::class, 'editNilaiMutu']);

            Route::post('action-nilai-mutu/{mode}/{id}', [NilaiMutuController::class, 'actionNilaiMutu']);

            // MENU Rentang Nilai Mutu
            Route::get('rentang-nilai-mutu', [RentangNilaiMutuController::class, 'viewRentangNilaiMutu']);
            Route::get('rentang-nilai-mutu/datatables', [RentangNilaiMutuController::class, 'datatablesRentangNilaiMutu']);
            Route::get('rentang-nilai-mutu/add', [RentangNilaiMutuController::class, 'addRentangNilaiMutu']);
            Route::get('rentang-nilai-mutu/edit/{id}', [RentangNilaiMutuController::class, 'editRentangNilaiMutu']);

            Route::post('action-rentang-nilai-mutu/{mode}/{id}', [RentangNilaiMutuController::class, 'actionRentangNilaiMutu']);

            // MENU Jam KBM
            Route::get('jam-kbm', [JamKBMController::class, 'viewJamKBM']);
            Route::get('jam-kbm/datatables', [JamKBMController::class, 'datatablesJamKBM']);
            Route::get('jam-kbm/add', [JamKBMController::class, 'addJamKBM']);
            Route::get('jam-kbm/edit/{id}', [JamKBMController::class, 'editJamKBM']);

            Route::post('action-jam-kbm/{mode}/{id}', [JamKBMController::class, 'actionJamKBM']);

            // MENU Data Nama Semester
            Route::get('nama-semester', [NamaSemesterController::class, 'viewNamaSemester']);
            Route::get('nama-semester/datatables', [NamaSemesterController::class, 'datatablesNamaSemester']);
            Route::get('nama-semester/add', [NamaSemesterController::class, 'addNamaSemester']);
            Route::get('nama-semester/edit/{id}', [NamaSemesterController::class, 'editNamaSemester']);

            Route::post('action-nama-semester/{mode}/{id}', [NamaSemesterController::class, 'actionNamaSemester']);

            // MENU Data Jalur
            Route::get('jalur', [JalurController::class, 'viewJalur']);
            Route::get('jalur/datatables', [JalurController::class, 'datatablesJalur']);
            Route::get('jalur/add', [JalurController::class, 'addJalur']);
            Route::get('jalur/edit/{id}', [JalurController::class, 'editJalur']);

            Route::post('action-jalur/{mode}/{id}', [JalurController::class, 'actionJalur']);

            // MENU Data Kegiatan
            Route::get('kegiatan', [KegiatanController::class, 'viewKegiatan']);
            Route::get('kegiatan/datatables', [KegiatanController::class, 'datatablesKegiatan']);
            Route::get('kegiatan/add', [KegiatanController::class, 'addKegiatan']);
            Route::get('kegiatan/edit/{id}', [KegiatanController::class, 'editKegiatan']);

            Route::post('action-kegiatan/{mode}/{id}', [KegiatanController::class, 'actionKegiatan']);

            // MENU Kalender Akademik
            Route::get('kalender-akademik', [KalenderAkademikController::class, 'viewKalenderAkademik']);
            Route::post('post-view-kalender-akademik', [KalenderAkademikController::class, 'actionViewSemesterKalenderAkademik']);
            Route::get('kalender-akademik/view-semester/{id_semester}', [KalenderAkademikController::class, 'viewSemesterKalenderAkademik']);
            Route::get('kalender-akademik/datatables/{id_semester}', [KalenderAkademikController::class, 'datatablesKalenderAkademik']);
            Route::get('kalender-akademik/add/{id_semester}', [KalenderAkademikController::class, 'addKalenderAkademik']);
            Route::get('kalender-akademik/edit/{id_semester}/{id}', [KalenderAkademikController::class, 'editKalenderAkademik']);

            Route::post('action-kalender-akademik/{mode}/{id}', [KalenderAkademikController::class, 'actionKalenderAkademik']);

            // MENU Data Jurusan
            Route::get('jurusan', [JurusanController::class, 'viewJurusan']);
            Route::get('jurusan/datatables', [JurusanController::class, 'datatablesJurusan']);
            Route::get('jurusan/add', [JurusanController::class, 'addJurusan']);
            Route::get('jurusan/edit/{id}', [JurusanController::class, 'editJurusan']);

            Route::post('action-jurusan/{mode}/{id}', [JurusanController::class, 'actionJurusan']);
        });

        Route::prefix('data-sekolah')->group(function () {
            //MENU Input Data Sekolah
            Route::get('input-data-sekolah', [InputDataSekolahController::class, 'viewInputDataSekolah']);
            Route::get('data-sekolah/get-kota/{alamat_provinsi}', [InputDataSekolahController::class, 'getKota']);
            Route::post('action-input-data-sekolah/{mode}/{id}', [InputDataSekolahController::class, 'actionInputDataSekolah']);
            Route::post('action-input-file-sekolah/{mode}/{id}', [InputDataSekolahController::class, 'actionInputFileSekolah']);
        });

        Route::prefix('kegiatan-harian')->group(function () {
            Route::prefix('mengisi-form-kesehatan')->group(function () {
                Route::get('/', [FormKesehatanController::class, 'viewFormKesehatan']);
                Route::get('add', [FormKesehatanController::class, 'viewAddFormKesehatan']);
                Route::get('detail/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

                Route::post('action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
                Route::post('datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
            });
        });

        Route::prefix('setting-kelas')->group(function () {
            // MENU Data Kelas
            Route::get('kelas', [KelasController::class, 'viewKelas']);
            Route::get('kelas/datatables', [KelasController::class, 'datatablesKelas']);
            Route::get('kelas/add', [KelasController::class, 'addKelas']);
            Route::get('kelas/edit/{id}', [KelasController::class, 'editKelas']);
            Route::get('kelas/copy', [KelasController::class, 'copyKelas']);

            Route::post('action-kelas/{mode}/{id}', [KelasController::class, 'actionKelas']);

            // MENU Setting Wali Kelas
            Route::get('wali-kelas', [WaliKelasController::class, 'viewWaliKelas']);
            Route::post('post-view-wali-kelas', [WaliKelasController::class, 'actionViewWaliKelas']);
            Route::get('wali-kelas/view-kelas/{id_kelas}', [WaliKelasController::class, 'viewKelasWaliKelas']);
            Route::get('wali-kelas/datatables/{id_kelas}', [WaliKelasController::class, 'datatablesWaliKelas']);
            Route::get('wali-kelas/add/{id_kelas}', [WaliKelasController::class, 'addWaliKelas']);
            Route::get('wali-kelas/edit/{id_kelas}/{id_semester}/{id}', [WaliKelasController::class, 'editWaliKelas']);

            Route::post('action-wali-kelas/{mode}/{id}', [WaliKelasController::class, 'actionWaliKelas']);

            // Menu Setting BK Kelas
            Route::get('setting-bk-kelas', [BkKelasController::class, 'viewBkKelas']);
            Route::post('post-view-bk-kelas', [BkKelasController::class, 'actionViewBkKelas']);
            Route::get('bk-kelas/view-kelas/{id_kelas}', [BkKelasController::class, 'viewKelasBkKelas']);
            Route::get('bk-kelas/datatables/{id_kelas}', [BkKelasController::class, 'datatablesBkKelas']);
            Route::get('bk-kelas/add/{id_kelas}', [BkKelasController::class, 'addBkKelas']);
            Route::get('bk-kelas/edit/{id_kelas}/{id_semester}/{id}', [BkKelasController::class, 'editBkKelas']);

            Route::post('action-bk-kelas/{mode}/{id}', [BkKelasController::class, 'actionBkKelas']);

            // MENU Setting Sekretaris Kelas
            Route::get('sekretaris-kelas', [SekretarisKelasController::class, 'viewSekretarisKelas']);
            Route::post('post-view-sekretaris-kelas', [SekretarisKelasController::class, 'actionViewSekretarisKelas']);
            Route::get('sekretaris-kelas/view-kelas/{id_kelas}', [SekretarisKelasController::class, 'viewKelasSekretarisKelas']);
            Route::get('sekretaris-kelas/datatables/{id_kelas}', [SekretarisKelasController::class, 'datatablesSekretarisKelas']);
            Route::get('sekretaris-kelas/add/{id_kelas}', [SekretarisKelasController::class, 'addSekretarisKelas']);
            Route::get('sekretaris-kelas/edit/{id_kelas}/{id_semester}/{id}', [SekretarisKelasController::class, 'editSekretarisKelas']);

            Route::post('action-sekretaris-kelas/{mode}/{id}', [SekretarisKelasController::class, 'actionSekretarisKelas']);

            // MENU Setting Ruangan Kelas
            Route::get('ruangan-kelas', [RuanganKelasController::class, 'viewRuanganKelas']);
            Route::post('post-view-ruangan-kelas', [RuanganKelasController::class, 'actionViewRuanganKelas']);
            Route::get('ruangan-kelas/view-kelas/{id_kelas}', [RuanganKelasController::class, 'viewKelasRuanganKelas']);
            Route::get('ruangan-kelas/datatables/{id_kelas}', [RuanganKelasController::class, 'datatablesRuanganKelas']);
            Route::get('ruangan-kelas/add/{id_kelas}', [RuanganKelasController::class, 'addRuanganKelas']);
            Route::get('ruangan-kelas/edit/{id_kelas}/{id_semester}/{id}', [RuanganKelasController::class, 'editRuanganKelas']);

            Route::post('action-ruangan-kelas/{mode}/{id}', [RuanganKelasController::class, 'actionRuanganKelas']);
        });

        Route::prefix('pendaftaran')->group(function () {
            // MENU Data Penerimaan (ambil dari Role PPDB)
            Route::get('penerimaan', [PenerimaanController::class, 'viewPenerimaan']);
            Route::get('penerimaan/datatables', [PenerimaanController::class, 'datatablesPenerimaan']);
            Route::get('penerimaan/add', [PenerimaanController::class, 'addPenerimaan']);
            Route::get('penerimaan/edit/{id}', [PenerimaanController::class, 'editPenerimaan']);

            Route::post('action-penerimaan/{mode}/{id}', [PenerimaanController::class, 'actionPenerimaan']);
        });

        Route::prefix('siswa')->group(function () {
            // MENU DATA SISWA
            Route::get('data-siswa', [DataSiswaController::class, 'viewDataSiswa']);
            Route::get('data-siswa/get-kelas/{id_jurusan}', [DataSiswaController::class, 'getKelas']);
            Route::post('post-view-data-siswa', [DataSiswaController::class, 'actionViewDataSiswa']);
            Route::get('data-siswa/view-detail-data-siswa/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}/{filter_by}', [DataSiswaController::class, 'viewDetailDataSiswa']);
            Route::get('data-siswa/datatables/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', [DataSiswaController::class, 'datatablesDataSiswa']);
            Route::get('insert-update-siswa/view-print-siswa/{nis_nama_siswa}', [InsertUpdateSiswaController::class, 'viewPrintSiswa']);

            //MENU CARI SISWA
            Route::get('cari-siswa', [CariSiswaController::class, 'viewCariSiswa']);
            Route::post('post-view-cari-siswa', [CariSiswaController::class, 'actionViewCariSiswa']);
            Route::get('cari-siswa/view-detail/{nis_nama_siswa}', [CariSiswaController::class, 'viewDetailCariSiswa']);
            Route::get('cari-siswa/datatables/{nis_nama_siswa}', [CariSiswaController::class, 'datatablesCariSiswa']);
            Route::get('cari-siswa/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', [CariSiswaController::class, 'viewDetailSiswaCariSiswa']);

            //MENU UPDATE FOTO
            // Route::get('update-foto', [UpdateFotoController::class, 'viewUpdateFoto']);
            // Route::post('post-view-update-foto', [UpdateFotoController::class, 'actionViewUpdateFoto']);
            // Route::get('update-foto/view-detail-update-foto/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', [UpdateFotoController::class, 'viewDetailUpdateFoto']);
            // Route::get('update-foto/datatables/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', [UpdateFotoController::class, 'datatablesUpdateFoto']);
            // Route::get('update-foto/upload/{id_pengguna}', [UpdateFotoController::class, 'viewUpload']);
            // Route::post('action-update-foto/{mode}/{id}', [UpdateFotoController::class, 'actionUpdateFoto']);

            //MENU UPLOAD DATA SISWA
            // Route::get('upload-data-siswa', [UploadDataSiswaController::class, 'viewUploadDataSiswa']);
            // Route::get('/download-file-excel', [UploadDataSiswaController::class, 'downloadFileExcel'])->name('siswa/download-file-excel');
            // Route::post('post-file-excel', [UploadDataSiswaController::class, 'uploadFileExcel']);

            //MENU ADMISI SISWA
            // Route::get('admisi-siswa', [AdmisiSiswaController::class, 'viewAdmisiSiswa']);
            // Route::post('post-view-admisi-siswa', [AdmisiSiswaController::class, 'actionViewAdmisiSiswa']);
            // Route::get('admisi-siswa/view-detail/{nis_nama_siswa}', [AdmisiSiswaController::class, 'viewDetailAdmisiSiswa']);
            // Route::post('action-admisi-siswa', [AdmisiSiswaController::class, 'actionAdmisiSiswa']);
            // Route::get('admisi-siswa/generate', [AdmisiSiswaController::class, 'generateAdmisiSiswa']);
            // Route::post('action-generate-admisi-siswa', [AdmisiSiswaController::class, 'actionGenerateAdmisiSiswa']);
            // Route::get('admisi-siswa/generate/laporan', [AdmisiSiswaController::class, 'laporanGenerateAdmisiSiswa']);
            // Route::post('post-view-laporan-admisi-siswa', [AdmisiSiswaController::class, 'actionViewLaporanAdmisiSiswa']);
            // Route::get('admisi-siswa/view-laporan/{id_semester}/{id_kelas}', [AdmisiSiswaController::class, 'viewLaporanAdmisiSiswa']);
            // Route::get('admisi-siswa/datatables/{id_semester}/{id_kelas}', [AdmisiSiswaController::class, 'datatablesAdmisiSiswa']);

            //MENU HISTORY ADMISI SISWA
            // Route::get('histori-admisi-siswa', [HistoryAdmisiSiswaController::class, 'viewHistoryAdmisiSiswa']);
            // Route::post('post-view-histori-admisi-siswa', [HistoryAdmisiSiswaController::class, 'actionViewHistoryAdmisiSiswa']);
            // Route::get('histori-admisi-siswa/view-detail/{nis_nama_siswa}', [HistoryAdmisiSiswaController::class, 'viewDetailHistoryAdmisiSiswa']);
            // Route::get('histori-admisi-siswa/datatables/{nis_siswa}', [HistoryAdmisiSiswaController::class, 'datatablesHistoryAdmisiSiswa']);
            // Route::post('action-histori-admisi-siswa/{mode}/{id}', [HistoryAdmisiSiswaController::class, 'actionHistoryAdmisiSiswa']);

            //MENU SISWA AKTIF
            // Route::get('siswa-aktif', [SiswaAktifController::class, 'viewSiswaAktif']);

            //MENU INSERT-UPDATE SISWA
            // Route::get('insert-update-siswa', [InsertUpdateSiswaController::class, 'viewInsertUpdateSiswa']);
            // Route::post('post-view-update-siswa', [InsertUpdateSiswaController::class, 'actionViewUpdateSiswa']);
            // Route::get('insert-update-siswa/view-detail/{nis_nama_siswa}', [InsertUpdateSiswaController::class, 'viewDetailUpdateSiswa']);

            // Route::post('action-insert-update-siswa/{mode}/{id}', [InsertUpdateSiswaController::class, 'actionInsertUpdateSiswa']);

            //MENU Setting Wali Murid
            // Route::get('setting-wali-murid', [SettingWaliMuridController::class, 'viewSettingWaliMurid']);
            // Route::post('post-view-setting-wali-murid', [SettingWaliMuridController::class, 'actionViewSettingWaliMurid']);
            // Route::get('setting-wali-murid/view-kelas/{id_kelas}', [SettingWaliMuridController::class, 'viewKelasWaliMurid']);
            // Route::get('setting-wali-murid/datatables/{id_kelas}', [SettingWaliMuridController::class, 'datatablesWaliMurid']);
            // Route::get('setting-wali-murid/edit/{id}', [SettingWaliMuridController::class, 'editWaliMurid']);

            // Route::post('action-setting-wali-murid/{mode}/{id}', [SettingWaliMuridController::class, 'actionSettingWaliMurid']);

            //MENU Setting Kelas Siswa
            // Route::get('setting-kelas-siswa', [SettingKelasSiswaController::class, 'viewSettingKelasSiswa']);
            // Route::post('post-view-setting-kelas-siswa', [SettingKelasSiswaController::class, 'actionViewSettingKelasSiswa']);
            // Route::get('setting-kelas-siswa/view-kelas/{id_kelas}', [SettingKelasSiswaController::class, 'viewKelasSettingKelas']);
            // Route::get('setting-kelas-siswa/datatables/{id_kelas}', [SettingKelasSiswaController::class, 'datatablesKelasSiswa']);
            // Route::get('setting-kelas-siswa/datatables-siswa', [SettingKelasSiswaController::class, 'datatablesSiswa']);
            // Route::get('setting-kelas-siswa/edit/{id}', [SettingKelasSiswaController::class, 'tambahKelasSiswa']);

            // Route::post('action-setting-kelas-siswa/{mode}/{id}', [SettingKelasSiswaController::class, 'actionSettingKelasSiswa']);
        });

        Route::prefix('wisuda')->group(function () {
            // MENU Nama Wisuda
            Route::get('nama-wisuda', [WisudaController::class, 'viewWisuda']);
            Route::get('nama-wisuda/datatables', [WisudaController::class, 'datatablesWisuda']);
            Route::get('nama-wisuda/add', [WisudaController::class, 'addWisuda']);
            Route::get('nama-wisuda/edit/{id}', [WisudaController::class, 'editWisuda']);

            Route::post('action-nama-wisuda/{mode}/{id}', [WisudaController::class, 'actionWisuda']);

            // MENU Periode Wisuda
            Route::get('periode-wisuda', [PeriodeWisudaController::class, 'viewPeriodeWisuda']);
            Route::get('periode-wisuda/datatables', [PeriodeWisudaController::class, 'datatablesPeriodeWisuda']);
            Route::get('periode-wisuda/add', [PeriodeWisudaController::class, 'addPeriodeWisuda']);
            Route::get('periode-wisuda/edit/{id}', [PeriodeWisudaController::class, 'editPeriodeWisuda']);

            Route::post('action-periode-wisuda/{mode}/{id}', [PeriodeWisudaController::class, 'actionPeriodeWisuda']);

            // MENU Pengajuan Wisuda
            Route::get('pengajuan-wisuda', [PengajuanWisudaController::class, 'viewPengajuanWisuda']);
            Route::post('post-view-pengajuan-wisuda', [PengajuanWisudaController::class, 'actionViewDetailPengajuanWisuda']);
            Route::get('pengajuan-wisuda/view-detail/{id_periode_wisuda}/{nis_nama_siswa}', [PengajuanWisudaController::class, 'viewDetailPengajuanWisuda']);
            Route::get('pengajuan-wisuda/datatables/{id_periode_wisuda}/{nis_nama_siswa}', [PengajuanWisudaController::class, 'datatablesPengajuanWisuda']);
            Route::get('pengajuan-wisuda/cancel/{id}/{id_periode_wisuda}/{nis_nama_siswa}/', [PengajuanWisudaController::class, 'cancelPengajuanWisuda']);

            Route::post('action-pengajuan-wisuda/{mode}/{id}/{id_siswa}/{id_periode_wisuda}', [PengajuanWisudaController::class, 'actionPengajuanWisuda']);

            // MENU Entri Data Wisuda
            Route::get('entri-wisuda', [EntriWisudaController::class, 'viewEntriWisuda']);
            Route::post('post-view-entri-wisuda', [EntriWisudaController::class, 'actionViewDetailEntriWisuda']);
            Route::get('entri-wisuda/view-detail/{id_periode_wisuda}/{nis_nama_siswa}', [EntriWisudaController::class, 'viewDetailEntriWisuda']);
            Route::get('entri-wisuda/datatables/{id_periode_wisuda}/{nis_nama_siswa}', [EntriWisudaController::class, 'datatablesEntriWisuda']);
            Route::get('entri-wisuda/input/{id}/{id_periode_wisuda}/{nis_nama_siswa}/', [EntriWisudaController::class, 'inputEntriWisuda']);

            Route::post('action-entri-wisuda/{mode}/{id}/{id_siswa}/{id_periode_wisuda}', [EntriWisudaController::class, 'actionEntriWisuda']);

            // MENU Set Lulus Siswa ==== (BELOM SEMUA) ====
            Route::get('set-lulus', [SetLulusController::class, 'viewSetLulus']);
            Route::get('set-lulus/datatables', [SetLulusController::class, 'datatablesSetLulus']);

            Route::post('action-set-lulus/{mode}/{id}', [SetLulusController::class, 'actionSetLulus']);
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
        });

        /** ==== MODUL LAPORAN AKADEMIK ==== **/
        Route::prefix('laporan-akademik')->group(function () {

            Route::prefix('jurnal-guru')->group(function () {
                Route::get('print-pdf/{id_kelas_mp}', [JurnalGuruController::class, 'printPdfJurnalGuru']);

                Route::get('/', [JurnalGuruController::class, 'viewJurnalGuru']);
                Route::get('/{id_guru}/{id_semester}', [JurnalGuruController::class, 'viewJurnalGuru']);
                Route::get('datatables/{id_guru}/{id_semester}', [JurnalGuruController::class, 'datatablesJurnalGuru']);

            });
            Route::prefix('jurnal-kelas')->group(function () {
                Route::get('print-pdf/{id_kelas_mp}', [JurnalKelasController::class, 'printPdfJurnalKelas']);

                Route::get('/', [JurnalKelasController::class, 'viewJurnalKelas']);
                Route::get('/{id_kelas}/{id_semester}', [JurnalKelasController::class, 'viewJurnalKelas']);
                Route::get('datatables/{id_kelas}/{id_semester}', [JurnalKelasController::class, 'datatablesJurnalKelas']);
            });
            Route::get('absensi-siswa', [AbsensiHarianSiswaController::class, 'viewAbsensiHarianSiswa']);

            //MENU ABSENSI SISWA
            // Route::get('absensi-siswa', [AbsensiSiswaController::class, 'viewAbsensiSiswa']);
            // Route::post('post-view-absensi-siswa', [AbsensiSiswaController::class, 'actionViewAbsensiSiswa']);
            // Route::get('absensi-siswa/absensi-kelas/{id_semester}/{id_jurusan}/{id_kelas}/{tgl_mulai}/{tgl_selesai}', [AbsensiSiswaController::class, 'viewAbsensiSiswa']);
        });
    });
});
