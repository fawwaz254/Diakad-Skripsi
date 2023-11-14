<?php

/*
|--------------------------------------------------------------------------
| ROLE PPDB Web Routes
|--------------------------------------------------------------------------
| Here ppdb route
|
*/

use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\PPDB\Pendaftaran\DataInformasiController;
use App\Http\Controllers\PPDB\Pendaftaran\PembukaanVoucherController;
use App\Http\Controllers\PPDB\Pendaftaran\PenawaranJurusanController;
use App\Http\Controllers\PPDB\Pendaftaran\PenerimaanController;
use App\Http\Controllers\PPDB\Pendaftaran\PetugasPenerimaanController;
use App\Http\Controllers\PPDB\Pendaftaran\SyaratPenerimaanController;
use App\Http\Controllers\PPDB\Penetapan\PenetapanController;
use App\Http\Controllers\PPDB\Penetapan\PersidanganController;
use App\Http\Controllers\PPDB\Peserta\PembayaranFormulirController;
use App\Http\Controllers\PPDB\Peserta\PindahPenerimaanController;
use App\Http\Controllers\PPDB\Peserta\ProsesPenetapanController;
use App\Http\Controllers\PPDB\Report\ReportPendaftaranController;
use App\Http\Controllers\PPDB\WelcomeController;

Route::middleware(['token_staff'])->group(function () {
	Route::prefix('ppdb')->group(function () {
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

		/** ==== MODUL PENDAFTARAN ==== **/
		Route::prefix('pendaftaran')->group(function () {
			// MENU Data Penerimaan
			Route::get('penerimaan', [PenerimaanController::class, 'viewPenerimaan']);
			Route::get('penerimaan/datatables', [PenerimaanController::class, 'datatablesPenerimaan']);
			Route::get('penerimaan/add', [PenerimaanController::class, 'addPenerimaan']);
			Route::get('penerimaan/edit/{id}', [PenerimaanController::class, 'editPenerimaan']);
			Route::post('action-penerimaan/{mode}/{id}', [PenerimaanController::class, 'actionPenerimaan']);

			// MENU Data Penawaran Jurusan			
			Route::get('penawaran-jurusan', [PenawaranJurusanController::class, 'viewPenawaranJurusan']);
			Route::post('penawaran-jurusan/post-view-penawaran-jurusan', [PenawaranJurusanController::class, 'actionViewPenawaranJurusan']);
			Route::get('penawaran-jurusan/edit-penawaran-jurusan/{id}', [PenawaranJurusanController::class, 'editPenawaranJurusan']);
			Route::get('penawaran-jurusan/edit-penawaran-jurusan/{id}/add', [PenawaranJurusanController::class, 'addPenawaranJurusan']);
			Route::post('penawaran-jurusan/edit-penawaran-jurusan/{id}/add', [PenawaranJurusanController::class, 'actionAddPenawaranJurusan']);
			Route::post('penawaran-jurusan/edit-penawaran-jurusan/delete/{id_penerimaan_jurusan}', [PenawaranJurusanController::class, 'actionDeletePenawaranJurusan']);
			Route::post('penawaran-jurusan/edit-penawaran-jurusan/activate/{id_penerimaan_jurusan}', [PenawaranJurusanController::class, 'actionActivatePenawaranJurusan']);

			// MENU syarat penerimaan
			Route::get('syarat-penerimaan', [SyaratPenerimaanController::class, 'viewSyaratPenerimaan']);
			Route::post('syarat-penerimaan/post-view-syarat-penerimaan', [SyaratPenerimaanController::class, 'actionViewSyaratPenerimaan']);
			Route::get('syarat-penerimaan/{id}', [SyaratPenerimaanController::class, 'syaratPenerimaan']);
			Route::get('syarat-penerimaan/{id}/add', [SyaratPenerimaanController::class, 'addSyaratPenerimaan']);
			Route::post('syarat-penerimaan/{id}/add', [SyaratPenerimaanController::class, 'actionAddSyaratPenerimaan']);
			Route::get('syarat-penerimaan/{id_penerimaan}/edit/{id_syarat_penerimaan}', [SyaratPenerimaanController::class, 'editSyaratPenerimaan']);
			Route::post('syarat-penerimaan/{id_penerimaan}/edit/{id_syarat_penerimaan}', [SyaratPenerimaanController::class, 'actionEditSyaratPenerimaan']);
			Route::post('syarat-penerimaan/{id_penerimaan}/delete/{id_syarat_penerimaan}', [SyaratPenerimaanController::class, 'actionDeleteSyaratPenerimaan']);

			// MENU Pembukaan voucher
			Route::get('pembukaan-voucher', [PembukaanVoucherController::class, 'viewPembukaanVoucher']);
			Route::post('pembukaan-voucher/post-view-pembukaan-voucher', [PembukaanVoucherController::class, 'actionViewPembuatanVoucher']);
			Route::get('pembukaan-voucher/{id_penerimaan}', [PembukaanVoucherController::class, 'pembukaanVoucher']);
			Route::get('pembukaan-voucher/{id_penerimaan}/add', [PembukaanVoucherController::class, 'addPembukaanVoucher']);
			Route::post('pembukaan-voucher/{id_penerimaan}/add', [PembukaanVoucherController::class, 'actionAddPembukaanVoucher']);
			Route::post('pembukaan-voucher/{id_penerimaan}/delete/{id_voucher_tarif}', [PembukaanVoucherController::class, 'actionDeleteVoucherTarif']);

			// MENU Pembukaan voucher generate voucher
			Route::get('pembukaan-voucher/{id_penerimaan}/generate-voucher', [PembukaanVoucherController::class, 'generateVoucher']);
			Route::post('pembukaan-voucher/{id_penerimaan}/generate-voucher', [PembukaanVoucherController::class, 'actionGenerateVoucher']);
			Route::post('pembukaan-voucher/{id_penerimaan}/delete-voucher/{id_voucher}', [PembukaanVoucherController::class, 'actionDeleteVoucher']);

			// MENU petugas penerimaan
			Route::get('petugas-penerimaan', [PetugasPenerimaanController::class, 'viewPetugasPenerimaan']);
			Route::post('petugas-penerimaan/post-view-petugas-penerimaan', [PetugasPenerimaanController::class, 'actionViewPetugasPenerimaan']);
			Route::get('petugas-penerimaan/{id_penerimaan}', [PetugasPenerimaanController::class, 'petugasPenerimaan']);
			Route::get('petugas-penerimaan/{id_penerimaan}/add', [PetugasPenerimaanController::class, 'addPetugasPenerimaan']);
			Route::post('petugas-penerimaan/{id_penerimaan}/add', [PetugasPenerimaanController::class, 'actionAddPetugasPenerimaan']);
			Route::post('petugas-penerimaan/{id_penerimaan}/delete/{id_penerimaan_petugas}', [PetugasPenerimaanController::class, 'actionDeletePetugasPenerimaan']);


			// MENU data informasi
			Route::get('data-informasi', [DataInformasiController::class, 'dataInformasi']);
			Route::post('data-informasi', [DataInformasiController::class, 'actionPostDataInformasi']);
		});

		Route::prefix('peserta')->group(function () {
			// MENU Pembayaran Formulir
			Route::get('pembayaran-formulir', [PembayaranFormulirController::class, 'viewPembayaranFormulir']);
			Route::post('pembayaran-formulir', [PembayaranFormulirController::class, 'findVoucher']);
			Route::post('pembayaran-formulir/reset-voucher/{kode_voucher}', [PembayaranFormulirController::class, 'deletePembayaranFormulir']);
			Route::get('pembayaran-formulir/{id_voucher}', [PembayaranFormulirController::class, 'showVoucher']);
			Route::post('pembayaran-formulir/{id_voucher}/bayar-voucher', [PembayaranFormulirController::class, 'bayarVoucher']);

			// MENU proses penetapan
			Route::get('proses-penetapan', [ProsesPenetapanController::class, 'viewProsesPenetapan']);
			Route::post('proses-penetapan/post-view-proses-penetapan', [ProsesPenetapanController::class, 'actionViewProsesPenetapan']);
			Route::get('proses-penetapan/{id_penerimaan}', [ProsesPenetapanController::class, 'showPeserta']);
			Route::get('proses-penetapan/datatables/{id_penerimaan}', [ProsesPenetapanController::class, 'datatablesProsesPenetapan']);
			Route::post('proses-penetapan/penetapan', [ProsesPenetapanController::class, 'actionPenetapan']);
			Route::get('proses-penetapan/excel/{id_penerimaan}', [ProsesPenetapanController::class, 'excelPenetapan']);
			Route::get('proses-penetapan/upload/{id_penerimaan}', [ProsesPenetapanController::class, 'uploadPenetapan']);
			Route::post('proses-penetapan/post-file-excel', [ProsesPenetapanController::class, 'postUploadPenetapan']);



			// MENU pindah penerimaan
			Route::get('pindah-penerimaan', [PindahPenerimaanController::class, 'viewPindahPenerimaan']);
			Route::post('pindah-penerimaan', [PindahPenerimaanController::class, 'findVoucher']);
			Route::get('pindah-penerimaan/{id_voucher}', [PindahPenerimaanController::class, 'showVoucher']);
			Route::post('pindah-penerimaan/{id_voucher}/pindah', [PindahPenerimaanController::class, 'actionPindahVoucher']);
		});

		Route::prefix('report')->group(function () {
			// MENU Report pendaftaran
			Route::get('report-pendaftaran', [ReportPendaftaranController::class, 'viewReportPendaftaran']);
			Route::get('report-pendaftaran/datatables', [ReportPendaftaranController::class, 'datatablesReportPendaftaran']);

			Route::get('report-pendaftaran/rekap/{id}', [ReportPendaftaranController::class, 'rekapReportPendaftaran']);

			Route::get('report-pendaftaran/detail/{id}', [ReportPendaftaranController::class, 'detailReportPendaftaran']);
			Route::get('report-pendaftaran/detail/datatables/{id_penerimaan}/{id_jurusan}', [ReportPendaftaranController::class, 'datatablesDetailReportPendaftaran']);
		});

		Route::prefix('penetapan')->group(function () {
			// MENU penetapan
			Route::get('data-penetapan', [PenetapanController::class, 'viewPenetapan']);
			Route::get('data-penetapan/datatables', [PenetapanController::class, 'datatablesPenetapan']);
			Route::get('data-penetapan/add', [PenetapanController::class, 'addPenetapan']);
			Route::get('data-penetapan/edit/{id}', [PenetapanController::class, 'editPenetapan']);
			Route::post('action-penetapan/{mode}/{id}', [PenetapanController::class, 'actionPenetapan']);
			Route::get('data-penetapan/view-penetapan-penerimaan/{id}', [PenetapanController::class, 'viewPenetapanPenerimaan']);
			Route::get('data-penetapan/datatables-penetapan-penerimaan/{id}', [PenetapanController::class, 'datatablesPenetapanPenerimaan']);
			Route::get('data-penetapan/add-penetapan-penerimaan/{id}', [PenetapanController::class, 'addPenetapanPenerimaan']);
			Route::get('data-penetapan/edit-penetapan-penerimaan/{id}', [PenetapanController::class, 'editPenetapanPenerimaan']);
			Route::post('action-penetapan-penerimaan/{mode}/{id}', [PenetapanController::class, 'actionPenetapanPenerimaan']);
			Route::get('data-penetapan/excel/{id}', [PenetapanController::class, 'excelPenetapan']);


			// MENU PERSIDANGAN
			Route::get('persidangan', [PersidanganController::class, 'viewPersidangan']);
			Route::post('persidangan/post-view-persidangan', [PersidanganController::class, 'actionViewPersidangan']);
			Route::get('persidangan/tahun/{id}', [PersidanganController::class, 'editPersidangan2']);
			Route::get('persidangan/datatables/{tahun}', [PersidanganController::class, 'datatablesPersidangan']);
			Route::get('persidangan/edit/{id}', [PersidanganController::class, 'editPersidangan']);
			Route::get('persidangan/view-persidangan-gelombang/{id}', [PersidanganController::class, 'viewPersidanganGelombang']);
			Route::get('persidangan/datatablesviewgelombang/{id}', [PersidanganController::class, 'datatablesPersidanganViewGelombang']);
		});
	});
});
