<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Humas\MagangSiswa\MagangSiswaController;
use App\Http\Controllers\Humas\MagangSiswa\LaporanMagangController;
use App\Http\Controllers\Humas\MagangSiswa\PeriodeMagangController;
use App\Http\Controllers\Humas\MagangSiswa\RekananMagangController;
use App\Http\Controllers\Humas\MagangSiswa\PengajuanMagangController;
use App\Http\Controllers\Humas\MagangSiswa\ApproveSiswaMagangController;
use App\Http\Controllers\Humas\MagangSiswa\KomponenNilaiMagangController;
use App\Http\Controllers\Humas\MagangSiswa\PengajuanSiswaMagangController;
use App\Http\Controllers\Humas\MagangSiswa\InputNilaiMagangController;
use App\Http\Controllers\Humas\MagangSiswa\PembimbingMagangController;
use App\Http\Controllers\Humas\MagangSiswa\RekapAbsensiMagangController;
use App\Http\Controllers\Humas\KunjunganMagangController;

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
            Route::delete('kunjungan-magang/{id}', [KunjunganMagangController::class, 'destroy'])->name('humas.destroy.kunjungan-magang');


        });