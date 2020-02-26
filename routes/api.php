<?php

// use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('v1/signin', 'Apiv1Controller@actionSignIn');
Route::group(array('middleware'=> ['auth.mobile'], 'prefix' => 'v1'), function () {
    Route::group(array('prefix' => 'guru'), function () {
        Route::group(array('prefix' => 'data-pribadi'), function () {
            Route::post('get', 'Apiv1Controller@actionGetDataPribadi');
            Route::post('submit', 'Apiv1Controller@actionDataPribadi');
        });

        Route::post('kota/get', 'Apiv1Controller@actionGetKota');
        Route::post('kelas-all/get', 'Apiv1Controller@actionGetKelasAll');
        Route::post('kelas-kbm/get', 'Apiv1Controller@actionGetKelasKBM');
        Route::post('siswa-by-kelas-kbm/get', 'Apiv1Controller@actionGetSiswaByJadwalKelasKBM');
        Route::post('pertemuan-kelas-kbm/get', 'Apiv1Controller@actionGetPertemuanByJadwalKelasKBM');

        Route::post('kelas-uts/get', 'Apiv1Controller@actionGetKelasUTS');
        Route::post('kelas-uas/get', 'Apiv1Controller@actionGetKelasUAS');
        
        Route::group(array('prefix' => 'presensi-kbm'), function () {
            Route::post('get', 'Apiv1Controller@actionGetPresensiKBM');
            Route::post('submit', 'Apiv1Controller@actionAbsensiKBMSiswa');
        });

        Route::group(array('prefix' => 'presensi-uts'), function () {
            Route::post('get', 'Apiv1Controller@actionGetPresensiUjian');
            Route::post('submit', 'Apiv1Controller@actionAbsensiUjianSiswa');
        });

        Route::group(array('prefix' => 'presensi-uas'), function () {
            Route::post('get', 'Apiv1Controller@actionGetPresensiUjian');
            Route::post('submit', 'Apiv1Controller@actionAbsensiUjianSiswa');
        });
        
        Route::post('semester/get', 'Apiv1Controller@actionGetSemester');
        Route::post('jadwal/get', 'Apiv1Controller@actionGetJadwal');

        Route::post('ruangan/get', 'Apiv1Controller@actionGetRuangan');
        Route::post('inventaris-ruangan/get', 'Apiv1Controller@actionGetInventarisRuangan');
        Route::post('buku-alat/get', 'Apiv1Controller@actionGetBukuAlat');

        Route::group(array('prefix' => 'komplain-sarpras'), function () {
            Route::post('ruangan/detail', 'Apiv1Controller@actionGetKomplainRuangan');
            Route::post('buku-alat/detail', 'Apiv1Controller@actionGetKomplainBukuAlat');
            
            Route::post('{mode}/submit', 'Apiv1Controller@actionKomplainSarpras');
        });

        Route::group(array('prefix' => 'pelanggaran-siswa'), function () {
            Route::post('get', 'Apiv1Controller@actionGetPelanggaranSiswa');
            Route::post('kategori/get', 'Apiv1Controller@actionGetKategoriPelanggaranSiswa');
            Route::post('subkategori/get', 'Apiv1Controller@actionGetSubkategoriPelanggaranSiswa');
            Route::post('{mode}/submit', 'Apiv1Controller@actionPelanggaranSiswa');
        });

        Route::group(array('prefix' => 'monitoring-kelas-kosong'), function () {
            Route::post('get', 'Apiv1Controller@actionGetMonitoringKelasKosong');
        });

        Route::group(array('prefix' => 'rekap-absen'), function () {
            Route::post('get', 'Apiv1Controller@actionGetRekapAbsen');
        });

        Route::group(array('prefix' => 'absensi-harian'), function () {
            Route::post('kelas/get', 'Apiv1Controller@actionGetAbsensiHarianKelas');
            Route::post('siswa/get', 'Apiv1Controller@actionGetAbsensiHarianSiswa');
            
            Route::post('{mode}/submit', 'Apiv1Controller@actionGetAbsensiHarianSave');
        });
    });
    
    Route::group(array('prefix' => 'tendik'), function () {
        Route::group(array('prefix' => 'data-pribadi'), function () {
            Route::post('get', 'Apiv1Controller@actionGetDataPribadi');
            Route::post('submit', 'Apiv1Controller@actionDataPribadi');
        });

        Route::post('semester/get', 'Apiv1Controller@actionGetSemester');
        Route::post('kota/get', 'Apiv1Controller@actionGetKota');
        Route::post('kelas-all/get', 'Apiv1Controller@actionGetKelasAll');

        Route::group(array('prefix' => 'monitoring-kelas-kosong'), function () {
            Route::post('get', 'Apiv1Controller@actionGetMonitoringKelasKosong');
        });

        Route::group(array('prefix' => 'rekap-absen'), function () {
            Route::post('get', 'Apiv1Controller@actionGetRekapAbsen');
        });

        Route::group(array('prefix' => 'absensi-harian'), function () {
            Route::post('kelas/get', 'Apiv1Controller@actionGetAbsensiHarianKelas');
            Route::post('siswa/get', 'Apiv1Controller@actionGetAbsensiHarianSiswa');
            
            Route::post('{mode}/submit', 'Apiv1Controller@actionGetAbsensiHarianSave');
        });
    });
});
