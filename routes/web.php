<?php

use App\Http\Controllers\Administrator\Device\FingerprintController;
use App\Http\Controllers\AuthGlobalController;
use App\Http\Controllers\Keuangan\SIM\PembayaranOnlineController;
use App\Http\Controllers\SignInController;
use App\Models\Sekolah;
use Carbon\Carbon;
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

// Testing
// Route::get('copy-biaya', function () {
//     $tahun_asal = 2016;
//     $tahun_copy = 2015;

//     $spp_juli_1011 = 165000;
//     $spp_non_juli_1011 = 170000;

//     $spp_juli_12 = 185000;
//     $spp_non_juli_12 = 200000;

//     $source_biaya_sekolah = BiayaSekolah::whereHas('semester', function ($q) use ($tahun_asal) {
//         $q->where('thn_akademik_semester', $tahun_asal);
//     })->get();

//     foreach ($source_biaya_sekolah as $biaya_sekolah) {
//         $source_semester = Semester::find($biaya_sekolah->id_semester);
//         $target_biaya_sekolah = $biaya_sekolah->replicate();

//         $target_biaya_sekolah->id_biaya_sekolah = generate_id();
//         $target_biaya_sekolah->id_semester = Semester::where('thn_akademik_semester', $tahun_copy)->where('nm_semester', $source_semester->nm_semester)->first()->id_semester;
//         $target_biaya_sekolah->created_at = '2022-08-31 00:00:00';
//         $target_biaya_sekolah->save();

//         foreach (DetailBiaya::where('id_biaya_sekolah', $biaya_sekolah->id_biaya_sekolah)->get() as $detail_biaya) {
//             $target_detail_biaya = $detail_biaya->replicate();

//             $target_detail_biaya->id_detail_biaya = generate_id();
//             $target_detail_biaya->id_biaya_sekolah = $target_biaya_sekolah->id_biaya_sekolah;
//             if ($target_biaya_sekolah->keterangan_biaya_sekolah == 'Kelas 10&11') {
//                 if ($target_detail_biaya->id_bulan == 7) {
//                     $target_detail_biaya->besar_biaya = $spp_juli_1011;
//                 } else {
//                     $target_detail_biaya->besar_biaya = $spp_non_juli_1011;
//                 }
//             }

//             if ($target_biaya_sekolah->keterangan_biaya_sekolah == 'Kelas 12') {
//                 if ($target_detail_biaya->id_bulan == 7) {
//                     $target_detail_biaya->besar_biaya = $spp_juli_12;
//                 } else {
//                     $target_detail_biaya->besar_biaya = $spp_non_juli_12;
//                 }
//             }

//             $target_detail_biaya->created_at = '2022-08-31 00:00:00';
//             $target_detail_biaya->save();
//         }
//     }

//     return 'OK';
// });

// Route::get('import-excel-tagihan', function () {
//     Excel::import(new UploadTagihanSiswa(true), 'FILE_TAGIHAN_SISWA.xls');

//     return 'OK';
// });

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
