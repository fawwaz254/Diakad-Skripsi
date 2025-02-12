<?php

use App\Http\Controllers\PembimbingMagang\PresensiMagang\RekapPresensiMagangController;
use App\Http\Controllers\PembimbingMagang\PresensiMagang\InputPresensiMagangController;
use App\Http\Controllers\PembimbingMagang\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['token_staff'])->group(function () {
	Route::prefix('pembimbing-magang')->group(function () {
		Route::get('welcome', [WelcomeController::class, 'indexWelcome']);

		Route::prefix('presensi-magang')->group(function () {
			// MENU Input Absensi magang
			Route::get('input-presensi-magang', [InputPresensiMagangController::class, 'viewInputPresensiMagang']);
			Route::get('input-presensi-magang/manage/{id_presensi_magang}', [InputPresensiMagangController::class, 'viewAddEditInputPresensiMagang']);
			Route::get('input-presensi-magang/detail/{id_presensi_magang}', [InputPresensiMagangController::class, 'viewDetailInputPresensiMagang']);

			Route::post('input-presensi-magang/datatables', [InputPresensiMagangController::class, 'datatablesInputPresensiMagang']);
			Route::post('input-presensi-magang/datatables-detail', [InputPresensiMagangController::class, 'datatablesSiswaInputPresensiMagang']);
			Route::post('input-presensi-magang/action/{mode}/{id_presensi_magang}', [InputPresensiMagangController::class, 'actionInputAbsensiMagang']);

			// MENU Rekap Absensi Ekskul
			Route::get('rekap-presensi-magang', [RekapPresensiMagangController::class, 'viewRekapAbsensiMagang']);
			Route::get('rekap-presensi-magang/print', [RekapPresensiMagangController::class, 'printAllRekapPresensiMagang']);
			Route::get('rekap-presensi-magang/print/{id_siswa}', [RekapPresensiMagangController::class, 'printRekapPresensiMagang']);
		});
	});
});
