<?php
// ROLE SISWA
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'siswa'), function() {
        Route::get('welcome', 'Siswa\WelcomeController@indexWelcome');

        /** ==== MODUL DATA PRIBADI ==== **/
        Route::group(array('prefix' => 'data-pribadi'), function() {

        	Route::get('data-siswa', 'Siswa\DataPribadi\DataSiswaController@viewDataSiswa');
        	Route::post('data-siswa/{id}', 'Siswa\DataPribadi\DataSiswaController@actionUpdateSiswa');

        });

        /** ==== MODUL AKADEMIK ==== **/
		Route::group(array('prefix' => 'akademik'), function() {

			// MENU Kalender Akademik
			Route::get('kalender-akademik', 'Siswa\Akademik\KalenderAkademikController@viewKalenderAkademik');
			Route::get('kalender-akademik/datatables', 'Siswa\Akademik\KalenderAkademikController@datatablesKalenderAkademik');

			// MENU Jadwal KBM
			Route::get('jadwal-kbm', 'Siswa\Akademik\JadwalKBMController@viewJadwalKBM');
			Route::get('jadwal-kbm/datatables', 'Siswa\Akademik\JadwalKBMController@datatablesJadwalKBM');

			// MENU Jadwal Ujian
			Route::get('jadwal-ujian', 'Siswa\Akademik\JadwalUjianController@viewJadwalUjian');
			Route::get('jadwal-ujian/datatables-uts', 'Siswa\Akademik\JadwalUjianController@datatablesJadwalUTS');
			Route::get('jadwal-ujian/datatables-uas', 'Siswa\Akademik\JadwalUjianController@datatablesJadwalUAS');

			// MENU Magang
			Route::get('magang', 'Siswa\Akademik\MagangController@viewMagang');
			Route::get('magang/datatables', 'Siswa\Akademik\MagangController@datatablesMagang');

		});

		/** ==== MODUL KEUANGAN ==== **/
		Route::group(array('prefix' => 'keuangan'), function() {

			// MENU Tagihan
			Route::get('tagihan', 'Siswa\Keuangan\TagihanController@viewTagihan');
			Route::get('tagihan/datatables', 'Siswa\Keuangan\TagihanController@datatablesTagihan');

			Route::post('tagihan/generate', 'Siswa\Keuangan\TagihanController@actionGenerate');

			// MENU Riwayat Bayar
			Route::get('riwayat-bayar', 'Siswa\Keuangan\RiwayatBayarController@viewRiwayatBayar');
			Route::get('riwayat-bayar/datatables', 'Siswa\Keuangan\RiwayatBayarController@datatablesRiwayatBayar');

		});

		/** ==== MODUL PELANGGARAN ==== **/
		Route::group(array('prefix' => 'pelanggaran'), function() {

			// MENU Jadwal Ujian
			Route::get('riwayat-pelanggaran', 'Siswa\Pelanggaran\RiwayatPelanggaranController@viewRiwayatPelanggaran');
			Route::get('riwayat-pelanggaran/datatables-non-kbm', 'Siswa\Pelanggaran\RiwayatPelanggaranController@datatablesPelanggaranNonKBM');
			Route::get('riwayat-pelanggaran/datatables-kbm', 'Siswa\Pelanggaran\RiwayatPelanggaranController@datatablesPelanggaranKBM');

		});

		/** ==== MODUL KESISWAAN ==== **/
		Route::group(array('prefix' => 'kesiswaan'), function() {

			//MENU Prestasi
            Route::get('prestasi', 'Siswa\Kesiswaan\PrestasiController@viewPrestasi');
			Route::get('prestasi/datatables', 'Siswa\Kesiswaan\PrestasiController@datatablesPrestasi');
			
			//MENU Prestasi
            Route::get('beasiswa', 'Siswa\Kesiswaan\BeasiswaController@viewBeasiswa');
            Route::get('beasiswa/datatables', 'Siswa\Kesiswaan\BeasiswaController@datatablesBeasiswa');

		});

		/** ==== MODUL SARANA PRASARANA ==== **/
		Route::group(array('prefix' => 'sarpras'), function() {
			// MENU Komplain Inventaris/Sarpras
			Route::get('komplain-sarpras', 'Siswa\Sarpras\KomplainSarprasController@viewKomplainSarpras');

			Route::post('post-view-ruangan-sarpras', 'Siswa\Sarpras\KomplainSarprasController@actionViewRuanganKomplainSarpras');
			Route::get('komplain-sarpras/ruangan-sarpras/view-ruangan/{id_ruangan}', 'Siswa\Sarpras\KomplainSarprasController@viewRuanganKomplainSarpras');
			Route::get('komplain-sarpras/ruangan-sarpras/datatables/{id_ruangan}', 'Siswa\Sarpras\KomplainSarprasController@datatablesRuanganKomplainSarpras');
			Route::get('komplain-sarpras/ruangan-sarpras/add/{id_ruangan}', 'Siswa\Sarpras\KomplainSarprasController@addRuanganKomplainSarpras');
			Route::get('komplain-sarpras/ruangan-sarpras/edit/{id_ruangan}/{id}', 'Siswa\Sarpras\KomplainSarprasController@editRuanganKomplainSarpras');

			Route::post('post-view-bukualat-sarpras', 'Siswa\Sarpras\KomplainSarprasController@actionViewBukualatKomplainSarpras');
			Route::get('komplain-sarpras/bukualat-sarpras/view-bukualat/{id_buku_alat}', 'Siswa\Sarpras\KomplainSarprasController@viewBukualatKomplainSarpras');
			Route::get('komplain-sarpras/bukualat-sarpras/datatables/{id_buku_alat}', 'Siswa\Sarpras\KomplainSarprasController@datatablesBukualatKomplainSarpras');
			Route::get('komplain-sarpras/bukualat-sarpras/add/{id_buku_alat}', 'Siswa\Sarpras\KomplainSarprasController@addBukualatKomplainSarpras');
			Route::get('komplain-sarpras/bukualat-sarpras/edit/{id_buku_alat}/{id}', 'Siswa\Sarpras\KomplainSarprasController@editBukualatKomplainSarpras');

			Route::post('action-komplain-sarpras/{mode}/{id}', 'Siswa\Sarpras\KomplainSarprasController@actionKomplainSarpras');
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
        
            Route::group(array('prefix' => 'dokumen'), function () {
                Route::get('/', 'Guru\Kesekretariatan\DokumenController@viewDokumen');
                Route::get('detail/{id}', 'Guru\Kesekretariatan\DokumenController@viewDetailDokumen');
                
                Route::post('datatables', 'Guru\Kesekretariatan\DokumenController@datatablesDokumen');
            });
        });

    });
});