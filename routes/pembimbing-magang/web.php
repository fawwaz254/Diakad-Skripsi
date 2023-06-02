<?php

use App\Http\Controllers\PembimbingMagang\WelcomeController;

Route::middleware(['token_staff'])->group(function () {
	Route::prefix('pembimbing-magang')->group(function () {
		Route::get('welcome', [WelcomeController::class, 'indexWelcome']);
	});
});
