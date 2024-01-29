<?php

use App\Http\Controllers\Kesiswaan\WelcomeController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\Pendidikan\Wisuda\WisudaController;
use App\Http\Controllers\Kesiswaan\Siswa\HomeVisitController;
use App\Http\Controllers\Kesiswaan\Siswa\PembayaranController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\Pendidikan\Siswa\CariSiswaController;
use App\Http\Controllers\Pendidikan\Siswa\DataSiswaController;
use App\Http\Controllers\Siswa\DataPribadi\DataSiswaController as DataSiswaControllerRoleSiswa;
use App\Http\Controllers\Pendidikan\Wisuda\SetLulusController;
use App\Http\Controllers\Kesiswaan\Laporan\WaliKelasController;
use App\Http\Controllers\Pendidikan\Siswa\SiswaAktifController;
use App\Http\Controllers\Pendidikan\Siswa\UpdateFotoController;
use App\Http\Controllers\PPDB\Pendaftaran\PenerimaanController;
use App\Http\Controllers\Pendidikan\Siswa\AdmisiSiswaController;
use App\Http\Controllers\Guru\GuruPiket\RekapKesehatanController;
use App\Http\Controllers\Kesiswaan\Siswa\BeasiswaSiswaController;
use App\Http\Controllers\Kesiswaan\Siswa\EvaluasiSiswaController;
use App\Http\Controllers\Kesiswaan\Siswa\KegiatanSiswaController;
use App\Http\Controllers\Kesiswaan\Siswa\PrestasiSiswaController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Pendidikan\Wisuda\EntriWisudaController;
use App\Http\Controllers\Pendidikan\Wisuda\LaporanWisudaController;
use App\Http\Controllers\Pendidikan\Wisuda\PeriodeWisudaController;
use App\Http\Controllers\Pendidikan\Siswa\UploadDataSiswaController;
use App\Http\Controllers\Pendidikan\Siswa\SettingWaliMuridController;
use App\Http\Controllers\Pendidikan\Wisuda\PengajuanWisudaController;
use App\Http\Controllers\Kesiswaan\Ijazah\PengambilanIjazahController;
use App\Http\Controllers\Pendidikan\Siswa\InsertUpdateSiswaController;
use App\Http\Controllers\Pendidikan\Siswa\SettingKelasSiswaController;
use App\Http\Controllers\Kesiswaan\SKPI\ApprovePrestasiSiswaController;
use App\Http\Controllers\Pendidikan\DataAkademik\StatusSiswaController;
use App\Http\Controllers\Pendidikan\Siswa\HistoryAdmisiSiswaController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;
use App\Http\Controllers\Kesiswaan\Ekstrakurikuler\DataEkskulController;
use App\Http\Controllers\Kesiswaan\Siswa\TingkatPrestasiSiswaController;
use App\Http\Controllers\Kesiswaan\Ekstrakurikuler\EkskulWajibController;
use App\Http\Controllers\Kesiswaan\PenangananSiswa\JenisTindakanController;
use App\Http\Controllers\Kesiswaan\PenangananSiswa\InputPelanggaranController;
use App\Http\Controllers\Kesiswaan\PenangananSiswa\TindakanPelanggaranController;
use App\Http\Controllers\Kesiswaan\Ekstrakurikuler\SettingPelatihEkskulController;
use App\Http\Controllers\Kesiswaan\Ekstrakurikuler\SettingPembinaEkskulController;
use App\Http\Controllers\Kesiswaan\Ekstrakurikuler\SettingPesertaEkskulController;
use App\Http\Controllers\Kesiswaan\Ekstrakurikuler\MonitoringNilaiEkskulController;
use App\Http\Controllers\Kesiswaan\Ekstrakurikuler\MonitoringAbsensiEkskulController;
use App\Http\Controllers\Guru\WaliKelas\RekapKesehatanController as WaliKelasRekapKesehatanController;
use App\Http\Controllers\Humas\KegiatanHarian\RekapKesehatanController as HumasRekapKesehatanController;

Route::middleware(['token_staff'])->group(function () {

    Route::prefix('kesiswaan')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);

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

        Route::prefix('monitoring-kesehatan')->group(function () {
            // MENU Rekap Kesehatan Siswa
            Route::get('rekap-kesehatan', [RekapKesehatanController::class, 'viewRekapKesehatan']);
            Route::get('rekap-kesehatan/user/{id}/{date}', [RekapKesehatanController::class, 'viewRekapKesehatanSiswa']);
            Route::get('rekap-kesehatan/detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

            Route::get('rekap-kesehatan/{id}', [RekapKesehatanController::class, 'viewDetailRekapKesehatan']);
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}', [RekapKesehatanController::class, 'viewDetailRekapKesehatan']);
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}/download', [RekapKesehatanController::class, 'downloadDetailRekapKesehatan']);

            Route::post('rekap-kesehatan/action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
            Route::post('rekap-kesehatan/datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);

            // Menu Rekap Kesehatan Guru
            Route::get('rekap-kesehatan-guru', [HumasRekapKesehatanController::class, 'viewRekapFormKesehatan']);
            Route::get('rekap-kesehatan-guru/{bulan}/{tahun}', [HumasRekapKesehatanController::class, 'viewRekapFormKesehatan']);
            Route::get('rekap-kesehatan-guru/{bulan}/{tahun}/download', [HumasRekapKesehatanController::class, 'downloadRekapFormKesehatan']);
            Route::get('rekap-kesehatan-guru/detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);
            Route::get('rekap-kesehatan-guru/user/{id}/{date}', [WaliKelasRekapKesehatanController::class, 'viewRekapKesehatanSiswa']);

            Route::post('rekap-kesehatan-guru/action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
            Route::post('rekap-kesehatan-guru/datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
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

        Route::prefix('skpi')->group(function () {
            Route::get('approve-prestasi-siswa', [ApprovePrestasiSiswaController::class, 'viewApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/datatables', [ApprovePrestasiSiswaController::class, 'datatablesApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/{id}/{param}', [ApprovePrestasiSiswaController::class, 'viewDetailPrestasiSiswa']);
            Route::get('approve-prestasi-siswa/print/skpi/{id}', [ApprovePrestasiSiswaController::class, 'printSkpi']);
            Route::get('approve-prestasi-siswa/printkelas/skpi/{id_kelas}', [ApprovePrestasiSiswaController::class, 'printSkpikelas']);

            Route::get('approve-prestasi-siswa/prestasi/datatables/{id}/{param}', [ApprovePrestasiSiswaController::class, 'datatablesPrestasiApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/kegiatan/datatables/{id}/{param}', [ApprovePrestasiSiswaController::class, 'datatablesKegiatanApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/informasi-tambahan/datatables/{id}/{param}', [ApprovePrestasiSiswaController::class, 'datatablesInformasiTambahan']);

            Route::post('approve-prestasi-siswa/{data}/{id}', [ApprovePrestasiSiswaController::class, 'actionApprovePrestasiSiswa']);
            Route::post('reject-prestasi-siswa/{data}/{id}', [ApprovePrestasiSiswaController::class, 'actionRejectPrestasiSiswa']);

            Route::get('edit-prestasi-siswa/{id}', [ApprovePrestasiSiswaController::class, 'editPrestasiSiswa']);
            Route::get('edit-kegiatan-siswa/{id}', [ApprovePrestasiSiswaController::class, 'editKegiatanSiswa']);
            Route::get('edit-informasi-tambahan-siswa/{id}', [ApprovePrestasiSiswaController::class, 'editInformasiTambahanSiswa']);

            Route::post('action-edit-prestasi-siswa/{id}', [ApprovePrestasiSiswaController::class, 'actionEditPrestasiSiswa']);
            Route::post('action-edit-kegiatan-siswa/{id}', [ApprovePrestasiSiswaController::class, 'actionEditKegiatanSiswa']);
            Route::post('action-edit-informasi-tambahan-siswa/{id}', [ApprovePrestasiSiswaController::class, 'actionEditInformasiTambahanSiswa']);
        });

        Route::prefix('ekstrakurikuler')->group(function () {
            // MENU Data Ekstrakurikuler
            Route::get('data-ekskul', [DataEkskulController::class, 'viewDataEkskul']);
            Route::get('data-ekskul/datatables', [DataEkskulController::class, 'datatablesDataEkskul']);
            Route::get('data-ekskul/add', [DataEkskulController::class, 'addDataEkskul']);
            Route::get('data-ekskul/edit/{id}', [DataEkskulController::class, 'editDataEkskul']);

            Route::post('action-data-ekskul/{mode}/{id}', [DataEkskulController::class, 'actionDataEkskul']);

            //MENU Ekskul Wajib
            Route::get('ekskul-wajib', [EkskulWajibController::class, 'viewEkskulWajib']);
            Route::get('ekskul-wajib/datatables', [EkskulWajibController::class, 'datatablesEkskulWajib']);
            Route::get('ekskul-wajib/add', [EkskulWajibController::class, 'addEkskulWajib']);
            Route::get('ekskul-wajib/edit/{id}', [EkskulWajibController::class, 'editEkskulWajib']);

            Route::post('action-ekskul-wajib/{mode}/{id}', [EkskulWajibController::class, 'actionEkskulWajib']);

            //MENU Setting Pelatih Ekskul
            Route::get('setting-pelatih-ekskul', [SettingPelatihEkskulController::class, 'viewSettingPelatihEkskul']);
            Route::get('setting-pelatih-ekskul/datatables', [SettingPelatihEkskulController::class, 'datatablesSettingPelatihEkskul']);
            Route::get('setting-pelatih-ekskul/datatables-pelatih', [SettingPelatihEkskulController::class, 'datatablesPelatihEkskul']);
            Route::get('setting-pelatih-ekskul/add', [SettingPelatihEkskulController::class, 'addSettingPelatihEkskul']);
            Route::get('setting-pelatih-ekskul/view-pelatih', [SettingPelatihEkskulController::class, 'viewPelatihEkskul']);
            Route::get('setting-pelatih-ekskul/edit/{id}', [SettingPelatihEkskulController::class, 'editSettingPelatihEkskul']);
            Route::get('setting-pelatih-ekskul/edit-status/{id}', [SettingPelatihEkskulController::class, 'editStatusSettingPelatihEkskul']);
            Route::get('setting-pelatih-ekskul/assign/{id}', [SettingPelatihEkskulController::class, 'assignSettingPelatihEkskul']);

            Route::post('action-setting-pelatih-ekskul/{mode}/{id}', [SettingPelatihEkskulController::class, 'actionSettingPelatihEkskul']);

            //MENU Setting Pembina Ekskul
            Route::get('setting-pembina-ekskul', [SettingPembinaEkskulController::class, 'viewSettingPembinaEkskul']);
            Route::get('setting-pembina-ekskul/datatables', [SettingPembinaEkskulController::class, 'datatablesSettingPembinaEkskul']);
            Route::get('setting-pembina-ekskul/assign/{id}', [SettingPembinaEkskulController::class, 'assignSettingPembinaEkskul']);

            Route::post('action-setting-pembina-ekskul/{mode}/{id}', [SettingPembinaEkskulController::class, 'actionSettingPembinaEkskul']);

            //MENU Setting Peserta Ekskul
            Route::get('setting-peserta-ekskul', [SettingPesertaEkskulController::class, 'viewSettingPesertaEkskul']);
            Route::post('post-view-setting-peserta-ekskul', [SettingPesertaEkskulController::class, 'actionViewSettingPesertaEkskul']);
            Route::get('setting-peserta-ekskul/view-ekskul/{id_semester}/{id_eskul}', [SettingPesertaEkskulController::class, 'viewEkskulSettingPesertaEkskul']);
            Route::get('setting-peserta-ekskul/datatables/{id_ekskul}/{id_semester}', [SettingPesertaEkskulController::class, 'datatablesSettingPesertaEkskul']);
            Route::get('setting-peserta-ekskul/datatables-siswa/{id_kelas}', [SettingPesertaEkskulController::class, 'datatablesSiswaSettingPesertaEkskul']);
            Route::get('setting-peserta-ekskul/add/{id_semester}/{id_ekskul}', [SettingPesertaEkskulController::class, 'addSettingPesertaEkskul']);
            Route::post('post-add-setting-peserta-ekskul', [SettingPesertaEkskulController::class, 'actionAddSettingPesertaEkskul']);
            Route::get('setting-peserta-ekskul/view-kelas/{id_semester}/{id_ekskul}/{id_kelas}', [SettingPesertaEkskulController::class, 'viewKelasSettingPesertaEkskul']);
            Route::get('setting-peserta-ekskul/edit/{id}', [SettingPesertaEkskulController::class, 'editSettingPesertaEkskul']);
            Route::get('setting-peserta-ekskul/setting/{id_ekskul}', [SettingPesertaEkskulController::class, 'setSettingPesertaEkskul']);

            Route::post('action-setting-peserta-ekskul/{mode}/{id}', [SettingPesertaEkskulController::class, 'actionSettingPesertaEkskul']);

            Route::prefix('monitoring-absensi-ekskul')->group(function () {
                Route::get('/', [MonitoringAbsensiEkskulController::class, 'viewMonitoringAbsensiEkskul']);
                Route::get('detail/{id_semester}/{id_ekskul}', [MonitoringAbsensiEkskulController::class, 'viewDetailMonitoringAbsensiEkskul']);
                Route::get('print/{id_semester}/{id_ekskul}', [MonitoringAbsensiEkskulController::class, 'printMonitoringAbsensiEkskul']);
            });
            Route::prefix('monitoring-nilai-ekskul')->group(function () {
                Route::get('/', [MonitoringNilaiEkskulController::class, 'viewMonitoringNilaiEkskul']);
                Route::get('detail/{id_semester}/{id_ekskul}', [MonitoringNilaiEkskulController::class, 'viewDetailMonitoringNilaiEkskul']);
                Route::get('print/{id_semester}/{id_ekskul}', [MonitoringNilaiEkskulController::class, 'printMonitoringNilaiEkskul']);
            });
        });

        Route::prefix('penanganan-siswa')->group(function () {
            // MENU Data Jenis Tindakan
            Route::get('jenis-tindakan', [JenisTindakanController::class, 'viewJenisTindakan']);
            Route::get('jenis-tindakan/datatables', [JenisTindakanController::class, 'datatablesJenisTindakan']);
            Route::get('jenis-tindakan/add', [JenisTindakanController::class, 'addJenisTindakan']);
            Route::get('jenis-tindakan/edit/{id}', [JenisTindakanController::class, 'editJenisTindakan']);

            Route::post('action-jenis-tindakan/{mode}/{id}', [JenisTindakanController::class, 'actionJenisTindakan']);

            // MENU Input Pelanggaran Siswa
            Route::get('input-pelanggaran', [InputPelanggaranController::class, 'viewInputPelanggaran']);
            Route::get('input-pelanggaran/datatables', [InputPelanggaranController::class, 'datatablesInputPelanggaran']);
            Route::get('input-pelanggaran/add', [InputPelanggaranController::class, 'addInputPelanggaran']);
            Route::get('input-pelanggaran/edit/{id}', [InputPelanggaranController::class, 'editInputPelanggaran']);

            Route::post('action-input-pelanggaran/{mode}/{id}', [InputPelanggaranController::class, 'actionInputPelanggaran']);

            // MENU Tindakan Pelanggaran
            Route::get('tindakan-pelanggaran', [TindakanPelanggaranController::class, 'viewTindakanPelanggaran']);
            Route::get('tindakan-pelanggaran/datatables-belum-nonkbm', [TindakanPelanggaranController::class, 'datatablesBelumTindakanNonKBM']);
            Route::get('tindakan-pelanggaran/datatables-belum-kbm', [TindakanPelanggaranController::class, 'datatablesBelumTindakanKBM']);
            Route::get('tindakan-pelanggaran/datatables-sudah', [TindakanPelanggaranController::class, 'datatablesSudahTindakan']);
            Route::get('tindakan-pelanggaran/add-nonkbm/{id}', [TindakanPelanggaranController::class, 'addTindakanPelanggaranNonKBM']);
            Route::get('tindakan-pelanggaran/add-kbm/{id}', [TindakanPelanggaranController::class, 'addTindakanPelanggaranKBM']);
            Route::get('tindakan-pelanggaran/edit/{id}', [TindakanPelanggaranController::class, 'editTindakanPelanggaran']);

            Route::post('action-tindakan-pelanggaran/{mode}/{id}', [TindakanPelanggaranController::class, 'actionTindakanPelanggaran']);

            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', [InputPelanggaranController::class, 'ajaxGetSiswaByKelas']);
            Route::post('siswa-pelanggaran', [InputPelanggaranController::class, 'ajaxGetPelanggaranSiswa']);

            // AJAX GET SUBKATEGORI PELANGGARAN BY KATEGORI
            Route::post('subkategori-bykategori', [InputPelanggaranController::class, 'ajaxGetSubkategoriByKategori']);
        });
        Route::prefix('siswa')->group(function () {
            // MENU Data Status Siswa
            Route::get('status-siswa', [StatusSiswaController::class, 'viewStatusSiswa']);
            Route::get('status-siswa/datatables', [StatusSiswaController::class, 'datatablesStatusSiswa']);
            Route::get('status-siswa/add', [StatusSiswaController::class, 'addStatusSiswa']);
            Route::get('status-siswa/edit/{id}', [StatusSiswaController::class, 'editStatusSiswa']);

            Route::post('action-status-siswa/{mode}/{id}', [StatusSiswaController::class, 'actionStatusSiswa']);

            // MENU DATA SISWA
            Route::get('data-siswa', [DataSiswaController::class, 'viewDataSiswa']);
            Route::get('data-siswa/get-kelas/{id_jurusan}', [DataSiswaController::class, 'getKelas']);
            Route::post('post-view-data-siswa', [DataSiswaController::class, 'actionViewDataSiswa']);
            Route::get('data-siswa/view-detail-data-siswa/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}/{filter_by}', [DataSiswaController::class, 'viewDetailDataSiswa']);
            Route::get('data-siswa/datatables/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', [DataSiswaController::class, 'datatablesDataSiswa']);
            //detail siswa
            Route::get('data-siswa/view-detail-siswa/{nis_siswa}', [DataSiswaController::class, 'viewDetailSiswa']);


            //MENU UPDATE FOTO
            Route::get('update-foto', [UpdateFotoController::class, 'viewUpdateFoto']);
            Route::get('update-foto/batch', [UpdateFotoController::class, 'viewBatchUpdateFoto']);
            Route::post('post-view-update-foto', [UpdateFotoController::class, 'actionViewUpdateFoto']);
            Route::get('update-foto/view-detail-update-foto/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', [UpdateFotoController::class, 'viewDetailUpdateFoto']);
            Route::get('update-foto/datatables/{id_jurusan}/{id_kelas}/{thn_masuk_siswa}/{id_jalur}/{id_status_pengguna}', [UpdateFotoController::class, 'datatablesUpdateFoto']);
            Route::get('update-foto/upload/{id_pengguna}', [UpdateFotoController::class, 'viewUpload']);
            Route::post('action-update-foto/{mode}/{id}', [UpdateFotoController::class, 'actionUpdateFoto']);
            Route::post('action-batch-upload-foto', [UpdateFotoController::class, 'actionBatchUploadFoto']);

            //MENU UPLOAD DATA SISWA
            Route::post('post-file-excel-email', [UploadDataSiswaController::class, 'uploadEmailExcel']);
            Route::get('update-data-siswa', [UploadDataSiswaController::class, 'updateDataSiswa']);
            Route::get('upload-data-siswa', [UploadDataSiswaController::class, 'viewUploadDataSiswa']);
            Route::get('/download-file-excel', [UploadDataSiswaController::class, 'downloadFileExcel'])->name('siswa/download-file-excel');
            Route::get('/download-file-excel-lite', [UploadDataSiswaController::class, 'downloadFileExcelLite'])->name('siswa/download-file-excel-lite');
            Route::post('post-file-excel', [UploadDataSiswaController::class, 'uploadFileExcel']);
            Route::post('cek-file-excel', [UploadDataSiswaController::class, 'cekFileExcel']);
            Route::get('upload-data-siswa/cek-data-siswa', [UploadDataSiswaController::class, 'viewCekFileExcel']);
            Route::post('upload-data-siswa/cek-data-siswa', [UploadDataSiswaController::class, 'postCekFileExcel']);



            //MENU CARI SISWA
            Route::get('cari-siswa', [CariSiswaController::class, 'viewCariSiswa']);
            Route::post('post-view-cari-siswa', [CariSiswaController::class, 'actionViewCariSiswa']);
            Route::get('cari-siswa/view-detail/{nis_nama_siswa}', [CariSiswaController::class, 'viewDetailCariSiswa']);
            Route::get('cari-siswa/datatables/{nis_nama_siswa}', [CariSiswaController::class, 'datatablesCariSiswa']);
            Route::get('cari-siswa/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', [CariSiswaController::class, 'viewDetailSiswaCariSiswa']);

            Route::post('reset-password', [CariSiswaController::class, 'resetPasswordSiswa']);

            // MENU Evaluasi Siswa
            Route::get('evaluasi-siswa', [EvaluasiSiswaController::class, 'viewEvaluasiSiswa']);
            Route::post('post-view-evaluasi-siswa', [EvaluasiSiswaController::class, 'actionViewEvaluasiSiswa']);
            Route::get('evaluasi-siswa/view-detail/{nis_nama_siswa}', [EvaluasiSiswaController::class, 'viewDetailEvaluasiSiswa']);
            Route::get('evaluasi-siswa/datatables/{nis_nama_siswa}', [EvaluasiSiswaController::class, 'datatablesEvaluasiSiswa']);
            Route::get('evaluasi-siswa/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', [EvaluasiSiswaController::class, 'viewDetailSiswaEvaluasiSiswa']);

            //datatable evaluasi siswa detail
            Route::get('evaluasi-siswa/datatables-beasiswa/{nis_nama_siswa}', [EvaluasiSiswaController::class, 'datatablesBeasiswa']);
            Route::get('evaluasi-siswa/datatables-prestasi/{nis_nama_siswa}', [EvaluasiSiswaController::class, 'datatablesPrestasi']);
            Route::get('evaluasi-siswa/datatables-ekskul/{nis_nama_siswa}', [EvaluasiSiswaController::class, 'datatablesEkskul']);

            //MENU Pembayaran
            Route::get('pembayaran', [PembayaranController::class, 'viewPembayaran']);
            Route::post('post-view-pembayaran', [PembayaranController::class, 'actionViewPembayaran']);
            Route::get('pembayaran/view-detail/{nis_nama_siswa}', [PembayaranController::class, 'viewDetailPembayaran']);
            Route::get('pembayaran/datatables/{nis_nama_siswa}', [PembayaranController::class, 'datatablesPembayaran']);
            Route::get('pembayaran/view-detail-siswa/{nis_siswa}/{nis_nama_siswa_asli}', [PembayaranController::class, 'viewDetailSiswaPembayaran']);
            Route::get('pembayaran/datatables-tagihan/{id_pengguna}/{nis_nama_siswa}', [PembayaranController::class, 'datatablesTagihanPembayaran']);
            Route::get('pembayaran/datatables-riwayat-bayar/{id_pengguna}', [PembayaranController::class, 'datatablesRiwayatBayarSiswa']);

            //MENU SISWA AKTIF
            Route::get('siswa-aktif', [SiswaAktifController::class, 'viewSiswaAktif']);

            //MENU INSERT-UPDATE SISWA
            Route::get('insert-update-siswa', [InsertUpdateSiswaController::class, 'viewInsertUpdateSiswa']);
            Route::post('post-view-update-siswa', [InsertUpdateSiswaController::class, 'actionViewUpdateSiswa']);
            Route::get('insert-update-siswa/view-detail/{nis_nama_siswa}', [InsertUpdateSiswaController::class, 'viewDetailUpdateSiswa']);
            Route::get('insert-update-siswa/view-print-siswa/{nis_nama_siswa}', [DataSiswaControllerRoleSiswa::class, 'viewPrintSiswa']);
            Route::get('insert-update-siswa/view-print-siswa-kelas/{id_kelas}', [InsertUpdateSiswaController::class, 'viewPrintSiswaKelas']);
            Route::get('insert-update-siswa/view-print-siswa-kelas-excel/{id_kelas}', [InsertUpdateSiswaController::class, 'viewExcelSiswaKelas']);
            Route::get('insert-update-siswa/view-cari-siswa/{nis_nama_siswa}', [InsertUpdateSiswaController::class, 'viewCariUpdateSiswa']);
            Route::get('insert-update-siswa/datatables/{nis_nama_siswa}', [InsertUpdateSiswaController::class, 'datatablesCariSiswa']);
            Route::get('insert-update-siswa/changeStatusMasuk', [InsertUpdateSiswaController::class, 'changeStatusSiswa']);

            Route::post('action-insert-update-siswa/{mode}/{id}', [InsertUpdateSiswaController::class, 'actionInsertUpdateSiswa']);

            //MENU Setting Wali Murid
            Route::get('setting-wali-murid', [SettingWaliMuridController::class, 'viewSettingWaliMurid']);
            //batch hapus data walimurid yang salah
            Route::get('setting-wali-murid/view-eror-data', [SettingWaliMuridController::class, 'viewErorData']);
            Route::get('setting-wali-murid/add', [SettingWaliMuridController::class, 'viewSettingWaliMurid']);
            Route::post('post-view-setting-wali-murid', [SettingWaliMuridController::class, 'actionViewSettingWaliMurid']);
            Route::get('setting-wali-murid/view-kelas/{id_jurusan}/{id_kelas}', [SettingWaliMuridController::class, 'viewKelasWaliMurid']);
            Route::get('setting-wali-murid/datatables/{id_jurusan}/{id_kelas}', [SettingWaliMuridController::class, 'datatablesWaliMurid']);
            Route::get('setting-wali-murid/edit/{id}', [SettingWaliMuridController::class, 'editWaliMurid']);
            Route::post('setting-wali-murid/getDataKelas', [SettingWaliMuridController::class, 'getDataKelas']);

            Route::get('setting-wali-murid/upload-setting-wali-murid/{id_jurusan}/{id_kelas}', [SettingWaliMuridController::class, 'viewUploadSettingWaliMurid']);
            Route::get('setting-wali-murid/upload-setting-wali-murid/download/{id_jurusan}/{id_kelas}', [SettingWaliMuridController::class, 'viewDownloadSettingWaliMurid']);
            Route::post('setting-wali-murid/upload/{id_jurusan}/{id_kelas}', [SettingWaliMuridController::class, 'uploadFileExcel']);
            Route::get('/download-file-excel-wali-murid/{id_kelas}', [SettingWaliMuridController::class, 'downloadFileExcel']);

            Route::post('setting-wali-murid/reset-wali-murid-collect', [SettingWaliMuridController::class, 'resetWaliMuridCollect']);
            Route::post('action-setting-wali-murid/{mode}/{id}', [SettingWaliMuridController::class, 'actionSettingWaliMurid']);

            Route::get('wali-murid/get-data', [SettingWaliMuridController::class, 'actionGetWaliMurid']);

            //MENU Setting Kelas Siswa
            Route::get('setting-kelas-siswa', [SettingKelasSiswaController::class, 'viewSettingKelasSiswa']);
            Route::post('post-view-setting-kelas-siswa', [SettingKelasSiswaController::class, 'actionViewSettingKelasSiswa']);
            Route::get('setting-kelas-siswa/view-kelas/{id_kelas}', [SettingKelasSiswaController::class, 'viewKelasSettingKelas']);
            Route::get('setting-kelas-siswa/datatables/{id_kelas}', [SettingKelasSiswaController::class, 'datatablesKelasSiswa']);
            Route::get('setting-kelas-siswa/datatables-siswa', [SettingKelasSiswaController::class, 'datatablesSiswa']);
            Route::get('setting-kelas-siswa/edit/{id}', [SettingKelasSiswaController::class, 'tambahKelasSiswa']);

            Route::post('action-setting-kelas-siswa/{mode}/{id}', [SettingKelasSiswaController::class, 'actionSettingKelasSiswa']);
        });

        Route::prefix('data-kesiswaan')->group(function () {
            //MENU ADMISI SISWA
            Route::get('admisi-siswa', [AdmisiSiswaController::class, 'viewAdmisiSiswa']);
            Route::post('post-view-admisi-siswa', [AdmisiSiswaController::class, 'actionViewAdmisiSiswa']);
            Route::get('admisi-siswa/view-detail/{nis_nama_siswa}', [AdmisiSiswaController::class, 'viewDetailAdmisiSiswa']);
            Route::post('action-admisi-siswa', [AdmisiSiswaController::class, 'actionAdmisiSiswa']);
            Route::get('admisi-siswa/generate', [AdmisiSiswaController::class, 'generateAdmisiSiswa']);
            Route::post('action-generate-admisi-siswa', [AdmisiSiswaController::class, 'actionGenerateAdmisiSiswa']);
            Route::get('admisi-siswa/generate/laporan', [AdmisiSiswaController::class, 'laporanGenerateAdmisiSiswa']);
            Route::post('post-view-laporan-admisi-siswa', [AdmisiSiswaController::class, 'actionViewLaporanAdmisiSiswa']);
            Route::get('admisi-siswa/view-laporan/{id_semester}/{id_kelas}', [AdmisiSiswaController::class, 'viewLaporanAdmisiSiswa']);
            Route::get('admisi-siswa/datatables/{id_semester}/{id_kelas}', [AdmisiSiswaController::class, 'datatablesAdmisiSiswa']);

            //MENU HISTORY ADMISI SISWA
            Route::get('histori-admisi-siswa', [HistoryAdmisiSiswaController::class, 'viewHistoryAdmisiSiswa']);
            Route::post('post-view-histori-admisi-siswa', [HistoryAdmisiSiswaController::class, 'actionViewHistoryAdmisiSiswa']);
            Route::get('histori-admisi-siswa/view-detail/{nis_nama_siswa}', [HistoryAdmisiSiswaController::class, 'viewDetailHistoryAdmisiSiswa']);
            Route::get('histori-admisi-siswa/datatables/{nis_siswa}', [HistoryAdmisiSiswaController::class, 'datatablesHistoryAdmisiSiswa']);
            Route::post('action-histori-admisi-siswa/{mode}/{id}', [HistoryAdmisiSiswaController::class, 'actionHistoryAdmisiSiswa']);

            //MENU Tingkat Prestasi Siswa
            Route::get('tingkat-prestasi-siswa', [TingkatPrestasiSiswaController::class, 'viewTingkatPrestasiSiswa']);
            Route::get('tingkat-prestasi-siswa/datatables', [TingkatPrestasiSiswaController::class, 'datatablesTingkatPrestasiSiswa']);
            Route::get('tingkat-prestasi-siswa/add', [TingkatPrestasiSiswaController::class, 'addTingkatPrestasiSiswa']);
            Route::get('tingkat-prestasi-siswa/edit/{id}', [TingkatPrestasiSiswaController::class, 'editTingkatPrestasiSiswa']);

            Route::post('action-tingkat-prestasi-siswa/{mode}/{id}', [TingkatPrestasiSiswaController::class, 'actionTingkatPrestasiSiswa']);

            //MENU Prestasi Siswa
            Route::get('prestasi-siswa', [PrestasiSiswaController::class, 'viewPrestasiSiswa']);
            Route::get('prestasi-siswa/datatables', [PrestasiSiswaController::class, 'datatablesPrestasiSiswa']);
            Route::get('prestasi-siswa/add', [PrestasiSiswaController::class, 'addPrestasiSiswa']);
            Route::get('prestasi-siswa/edit/{id}', [PrestasiSiswaController::class, 'editPrestasiSiswa']);

            Route::post('action-prestasi-siswa/{mode}/{id}', [PrestasiSiswaController::class, 'actionPrestasiSiswa']);

            //MENU Kegiatan Siswa
            Route::get('kegiatan-siswa', [KegiatanSiswaController::class, 'viewKegiatanSiswa']);
            Route::get('kegiatan-siswa/datatables', [KegiatanSiswaController::class, 'datatablesKegiatanSiswa']);
            Route::get('kegiatan-siswa/add', [KegiatanSiswaController::class, 'addKegiatanSiswa']);
            Route::get('kegiatan-siswa/edit/{id}', [KegiatanSiswaController::class, 'editKegiatanSiswa']);

            Route::post('action-kegiatan-siswa/{mode}/{id}', [KegiatanSiswaController::class, 'actionKegiatanSiswa']);

            //MENU Beasiswa Siswa
            Route::get('beasiswa-siswa', [BeasiswaSiswaController::class, 'viewBeasiswaSiswa']);
            Route::get('beasiswa-siswa/datatables', [BeasiswaSiswaController::class, 'datatablesBeasiswaSiswa']);
            Route::get('beasiswa-siswa/add', [BeasiswaSiswaController::class, 'addBeasiswaSiswa']);
            Route::get('beasiswa-siswa/edit/{id}', [BeasiswaSiswaController::class, 'editBeasiswaSiswa']);

            Route::post('action-beasiswa-siswa/{mode}/{id}', [BeasiswaSiswaController::class, 'actionBeasiswaSiswa']);

            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', [PrestasiSiswaController::class, 'ajaxGetSiswaByKelas']);
            Route::post('siswa-bykelas', [BeasiswaSiswaController::class, 'ajaxGetSiswaByKelas']);

            //MENU Home Visit
            Route::get('home-visit', [HomeVisitController::class, 'viewHomeVisit']);
            Route::get('home-visit/datatables/{id}', [HomeVisitController::class, 'datatablesHomeVisit']);
            Route::get('home-visit/edit/{id}', [HomeVisitController::class, 'editHomeVisit']);

            Route::post('action-home-visit/{mode}/{id}', [HomeVisitController::class, 'actionHomeVisit']);

            //approve skpi
            Route::get('approve-prestasi-siswa', [ApprovePrestasiSiswaController::class, 'viewApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/datatables', [ApprovePrestasiSiswaController::class, 'datatablesApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/{id}/{param}', [ApprovePrestasiSiswaController::class, 'viewDetailPrestasiSiswa']);
            Route::get('approve-prestasi-siswa/print/skpi/{id}', [ApprovePrestasiSiswaController::class, 'printSkpi']);
            Route::get('approve-prestasi-siswa/printkelas/skpi/{id_kelas}', [ApprovePrestasiSiswaController::class, 'printSkpikelas']);

            Route::get('approve-prestasi-siswa/prestasi/datatables/{id}/{param}', [ApprovePrestasiSiswaController::class, 'datatablesPrestasiApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/kegiatan/datatables/{id}/{param}', [ApprovePrestasiSiswaController::class, 'datatablesKegiatanApprovePrestasiSiswa']);
            Route::get('approve-prestasi-siswa/informasi-tambahan/datatables/{id}/{param}', [ApprovePrestasiSiswaController::class, 'datatablesInformasiTambahan']);

            Route::post('approve-prestasi-siswa/{data}/{id}', [ApprovePrestasiSiswaController::class, 'actionApprovePrestasiSiswa']);
            Route::post('reject-prestasi-siswa/{data}/{id}', [ApprovePrestasiSiswaController::class, 'actionRejectPrestasiSiswa']);

            Route::get('edit-prestasi-siswa/{id}', [ApprovePrestasiSiswaController::class, 'editPrestasiSiswa']);
            Route::get('edit-kegiatan-siswa/{id}', [ApprovePrestasiSiswaController::class, 'editKegiatanSiswa']);
            Route::get('edit-informasi-tambahan-siswa/{id}', [ApprovePrestasiSiswaController::class, 'editInformasiTambahanSiswa']);

            Route::post('action-edit-prestasi-siswa/{id}', [ApprovePrestasiSiswaController::class, 'actionEditPrestasiSiswa']);
            Route::post('action-edit-kegiatan-siswa/{id}', [ApprovePrestasiSiswaController::class, 'actionEditKegiatanSiswa']);
            Route::post('action-edit-informasi-tambahan-siswa/{id}', [ApprovePrestasiSiswaController::class, 'actionEditInformasiTambahanSiswa']);
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
            Route::get('pengajuan-wisuda/view-detail/{id_periode_wisuda}/{id_kelas}', [PengajuanWisudaController::class, 'viewDetailPengajuanWisuda']);
            Route::get('pengajuan-wisuda/datatables/{id_periode_wisuda}/{id_kelas}', [PengajuanWisudaController::class, 'datatablesPengajuanWisuda']);
            Route::get('pengajuan-wisuda/cancel/{id}/{id_periode_wisuda}/{id_kelas}/', [PengajuanWisudaController::class, 'cancelPengajuanWisuda']);

            Route::post('action-pengajuan-wisuda/{mode}/{id}/{id_siswa}/{id_periode_wisuda}', [PengajuanWisudaController::class, 'actionPengajuanWisuda']);

            // MENU Entri Data Wisuda
            Route::get('entri-wisuda', [EntriWisudaController::class, 'viewEntriWisuda']);
            Route::post('post-view-entri-wisuda', [EntriWisudaController::class, 'actionViewDetailEntriWisuda']);
            Route::get('entri-wisuda/view-detail/{id_periode_wisuda}/{id_kelas}', [EntriWisudaController::class, 'viewDetailEntriWisuda']);
            Route::get('entri-wisuda/datatables/{id_periode_wisuda}/{id_kelas}', [EntriWisudaController::class, 'datatablesEntriWisuda']);
            Route::get('entri-wisuda/input/{id}/{id_periode_wisuda}/{id_kelas}/', [EntriWisudaController::class, 'inputEntriWisuda']);

            Route::post('action-entri-wisuda/{mode}/{id}/{id_siswa}/{id_periode_wisuda}', [EntriWisudaController::class, 'actionEntriWisuda']);

            //Import nomor ijasah
            Route::get('import_nomor_ijazah', [EntriWisudaController::class, 'viewImportNomorIjasah']);
            Route::post('post-file-excel-nomor-ijasah', [EntriWisudaController::class, 'uploadNomorIjasah']);
            Route::get('/download-file-excel-nomor-ijasah', [EntriWisudaController::class, 'downloadExcel'])->name('wisuda/download-file-excel-nomor-ijasah');

            // MENU Set Lulus Siswa ==== (BELOM SEMUA) ====
            Route::get('set-lulus', [SetLulusController::class, 'viewSetLulus']);
            Route::post('post-view-set-lulus', [SetLulusController::class, 'actionViewDetailSetLulus']);
            Route::get('set-lulus/view-detail/{id_periode_wisuda}/{id_kelas}', [SetLulusController::class, 'viewDetailSetLulus']);
            Route::get('set-lulus/datatables/{id_periode_wisuda}/{id_kelas}', [SetLulusController::class, 'datatablesSetLulus']);

            Route::post('action-set-lulus/{mode}/{id}', [SetLulusController::class, 'actionSetLulus']);

            Route::prefix('laporan-wisuda')->group(function () {
                Route::get('/', [LaporanWisudaController::class, 'viewLaporanWisuda']);
                Route::get('print-laporan-wisuda/{id_periode}', [LaporanWisudaController::class, 'printLaporanWisuda']);
            });
        });

        Route::prefix('pendaftaran')->group(function () {
            // MENU Data Penerimaan (ambil dari Role PPDB)
            Route::get('penerimaan', [PenerimaanController::class, 'viewPenerimaan']);
            Route::get('penerimaan/datatables', [PenerimaanController::class, 'datatablesPenerimaan']);
            Route::get('penerimaan/add', [PenerimaanController::class, 'addPenerimaan']);
            Route::get('penerimaan/edit/{id}', [PenerimaanController::class, 'editPenerimaan']);

            Route::post('action-penerimaan/{mode}/{id}', [PenerimaanController::class, 'actionPenerimaan']);
        });
        Route::prefix('ijazah')->group(function () {
            // MENU Data Pengambilan Ijazah
            Route::get('pengambilan-ijazah', [PengambilanIjazahController::class, 'viewPengambilanIjazah']);
            Route::get('pengambilan-ijazah/datatables', [PengambilanIjazahController::class, 'datatablesPengambilanIjazah']);
            Route::get('pengambilan-ijazah/datatables-siswa', [PengambilanIjazahController::class, 'datatablesPengambilanIjazahSiswa']);
            Route::get('pengambilan-ijazah/add', [PengambilanIjazahController::class, 'addPengambilanIjazah']);
            Route::get('pengambilan-ijazah/edit/{id}', [PengambilanIjazahController::class, 'editPengambilanIjazah']);
            Route::get('pengambilan-ijazah/print/{id}', [PengambilanIjazahController::class, 'printPengambilanIjazah']);

            Route::post('action-pengambilan-ijazah/{mode}/{id}', [PengambilanIjazahController::class, 'actionPengambilanIjazah']);
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
