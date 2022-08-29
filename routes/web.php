<?php

use Carbon\Carbon;
use App\Models\Sekolah;
use App\Http\Controllers\SignInController;
use App\Http\Controllers\AuthGlobalController;
use UniSharp\LaravelFilemanager\Controllers\LfmController;
use UniSharp\LaravelFilemanager\Controllers\CropController;
use UniSharp\LaravelFilemanager\Controllers\DemoController;
use UniSharp\LaravelFilemanager\Controllers\ItemsController;
use UniSharp\LaravelFilemanager\Controllers\DeleteController;
use UniSharp\LaravelFilemanager\Controllers\FolderController;
use UniSharp\LaravelFilemanager\Controllers\RenameController;
use UniSharp\LaravelFilemanager\Controllers\ResizeController;
use UniSharp\LaravelFilemanager\Controllers\UploadController;
use UniSharp\LaravelFilemanager\Controllers\DownloadController;
use App\Http\Controllers\Keuangan\SIM\PembayaranOnlineController;
use App\Http\Controllers\Administrator\Device\FingerprintController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// DO NOT CHANGE
Route::get('merge/key-6c8c263f-4bf6-47ad-9ed2-eba730bde41b', [AuthGlobalController::class, 'actionMerge']);

Route::prefix('laravel-filemanager')->group(function () {
    Route::get('/', [LfmController::class, 'show'])->name('unisharp.lfm.show');
    // display integration error messages
    Route::get('/errors', [LfmController::class, 'getErrors'])->name('unisharp.lfm.getErrors');
    // upload
    Route::any('/upload', [UploadController::class, 'upload'])->name('unisharp.lfm.upload');
    // list images & files
    Route::get('/jsonitems', [ItemsController::class, 'getItems'])->name('unisharp.lfm.getItems');
    Route::get('/move', [ItemsController::class, 'move'])->name('unisharp.lfm.move');
    Route::get('/domove', [ItemsController::class, 'domove'])->name('unisharp.lfm.domov');
    // folders
    Route::get('/newfolder', [FolderController::class, 'getAddfolder'])->name('unisharp.lfm.getAddfolder');
    // list folders
    Route::get('/folders', [FolderController::class, 'getFolders'])->name('unisharp.lfm.getFolders');
    // crop
    Route::get('/crop', [CropController::class, 'getCrop'])->name('unisharp.lfm.getCrop');
    // rename
    Route::get('/rename', [RenameController::class, 'getRename'])->name('unisharp.lfm.getRename');
    // scale/resize
    Route::get('/resize', [ResizeController::class, 'getResize'])->name('unisharp.lfm.getResize');
    // download
    Route::get('/download', [DownloadController::class, 'getDownload'])->name('unisharp.lfm.getDownload');
    // delete
    Route::get('/delete', [DeleteController::class, 'getDelete'])->name('unisharp.lfm.getDelete');
    Route::get('/demo', [DemoController::class, 'index']);
});

// START USING FOR FINGERPRINT
// url: /iclock

Route::prefix('iclock')->group(function () {
    Route::get('getrequest', [FingerprintController::class, 'actionCheck']);
    Route::get('cdata', function () {
        return 'OK';
    });
    Route::post('cdata', [FingerprintController::class, 'actionGetFinger']);
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

Route::get('success-page', function () {
    return view('form-pengisian-alumni.success-page');
});
Route::get('error-page', function () {
    return view('form-pengisian-alumni.error-page');
});
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

Route::get('payment/detail/{id}', [PembayaranOnlineController::class, 'viewDetail']);
Route::post('payment/notification/{id}', [PembayaranOnlineController::class, 'actionPayment']);
Route::post('payment/callback/{id}', [PembayaranOnlineController::class, 'actionCallback']);

Route::get('check/payment/expired', [PembayaranOnlineController::class, 'actionCheckExp']);

Route::get('/', [SignInController::class, 'indexSignIn']);
Route::post('signin', [SignInController::class, 'actionSignIn']);

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
        Route::get('must-change-password', [AuthGlobalController::class, 'indexMustChangePassword']);
        Route::post('must-change-password', [AuthGlobalController::class, 'actionMustChangePassword']);
        Route::post('by-pass-change-password', [AuthGlobalController::class, 'actionByPassChangePassword']);

        Route::get('/', [AuthGlobalController::class, 'indexDashboard']);
        Route::get('search', [AuthGlobalController::class, 'indexSearch']);
        Route::get('profile', [AuthGlobalController::class, 'indexProfile']);
        Route::post('profile', [AuthGlobalController::class, 'actionSaveProfile']);
        Route::get('password', [AuthGlobalController::class, 'indexPassword']);
        Route::post('password', [AuthGlobalController::class, 'actionChangePassword']);
        Route::get('signout', [AuthGlobalController::class, 'actionSignOut']);
    });
});

// Route::middleware(['token_staff'])->group(function () {
// Route::prefix('foo')->group(function () {
// });
// });
