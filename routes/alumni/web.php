<?php
// ROLE ALUMNI
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'alumni'), function() {
        Route::get('welcome', 'Alumni\WelcomeController@indexWelcome');

    });
});