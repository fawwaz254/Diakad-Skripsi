<?php

use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Setting;
use App\Models\Pengguna;
use App\Models\TagihanBiaya;
use App\Models\WhatsappGroup;
use App\Models\PresensiPengguna;
use App\Models\ManajemenHariLibur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\WaNotifKehadiranSiswa;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FeaturemenuController;
use App\Http\Controllers\Administrator\WelcomeController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Administrator\Device\FingerprintController;
use App\Http\Controllers\Administrator\PengelolaanAkun\GuruController;
use App\Http\Controllers\Administrator\PengelolaanAkun\SiswaController;
use App\Http\Controllers\Administrator\PengelolaanAkun\TendikController;
use App\Http\Controllers\Administrator\Notification\NotificationController;
use App\Http\Controllers\Administrator\PengelolaanAkun\PencarianController;
use App\Http\Controllers\Administrator\Device\FingerprintRealtimeController;
use App\Http\Controllers\Administrator\JurnalPimpinan\JurnalPimpinanController;
use App\Http\Controllers\Administrator\ManajemenMenu\SettingDashboardController;
use App\Http\Controllers\Administrator\JurnalPimpinan\JenisKategoriJurnalPimpinanController;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('administrator')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);
        Route::get('/resetPassword/{username}', function ($username) {
            $pengguna = Pengguna::where('username', $username)->first();
            if ($pengguna) {
                $pengguna->password = Hash::make($pengguna->username);
                $pengguna->must_change_password = 1;
                $pengguna->save();
                return 'OK';
            } else {
                return 'fail';
            }
        });

        Route::get('/test', function () {
            try {
                $url = env('WHATSAPP_API_SEND');
                if (empty($url)) {
                    return;
                }

                $now = '2023-08-18';
                $hari_libur = ManajemenHariLibur::where('date', $now)->exists();

                if ($hari_libur) {
                    return;
                }

                $list_kelas = Kelas::has('whatsapp_group')->with('whatsapp_group')->get();
                $kelas = [];
                foreach ($list_kelas as $item) {
                    $nm_kelas = $item->nm_kelas;
                    $id_group = $item->whatsapp_group->id_group;
                    $kelas[$nm_kelas] = $id_group;
                }

                $nama_sekolah = Sekolah::value('nm_sekolah');
                $mode = Setting::where('key_setting', 'mode_notif_kehadiran_siswa')->value('value');

                if ($mode === 'PRESENT_ONLY' || $mode === 'ALL') {
                    $list_presensi_pengguna_group = PresensiPengguna::with('pengguna.siswa.wali_murid', 'pengguna.siswa.kelas')
                        ->whereHas('pengguna.siswa.wali_murid', function ($q) {
                            $q->whereNotNull('nomor_hp_wali_murid');
                        })
                        ->where('status_join_table', 3)
                        ->where('date', $now)
                        ->get()
                        ->groupBy('pengguna.siswa.kelas.nm_kelas');

                    foreach ($list_presensi_pengguna_group as $key => $list_presensi_pengguna) {
                        if (!isset($kelas[$key])) {
                            continue;
                        }

                        $siswa_kelas = [];
                        foreach ($list_presensi_pengguna as $presensi_pengguna) {
                            $siswa_kelas[] = $presensi_pengguna->pengguna->nm_pengguna . ' || Masuk: ' . $presensi_pengguna->check_in;
                        };

                        $message = join("\n -------------------------------------------------------------------------------- \n", $siswa_kelas);
                        $message = "*Notifikasi Kehadiran Siswa Harian*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami dengan senang hati memberitahukan kehadiran putra/putri Anda di sekolah hari ini, " . now()->translatedFormat('l, d F Y') . "\n\n\n" . $message;
                        $message .= "\n\n\nJika Anda memiliki pertanyaan terkait kesiswaan atau informasi lainnya, jangan ragu untuk menghubungi kami.\n\nTerima kasih atas perhatian dan kerjasama Anda.\n\n\nSalam,\n*Kesiswaan " . $nama_sekolah . "*";

                        $data = [
                            'message' => $message,
                            'group_id' => $kelas[$key],
                        ];

                        $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                            ->post($url, $data);

                        $response_data = $response->json();

                        if ($response_data['response'] == 'Device Bot Logged Out') {
                            \Log::info("Notification Warning: Failed to send notification, Device bot logged out");
                        } else {
                            $notif_kehadiran = new WaNotifKehadiranSiswa();
                            $notif_kehadiran->id_notif = strtotime($now) . uniqid();
                            $notif_kehadiran->id_siswa = $presensi_pengguna->pengguna->siswa->id_siswa;
                            // $notif_kehadiran->save();

                            \Log::info("Notification Success: Notification attendance sent at " . now());
                        }

                        sleep(rand(5, 20));
                    }
                }

                if ($mode === 'ABSENT_ONLY' || $mode === 'ALL') {
                    $id_pengguna_hadir = PresensiPengguna::where('status_join_table', 3)
                        ->where('date', $now)
                        ->pluck('id_pengguna')
                        ->toArray();

                    $list_siswa_group = Siswa::with([
                        'kelas',
                        'pengguna.presensi_pengguna',
                        'wali_murid',
                        'pengguna.shiftPengguna' => function ($q) use ($now) {
                            $q->where('date', $now)->with('shift_master');
                        }
                    ])
                        ->whereHas('pengguna', function ($q) use ($id_pengguna_hadir) {
                            $q->where('status_join_table', 3)->whereNotIn('pengguna.id_pengguna', $id_pengguna_hadir);
                        })
                        ->whereHas('pengguna.status_pengguna', function ($q) {
                            $q->where('aktif_status_pengguna', 1)->where('nm_status_pengguna', 'AKTIF');
                        })
                        ->whereHas('wali_murid', function ($q) {
                            $q->whereNotNull('nomor_hp_wali_murid');
                        })
                        ->whereNotNull('id_kelas')
                        ->get()
                        ->groupBy('kelas.nm_kelas');

                    foreach ($list_siswa_group as $key => $list_siswa) {
                        if (!isset($kelas[$key])) {
                            continue;
                        }

                        $siswa_kelas = [];
                        foreach ($list_siswa as $siswa) {
                            $siswa_kelas[] = $siswa->pengguna->nm_pengguna;
                        };

                        $message = join("\n -------------------------------------------------------------------------------- \n", $siswa_kelas);
                        $message = "*Notifikasi Ketidakhadiran Siswa Harian*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami dengan berat hati memberitahukan ketidakhadiran putra/putri Anda di sekolah hari ini, " . now()->translatedFormat('l, d F Y') . "\n\n\n" . $message;
                        $message .= "\n\n\nJika Anda memiliki pertanyaan terkait kesiswaan atau informasi lainnya, jangan ragu untuk menghubungi kami.\n\nTerima kasih atas perhatian dan kerjasama Anda.\n\n\nSalam,\n*Kesiswaan " . $nama_sekolah . "*";

                        $data = [
                            'message' => $message,
                            'group_id' => $kelas[$key],
                        ];

                        $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                            ->post($url, $data);

                        $response_data = $response->json();

                        if ($response_data['response'] == 'Device Bot Logged Out') {
                            \Log::info("Notification Warning: Failed to send notification, Device bot logged out");
                        } else {
                            $notif_kehadiran = new WaNotifKehadiranSiswa();
                            $notif_kehadiran->id_notif = strtotime($now) . uniqid();
                            $notif_kehadiran->id_siswa = $siswa->id_siswa;
                            // $notif_kehadiran->save();

                            \Log::info("Notification Success: Notification attendance sent at " . now());
                        }

                        sleep(rand(5, 20));
                    }
                }
            } catch (\Exception $e) {
                \Log::info("Notification Error: " . $e->getMessage());
            }
        });



        Route::get('/report-pimpinan', [ReportController::class, 'viewAllDiakad'])->name('report.pimpinan');
        Route::get('/report-wali-kelas', [ReportController::class, 'viewReportWaliKelas'])->name('report.walikelas');
        Route::get('/report-guru', [ReportController::class, 'viewReportGuru'])->name('report.guru');

        Route::prefix('device')->group(function () {
            Route::prefix('fingerprint')->group(function () {
                Route::get('/', [FingerprintController::class, 'indexList']);
                Route::get('/datatables', [FingerprintController::class, 'commonList']);
            });
            // Route::prefix('fingerprintRealTime')->group(function () {
            //     Route::get('/', [FingerprintRealtimeController::class, 'viewFingerprintRealtime']);
            //     Route::get('/datatables', [FingerprintRealtimeController::class, 'datatableFingerprintRealtime']);
            //     Route::post('/getData', [FingerprintRealtimeController::class, 'getDataFingerprintRealtime']);
            //     Route::post('/syncData', [FingerprintRealtimeController::class, 'syncDataFingerprintRealtime']);
            // });
        });

        Route::prefix('notification')->group(function () {
            // Route::get('/', [NotificationController::class, 'indexList']);
            // Route::get('/datatables', [NotificationController::class, 'commonList']);
            Route::get('/send', [NotificationController::class, 'send']);

            Route::get('/whatsapp', [NotificationController::class, 'viewWhatsappGroup']);
            Route::view('/whatsapp/scan', 'administrator.notification.view-whatsapp-scan');
            Route::post('/whatsapp/group', [NotificationController::class, 'fetchWhatsappGroup']);
            Route::post('/whatsapp/group/{mode}', [NotificationController::class, 'actionWhatsappGroup']);
        });



        Route::prefix('manajemen-file')->group(function () {
            Route::prefix('data-kategori')->group(function () {
                Route::get('/', [DataKategoriController::class, 'viewDataKategori']);
                Route::get('/datatables', [DataKategoriController::class, 'datatablesCategoryfile']);
            });
            Route::prefix('data-sub-kategori')->group(function () {
                Route::get('/', [SubDataKategoriController::class, 'viewSubDataKategori']);
                Route::get('/add', [SubDataKategoriController::class, 'addSubDataKategori']);
                Route::get('/datatables', [SubDataKategoriController::class, 'datatablesSubCategoryfile']);
                Route::get('/edit/{id}', [SubDataKategoriController::class, 'editSubDataKategori']);

                //action input sub data kategori
                Route::post('action-data-sub-kategori/{mode}/{id}', [SubDataKategoriController::class, 'actionSubDataKategori']);
            });
            Route::prefix('data-file')->group(function () {
                Route::get('/', [DataFileController::class, 'viewDataFile']);
                Route::get('add', [DataFileController::class, 'addDataFile']);
                Route::get('category/{category_file_id}', [DataFileController::class, 'viewDataFileCategory']);
                Route::get('dropdown-category', [DataFileController::class, 'dropdownCategory']);
                Route::get('sub-category/{sub_category_file_id}', [DataFileController::class, 'viewDataFileSubCategory']);

                Route::post('action-data-file/{mode}/{id}', [DataFileController::class, 'actionDataFile']);
                Route::get('download/{id}', [DataFileController::class, 'downloadDataFile']);
            });
        });

        Route::prefix('pengelolaan-akun')->group(function () {
            Route::get('pencarian', [PencarianController::class, 'viewPencarian']);
            Route::post('post-view-pencarian', [PencarianController::class, 'actionViewPencarian']);
            Route::get('pencarian/view-detail/{username_nama_cari}', [PencarianController::class, 'viewDetailPencarian']);
            Route::get('pencarian/datatables/{username_nama_cari}', [PencarianController::class, 'datatablesPencarian']);
            Route::get('pencarian/view-detail-pengguna/{id_pengguna}/{username_nama_cari}', [PencarianController::class, 'viewDetailPenggunaPencarian']);
            Route::get('pencarian/datatables-role/{id_pengguna}', [PencarianController::class, 'datatablesRolePencarian']);
            Route::get('pencarian/add-role-pengguna/{id_pengguna}/{username_nama_cari}', [PencarianController::class, 'addRolePenggunaPencarian']);

            Route::post('action-pencarian/{mode}/{id}', [PencarianController::class, 'actionPencarian']);

            Route::post('reset-some-password', [PencarianController::class, 'resetPasswordCollection']);

            // MENU Tenaga Pendidik
            // url: /administrator/pengelolaan-akun/tendik
            Route::get('tendik', [TendikController::class, 'viewDetailTendik']);
            Route::post('post-view-tendik', [TendikController::class, 'actionViewTendik']);
            Route::get('tendik/view-detail/{id_role}', [TendikController::class, 'viewDetailTendik']);
            Route::get('tendik/datatables/{id_role}', [TendikController::class, 'datatablesTendik']);

            // MENU Guru
            // url: /administrator/pengelolaan-akun/guru
            Route::get('guru', [GuruController::class, 'viewDetailGuru']);
            Route::post('post-view-guru', [GuruController::class, 'actionViewGuru']);
            Route::get('guru/view-detail/{id_role}', [GuruController::class, 'viewDetailGuru']);
            Route::get('guru/datatables/{id_role}', [GuruController::class, 'datatablesGuru']);

            // MENU Siswa
            // url: /administrator/pengelolaan-akun/siswa
            Route::get('siswa', [SiswaController::class, 'viewSiswa']);
            Route::post('post-view-siswa', [SiswaController::class, 'actionViewSiswa']);
            Route::get('siswa/view-detail/{id_kelas}', [SiswaController::class, 'viewDetailSiswa']);
            Route::get('siswa/datatables/{id_kelas}', [SiswaController::class, 'datatablesSiswa']);
        });

        //Jurnal pimpinan
        Route::prefix('jurnal-pimpinan')->group(function () {
            Route::prefix('tambah-jurnal-pimpinan')->group(function () {
                Route::get('/', [JurnalPimpinanController::class, 'viewSettingJurnalPimpinan']);
                Route::get('datatables', [JurnalPimpinanController::class, 'datatablesSettingJurnalPimpinan']);
                Route::get('add', [JurnalPimpinanController::class, 'addSettingJurnalPimpinan']);
                Route::get('edit/{id}', [JurnalPimpinanController::class, 'editSettingJurnalPimpinan']);
                Route::get('datatablesJurnalPimpinan', [JurnalPimpinanController::class, 'datatablesAddJurnalPimpinan']);

                Route::post('action-setting-jurnal-pimpinan/{mode}/{id}', [JurnalPimpinanController::class, 'actionSettingJurnalPimpinan']);
            });
            Route::prefix('jenis-jurnal-pimpinan')->group(function () {
                Route::get('/', [JenisKategoriJurnalPimpinanController::class, 'viewDataJenis']);
                Route::get('/datatables', [JenisKategoriJurnalPimpinanController::class, 'datatablesjenis']);
                // Route::get('/add', [JenisKategoriJurnalPimpinanController::class, 'addDataJenis']);
                Route::post('action-data-kategori/{mode}/{id}', [JenisKategoriJurnalPimpinanController::class, 'actionDataJenis']);
                Route::get('/import-excel', [JenisKategoriJurnalPimpinanController::class, 'importExcel']);
                Route::post('/import-excel', [JenisKategoriJurnalPimpinanController::class, 'importExcelAction']);
            });

            Route::group(array('prefix' => 'laporan-jurnal-pimpinan'), function () {
                Route::get('/', [JurnalPimpinanController::class, 'viewLaporanAllJurnalPimpinan']);
                Route::get('/datatables', [JurnalPimpinanController::class, 'datatablesLaporanJurnalPimpinan']);
                Route::get('preview-file/{id}', [JurnalPimpinanController::class, 'previewFile']);
                Route::get('download-file/{id}', [JurnalPimpinanController::class, 'downloadFile']);
            });
        });

        Route::prefix('manajemen-menu')->group(function () {
            // MENU Setting Dashboard
            // url: /administrator/manajemen-menu/setting-dashboard
            Route::get('setting-dashboard', [SettingDashboardController::class, 'viewSettingDashboard']);
            Route::post('post-view-setting-dashboard', [SettingDashboardController::class, 'actionViewSettingDashboard']);
            Route::get('setting-dashboard/view-detail/{id_role}', [SettingDashboardController::class, 'viewDetailSettingDashboard']);
            Route::post('setting-dashboard', [SettingDashboardController::class, 'actionSettingDashboard']);
            Route::get('setting-feature-guru', [FeaturemenuController::class, 'index']);
            Route::post('action-setting-feature-guru', [FeaturemenuController::class, 'save']);
        });
    });
});
