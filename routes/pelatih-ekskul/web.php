<?php
// ROLE PELATIH EKSKUL
Route::group(array('middleware'=> ['token_staff']), function() {
	Route::group(array('prefix' => 'pelatih-ekskul'), function() {
		Route::get('welcome', 'PelatihEkskul\WelcomeController@indexWelcome');
	
	});
});