<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tendik\WelcomeController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\Guru\Laporan\KerjaHarianController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\Tendik\Biodata\DataPribadiController;
use App\Http\Controllers\Guru\Absensi\HistoriAbsensiController;
use App\Http\Controllers\Tendik\FormTendik\FormHarianController;
use App\Http\Controllers\Guru\Kesekretariatan\DokumenController;
use App\Http\Controllers\Guru\GuruPiket\RekapKesehatanController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Guru\GuruPiket\InputPelanggaranController;
use App\Http\Controllers\Guru\GuruPiket\AbsensiHarianSiswaController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;
use App\Http\Controllers\Guru\GuruPiket\MonitoringKelasKosongController;
use App\Http\Controllers\Guru\GuruPiket\RekapAbsenTanpaJadwalController;
use App\Http\Controllers\Tendik\JurnalHarian\JurnalHarianTendikController;
use App\Http\Controllers\Humas\MagangSiswa\LaporanMagangController;
use App\Http\Controllers\Humas\MagangSiswa\PeriodeMagangController;
use App\Http\Controllers\Humas\MagangSiswa\RekananMagangController;use App\Http\Controllers\Humas\MagangSiswa\PembimbingMagangController;
use App\Http\Controllers\Humas\MagangSiswa\RekapAbsensiMagangController;
use App\Http\Controllers\Humas\MagangSiswa\InputNilaiMagangController;
use App\Http\Controllers\Humas\MagangSiswa\ApproveSiswaMagangController;
use App\Http\Controllers\Humas\MagangSiswa\KomponenNilaiMagangController;
use App\Http\Controllers\Humas\MagangSiswa\PengajuanSiswaMagangController;
use App\Http\Controllers\Humas\MagangSiswa\PengajuanMagangController;
use App\Http\Controllers\Humas\MagangSiswa\MagangSiswaController;
use App\Http\Controllers\Humas\KunjunganMagangController;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('tendik')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);
        Route::get('biodata', [\App\Http\Controllers\Administrator\WelcomeController::class, 'viewBiodata']);

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

        Route::prefix('biodata')->group(function () {
            Route::get('data-pribadi', [DataPribadiController::class, 'viewDataPribadi']);
            Route::post('action-input-tendik/{mode}/{id}', [DataPribadiController::class, 'actionInputTendik']);
        });

        /** ==== Jurnal Harian ==== **/
        Route::prefix('jurnal-harian')->group(function () {
            Route::prefix('laporan-individu-jurnal-harian')->group(function () {
                Route::get('/', [JurnalHarianTendikController::class, 'viewLaporanJurnalHarian']);
                Route::get('add', [JurnalHarianTendikController::class, 'addLaporanHarianJurnalHarian']);
                Route::post('action-kerja-harian/{mode}/{id}', [JurnalHarianTendikController::class, 'actionLaporanHarianTendik']);
                Route::get('datatables', [JurnalHarianTendikController::class, 'datatablesKerjaHarianTendik']);
                Route::get('edit/{id}', [JurnalHarianTendikController::class, 'editKerjaHarian']);
                Route::get('preview-file/{id}/{no}', [JurnalHarianTendikController::class, 'previewFile']);
                Route::get('download-file/{id}', [JurnalHarianTendikController::class, 'downloadFile']);
            });
            Route::prefix('laporan-kelompok-jurnal-harian')->group(function () {
                Route::get('/', [JurnalHarianTendikController::class, 'viewLaporanKelompokKerjaHarian']);
                Route::get('datatables', [JurnalHarianTendikController::class, 'datatablesKerjaHarianKelompokTendik']);
                Route::get('/detail/{id}', [JurnalHarianTendikController::class, 'detailLaporanKelompokTendik']);
                Route::get('/detail/datatables/{id}', [JurnalHarianTendikController::class, 'datatablesDetailKerjaHarianKelompokTendik']);
                Route::get('preview-file/{id}/{no}', [JurnalHarianTendikController::class, 'previewFile']);
                Route::get('download-file/{id}', [JurnalHarianTendikController::class, 'downloadFile']);
            });
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

            Route::post('absensi-harian-siswa/datatables/{id_semester}/{id_kelas}', [AbsensiHarianSiswaController::class, 'datatablesAbsensiHarianSiswa']);
            Route::post('absensi-harian-siswa/datatables-detail/{id_semester}/{id_kelas}/{id_presensi_harian}', [AbsensiHarianSiswaController::class, 'datatablesKelasAbsensiHariSiswa']);
            Route::post('absensi-harian-siswa/action/{mode}', [AbsensiHarianSiswaController::class, 'actionAbsensiHarianSiswa']);
            Route::post('absensi-harian-siswa/action/{mode}/{id}', [AbsensiHarianSiswaController::class, 'actionAbsensiHarianSiswa']);

            // MENU Input Pelanggaran Siswa Non-KBM
            Route::get('input-pelanggaran', [InputPelanggaranController::class, 'viewInputPelanggaran']);
            Route::get('input-pelanggaran/datatables', [InputPelanggaranController::class, 'datatablesInputPelanggaran']);
            Route::get('input-pelanggaran/add', [InputPelanggaranController::class, 'addInputPelanggaran']);
            Route::get('input-pelanggaran/edit/{id}', [InputPelanggaranController::class, 'editInputPelanggaran']);

            Route::post('action-input-pelanggaran/{mode}/{id}', [InputPelanggaranController::class, 'actionInputPelanggaran']);

            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', [InputPelanggaranController::class, 'ajaxGetSiswaByKelas']);

            // MENU Rekap Kesehatan Siswa
            Route::get('rekap-kesehatan', [RekapKesehatanController::class, 'viewRekapKesehatan']);
            Route::get('rekap-kesehatan/user/{id}/{date}', [RekapKesehatanController::class, 'viewRekapKesehatanSiswa']);
            Route::get('rekap-kesehatan/detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

            Route::get('rekap-kesehatan/{id}', [RekapKesehatanController::class, 'viewDetailRekapKesehatan']);
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}', [RekapKesehatanController::class, 'viewDetailRekapKesehatan']);
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}/download', [RekapKesehatanController::class, 'downloadDetailRekapKesehatan']);

            Route::post('rekap-kesehatan/action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
            Route::post('rekap-kesehatan/datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);

            // MENU Rekap Absen Tanpa Jadwal
            Route::get('rekap-absen-tanpa-jadwal', [RekapAbsenTanpaJadwalController::class, 'viewRekapAbsenTanpaJadwal']);
            Route::post('post-get-kbm-by-kelas', [RekapAbsenTanpaJadwalController::class, 'actionGetKBMByKelas']);

            Route::post('post-kbm-rekap-absen-tanpa-jadwal', [RekapAbsenTanpaJadwalController::class, 'actionViewKBMRekapAbsenTanpaJadwal']);
            Route::get('rekap-absen-tanpa-jadwal/view-kbm/{id_kelas_mp}', [RekapAbsenTanpaJadwalController::class, 'viewKBMRekapAbsenTanpaJadwal']);

            Route::get('rekap-absen-tanpa-jadwal/print/{id_kelas_mp}', [RekapAbsenTanpaJadwalController::class, 'printKBMRekapAbsenTanpaJadwal']);
        });

        Route::prefix('kegiatan-harian')->group(function () {

            Route::prefix('mengisi-form-kesehatan')->group(function () {
                // MENU Mengisi form kesehatan
                Route::get('/', [FormKesehatanController::class, 'viewFormKesehatan']);
                Route::get('add', [FormKesehatanController::class, 'viewAddFormKesehatan']);
                Route::get('detail/form/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

                Route::post('action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
                Route::post('datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
            });
        });

        Route::prefix('laporan')->group(function () {

            Route::prefix('kerja-harian')->group(function () {
                Route::get('/', [KerjaHarianController::class, 'viewKerjaHarian']);
                Route::get('datatables', [KerjaHarianController::class, 'datatablesKerjaHarian']);
                Route::get('add', [KerjaHarianController::class, 'addKerjaHarian']);
                Route::get('edit/{id}', [KerjaHarianController::class, 'editKerjaHarian']);
                Route::get('preview-file/{id}', [KerjaHarianController::class, 'previewFile']);
                Route::get('print-kerja-harian/{start_date}/{end_date}', [KerjaHarianController::class, 'printKerjaHarian']);
                Route::post('action-kerja-harian/{mode}/{id}', [KerjaHarianController::class, 'actionKerjaHarian']);
            });
        });

        Route::prefix('absensi')->group(function () {

            Route::prefix('histori-absensi')->group(function () {
                Route::get('/', [HistoriAbsensiController::class, 'viewHistoriAbsensi']);
                Route::get('/{start_date}/{end_date}', [HistoriAbsensiController::class, 'viewHistoriAbsensi']);
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

        Route::prefix('form-tendik')->group(function () {
            Route::prefix('input-form-harian')->group(function () {
                Route::get('/', [FormHarianController::class, 'viewInputFormHarian']);
                Route::get('/datatables', [FormHarianController::class, 'datatablesInputFormHarian']);
                Route::get('/add/{id_form}', [FormHarianController::class, 'addInputFormHarian']);
                Route::get('/edit/{id_jawaban}', [FormHarianController::class, 'editSubmittedForm']);
                Route::get('/all/{id_form}', [FormHarianController::class, 'viewAllSubmittedForm']);
                Route::get('/all/datatables/{id_form}', [FormHarianController::class, 'datatablesJawaban']);
                Route::post('action-list-form/{mode}/{id}', [FormHarianController::class, 'actionInputFormHarian']);
            });
        });

        Route::prefix('magang-siswa')->group(function () {
            // Menu nama magang
            Route::get('nama-magang', [MagangSiswaController::class, 'viewMagangSiswa']);
            Route::get('nama-magang/datatables', [MagangSiswaController::class, 'datatablesMagangSiswa']);
            Route::get('nama-magang/add', [MagangSiswaController::class, 'addMagangSiswa']);
            Route::get('nama-magang/edit/{id}', [MagangSiswaController::class, 'editMagangSiswa']);

            Route::post('action-nama-magang/{mode}/{id}', [MagangSiswaController::class, 'actionMagang']);

            // Menu Laporan Magang
            Route::get('laporan-magang', [LaporanMagangController::class, 'viewLaporanMagang']);
            Route::get('laporan-magang/datatables', [LaporanMagangController::class, 'datatablesLaporanMagang']);
            Route::get('laporan-magang/print/{id_rekanan_magang}/{id_periode_magang}', [LaporanMagangController::class, 'printLaporanMagang']);
            Route::get('laporan-magang/input/{id_rekanan_magang}/{id_periode_magang}', [LaporanMagangController::class, 'viewInputLaporanMagang']);
            Route::get('laporan-magang/edit/{id_rekanan_magang}/{id_periode_magang}', [LaporanMagangController::class, 'editInputLaporanMagang']);
            Route::get('laporan-magang/open/{id_rekanan_magang}/{id_periode_magang}', [LaporanMagangController::class, 'openLink']);
            Route::get('laporan-magang/delete/{id_rekanan_magang}/{id_periode_magang}', [LaporanMagangController::class, 'actionDeleteLaporanLinkMagang']);

            Route::post('input/action-laporan-input-siswa/{mode}/{id}', [LaporanMagangController::class, 'actionInputLaporanMagang']);

            //MENU Periode Magang
            Route::get('periode-magang', [PeriodeMagangController::class, 'viewPeriodeMagang']);
            Route::get('periode-magang/datatables', [PeriodeMagangController::class, 'datatablesPeriodeMagang']);
            Route::get('periode-magang/add', [PeriodeMagangController::class, 'addPeriodeMagang']);
            Route::get('periode-magang/edit/{id}', [PeriodeMagangController::class, 'editPeriodeMagang']);

            Route::post('action-periode-magang/{mode}/{id}', [PeriodeMagangController::class, 'actionPeriodeMagang']);

            //MENU Rekanan Magang
            Route::get('rekanan-magang', [RekananMagangController::class, 'viewRekananMagang']);
            Route::get('rekanan-magang/datatables', [RekananMagangController::class, 'datatablesRekananMagang']);
            Route::get('rekanan-magang/add', [RekananMagangController::class, 'addRekananMagang']);
            Route::get('rekanan-magang/edit/{id}', [RekananMagangController::class, 'editRekananMagang']);
            Route::get('rekanan-magang/import-excel', [RekananMagangController::class, 'importExcel']);
            Route::post('rekanan-magang/import-excel', [RekananMagangController::class, 'importExcelAction']);

            Route::post('action-rekanan-magang/{mode}/{id}', [RekananMagangController::class, 'actionRekananMagang']);

            // Menu Pengajuan Magang
            Route::get('pengajuan-magang', [PengajuanMagangController::class, 'viewPengajuanMagang']);
            Route::get('pengajuan-magang/import-excel', [PengajuanMagangController::class, 'importExcel']);
            Route::post('pengajuan-magang/import-excel', [PengajuanMagangController::class, 'importExcelAction']);
            Route::get('pengajuan-magang/datatables', [PengajuanMagangController::class, 'datatablesPengajuanMagang']);
            Route::get('pengajuan-magang/add/{id_rekanan_magang}/{id_periode_magang}', [PengajuanMagangController::class, 'addPengajuanMagang']);
            Route::get('pengajuan-magang/datatables-list-siswa/{id_rekanan_magang}/{id_periode_magang}', [PengajuanMagangController::class, 'datatablesListSiswa']);
            Route::post('pengajuan-magang/action-pengajuan-magang', [PengajuanMagangController::class, 'actionPengajuanMagang']);

            //MENU Pengajuan Siswa Magang
            Route::get('pengajuan-siswa-magang', [PengajuanSiswaMagangController::class, 'viewPengajuanSiswaMagang']);
            Route::post('post-view-pengajuan-magang', [PengajuanSiswaMagangController::class, 'actionViewDetailPengajuanMagang']);
            Route::get('pengajuan-siswa-magang/view-detail/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', [PengajuanSiswaMagangController::class, 'viewDetailPengajuanMagang']);
            Route::get('pengajuan-siswa-magang/datatables/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', [PengajuanSiswaMagangController::class, 'datatablesPengajuanMagang']);
            Route::get('pengajuan-siswa-magang/cancel/{id}', [PengajuanSiswaMagangController::class, 'cancelPengajuanMagang']);

            Route::post('action-pengajuan-siswa-magang/{mode}/{id}/{id_siswa}/{id_periode_magang}/{id_rekanan_magang}', [PengajuanSiswaMagangController::class, 'actionPengajuanMagang']);

            //MENU Approve Siswa Magang
            Route::get('approve-siswa-magang', [ApproveSiswaMagangController::class, 'viewApproveSiswaMagang']);
            Route::post('post-view-approve-siswa-magang', [ApproveSiswaMagangController::class, 'actionViewDetailApproveSiswaMagang']);
            Route::get('approve-siswa-magang/view-detail/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', [ApproveSiswaMagangController::class, 'viewDetailApproveSiswaMagang']);
            Route::get('approve-siswa-magang/datatables/{id_periode_magang}/{id_rekanan_magang}/{nis_nama_siswa}', [ApproveSiswaMagangController::class, 'datatablesApproveSiswaMagang']);

            Route::post('action-approve-siswa-magang/{mode}/{id}/{id_siswa}/{id_periode_magang/{id_rekanan_magang}', [ApproveSiswaMagangController::class, 'actionApproveSiswaMagang']);

            //MENU Komponen Nilai Magang
            Route::get('komponen-nilai-magang', [KomponenNilaiMagangController::class, 'viewKomponenNilaiMagang']);
            Route::post('post-view-komponen-nilai-magang', [KomponenNilaiMagangController::class, 'actionViewKelasKomponenNilaiMagang']);
            Route::get('komponen-nilai-magang/view-periode/{id_periode_magang}', [KomponenNilaiMagangController::class, 'viewKelasKomponenNilaiMagang']);
            Route::get('komponen-nilai-magang/datatables/{id_periode_magang}', [KomponenNilaiMagangController::class, 'datatablesKomponenNilaiMagang']);
            Route::get('komponen-nilai-magang/add/{id_periode_magang}', [KomponenNilaiMagangController::class, 'addKomponenNilai']);
            Route::get('komponen-nilai-magang/edit/{id_periode_magang}/{id}', [KomponenNilaiMagangController::class, 'editKomponenNilai']);

            Route::post('action-komponen-nilai-magang/{mode}/{id}', [KomponenNilaiMagangController::class, 'actionKomponenNilaiMagang']);

            //MENU Input Nilai
            Route::get('input-nilai-magang', [InputNilaiMagangController::class, 'viewPeriodeMagang']);
            Route::post('post-view-input-nilai-magang', [InputNilaiMagangController::class, 'actionViewKomponenInputNilaiMagang']);
            Route::get('input-nilai-magang/view-komponen/{id_periode_magang}', [InputNilaiMagangController::class, 'viewKomponenInputNilaiMagang']);
            Route::get('input-nilai-magang/datatables/{id_periode_magang}', [InputNilaiMagangController::class, 'datatablesKomponenNilaiMagang']);

            Route::post('action-input-nilai-magang/{mode}/{id}', [InputNilaiMagangController::class, 'actionInputNilaiMagang']);

            //Pembimbing Magang
            Route::get('pembimbing-magang', [PembimbingMagangController::class, 'viewPembimbingMagang']);
            Route::get('pembimbing-magang/datatables', [PembimbingMagangController::class, 'datatablesPembimbingMagang']);
            Route::get('pembimbing-magang/add/{id_pengambil_magang}', [PembimbingMagangController::class, 'addPembimbingMagang']);
            Route::get('pembimbing-magang/edit/{id_pembimbing_magang}', [PembimbingMagangController::class, 'editPembimbingMagang']);
            Route::post('action-input-pembimbing-magang/{mode}/{id}', [PembimbingMagangController::class, 'actionInputPembimbingMagang']);

            //rekap absensi magang
            Route::get('rekap-absensi-magang', [RekapAbsensiMagangController::class, 'viewRekapAbsensiMagang']);
            Route::get('rekap-absensi-magang/detail/{id_rekanan}/{id_periode}/{date}', [RekapAbsensiMagangController::class, 'viewDetailRekapAbsensiMagang']);
            Route::get('rekap-absensi-magang/print/{id_siswa}', [RekapAbsensiMagangController::class, 'printRekapPresensiMagang']);

            // Kunjungan magang
            Route::get('kunjungan-magang', [KunjunganMagangController::class, 'index'])->name('humas.kunjungan-magang');
            Route::get('kunjungan-magang/datatables', [KunjunganMagangController::class, 'dataKunjunganMagang'])->name('humas.dataKunjunganMagang');
            Route::delete('kunjungan-magang/{id}', [KunjunganMagangController::class, 'destroy'])->name('destroy.kunjungan-magang');


        });
    });
});
