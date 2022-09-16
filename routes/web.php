<?php

use App\Models\Sekolah;
use Carbon\Carbon;

// DO NOT CHANGE
Route::get('merge/key-6c8c263f-4bf6-47ad-9ed2-eba730bde41b', 'AuthGlobalController@actionMerge');

Route::group(['prefix' => 'laravel-filemanager'], function () {
    Route::get('/', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show')->name('unisharp.lfm.show');
    // display integration error messages
    Route::get('/errors', '\UniSharp\LaravelFilemanager\Controllers\LfmController@getErrors')->name('unisharp.lfm.getErrors');
    // upload
    Route::any('/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload')->name('unisharp.lfm.upload');
    // list images & files
    Route::get('/jsonitems', '\UniSharp\LaravelFilemanager\Controllers\ItemsController@getItems')->name('unisharp.lfm.getItems');
    Route::get('/move', '\UniSharp\LaravelFilemanager\Controllers\ItemsController@move')->name('unisharp.lfm.move');
    Route::get('/domove', '\UniSharp\LaravelFilemanager\Controllers\ItemsController@domove')->name('unisharp.lfm.domov');
    // folders
    Route::get('/newfolder', '\UniSharp\LaravelFilemanager\Controllers\FolderController@getAddfolder')->name('unisharp.lfm.getAddfolder');
    // list folders
    Route::get('/folders', '\UniSharp\LaravelFilemanager\Controllers\FolderController@getFolders')->name('unisharp.lfm.getFolders');
    // crop
    Route::get('/crop', '\UniSharp\LaravelFilemanager\Controllers\CropController@getCrop')->name('unisharp.lfm.getCrop');
    // rename
    Route::get('/rename', '\UniSharp\LaravelFilemanager\Controllers\RenameController@getRename')->name('unisharp.lfm.getRename');
    // scale/resize
    Route::get('/resize', '\UniSharp\LaravelFilemanager\Controllers\ResizeController@getResize')->name('unisharp.lfm.getResize');
    // download
    Route::get('/download', '\UniSharp\LaravelFilemanager\Controllers\DownloadController@getDownload')->name('unisharp.lfm.getDownload');
    // delete
    Route::get('/delete', '\UniSharp\LaravelFilemanager\Controllers\DeleteController@getDelete')->name('unisharp.lfm.getDelete');
    Route::get('/demo', '\UniSharp\LaravelFilemanager\Controllers\DemoController@index');
});

// START USING FOR FINGERPRINT
// url: /iclock

Route::group(array('prefix' => 'iclock'), function () {
    Route::get('getrequest', 'Administrator\Device\FingerprintController@actionCheck');
    Route::get('cdata', function () {
        return 'OK';
    });
    Route::post('cdata', 'Administrator\Device\FingerprintController@actionGetFinger');

    Route::get('manual-get-data', 'Administrator\Device\FingerprintController@actionGetDataFinger');
});
// END USING FOR FINGERPRINT

Route::get('guid', function () {
    $now = Carbon::now(env('APP_TIMEZONE', ''));
    $prefix = Sekolah::first()->prefix;
    if (!empty($_GET['c'])) {
        $html = '';
        for ($i = 0; $i < $_GET['c']; $i++) {
            $html .= $prefix . strtotime($now) . uniqid() . '<br>';
        }
    } else {
        $html = $prefix . strtotime($now) . uniqid();
    }
    return $html;
});

Route::view('success-page', 'form-pengisian-alumni.success-page');
Route::view('error-page', 'form-pengisian-alumni.error-page');
Route::get('pengisian-alumni', 'PengisianAlumniController@viewPengisianAlumni');
Route::post('action-pengisian-alumni', 'PengisianAlumniController@actionPengisianAlumni');

Route::get('forget-password', 'ForgetPasswordController@index');
Route::post('send-link-reset-password', 'ForgetPasswordController@sendLinkResetPassword');
Route::get('check-link-reset-password', 'ForgetPasswordController@checkLinkResetPassword');
Route::get('reset-password', 'ForgetPasswordController@resetPassword');
Route::post('reset-password-action', 'ForgetPasswordController@resetPasswordAction');

Route::get('payment/detail/{id}', 'Keuangan\SIM\PembayaranOnlineController@viewDetail');
Route::post('payment/notification/{id}', 'Keuangan\SIM\PembayaranOnlineController@actionPayment');
Route::post('payment/callback/{id}', 'Keuangan\SIM\PembayaranOnlineController@actionCallback');

Route::get('check/payment/expired', 'Keuangan\SIM\PembayaranOnlineController@actionCheckExp');

Route::get('/', 'SignInController@indexSignIn');
Route::post('signin', 'SignInController@actionSignIn');

Route::get('report-pimpinan', 'ReportController@viewAllDiakad');
Route::get('report-pimpinan-print', 'ReportController@printAllDiakad');

Route::group(array('prefix' => 'reporting-dashboard'), function () {
    Route::get('/', 'SignInController@indexReportingDashboard');
    Route::get('all-diakad/{id}', 'ReportController@checkProgress');
    Route::get('akademik', function () {
        return view('reporting-dashboard/akademik');
    });
    Route::get('bk-kesiswaan', function () {
        return view('reporting-dashboard/bk-kesiswaan');
    });

    Route::get('keuangan', function () {
        return view('reporting-dashboard/keuangan');
    });

    Route::get('sarpras', function () {
        return view('reporting-dashboard/sarpras');
    });

    Route::get('sekretariat', function () {
        return view('reporting-dashboard/sekretariat');
    });

    Route::get('sumber-daya', function () {
        return view('reporting-dashboard/sumber-daya');
    });
});

Route::group(array('middleware' => ['token_staff']), function () {
    //
    Route::group(array('prefix' => '{global}'), function () {
        Route::get('must-change-password', 'AuthGlobalController@indexMustChangePassword');
        Route::post('must-change-password', 'AuthGlobalController@actionMustChangePassword');
        Route::post('by-pass-change-password', 'AuthGlobalController@actionByPassChangePassword');

        Route::get('/', 'AuthGlobalController@indexDashboard');
        Route::get('search', 'AuthGlobalController@indexSearch');
        Route::get('profile', 'AuthGlobalController@indexProfile');
        Route::post('profile', 'AuthGlobalController@actionSaveProfile');
        Route::get('password', 'AuthGlobalController@indexPassword');
        Route::post('password', 'AuthGlobalController@actionChangePassword');
        Route::get('signout', 'AuthGlobalController@actionSignOut');
    });
});
