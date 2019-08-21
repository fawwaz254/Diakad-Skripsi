<?php

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

Route::get('upload', function() {
    $files = Storage::disk('spaces')->files('smawh2/calon_siswa');

    return view('contoh-upload', compact('files'));
});
Route::get('delete', function() {
    $file = request()->input('id');

    $files = Storage::disk('spaces')->delete($file);

    return redirect()->back();
});
Route::post('upload', function(Request $request) {
	$validator = Validator::make($request->all(), [
		'file' => 'file|required|max:2048|mimes:jpeg,bmp,png'
	]);

    $file = Storage::disk('spaces')->putFile('smawh2/calon_siswa', request()->file, 'public');

    return redirect()->back();
});*/
// END CONTOH UPLOAD DO

Route::get('/', 'SignInController@indexSignIn');
Route::post('signin', 'SignInController@actionSignIn');

Route::group(array('middleware'=> ['token_staff']), function() {
	//
	Route::group(array('prefix' => '{global}'), function() {
		Route::get('/', 'AuthGlobalController@indexDashboard');
		Route::get('search', 'AuthGlobalController@indexSearch');
		Route::get('profile', 'AuthGlobalController@indexProfile');
		Route::post('profile', 'AuthGlobalController@actionSaveProfile');
		Route::get('password', 'AuthGlobalController@indexPassword');
		Route::post('password', 'AuthGlobalController@actionChangePassword');
		Route::get('signout', 'AuthGlobalController@actionSignOut');
	});
});