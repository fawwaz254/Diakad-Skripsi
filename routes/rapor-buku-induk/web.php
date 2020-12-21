<?php
// ROLE ALUMNI
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'rapor-buku-induk'), function() {
        Route::get('welcome', 'RaporBukuInduk\WelcomeController@indexWelcome');

    });
});