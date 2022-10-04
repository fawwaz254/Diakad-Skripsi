<?php

// use Illuminate\Http\Request;

use App\Http\Controllers\Api\v2\KeuanganController;
use App\Http\Controllers\Apiv1Controller;

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

// Route::get('testing', function(){
//     header("Cache-Control: no-cache");
//     header("Content-Type: text/event-stream");

//     // $counter = rand(1, 10);
//     while (true) {
//         $counter = rand(1, 10);
//         // Every second, send a "ping" event.

//         // echo "event: ping\n";
//         $curDate = date(DATE_ISO8601);
//         // echo 'data: {"time": "' . $curDate . '"}';
//         // echo "\n\n";

//         // Send a simple message at random intervals.

//         // $counter--;

//         $sekolah = new Sekolah;
//         $sekolah->id_sekolah = $counter . $curDate;
//         $sekolah->nm_sekolah = $curDate;
//         $sekolah->save();
//         // if (!$counter) {
//             echo 'data: This is a message at time ' . $curDate . "\n\n";
//         //     $counter = rand(1, 10);
//         // }

//         ob_end_flush();
//         flush();

//         // Break the loop if the client aborted the connection (closed the page)

//         if ( connection_aborted() ) break;

//         sleep(1);
//     }
// });
Route::post('v1/signin', [Apiv1Controller::class, 'actionSignIn']);

Route::middleware(['auth.mobile'])->group(function () {
    Route::prefix('v2')->group(function () {
        Route::post('get-tagihan/by', [KeuanganController::class, 'getTagihanBy']);
    });
});

Route::middleware(['auth.mobile'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('token')->group(function () {
            Route::post('save', [Apiv1Controller::class, 'actionSaveToken']);
        });
        Route::post('notifikasi/read', [Apiv1Controller::class, 'actionNotifikasiUpdate']);
        Route::post('notifikasi/get', [Apiv1Controller::class, 'actionGetNotifikasi']);

        Route::prefix('guru')->group(function () {
            Route::prefix('data-pribadi')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'geteditprofile']);
                Route::post('submit', [Apiv1Controller::class, 'submiteditprofile']);
            });

            Route::post('kota/get', [Apiv1Controller::class, 'actionGetKota']);
            Route::post('kelas-all/get', [Apiv1Controller::class, 'actionGetKelasAll']);
            Route::post('kelas-kbm/get', [Apiv1Controller::class, 'actionGetKelasKBM']);
            Route::post('siswa-by-kelas-kbm/get', [Apiv1Controller::class, 'actionGetSiswaByJadwalKelasKBM']);
            Route::post('pertemuan-kelas-kbm/get', [Apiv1Controller::class, 'actionGetPertemuanByJadwalKelasKBM']);

            Route::post('kelas-uts/get', [Apiv1Controller::class, 'actionGetKelasUTS']);
            Route::post('kelas-uas/get', [Apiv1Controller::class, 'actionGetKelasUAS']);

            Route::prefix('presensi-kbm')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetPresensiKBM']);
                Route::post('submit', [Apiv1Controller::class, 'actionAbsensiKBMSiswa']);
            });

            Route::prefix('presensi-uts')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetPresensiUjian']);
                Route::post('submit', [Apiv1Controller::class, 'actionAbsensiUjianSiswa']);
            });
            Route::prefix('presensi-uas')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetPresensiUjian']);
                Route::post('submit', [Apiv1Controller::class, 'actionAbsensiUjianSiswa']);
            });

            Route::post('semester/get', [Apiv1Controller::class, 'actionGetSemester']);
            Route::post('jadwal/get', [Apiv1Controller::class, 'actionGetJadwal']);

            Route::post('ruangan/get', [Apiv1Controller::class, 'actionGetRuangan']);
            Route::post('inventaris-ruangan/get', [Apiv1Controller::class, 'actionGetInventarisRuangan']);
            Route::post('buku-alat/get', [Apiv1Controller::class, 'actionGetBukuAlat']);

            Route::prefix('komplain-sarpras')->group(function () {
                Route::post('ruangan/detail', [Apiv1Controller::class, 'actionGetKomplainRuangan']);
                Route::post('buku-alat/detail', [Apiv1Controller::class, 'actionGetKomplainBukuAlat']);

                Route::post('{mode}/submit', [Apiv1Controller::class, 'actionKomplainSarpras']);
            });
            Route::prefix('pelanggaran-siswa')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetPelanggaranSiswa']);
                Route::post('kategori/get', [Apiv1Controller::class, 'actionGetKategoriPelanggaranSiswa']);
                Route::post('subkategori/get', [Apiv1Controller::class, 'actionGetSubkategoriPelanggaranSiswa']);
                Route::post('{mode}/submit', [Apiv1Controller::class, 'actionPelanggaranSiswa']);
            });

            Route::prefix('input-jadwal')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetInputJadwal']);
                Route::post('detail/get', [Apiv1Controller::class, 'actionGetDetailInputJadwal']);
                Route::post('{mode}/submit', [Apiv1Controller::class, 'actionInputJadwal']);
            });

            Route::prefix('monitoring-kesehatan')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'viewAddFormKesehatan']);
                Route::post('delete', [Apiv1Controller::class, 'deletemonitoringkesehatan']);
                Route::post('post', [Apiv1Controller::class, 'postmonitoringkesehatan']);
                Route::post('getdataform', [Apiv1Controller::class, 'getdatamonitoringkesehatan']);
                Route::post('view/{id}', [Apiv1Controller::class, 'viewDetailFormKesehatan']);
                Route::post('view-siswa/{id}', [Apiv1Controller::class, 'viewDetailRekapKesehatan']);
                Route::post('kelas-all/get', [Apiv1Controller::class, 'actionGetKelasAll']);
            });
            Route::prefix('monitoring-kelas-kosong')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetMonitoringKelasKosong']);
            });

            Route::prefix('rekap-monitoring-kelas-kosong')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetRekapMonitoringKelasKosong']);
            });

            Route::prefix('rekap-absen')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetRekapAbsen']);
            });

            Route::prefix('absensi-harian')->group(function () {
                Route::post('kelas/get', [Apiv1Controller::class, 'actionGetAbsensiHarianKelas']);
                Route::post('siswa/get', [Apiv1Controller::class, 'actionGetAbsensiHarianSiswa']);

                Route::post('{mode}/submit', [Apiv1Controller::class, 'actionGetAbsensiHarianSave']);
            });
        });

        Route::prefix('tendik')->group(function () {
            Route::prefix('data-pribadi')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'geteditprofile']);
                Route::post('submit', [Apiv1Controller::class, 'submiteditprofile']);
            });

            Route::post('semester/get', [Apiv1Controller::class, 'actionGetSemester']);
            Route::post('kota/get', [Apiv1Controller::class, 'actionGetKota']);
            Route::post('kelas-all/get', [Apiv1Controller::class, 'actionGetKelasAll']);

            Route::prefix('monitoring-kelas-kosong')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetMonitoringKelasKosong']);
            });

            Route::prefix('input-jadwal')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetInputJadwal']);
                Route::post('detail/get', [Apiv1Controller::class, 'actionGetDetailInputJadwal']);
                Route::post('{mode}/submit', [Apiv1Controller::class, 'actionInputJadwal']);
            });

            Route::prefix('rekap-monitoring-kelas-kosong')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetRekapMonitoringKelasKosong']);
            });

            Route::prefix('rekap-absen')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetRekapAbsen']);
            });

            Route::prefix('absensi-harian')->group(function () {
                Route::post('kelas/get', [Apiv1Controller::class, 'actionGetAbsensiHarianKelas']);
                Route::post('siswa/get', [Apiv1Controller::class, 'actionGetAbsensiHarianSiswa']);

                Route::post('{mode}/submit', [Apiv1Controller::class, 'actionGetAbsensiHarianSave']);
            });
        });


        Route::prefix('wali-murid')->group(function () {

            Route::prefix('data-pribadi')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'geteditprofile']);
                Route::post('submit', [Apiv1Controller::class, 'submiteditprofile']);
            });
            Route::post('siswa/get', [Apiv1Controller::class, 'actionGetWaliMuridSiswa']);
            Route::post('semester/get', [Apiv1Controller::class, 'actionGetSemester']);
            Route::post('kota/get', [Apiv1Controller::class, 'actionGetKota']);
            Route::post('kelas-all/get', [Apiv1Controller::class, 'actionGetKelasAll']);

            Route::prefix('beasiswa')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetBeasiswa']);
            });

            Route::prefix('prestasi')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetPrestasi']);
            });

            Route::prefix('tagihan')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetTagihan']);
            });

            Route::prefix('riwayat-bayar')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetRiwayatBayar']);
            });

            Route::prefix('pelanggaran-kbm')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetPelanggaranKBM']);
            });

            Route::prefix('pelanggaran-non-kbm')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetPelanggaranNonKBM']);
            });

            Route::prefix('magang')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetMagang']);
            });
            Route::prefix('kalender-akademik')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetKalenderAkademik']);
            });

            Route::post('jadwal/get', [Apiv1Controller::class, 'actionGetJadwalWaliMurid']);
        });

        Route::prefix('siswa')->group(function () {
            Route::prefix('data-pribadi')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'geteditprofile']);
                Route::post('submit', [Apiv1Controller::class, 'submiteditprofile']);
            });

            Route::post('semester/get', [Apiv1Controller::class, 'actionGetSemester']);
            Route::post('kota/get', [Apiv1Controller::class, 'actionGetKota']);
            Route::post('kelas-all/get', [Apiv1Controller::class, 'actionGetKelasAll']);

            Route::post('jadwal/get', [Apiv1Controller::class, 'actionGetJadwalSiswa']);

            Route::prefix('kalender-akademik')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetKalenderAkademikSiswa']);
            });

            Route::prefix('magang')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetMagangSiswa']);
            });

            Route::prefix('riwayat-bayar')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetRiwayatBayarSiswa']);
            });

            Route::prefix('tagihan')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetTagihanSiswa']);
            });

            Route::prefix('pelanggaran-kbm')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetPelanggaranKBMSiswa']);
            });

            Route::prefix('pelanggaran-non-kbm')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetPelanggaranNonKBMSiswa']);
            });

            Route::prefix('komplain-sarpras')->group(function () {
                Route::post('ruangan/detail', [Apiv1Controller::class, 'actionGetKomplainRuanganSiswa']);
                Route::post('buku-alat/detail', [Apiv1Controller::class, 'actionGetKomplainBukuAlatSiswa']);
                Route::post('{mode}/submit', [Apiv1Controller::class, 'actionKomplainSarprasSiswa']);
            });

            Route::prefix('beasiswa')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetBeasiswaSiswa']);
            });

            Route::prefix('prestasi')->group(function () {
                Route::post('get', [Apiv1Controller::class, 'actionGetPrestasiSiswa']);
            });

            Route::post('ruangan/get', [Apiv1Controller::class, 'actionGetRuangan']);
            Route::post('inventaris-ruangan/get', [Apiv1Controller::class, 'actionGetInventarisRuangan']);
            Route::post('buku-alat/get', [Apiv1Controller::class, 'actionGetBukuAlat']);
        });
    });
});
