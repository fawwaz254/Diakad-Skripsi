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
    Route::group(array('prefix' => 'token'), function () {
        Route::post('save', 'Apiv1Controller@actionSaveToken');
    });

    Route::post('notifikasi/read', 'Apiv1Controller@actionNotifikasiUpdate');
    Route::post('notifikasi/get', 'Apiv1Controller@actionGetNotifikasi');

    Route::group(array('prefix' => 'guru'), function () {
        Route::group(array('prefix' => 'data-pribadi'), function () {
            Route::post('get', 'Apiv1Controller@geteditprofile');
            Route::post('submit', 'Apiv1Controller@submiteditprofile');
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

        Route::group(array('prefix' => 'input-jadwal'), function () {
            Route::post('get', 'Apiv1Controller@actionGetInputJadwal');
            Route::post('detail/get', 'Apiv1Controller@actionGetDetailInputJadwal');
            Route::post('{mode}/submit', 'Apiv1Controller@actionInputJadwal');
        });
        Route::group(array('prefix' => 'monitoring-kesehatan'), function () {
            Route::post('get', 'Apiv1Controller@viewAddFormKesehatan');
            Route::post('delete', 'Apiv1Controller@deletemonitoringkesehatan');
            Route::post('post', 'Apiv1Controller@postmonitoringkesehatan');
            Route::post('getdataform', 'Apiv1Controller@getdatamonitoringkesehatan');
            Route::post('view/{id}', 'Apiv1Controller@viewDetailFormKesehatan');
            Route::post('view-siswa/{id}', 'Apiv1Controller@viewDetailRekapKesehatan');
            Route::post('kelas-all/get', 'Apiv1Controller@actionGetKelasAll');

        });

        Route::group(array('prefix' => 'monitoring-kelas-kosong'), function () {
            Route::post('get', 'Apiv1Controller@actionGetMonitoringKelasKosong');
        });

        Route::group(array('prefix' => 'rekap-monitoring-kelas-kosong'), function () {
            Route::post('get', 'Apiv1Controller@actionGetRekapMonitoringKelasKosong');
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
            Route::post('get', 'Apiv1Controller@geteditprofile');
            Route::post('submit', 'Apiv1Controller@submiteditprofile');
        });

        Route::post('semester/get', 'Apiv1Controller@actionGetSemester');
        Route::post('kota/get', 'Apiv1Controller@actionGetKota');
        Route::post('kelas-all/get', 'Apiv1Controller@actionGetKelasAll');

        Route::group(array('prefix' => 'monitoring-kelas-kosong'), function () {
            Route::post('get', 'Apiv1Controller@actionGetMonitoringKelasKosong');
        });

        Route::group(array('prefix' => 'input-jadwal'), function () {
            Route::post('get', 'Apiv1Controller@actionGetInputJadwal');
            Route::post('detail/get', 'Apiv1Controller@actionGetDetailInputJadwal');
            Route::post('{mode}/submit', 'Apiv1Controller@actionInputJadwal');
        });

        Route::group(array('prefix' => 'rekap-monitoring-kelas-kosong'), function () {
            Route::post('get', 'Apiv1Controller@actionGetRekapMonitoringKelasKosong');
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

    Route::group(array('prefix' => 'wali-murid'), function () {
        Route::group(array('prefix' => 'data-pribadi'), function () {
            Route::post('get', 'Apiv1Controller@geteditprofile');
            Route::post('submit', 'Apiv1Controller@submiteditprofile');
        });

        Route::post('siswa/get', 'Apiv1Controller@actionGetWaliMuridSiswa');
        Route::post('semester/get', 'Apiv1Controller@actionGetSemester');
        Route::post('kota/get', 'Apiv1Controller@actionGetKota');
        Route::post('kelas-all/get', 'Apiv1Controller@actionGetKelasAll');

        Route::group(array('prefix' => 'beasiswa'), function () {
            Route::post('get', 'Apiv1Controller@actionGetBeasiswa');
        });

        Route::group(array('prefix' => 'prestasi'), function () {
            Route::post('get', 'Apiv1Controller@actionGetPrestasi');
        });

        Route::group(array('prefix' => 'tagihan'), function () {
            Route::post('get', 'Apiv1Controller@actionGetTagihan');
        });

        Route::group(array('prefix' => 'riwayat-bayar'), function () {
            Route::post('get', 'Apiv1Controller@actionGetRiwayatBayar');
        });

        Route::group(array('prefix' => 'pelanggaran-kbm'), function () {
            Route::post('get', 'Apiv1Controller@actionGetPelanggaranKBM');
        });

        Route::group(array('prefix' => 'pelanggaran-non-kbm'), function () {
            Route::post('get', 'Apiv1Controller@actionGetPelanggaranNonKBM');
        });

        Route::group(array('prefix' => 'magang'), function () {
            Route::post('get', 'Apiv1Controller@actionGetMagang');
        });

        Route::group(array('prefix' => 'kalender-akademik'), function () {
            Route::post('get', 'Apiv1Controller@actionGetKalenderAkademik');
        });

        Route::post('jadwal/get', 'Apiv1Controller@actionGetJadwalWaliMurid');
    });

    Route::group(array('prefix' => 'siswa'), function () {
        Route::group(array('prefix' => 'data-pribadi'), function () {
            Route::post('get', 'Apiv1Controller@geteditprofile');
            Route::post('submit', 'Apiv1Controller@submiteditprofile');
        });

        Route::post('semester/get', 'Apiv1Controller@actionGetSemester');
        Route::post('kota/get', 'Apiv1Controller@actionGetKota');
        Route::post('kelas-all/get', 'Apiv1Controller@actionGetKelasAll');

        Route::post('jadwal/get', 'Apiv1Controller@actionGetJadwalSiswa');

        Route::group(array('prefix' => 'kalender-akademik'), function () {
            Route::post('get', 'Apiv1Controller@actionGetKalenderAkademikSiswa');
        });

        Route::group(array('prefix' => 'magang'), function () {
            Route::post('get', 'Apiv1Controller@actionGetMagangSiswa');
        });

        Route::group(array('prefix' => 'riwayat-bayar'), function () {
            Route::post('get', 'Apiv1Controller@actionGetRiwayatBayarSiswa');
        });

        Route::group(array('prefix' => 'tagihan'), function () {
            Route::post('get', 'Apiv1Controller@actionGetTagihanSiswa');
        });

        Route::group(array('prefix' => 'pelanggaran-kbm'), function () {
            Route::post('get', 'Apiv1Controller@actionGetPelanggaranKBMSiswa');
        });

        Route::group(array('prefix' => 'pelanggaran-non-kbm'), function () {
            Route::post('get', 'Apiv1Controller@actionGetPelanggaranNonKBMSiswa');
        });

        Route::group(array('prefix' => 'komplain-sarpras'), function () {
            Route::post('ruangan/detail', 'Apiv1Controller@actionGetKomplainRuanganSiswa');
            Route::post('buku-alat/detail', 'Apiv1Controller@actionGetKomplainBukuAlatSiswa');
            Route::post('{mode}/submit', 'Apiv1Controller@actionKomplainSarprasSiswa');
        });

        Route::group(array('prefix' => 'beasiswa'), function () {
            Route::post('get', 'Apiv1Controller@actionGetBeasiswaSiswa');
        });

        Route::group(array('prefix' => 'prestasi'), function () {
            Route::post('get', 'Apiv1Controller@actionGetPrestasiSiswa');
        });

        Route::post('ruangan/get', 'Apiv1Controller@actionGetRuangan');
        Route::post('inventaris-ruangan/get', 'Apiv1Controller@actionGetInventarisRuangan');
        Route::post('buku-alat/get', 'Apiv1Controller@actionGetBukuAlat');
    });
});
