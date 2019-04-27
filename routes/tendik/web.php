<?php
// ROLE TENAGA PENDIDIK
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'tendik'), function() {
        Route::get('welcome', 'Tendik\WelcomeController@indexWelcome');

    });
});