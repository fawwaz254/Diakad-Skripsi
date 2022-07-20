<?php

use App\Http\Controllers\Administrator\Device\FingerprintController;
use App\Http\Controllers\AuthGlobalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SignInController;
use Carbon\Carbon;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Hash;
use UniSharp\LaravelFilemanager\Controllers\CropController;
use UniSharp\LaravelFilemanager\Controllers\DeleteController;
use UniSharp\LaravelFilemanager\Controllers\DemoController;
use UniSharp\LaravelFilemanager\Controllers\DownloadController;
use UniSharp\LaravelFilemanager\Controllers\FolderController;
use UniSharp\LaravelFilemanager\Controllers\ItemsController;
use UniSharp\LaravelFilemanager\Controllers\LfmController;
use UniSharp\LaravelFilemanager\Controllers\RenameController;
use UniSharp\LaravelFilemanager\Controllers\ResizeController;
use UniSharp\LaravelFilemanager\Controllers\UploadController;

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

// CONTOH UPLOAD DO
/*use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ContohLaravelJob;

Route::get('upload', function () {
    $files = Storage::disk('spaces')->files('demo/global');

    return view('contoh-upload', compact('files'));
});
Route::get('delete', function () {
    $file = request()->input('id');

    $files = Storage::disk('spaces')->delete($file);

    return redirect()->back();
});
Route::post('upload', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'file' => 'file|required|max:2048|mimes:jpeg,bmp,png'
    ]);

    $file = Storage::disk('spaces')->putFile('demo/global', request()->file, 'public');
    ContohLaravelJob::dispatch($file)->delay(now()->addMinutes(2));

    return redirect()->back();
});*/
// END CONTOH UPLOAD DO

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

Route::get('cekHashPassword', function () {
    $password =  Hash::make($_GET['c']);
    return  $password;
});

Route::view('success-page', 'form-pengisian-alumni.success-page');
Route::view('error-page', 'form-pengisian-alumni.error-page');
Route::get('pengisian-alumni', [PengisianAlumniController::class, 'viewPengisianAlumni']);
Route::post('action-pengisian-alumni', [PengisianAlumniController::class, 'actionPengisianAlumni']);

Route::get('forget-password', [ForgetPasswordController::class, 'index']);
Route::post('send-link-reset-password', [ForgetPasswordController::class, 'sendLinkResetPassword']);
Route::get('check-link-reset-password', [ForgetPasswordController::class, 'checkLinkResetPassword']);
Route::get('reset-password', [ForgetPasswordController::class, 'resetPassword']);
Route::post('reset-password-action', [ForgetPasswordController::class, 'resetPasswordAction']);

Route::get('payment/detail/{id}', [PembayaranOnlineController::class, 'viewDetail']);
Route::post('payment/notification/{id}', [PembayaranOnlineController::class, 'actionPayment']);
Route::post('payment/callback/{id}', [PembayaranOnlineController::class, 'actionCallback']);

Route::get('check/payment/expired', [PembayaranOnlineController::class, 'actionCheckExp']);

Route::get('/', [SignInController::class, 'indexSignIn']);
Route::post('signin', [SignInController::class, 'actionSignIn']);

Route::get('report-pimpinan', [ReportController::class, 'viewAllDiakad']);
Route::get('report-pimpinan-print', [ReportController::class, 'printAllDiakad']);

Route::prefix('reporting-dashboard')->group(function () {
    Route::get('/', [SignInController::class, 'indexReportingDashboard']);
    Route::get('all-diakad/{id}', [ReportController::class, 'checkProgress']);
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

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('{global}')->group(function () {
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
