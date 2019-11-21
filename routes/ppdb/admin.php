<?php

/*
|--------------------------------------------------------------------------
| ROLE PPDB Web Routes
|--------------------------------------------------------------------------
| Here ppdb route
|
*/

Route::group(array('middleware'=> ['token_staff']), function() {

  Route::group(array('prefix' => 'ppdb'), function() {
		Route::get('welcome', 'PPDB\WelcomeController@indexWelcome');
		
		/** ==== MODUL PENDAFTARAN ==== **/
		Route::group(array('prefix' => 'pendaftaran'), function() {
			
			// MENU Data Penerimaan
			Route::get('penerimaan', 'PPDB\Pendaftaran\PenerimaanController@viewPenerimaan');
			Route::get('penerimaan/datatables', 'PPDB\Pendaftaran\PenerimaanController@datatablesPenerimaan');
			Route::get('penerimaan/add', 'PPDB\Pendaftaran\PenerimaanController@addPenerimaan');
			Route::get('penerimaan/edit/{id}', 'PPDB\Pendaftaran\PenerimaanController@editPenerimaan');
			Route::post('action-penerimaan/{mode}/{id}', 'PPDB\Pendaftaran\PenerimaanController@actionPenerimaan');
			
			// MENU Data Penawaran Jurusan			
			Route::get('penawaran-jurusan', 'PPDB\Pendaftaran\PenawaranJurusanController@viewPenawaranJurusan');
			Route::post('penawaran-jurusan/post-view-penawaran-jurusan', 'PPDB\Pendaftaran\PenawaranJurusanController@actionViewPenawaranJurusan');
			Route::get('penawaran-jurusan/edit-penawaran-jurusan/{id}', 'PPDB\Pendaftaran\PenawaranJurusanController@editPenawaranJurusan');
			Route::get('penawaran-jurusan/edit-penawaran-jurusan/{id}/add', 'PPDB\Pendaftaran\PenawaranJurusanController@addPenawaranJurusan');
			Route::post('penawaran-jurusan/edit-penawaran-jurusan/{id}/add', 'PPDB\Pendaftaran\PenawaranJurusanController@actionAddPenawaranJurusan');
			Route::post('penawaran-jurusan/edit-penawaran-jurusan/delete/{id_penerimaan_jurusan}', 'PPDB\Pendaftaran\PenawaranJurusanController@actionDeletePenawaranJurusan');
			Route::post('penawaran-jurusan/edit-penawaran-jurusan/activate/{id_penerimaan_jurusan}', 'PPDB\Pendaftaran\PenawaranJurusanController@actionActivatePenawaranJurusan');

			// MENU syarat penerimaan
			Route::get('syarat-penerimaan', 'PPDB\Pendaftaran\SyaratPenerimaanController@viewSyaratPenerimaan');
			Route::post('syarat-penerimaan/post-view-syarat-penerimaan', 'PPDB\Pendaftaran\SyaratPenerimaanController@actionViewSyaratPenerimaan');
			Route::get('syarat-penerimaan/{id}', 'PPDB\Pendaftaran\SyaratPenerimaanController@syaratPenerimaan');
			Route::get('syarat-penerimaan/{id}/add', 'PPDB\Pendaftaran\SyaratPenerimaanController@addSyaratPenerimaan');
			Route::post('syarat-penerimaan/{id}/add', 'PPDB\Pendaftaran\SyaratPenerimaanController@actionAddSyaratPenerimaan');
			Route::get('syarat-penerimaan/{id_penerimaan}/edit/{id_syarat_penerimaan}', 'PPDB\Pendaftaran\SyaratPenerimaanController@editSyaratPenerimaan');
			Route::post('syarat-penerimaan/{id_penerimaan}/edit/{id_syarat_penerimaan}', 'PPDB\Pendaftaran\SyaratPenerimaanController@actionEditSyaratPenerimaan');
			Route::post('syarat-penerimaan/{id_penerimaan}/delete/{id_syarat_penerimaan}', 'PPDB\Pendaftaran\SyaratPenerimaanController@actionDeleteSyaratPenerimaan');

			// MENU Pembukaan voucher
			Route::get('pembukaan-voucher', 'PPDB\Pendaftaran\PembukaanVoucherController@viewPembukaanVoucher');
			Route::post('pembukaan-voucher/post-view-pembukaan-voucher', 'PPDB\Pendaftaran\PembukaanVoucherController@actionViewPembuatanVoucher');
			Route::get('pembukaan-voucher/{id_penerimaan}', 'PPDB\Pendaftaran\PembukaanVoucherController@pembukaanVoucher');
			Route::get('pembukaan-voucher/{id_penerimaan}/add', 'PPDB\Pendaftaran\PembukaanVoucherController@addPembukaanVoucher');
			Route::post('pembukaan-voucher/{id_penerimaan}/add', 'PPDB\Pendaftaran\PembukaanVoucherController@actionAddPembukaanVoucher');
			Route::post('pembukaan-voucher/{id_penerimaan}/delete/{id_voucher_tarif}', 'PPDB\Pendaftaran\PembukaanVoucherController@actionDeleteVoucherTarif');

			// MENU Pembukaan voucher generate voucher
			Route::get('pembukaan-voucher/{id_penerimaan}/generate-voucher', 'PPDB\Pendaftaran\PembukaanVoucherController@generateVoucher');
			Route::post('pembukaan-voucher/{id_penerimaan}/generate-voucher', 'PPDB\Pendaftaran\PembukaanVoucherController@actionGenerateVoucher');
			Route::post('pembukaan-voucher/{id_penerimaan}/delete-voucher/{id_voucher}', 'PPDB\Pendaftaran\PembukaanVoucherController@actionDeleteVoucher');

			// MENU petugas penerimaan
			Route::get('petugas-penerimaan', 'PPDB\Pendaftaran\PetugasPenerimaanController@viewPetugasPenerimaan');
			Route::post('petugas-penerimaan/post-view-petugas-penerimaan', 'PPDB\Pendaftaran\PetugasPenerimaanController@actionViewPetugasPenerimaan');
			Route::get('petugas-penerimaan/{id_penerimaan}', 'PPDB\Pendaftaran\PetugasPenerimaanController@petugasPenerimaan');
			Route::get('petugas-penerimaan/{id_penerimaan}/add', 'PPDB\Pendaftaran\PetugasPenerimaanController@addPetugasPenerimaan');
			Route::post('petugas-penerimaan/{id_penerimaan}/add', 'PPDB\Pendaftaran\PetugasPenerimaanController@actionAddPetugasPenerimaan');
			Route::post('petugas-penerimaan/{id_penerimaan}/delete/{id_penerimaan_petugas}', 'PPDB\Pendaftaran\PetugasPenerimaanController@actionDeletePetugasPenerimaan');

			// MENU data informasi
			Route::get('data-informasi', 'PPDB\Pendaftaran\DataInformasiController@dataInformasi');
			Route::post('data-informasi', 'PPDB\Pendaftaran\DataInformasiController@actionPostDataInformasi');

		});

		/** ==== MODUL PESERTA ==== **/
		Route::group(array('prefix' => 'peserta'), function() {
			
			// MENU Pembayaran Formulir
			Route::get('pembayaran-formulir', 'PPDB\Peserta\PembayaranFormulirController@viewPembayaranFormulir');
			Route::post('pembayaran-formulir', 'PPDB\Peserta\PembayaranFormulirController@findVoucher');
			Route::post('pembayaran-formulir/reset-voucher/{kode_voucher}', 'PPDB\Peserta\PembayaranFormulirController@deletePembayaranFormulir');
			Route::get('pembayaran-formulir/{id_voucher}', 'PPDB\Peserta\PembayaranFormulirController@showVoucher');
			Route::post('pembayaran-formulir/{id_voucher}/bayar-voucher', 'PPDB\Peserta\PembayaranFormulirController@bayarVoucher');

			// MENU pindah penerimaan
			Route::get('pindah-penerimaan', 'PPDB\Peserta\PindahPenerimaanController@viewPindahPenerimaan');
			Route::post('pindah-penerimaan', 'PPDB\Peserta\PindahPenerimaanController@findVoucher');
			Route::get('pindah-penerimaan/{id_voucher}', 'PPDB\Peserta\PindahPenerimaanController@showVoucher');
			Route::post('pindah-penerimaan/{id_voucher}/pindah', 'PPDB\Peserta\PindahPenerimaanController@actionPindahVoucher');


		});

		/** ==== MODUL REPORT ==== **/
		Route::group(array('prefix' => 'report'), function() {
			
			// MENU Report pendaftaran
			Route::get('report-pendaftaran', 'PPDB\Report\ReportPendaftaranController@viewReportPendaftaran');
			Route::get('report-pendaftaran/datatables', 'PPDB\Report\ReportPendaftaranController@datatablesReportPendaftaran');

			Route::get('report-pendaftaran/rekap/{id}', 'PPDB\Report\ReportPendaftaranController@rekapReportPendaftaran');

			Route::get('report-pendaftaran/detail/{id}', 'PPDB\Report\ReportPendaftaranController@detailReportPendaftaran');
			Route::get('report-pendaftaran/detail/datatables/{id}', 'PPDB\Report\ReportPendaftaranController@datatablesDetailReportPendaftaran');
		});

		/** ==== MODUL PENETAPAN ==== **/
		Route::group(array('prefix' => 'penetapan'), function() {
			
			// MENU penetapan
			Route::get('data-penetapan', 'PPDB\Penetapan\PenetapanController@viewPenetapan');
			Route::get('data-penetapan/datatables', 'PPDB\Penetapan\PenetapanController@datatablesPenetapan');
			Route::get('data-penetapan/add', 'PPDB\Penetapan\PenetapanController@addPenetapan');
			Route::get('data-penetapan/edit/{id}', 'PPDB\Penetapan\PenetapanController@editPenetapan');
			Route::post('action-penetapan/{mode}/{id}', 'PPDB\Penetapan\PenetapanController@actionPenetapan');

			Route::get('data-penetapan/view-penetapan-penerimaan/{id}', 'PPDB\Penetapan\PenetapanController@viewPenetapanPenerimaan');
			Route::get('data-penetapan/datatables-penetapan-penerimaan/{id}', 'PPDB\Penetapan\PenetapanController@datatablesPenetapanPenerimaan');
			Route::get('data-penetapan/add-penetapan-penerimaan/{id}', 'PPDB\Penetapan\PenetapanController@addPenetapanPenerimaan');
			Route::get('data-penetapan/edit-penetapan-penerimaan/{id}', 'PPDB\Penetapan\PenetapanController@editPenetapanPenerimaan');
			Route::post('action-penetapan-penerimaan/{mode}/{id}', 'PPDB\Penetapan\PenetapanController@actionPenetapanPenerimaan');


			// MENU PERSIDANGAN
			Route::get('persidangan', 'PPDB\Penetapan\PersidanganController@viewPersidangan');
			Route::post('persidangan/post-view-persidangan', 'PPDB\Penetapan\PersidanganController@actionViewPersidangan');
			Route::get('persidangan/tahun/{id}', 'PPDB\Penetapan\PersidanganController@editPersidangan2');		
			Route::get('persidangan/datatables/{tahun}', 'PPDB\Penetapan\PersidanganController@datatablesPersidangan');		
			Route::get('persidangan/edit/{id}', 'PPDB\Penetapan\PersidanganController@editPersidangan');

			Route::get('persidangan/view-persidangan-gelombang/{id}', 'PPDB\Penetapan\PersidanganController@viewPersidanganGelombang');

			Route::get('persidangan/datatablesviewgelombang', 'PPDB\Penetapan\PersidanganController@datatablesPersidanganViewGelombang');


		});		
	});
  
});