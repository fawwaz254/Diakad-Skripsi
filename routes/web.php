<?php

use Carbon\Carbon;
use App\Models\Sekolah;

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
