<?php
// ROLE GURU
Route::group(array('middleware'=> ['token_staff']), function() {
	Route::group(array('prefix' => 'guru'), function() {
		Route::get('welcome', 'Guru\WelcomeController@indexWelcome');

		/** ==== MODUL BIDATA ==== **/
		Route::group(array('prefix' => 'biodata'), function() {
			// MENU Data Pribadi
			Route::get('data-pribadi', 'Guru\Biodata\DataPribadiController@viewDataPribadi');

		});

		/** ==== MODUL JADWAL ==== **/
		Route::group(array('prefix' => 'jadwal'), function() {
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

		});

		/** ==== MODUL PRESENSI ==== **/
		Route::group(array('prefix' => 'presensi'), function() {
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

		});

		/** ==== MODUL PENILAIAN ==== **/
		Route::group(array('prefix' => 'penilaian'), function() {
			// MENU Komponen Nilai
			Route::get('komponen-nilai', 'Guru\Penilaian\KomponenNilaiController@viewKomponenNilai');
			Route::post('post-view-komponen-nilai', 'Guru\Penilaian\KomponenNilaiController@actionViewKelasKomponenNilai');
			Route::get('komponen-nilai/view-kelas/{id_kelas_mp}', 'Guru\Penilaian\KomponenNilaiController@viewKelasKomponenNilai');
			Route::get('komponen-nilai/datatables/{id_kelas_mp}', 'Guru\Penilaian\KomponenNilaiController@datatablesKomponenNilai');
			Route::get('komponen-nilai/add/{id_kelas_mp}', 'Guru\Penilaian\KomponenNilaiController@addKomponenNilai');
			Route::get('komponen-nilai/edit/{id_kelas_mp}/{id}', 'Guru\Penilaian\KomponenNilaiController@editKomponenNilai');

			Route::post('action-komponen-nilai/{mode}/{id}', 'Guru\Penilaian\KomponenNilaiController@actionKomponenNilai');

			// MENU Input Nilai KBM/Try Out
			/*Route::get('input-nilai', 'Guru\Penilaian\InputNilaiController@viewInputNilai');

			Route::post('post-view-input-nilai', 'Guru\Penilaian\InputNilaiController@actionViewKelasInputNilai');
			Route::get('input-nilai/view-kelas/{id_kelas_mp}', 'Guru\Penilaian\InputNilaiController@viewKelasInputNilai');
			Route::get('input-nilai/datatables/{id_kelas_mp}', 'Guru\Penilaian\InputNilaiController@datatablesInputNilai');

			Route::post('post-view-input-tryout', 'Guru\Penilaian\InputNilaiController@actionViewKelasInputTryOut');
			Route::get('input-tryout/view-kelas/{id_kelas_mp}', 'Guru\Penilaian\InputNilaiController@viewKelasInputTryOut');
			Route::get('input-tryout/datatables/{id_kelas_mp}', 'Guru\Penilaian\InputNilaiController@datatablesInputTryOut');

			Route::post('action-input-nilai/{mode}/{id}', 'Guru\Penilaian\InputNilaiController@actionInputNilai');*/

		});


		/** ==== MODUL PELANGGARAN SISWA ==== **/
		// alurnya berbeda dengan input pelanggaran yg lain (merujuk ke presensi_mp) 
		Route::group(array('prefix' => 'pelanggaran-siswa'), function() {
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
		});

		/** ==== MODUL SARANA PRASARANA ==== **/
		Route::group(array('prefix' => 'sarpras'), function() {
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

		/** ==== MODUL WALI KELAS ==== **/
		Route::group(array('prefix' => 'wali-kelas'), function() {
			// MENU Data Inventaris Kelas/Sarana
			Route::get('inventaris-kelas', 'Guru\WaliKelas\InventarisKelasController@viewInventarisKelas');
			Route::get('inventaris-kelas/datatables', 'Guru\WaliKelas\InventarisKelasController@datatablesInventarisKelas');

			// MENU Input Pelanggaran Siswa
			Route::get('input-pelanggaran', 'Guru\WaliKelas\InputPelanggaranController@viewInputPelanggaran');
			Route::get('input-pelanggaran/datatables', 'Guru\WaliKelas\InputPelanggaranController@datatablesInputPelanggaran');
			Route::get('input-pelanggaran/add', 'Guru\WaliKelas\InputPelanggaranController@addInputPelanggaran');
			Route::get('input-pelanggaran/edit/{id}', 'Guru\WaliKelas\InputPelanggaranController@editInputPelanggaran');

			Route::post('action-input-pelanggaran/{mode}/{id}', 'Guru\WaliKelas\InputPelanggaranController@actionInputPelanggaran');

			// MENU Home Visit
			Route::get('home-visit', 'Guru\WaliKelas\HomeVisitController@viewHomeVisit');
			Route::get('home-visit/datatables', 'Guru\WaliKelas\HomeVisitController@datatablesHomeVisit');
			Route::get('home-visit/add', 'Guru\WaliKelas\HomeVisitController@addHomeVisit');
			Route::get('home-visit/edit/{id}', 'Guru\WaliKelas\HomeVisitController@editHomeVisit');

			Route::post('action-home-visit/{mode}/{id}', 'Guru\WaliKelas\HomeVisitController@actionHomeVisit');

            // AJAX GET SUBKATEGORI PELANGGARAN BY KATEGORI
            Route::post('subkategori-bykategori', 'Guru\WaliKelas\InputPelanggaranController@ajaxGetSubkategoriByKategori');
		});



	});
});