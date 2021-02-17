<?php
// ROLE GURU
Route::group(array('middleware'=> ['token_staff']), function () {
    Route::group(array('prefix' => 'guru'), function () {
        Route::get('welcome', 'Guru\WelcomeController@indexWelcome');

        /** ==== MODUL BIODATA ==== **/
        Route::group(array('prefix' => 'biodata'), function () {
            // MENU Data Pribadi
            Route::get('data-pribadi', 'Guru\Biodata\DataPribadiController@viewDataPribadi');
            Route::post('action-data-pribadi', 'Guru\Biodata\DataPribadiController@actionSaveDataPribadi');
        });

        /** ==== MODUL JADWAL ==== **/
        Route::group(array('prefix' => 'jadwal'), function () {
            // MENU Kalender Akademik
            Route::get('kalender-akademik', 'Guru\Jadwal\KalenderAkademikController@viewKalenderAkademik');
            Route::get('kalender-akademik/datatables', 'Guru\Jadwal\KalenderAkademikController@datatablesKalenderAkademik');

            // MENU Jadwal KBM
            Route::get('jadwal-kbm', 'Guru\Jadwal\JadwalKBMController@viewJadwalKBM');
            Route::get('jadwal-kbm/datatables', 'Guru\Jadwal\JadwalKBMController@datatablesJadwalKBM');

            // MENU Jadwal Ujian
            Route::get('jadwal-ujian', 'Guru\Jadwal\JadwalUjianController@viewJadwalUjian');
            Route::get('jadwal-ujian/datatables-uts', 'Guru\Jadwal\JadwalUjianController@datatablesJadwalUTS');
            Route::get('jadwal-ujian/datatables-uas', 'Guru\Jadwal\JadwalUjianController@datatablesJadwalUAS');



            /*Route::get('usulan-mata-ajar/view-semester-usulan-mata-ajar/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@viewSemesterUsulanMataAjar');
            Route::get('usulan-mata-ajar/datatables/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@datatablesUsulanMataAjar');
            Route::get('usulan-mata-ajar/edit/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@editUsulanMataAjar');

            Route::post('action-usulan-mata-ajar/{mode}/{id}', 'Akademik\AktivitasSemester\UsulanMataAjarController@actionUsulanMataAjar');*/
            // MENU Input Jadwal
            Route::get('input-jadwal', 'Guru\Jadwal\InputJadwalController@viewInputJadwal');
            Route::get('input-jadwal/datatables/{id}', 'Guru\Jadwal\InputJadwalController@datatablesInputJadwal');
            Route::get('input-jadwal/edit/{id}', 'Guru\Jadwal\InputJadwalController@editInputJadwal');

            Route::post('action-input-jadwal/{mode}/{id}', 'Guru\Jadwal\InputJadwalController@actionInputJadwal');
        });

        /** ==== MODUL PRESENSI ==== **/
        Route::group(array('prefix' => 'presensi'), function () {
            // MENU Absensi Siswa
            Route::get('absensi-siswa', 'Guru\Presensi\AbsensiSiswaController@viewAbsensiSiswa');

            Route::post('post-kbm-absensi-siswa', 'Guru\Presensi\AbsensiSiswaController@actionViewKBMAbsensiSiswa');
            Route::get('absensi-siswa/view-kbm/{id_kelas_mp}/{pertemuan_ke}', 'Guru\Presensi\AbsensiSiswaController@viewKBMAbsensiSiswa');
            Route::get('absensi-siswa/datatables-kbm/{id_kelas_mp}/{pertemuan_ke}', 'Guru\Presensi\AbsensiSiswaController@datatablesKBMAbsensiSiswa');

            Route::post('post-uts-absensi-siswa', 'Guru\Presensi\AbsensiSiswaController@actionViewUTSAbsensiSiswa');
            Route::get('absensi-siswa/view-uts/{id_ujian_mp}', 'Guru\Presensi\AbsensiSiswaController@viewUTSAbsensiSiswa');
            Route::get('absensi-siswa/datatables-uts/{id_ujian_mp}', 'Guru\Presensi\AbsensiSiswaController@datatablesUTSAbsensiSiswa');

            Route::post('post-uas-absensi-siswa', 'Guru\Presensi\AbsensiSiswaController@actionViewUASAbsensiSiswa');
            Route::get('absensi-siswa/view-uas/{id_ujian_mp}', 'Guru\Presensi\AbsensiSiswaController@viewUASAbsensiSiswa');
            Route::get('absensi-siswa/datatables-uas/{id_ujian_mp}', 'Guru\Presensi\AbsensiSiswaController@datatablesUASAbsensiSiswa');

            Route::post('action-absensi-siswa/{mode}/{id}', 'Guru\Presensi\AbsensiSiswaController@actionAbsensiSiswa');
            Route::post('action-absensi-siswa/{mode}/{id}/{pertemuan_ke}', 'Guru\Presensi\AbsensiSiswaController@actionAbsensiSiswa');

            // AJAX GET PERTEMUAN BY KELAS_MP
            Route::post('pertemuan-byjadwalkelasmp', 'Guru\Presensi\AbsensiSiswaController@ajaxGetPertemuanByJadwalKelasMp');

            // MENU Rekap Absen
            Route::get('rekap-absen', 'Guru\Presensi\RekapAbsenController@viewRekapAbsen');
            Route::post('post-kbm-rekap-absen', 'Guru\Presensi\RekapAbsenController@actionViewKBMRekapAbsen');
            Route::get('rekap-absen/view-kbm/{id_jadwal_kelas_mp}', 'Guru\Presensi\RekapAbsenController@viewKBMRekapAbsen');

            Route::get('rekap-absen/print/{id_jadwal_kelas_mp}', 'Guru\Presensi\RekapAbsenController@printKBMRekapAbsen');

            // MENU Absensi Tanpa Jadwal
            Route::get('absensi-tanpa-jadwal', 'Guru\Presensi\AbsensiTanpaJadwalController@viewAbsensiTanpaJadwal');
            Route::get('absensi-tanpa-jadwal/view-kbm/{id_guru}/{id_mata_pelajaran}/{id_kelas}/{opsi}', 'Guru\Presensi\AbsensiTanpaJadwalController@viewKBMAbsensiTanpaJadwal');
            Route::get('absensi-tanpa-jadwal/datatables-kbm/{id_guru}/{id_mata_pelajaran}/{id_kelas}', 'Guru\Presensi\AbsensiTanpaJadwalController@datatablesKBMAbsensiTanpaJadwal');

            Route::post('absensi-tanpa-jadwal/view-kbm', 'Guru\Presensi\AbsensiTanpaJadwalController@actionViewKBMAbsensiTanpaJadwal');
            Route::post('action-absensi-tanpa-jadwal/{mode}/{id_guru}/{id_mata_pelajaran}/{id_kelas}', 'Guru\Presensi\AbsensiTanpaJadwalController@actionAbsensiTanpaJadwal');

            // MENU Rekap Absen Tanpa Jadwal
            Route::get('rekap-absen-tanpa-jadwal', 'Guru\Presensi\RekapAbsenTanpaJadwalController@viewRekapAbsenTanpaJadwal');
            Route::post('post-kbm-rekap-absen-tanpa-jadwal', 'Guru\Presensi\RekapAbsenTanpaJadwalController@actionViewKBMRekapAbsenTanpaJadwal');
            Route::get('rekap-absen-tanpa-jadwal/view-kbm/{id_kelas_mp}', 'Guru\Presensi\RekapAbsenTanpaJadwalController@viewKBMRekapAbsenTanpaJadwal');

            Route::get('rekap-absen-tanpa-jadwal/print/{id_kelas_mp}', 'Guru\Presensi\RekapAbsenTanpaJadwalController@printKBMRekapAbsenTanpaJadwal');
        });

        /** ==== MODUL PENILAIAN ==== **/
        Route::group(array('prefix' => 'penilaian'), function () {
            // MENU Komponen Nilai
            Route::get('komponen-nilai', 'Guru\Penilaian\KomponenNilaiController@viewKomponenNilai');
            Route::post('post-view-komponen-nilai', 'Guru\Penilaian\KomponenNilaiController@actionViewKelasKomponenNilai');
            Route::get('komponen-nilai/view-kelas/{id_kelas_mp}', 'Guru\Penilaian\KomponenNilaiController@viewKelasKomponenNilai');
            Route::get('komponen-nilai/datatables/{id_kelas_mp}', 'Guru\Penilaian\KomponenNilaiController@datatablesKomponenNilai');
            Route::get('komponen-nilai/add/{id_kelas_mp}', 'Guru\Penilaian\KomponenNilaiController@addKomponenNilai');
            Route::get('komponen-nilai/edit/{id_kelas_mp}/{id}', 'Guru\Penilaian\KomponenNilaiController@editKomponenNilai');

            Route::post('action-komponen-nilai/{mode}/{id}', 'Guru\Penilaian\KomponenNilaiController@actionKomponenNilai');

                // MENU Sub Komponen Nilai
                Route::get('komponen-nilai/view-sub-komponen/{id_kelas_mp}/{id_komponen_mp}', 'Guru\Penilaian\KomponenNilaiController@viewKelasSubKomponenNilai');
                Route::get('komponen-nilai/datatables-subkomponen/{id_komponen_mp}', 'Guru\Penilaian\KomponenNilaiController@datatablesSubKomponenNilai');
                Route::get('komponen-nilai/add-sub-komponen/{id_kelas_mp}/{id_komponen_mp}', 'Guru\Penilaian\KomponenNilaiController@addSubKomponenNilai');
                Route::get('komponen-nilai/edit-sub-komponen/{id_kelas_mp}/{id_komponen_mp}/{id}', 'Guru\Penilaian\KomponenNilaiController@editSubKomponenNilai');

                Route::post('action-subkomponen-nilai/{mode}/{id?}', 'Guru\Penilaian\KomponenNilaiController@actionSubKomponenNilai');

            // MENU Input Nilai KBM/Try Out
            Route::get('input-nilai', 'Guru\Penilaian\InputNilaiController@viewInputNilai');
            Route::post('post-view-input-nilai', 'Guru\Penilaian\InputNilaiController@actionViewKelasInputNilai');
            Route::get('input-nilai/view-kelas/{id_kelas_mp}', 'Guru\Penilaian\InputNilaiController@viewKelasInputNilai');
            Route::get('input-nilai/datatables/{id_kelas_mp}', 'Guru\Penilaian\InputNilaiController@datatablesInputNilai');
            /*
            Route::post('post-view-input-tryout', 'Guru\Penilaian\InputNilaiController@actionViewKelasInputTryOut');
            Route::get('input-tryout/view-kelas/{id_kelas_mp}', 'Guru\Penilaian\InputNilaiController@viewKelasInputTryOut');
            Route::get('input-tryout/datatables/{id_kelas_mp}', 'Guru\Penilaian\InputNilaiController@datatablesInputTryOut');
            */

            Route::post('action-input-nilai/{mode}/{id}', 'Guru\Penilaian\InputNilaiController@actionInputNilai');

            // MENU Rekap Nilai
            Route::get('rekap-nilai', 'Guru\Penilaian\RekapNilaiController@viewRekapNilai');
            Route::get('rekap-nilai/detail/{id_kelas_mp}', 'Guru\Penilaian\RekapNilaiController@viewDetailRekapNilai');
            Route::get('rekap-nilai/print/{id_kelas_mp}', 'Guru\Penilaian\RekapNilaiController@printRekapNilai');
        });


        /** ==== MODUL PELANGGARAN SISWA ==== **/
        // alurnya berbeda dengan input pelanggaran yg lain (merujuk ke presensi_mp)
        Route::group(array('prefix' => 'pelanggaran-siswa'), function () {
            // MENU Input Pelanggaran Siswa
            Route::get('input-pelanggaran-mp', 'Guru\PelanggaranSiswa\InputPelanggaranController@viewInputPelanggaran');
            
            Route::post('post-input-pelanggaran-mp', 'Guru\PelanggaranSiswa\InputPelanggaranController@actionViewKBMInputPelanggaran');
            Route::get('input-pelanggaran-mp/view-kbm/{id_jadwal_kelas_mp}/{pertemuan_ke}', 'Guru\PelanggaranSiswa\InputPelanggaranController@viewKBMInputPelanggaran');
            Route::get('input-pelanggaran-mp/datatables/{id_presensi_mp}', 'Guru\PelanggaranSiswa\InputPelanggaranController@datatablesInputPelanggaran');
            Route::get('input-pelanggaran-mp/add/{id_presensi_mp}/{id_siswa}', 'Guru\PelanggaranSiswa\InputPelanggaranController@addInputPelanggaran');
            Route::get('input-pelanggaran-mp/edit/{id}', 'Guru\PelanggaranSiswa\InputPelanggaranController@editInputPelanggaran');
            
            Route::post('action-input-pelanggaran-mp/{mode}/{id}', 'Guru\PelanggaranSiswa\InputPelanggaranController@actionInputPelanggaran');
            Route::post('pertemuan-byjadwalkelasmp', 'Guru\PelanggaranSiswa\InputPelanggaranController@ajaxGetPertemuanByJadwalKelasMp');

            Route::get('rekap-input-pelanggaran-mp', 'Guru\PelanggaranSiswa\InputPelanggaranController@viewRekapInputPelanggaran');
            Route::get('rekap-input-pelanggaran-mp/datatables', 'Guru\PelanggaranSiswa\InputPelanggaranController@datatablesRekapInputPelanggaran');

            Route::post('subkategori-bykategori', 'Guru\PelanggaranSiswa\InputPelanggaranController@ajaxGetSubkategoriByKategori');

            // MENU Input Pelanggaran Siswa Non-KBM
            Route::get('input-pelanggaran', 'Guru\GuruPiket\InputPelanggaranController@viewInputPelanggaran');
            Route::get('input-pelanggaran/datatables', 'Guru\GuruPiket\InputPelanggaranController@datatablesInputPelanggaran');
            Route::get('input-pelanggaran/add', 'Guru\GuruPiket\InputPelanggaranController@addInputPelanggaran');
            Route::get('input-pelanggaran/edit/{id}', 'Guru\GuruPiket\InputPelanggaranController@editInputPelanggaran');

            Route::post('action-input-pelanggaran/{mode}/{id}', 'Guru\GuruPiket\InputPelanggaranController@actionInputPelanggaran');

            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', 'Guru\GuruPiket\InputPelanggaranController@ajaxGetSiswaByKelas');
        });

        /** ==== MODUL REWARD SISWA ==== **/
        Route::group(array('prefix' => 'reward-siswa'), function () {
            // MENU Input Pelanggaran Siswa
            Route::get('input-reward-siswa', 'Guru\RewardSiswa\InputRewardSiswaController@viewInputRewardSiswa');
            Route::post('post-input-reward-siswa', 'Guru\RewardSiswa\InputRewardSiswaController@actionViewInputRewardSiswa');
            Route::get('input-reward-siswa/view-kelas/{id_kelas}', 'Guru\RewardSiswa\InputRewardSiswaController@viewKelasInputRewardSiswa');
            Route::get('input-reward-siswa/add/{id_siswa}', 'Guru\RewardSiswa\InputRewardSiswaController@addInputRewardSiswa');
            Route::get('input-reward-siswa/edit/{id}', 'Guru\RewardSiswa\InputRewardSiswaController@editInputRewardSiswa');

            Route::get('input-reward-siswa/datatables/{id_kelas}', 'Guru\RewardSiswa\InputRewardSiswaController@datatablesInputRewardSiswa');
            Route::post('action-input-reward-siswa/{mode}/{id}', 'Guru\RewardSiswa\InputRewardSiswaController@actionInputRewardSiswa');

            Route::get('rekap-input-reward-siswa', 'Guru\RewardSiswa\InputRewardSiswaController@viewRekapInputRewardSiswa');
            Route::get('rekap-input-reward-siswa/datatables', 'Guru\RewardSiswa\InputRewardSiswaController@datatablesRekapInputRewardSiswa');
        });

        /** ==== MODUL SARANA PRASARANA ==== **/
        Route::group(array('prefix' => 'sarpras'), function () {
            // MENU Komplain Inventaris/Sarpras
            Route::get('komplain-sarpras', 'Guru\Sarpras\KomplainSarprasController@viewKomplainSarpras');

            Route::post('post-view-ruangan-sarpras', 'Guru\Sarpras\KomplainSarprasController@actionViewRuanganKomplainSarpras');
            Route::get('komplain-sarpras/ruangan-sarpras/view-ruangan/{id_ruangan}', 'Guru\Sarpras\KomplainSarprasController@viewRuanganKomplainSarpras');
            Route::get('komplain-sarpras/ruangan-sarpras/datatables/{id_ruangan}', 'Guru\Sarpras\KomplainSarprasController@datatablesRuanganKomplainSarpras');
            Route::get('komplain-sarpras/ruangan-sarpras/add/{id_ruangan}', 'Guru\Sarpras\KomplainSarprasController@addRuanganKomplainSarpras');
            Route::get('komplain-sarpras/ruangan-sarpras/edit/{id_ruangan}/{id}', 'Guru\Sarpras\KomplainSarprasController@editRuanganKomplainSarpras');

            Route::post('post-view-bukualat-sarpras', 'Guru\Sarpras\KomplainSarprasController@actionViewBukualatKomplainSarpras');
            Route::get('komplain-sarpras/bukualat-sarpras/view-bukualat/{id_buku_alat}', 'Guru\Sarpras\KomplainSarprasController@viewBukualatKomplainSarpras');
            Route::get('komplain-sarpras/bukualat-sarpras/datatables/{id_buku_alat}', 'Guru\Sarpras\KomplainSarprasController@datatablesBukualatKomplainSarpras');
            Route::get('komplain-sarpras/bukualat-sarpras/add/{id_buku_alat}', 'Guru\Sarpras\KomplainSarprasController@addBukualatKomplainSarpras');
            Route::get('komplain-sarpras/bukualat-sarpras/edit/{id_buku_alat}/{id}', 'Guru\Sarpras\KomplainSarprasController@editBukualatKomplainSarpras');

            Route::post('action-komplain-sarpras/{mode}/{id}', 'Guru\Sarpras\KomplainSarprasController@actionKomplainSarpras');

        });

        /** ==== MODUL GURU PIKET ==== **/
        Route::group(array('prefix' => 'guru-piket'), function () {
            // MENU Monitoring kelas kosong
            Route::get('monitoring-kelas-kosong', 'Guru\GuruPiket\MonitoringKelasKosongController@viewMonitoringKelasKosong');
            Route::get('monitoring-kelas-kosong/datatables', 'Guru\GuruPiket\MonitoringKelasKosongController@datatablesMonitoringKelasKosong');

            // MENU Monitoring kelas kosong
            Route::get('rekap-monitoring-kelas-kosong', 'Guru\GuruPiket\MonitoringKelasKosongController@viewRekapMonitoringKelasKosong');
            Route::get('rekap-monitoring-kelas-kosong/datatables', 'Guru\GuruPiket\MonitoringKelasKosongController@datatablesRekapMonitoringKelasKosong');

            // MENU Absensi Harian Siswa
            Route::get('absensi-harian-siswa', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewManageAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/manage/{id_semester}/{id_kelas}/{id_presensi_harian}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewManageAbsensiHarianSiswa');
            Route::get('absensi-harian-siswa/detail/{id_semester}/{id_kelas}/{tahun}/{id_bulan}', 'Guru\GuruPiket\AbsensiHarianSiswaController@viewDetailAbsensiHarianSiswa');
            
            Route::post('absensi-harian-siswa/datatables/{id_semester}/{id_kelas}', 'Guru\GuruPiket\AbsensiHarianSiswaController@datatablesAbsensiHarianSiswa');
            Route::post('absensi-harian-siswa/datatables-detail/{id_semester}/{id_kelas}/{id_presensi_harian}', 'Guru\GuruPiket\AbsensiHarianSiswaController@datatablesKelasAbsensiHariSiswa');
            Route::post('absensi-harian-siswa/action/{mode}', 'Guru\GuruPiket\AbsensiHarianSiswaController@actionAbsensiHarianSiswa');
            Route::post('absensi-harian-siswa/action/{mode}/{id}', 'Guru\GuruPiket\AbsensiHarianSiswaController@actionAbsensiHarianSiswa');

            // MENU Input Pelanggaran Siswa Non-KBM
            Route::get('input-pelanggaran', 'Guru\GuruPiket\InputPelanggaranController@viewInputPelanggaran');
            Route::get('input-pelanggaran/datatables', 'Guru\GuruPiket\InputPelanggaranController@datatablesInputPelanggaran');
            Route::get('input-pelanggaran/add', 'Guru\GuruPiket\InputPelanggaranController@addInputPelanggaran');
            Route::get('input-pelanggaran/edit/{id}', 'Guru\GuruPiket\InputPelanggaranController@editInputPelanggaran');

            Route::post('action-input-pelanggaran/{mode}/{id}', 'Guru\GuruPiket\InputPelanggaranController@actionInputPelanggaran');

            // AJAX GET SISWA BY KELAS
            Route::post('siswa-bykelas', 'Guru\GuruPiket\InputPelanggaranController@ajaxGetSiswaByKelas');

            // MENU Rekap Kesehatan Siswa
            Route::get('rekap-kesehatan', 'Guru\GuruPiket\RekapKesehatanController@viewRekapKesehatan');
            Route::get('rekap-kesehatan/user/{id}/{date}', 'Guru\WaliKelas\RekapKesehatanController@viewRekapKesehatanSiswa');
            Route::get('rekap-kesehatan/detail/form/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');
            
            Route::get('rekap-kesehatan/{id}', 'Guru\GuruPiket\RekapKesehatanController@viewDetailRekapKesehatan');
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}', 'Guru\GuruPiket\RekapKesehatanController@viewDetailRekapKesehatan');
            Route::get('rekap-kesehatan/{id}/{bulan}/{tahun}/download', 'Guru\GuruPiket\RekapKesehatanController@downloadDetailRekapKesehatan');
            
                
            Route::post('rekap-kesehatan/action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
            Route::post('rekap-kesehatan/datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');

            // MENU Rekap Absen Tanpa Jadwal
            Route::get('rekap-absen-tanpa-jadwal', 'Guru\GuruPiket\RekapAbsenTanpaJadwalController@viewRekapAbsenTanpaJadwal');
            Route::post('post-get-kbm-by-kelas', 'Guru\GuruPiket\RekapAbsenTanpaJadwalController@actionGetKBMByKelas');

            Route::post('post-kbm-rekap-absen-tanpa-jadwal', 'Guru\GuruPiket\RekapAbsenTanpaJadwalController@actionViewKBMRekapAbsenTanpaJadwal');
            Route::get('rekap-absen-tanpa-jadwal/view-kbm/{id_kelas_mp}', 'Guru\GuruPiket\RekapAbsenTanpaJadwalController@viewKBMRekapAbsenTanpaJadwal');

            Route::get('rekap-absen-tanpa-jadwal/print/{id_kelas_mp}', 'Guru\GuruPiket\RekapAbsenTanpaJadwalController@printKBMRekapAbsenTanpaJadwal');
        });

        /** ==== MODUL WALI KELAS ==== **/
        Route::group(array('prefix' => 'wali-kelas'), function () {
            // MENU Data Inventaris Kelas/Sarana
            Route::get('inventaris-kelas', 'Guru\WaliKelas\InventarisKelasController@viewInventarisKelas');
            Route::get('inventaris-kelas/datatables', 'Guru\WaliKelas\InventarisKelasController@datatablesInventarisKelas');

            // MENU Input Pelanggaran Siswa
            Route::get('input-pelanggaran', 'Guru\WaliKelas\InputPelanggaranController@viewInputPelanggaran');
            Route::get('input-pelanggaran/datatables', 'Guru\WaliKelas\InputPelanggaranController@datatablesInputPelanggaran');
            Route::get('input-pelanggaran/add', 'Guru\WaliKelas\InputPelanggaranController@addInputPelanggaran');
            Route::get('input-pelanggaran/edit/{id}', 'Guru\WaliKelas\InputPelanggaranController@editInputPelanggaran');

            Route::post('action-input-pelanggaran/{mode}/{id}', 'Guru\WaliKelas\InputPelanggaranController@actionInputPelanggaran');

            // MENU Rekap Absensi Kelas
            Route::get('rekap-absensi-kelas', 'Guru\WaliKelas\RekapAbsensiKelasController@viewRekapAbsensiKelas');
            Route::post('post-rekap-absensi-kelas', 'Guru\WaliKelas\RekapAbsensiKelasController@actionViewRekapAbsensiKelas');
            Route::get('rekap-absensi-kelas/rekap-absensi-kelas-siswa/{id_jadwal_kelas_mp}', 'Guru\WaliKelas\RekapAbsensiKelasController@viewRekapAbsensiKelasSiswa');

            // MENU Home Visit
            Route::get('home-visit', 'Guru\WaliKelas\HomeVisitController@viewHomeVisit');
            Route::get('home-visit/datatables', 'Guru\WaliKelas\HomeVisitController@datatablesHomeVisit');
            Route::get('home-visit/add', 'Guru\WaliKelas\HomeVisitController@addHomeVisit');
            Route::get('home-visit/edit/{id}', 'Guru\WaliKelas\HomeVisitController@editHomeVisit');

            Route::post('action-home-visit/{mode}/{id}', 'Guru\WaliKelas\HomeVisitController@actionHomeVisit');

            // AJAX GET SUBKATEGORI PELANGGARAN BY KATEGORI
            Route::post('subkategori-bykategori', 'Guru\WaliKelas\InputPelanggaranController@ajaxGetSubkategoriByKategori');

            // MENU PEMBAYARAN ONLINE
            Route::group(array('prefix' => 'pembayaran-online'), function () {
                Route::get('/', 'Keuangan\SIM\PembayaranOnlineController@viewIndex');
                Route::get('add', 'Keuangan\SIM\PembayaranOnlineController@viewAdd');

                Route::post('datatables', 'Keuangan\SIM\PembayaranOnlineController@datatables');
                Route::post('tagihan/datatables/{id}', 'Keuangan\SIM\PembayaranOnlineController@datatablesTagihan');
                Route::post('save', 'Keuangan\SIM\PembayaranOnlineController@actionSave');
                
                Route::post('siswa-bykelas', 'Keuangan\SIM\PembayaranOnlineController@ajaxGetSiswaByKelas');
            });

            Route::get('rekap-kesehatan', 'Guru\WaliKelas\RekapKesehatanController@viewRekapFormKesehatan');
            Route::get('rekap-kesehatan/user/{id}/{date}', 'Guru\WaliKelas\RekapKesehatanController@viewRekapKesehatanSiswa');
            Route::get('rekap-kesehatan/detail/form/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');

            Route::post('rekap-kesehatan/action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
            Route::post('rekap-kesehatan/datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');
            
            Route::get('rekap-kesehatan/{bulan}/{tahun}', 'Guru\WaliKelas\RekapKesehatanController@viewRekapFormKesehatan');
            Route::get('rekap-kesehatan/{bulan}/{tahun}/download', 'Guru\WaliKelas\RekapKesehatanController@downloadRekapKesehatan');
                

            Route::get('approve-prestasi-siswa', 'Guru\WaliKelas\ApprovePrestasiSiswaController@viewApprovePrestasiSiswa');
            Route::get('approve-prestasi-siswa/datatables', 'Guru\WaliKelas\ApprovePrestasiSiswaController@datatablesApprovePrestasiSiswa');
            Route::get('approve-prestasi-siswa/{id}', 'Guru\WaliKelas\ApprovePrestasiSiswaController@viewDetailPrestasiSiswa');
            Route::get('approve-prestasi-siswa/prestasi/datatables/{id}', 'Guru\WaliKelas\ApprovePrestasiSiswaController@datatablesPrestasiApprovePrestasiSiswa');
            Route::get('approve-prestasi-siswa/kegiatan/datatables/{id}', 'Guru\WaliKelas\ApprovePrestasiSiswaController@datatablesKegiatanApprovePrestasiSiswa');
            Route::post('approve-prestasi-siswa/{data}/{id}', 'Guru\WaliKelas\ApprovePrestasiSiswaController@actionApprovePrestasiSiswa');
            Route::get('approve-prestasi-siswa/print-skpi/{id}', 'Guru\WaliKelas\ApprovePrestasiSiswaController@PrintSkpi');
        });

        // MODUL KELAS DARING
        Route::group(array('prefix' => 'kelas-daring'), function () {
            Route::group(array('prefix' => 'jadwal-kelas'), function () {
                Route::get('/', 'Guru\KelasDaring\SettingKelasDaringController@viewKelasDaring');
                Route::get('add', 'Guru\KelasDaring\SettingKelasDaringController@viewAddKelasDaring');
                Route::get('edit/{id}', 'Guru\KelasDaring\SettingKelasDaringController@viewEditKelasDaring');

                Route::get('materi/edit/{id_kelas_mp_grup}/{id}', 'Guru\KelasDaring\SettingKelasDaringController@viewEditMateriKelasDaring');
                Route::post('materi/action/{mode}', 'Guru\KelasDaring\SettingKelasDaringController@actionEditMateriKelasDaring');
                
                Route::post('datatables', 'Guru\KelasDaring\SettingKelasDaringController@datatablesKelasDaring');
                Route::post('save', 'Guru\KelasDaring\SettingKelasDaringController@actionAddKelasDaring');
                
                Route::group(array('prefix' => 'data-kelas'), function () {
                    Route::get('/{id}', 'Guru\KelasDaring\SettingKelasDaringController@viewKelasMpKelasDaring');

                    Route::post('datatables', 'Guru\KelasDaring\SettingKelasDaringController@datatablesKelasMpKelasDaring');
                    Route::post('action/{mode}', 'Guru\KelasDaring\SettingKelasDaringController@actionKelasMpKelasDaring');
                });
                
                Route::group(array('prefix' => 'data-jadwal'), function () {
                    Route::get('/{id}', 'Guru\KelasDaring\SettingKelasDaringController@viewPresensiMpKelasDaring');
                    Route::get('add/{id_kelas_mp_grup}', 'Guru\KelasDaring\SettingKelasDaringController@viewAddPresensiMpKelasDaring');
                    Route::get('edit/{id_kelas_mp_grup}/{id}', 'Guru\KelasDaring\SettingKelasDaringController@viewEditPresensiMpKelasDaring');

                    Route::post('datatables', 'Guru\KelasDaring\SettingKelasDaringController@datatablesPresensiMpKelasDaring');
                    Route::post('action/{mode}', 'Guru\KelasDaring\SettingKelasDaringController@actionPresensiMpKelasDaring');
                });
            });


            Route::get('mengajar-daring', 'Guru\KelasDaring\SettingKelasDaringController@viewAdd');
        });

        Route::group(array('prefix' => 'kegiatan-harian'), function () {
        
            Route::group(array('prefix' => 'mengisi-form-kesehatan'), function () {
                // MENU Mengisi form kesehatan
                Route::get('/', 'Tendik\KegiatanHarian\FormKesehatanController@viewFormKesehatan');
                Route::get('add', 'Tendik\KegiatanHarian\FormKesehatanController@viewAddFormKesehatan');
                Route::get('detail/{id}', 'Tendik\KegiatanHarian\FormKesehatanController@viewDetailFormKesehatan');
                
                Route::post('action/{mode}', 'Tendik\KegiatanHarian\FormKesehatanController@actionFormKesehatan');
                Route::post('datatables', 'Tendik\KegiatanHarian\FormKesehatanController@showDatatablesFormKesehatan');
            });
        });

        Route::group(array('prefix' => 'kesekretariatan'), function () {

            Route::group(array('prefix' => 'upload-dokumen'), function () {
                Route::get('/', 'Guru\Kesekretariatan\DokumenController@manageInputDokumen');
                Route::get('edit/{id}', 'Guru\Kesekretariatan\DokumenController@manageInputDokumen');
                Route::get('upload/{id}', 'Guru\Kesekretariatan\DokumenController@uploadInputDokumen');
            });

            // action upload dokumen
            Route::post('action-upload-dokumen/{mode}/{id}', 'Guru\Kesekretariatan\DokumenController@actionUploadDokumen');

            // ajax sub kategori
            Route::post('sub-kategori', 'Guru\Kesekretariatan\DokumenController@ajaxGetSubkategori');
        
            Route::group(array('prefix' => 'dokumen'), function () {
                Route::get('/', 'Guru\Kesekretariatan\DokumenController@viewDokumen');
                Route::get('detail/{id}', 'Guru\Kesekretariatan\DokumenController@viewDetailDokumen');
                
                Route::post('datatables', 'Guru\Kesekretariatan\DokumenController@datatablesDokumen');
            });
        });

        // MODUL PEMBINA EKSKUL
        Route::group(['prefix' => 'pembina-ekskul'], function(){
            // Menu Rekap Absensi Ekskul
            Route::get('rekap-absensi-ekskul', 'Guru\PembinaEkskul\RekapAbsensiEkskulController@viewRekapAbsensiEkskul');
            Route::get('rekap-absensi-ekskul/detail/{id_semester}/{id_ekskul}', 'Guru\PembinaEkskul\RekapAbsensiEkskulController@viewDetailRekapAbsensiEkskul');
            Route::get('rekap-absensi-ekskul/print/{id_semester}/{id_ekskul}', 'Guru\PembinaEkskul\RekapAbsensiEkskulController@printRekapAbsensiEkskul');

            // Menu Komponen Nilai Ekskul
            Route::get('komponen-nilai-ekskul', 'Guru\PembinaEkskul\KomponenNilaiEkskulController@viewKomponenNilaiEkskul');
            Route::post('post-view-komponen-nilai', 'Guru\PembinaEkskul\KomponenNilaiEkskulController@postViewKomponenNilaiEkskul');
            Route::get('komponen-nilai-ekskul/list/{id_semester}/{id_ekskul}', 'Guru\PembinaEkskul\KomponenNilaiEkskulController@viewListKomponenNilaiEkskul');
            Route::get('komponen-nilai-ekskul/datatables/{id_semester}/{id_ekskul}', 'Guru\PembinaEkskul\KomponenNilaiEkskulController@datatablesKomponenNilaiEkskul');
            Route::get('komponen-nilai-ekskul/add/{id_semester}/{id_ekskul}', 'Guru\PembinaEkskul\KomponenNilaiEkskulController@addKomponenNilaiEkskul');
            Route::get('komponen-nilai-ekskul/edit/{id_semester}/{id_ekskul}/{id}', 'Guru\PembinaEkskul\KomponenNilaiEkskulController@editKomponenNilaiEkskul');

            Route::post('action-komponen-nilai-ekskul/{mode}/{id?}', 'Guru\PembinaEkskul\KomponenNilaiEkskulController@actionKomponenNilaiEkskul');

            // Menu Input Nilai Ekskul
            Route::get('input-nilai-ekskul', 'Guru\PembinaEkskul\InputNilaiEkskulController@viewInputNilaiEkskul');
            Route::post('post-view-input-nilai', 'Guru\PembinaEkskul\InputNilaiEkskulController@postViewInputNilaiEkskul');
            Route::get('input-nilai-ekskul/detail/{id_semester}/{id_ekskul}', 'Guru\PembinaEkskul\InputNilaiEkskulController@viewDetailInputNilaiEkskul');

            Route::post('input-nilai-ekskul/save', 'Guru\PembinaEkskul\InputNilaiEkskulController@saveInputNilaiEkskul');

            // Menu Rekap Nilai Ekskul
            Route::get('rekap-nilai-ekskul', 'Guru\PembinaEkskul\RekapNilaiEkskulController@viewRekapNilaiEkskul');
            Route::get('rekap-nilai-ekskul/detail/{id_semester}/{id_ekskul}', 'Guru\PembinaEkskul\RekapNilaiEkskulController@viewDetailRekapNilaiEkskul');
            Route::get('rekap-nilai-ekskul/print/{id_semester}/{id_ekskul}', 'Guru\PembinaEkskul\RekapNilaiEkskulController@printRekapNilaiEkskul');
        });
    });
});
