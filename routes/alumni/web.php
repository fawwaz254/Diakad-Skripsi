<?php
// ROLE ALUMNI
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'alumni'), function() {
        Route::get('welcome', 'Alumni\WelcomeController@indexWelcome');

    Route::group(array('prefix' => 'bursa-kerja'), function() {

    	Route::group(array('prefix' => 'bkk'), function() {

    		Route::get('/', 'Alumni\BursaKerja\BKKController@viewBkk');
    		Route::get('detail/{id}', 'Alumni\BursaKerja\BKKController@viewDetailBkk');
    		Route::get('datatables', 'Alumni\BursaKerja\BKKController@showDatatablesBkk');

    	});

    });

    });
});
