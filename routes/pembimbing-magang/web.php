<?php

use App\Http\Controllers\PembimbingMagang\PresensiMagang\RekapPresensiMagangController;
use App\Http\Controllers\PembimbingMagang\PresensiMagang\InputPresensiMagangController;
use App\Http\Controllers\PembimbingMagang\WelcomeController;

Route::middleware(['token_staff'])->group(function () {
	Route::prefix('pembimbing-magang')->group(function () {
		Route::get('welcome', [WelcomeController::class, 'indexWelcome']);

		Route::prefix('presensi-magang')->group(function () {
			// MENU Input Absensi magang
			Route::get('input-presensi-magang', [InputPresensiMagangController::class, 'viewInputPresensiMagang']);
			Route::get('input-presensi-magang/manage/{id_presensi_magang}', [InputPresensiMagangController::class, 'viewAddEditInputPresensiMagang']);
			// Route::get('input-presensi-magang/edit/{id_presensi_magang}', [InputPresensiMagangController::class, 'viewAddEditInputPresensiMagang']);
			// Route::get('input-absensi-ekskul/manage/{id_semester}/{id_ekskul}/{id_presensi_ekskul}', [InputAbsensiEkskulController::class, 'viewManageInputAbsensiEkskul']);
			Route::get('input-presensi-magang/detail/{id_presensi_magang}', [InputPresensiMagangController::class, 'viewDetailInputPresensiMagang']);

			Route::post('input-presensi-magang/datatables', [InputPresensiMagangController::class, 'datatablesInputPresensiMagang']);
			Route::post('input-presensi-magang/datatables-detail', [InputPresensiMagangController::class, 'datatablesSiswaInputPresensiMagang']);
			Route::post('input-presensi-magang/action/{mode}/{id_presensi_magang}', [InputPresensiMagangController::class, 'actionInputAbsensiMagang']);
			// Route::post('input-absensi-ekskul/action/{mode}/{id}', [InputAbsensiEkskulController::class, 'actionInputAbsensiEkskul']);

			// MENU Rekap Absensi Ekskul
			Route::get('rekap-presensi-magang', [RekapPresensiMagangController::class, 'viewRekapAbsensiMagang']);
			// Route::get('rekap-absensi-ekskul/detail/{id_semester}/{id_ekskul}', [RekapAbsensiEkskulController::class, 'viewDetailRekapAbsensiEkskul']);
			Route::get('rekap-presensi-magang/print', [RekapPresensiMagangController::class, 'printAllRekapPresensiMagang']);
			Route::get('rekap-presensi-magang/print/{id_siswa}', [RekapPresensiMagangController::class, 'printRekapPresensiMagang']);
		});
	});
});
