<?php

use App\Http\Controllers\Siswa\WelcomeController;
use App\Http\Controllers\Alumni\BursaKerja\BKKController;
use App\Http\Controllers\Siswa\Akademik\MagangController;
use App\Http\Controllers\ManajemenFile\DataFileController;
use App\Http\Controllers\Siswa\Keuangan\TagihanController;
use App\Http\Controllers\Siswa\Akademik\JadwalKBMController;
use App\Http\Controllers\Siswa\Kesiswaan\BeasiswaController;
use App\Http\Controllers\Siswa\Kesiswaan\PrestasiController;
use App\Http\Controllers\Humas\Alumni\TracerAlumniController;
use App\Http\Controllers\ManajemenFile\DataKategoriController;
use App\Http\Controllers\Siswa\Akademik\JadwalUjianController;
use App\Http\Controllers\Siswa\ELearning\MateriAjarController;
use App\Http\Controllers\Siswa\Akademik\LihatAbsensiController;
use App\Http\Controllers\Siswa\DataPribadi\DataSiswaController;
use App\Http\Controllers\Siswa\Kesiswaan\NilaiEkskulController;
use App\Http\Controllers\Siswa\Keuangan\RiwayatBayarController;
use App\Http\Controllers\Guru\Kesekretariatan\DokumenController;
use App\Http\Controllers\Siswa\SKPI\DataKegiatanSiswaController;
use App\Http\Controllers\Siswa\SKPI\DataPrestasiSiswaController;
use App\Http\Controllers\Siswa\SKPI\InformasiTambahanController;
use App\Http\Controllers\ManajemenFile\SubDataKategoriController;
use App\Http\Controllers\Siswa\ELearningSoal\ListUjianController;
use App\Http\Controllers\Siswa\Kesiswaan\AbsensiEkskulController;
use App\Http\Controllers\Siswa\Sarpras\KomplainSarprasController;
use App\Http\Controllers\Siswa\Alumni\TracerAlumniSiswaController;
use App\Http\Controllers\Siswa\ELearningSoal\NilaiUjianController;
use App\Http\Controllers\Siswa\Akademik\KalenderAkademikController;
use App\Http\Controllers\Siswa\Akademik\JadwalKelasDaringController;
use App\Http\Controllers\Siswa\Absensi\HistoriAbsensiSiswaController;
use App\Http\Controllers\Tendik\KegiatanHarian\FormKesehatanController;
use App\Http\Controllers\Siswa\Pelanggaran\RiwayatPelanggaranController;

Route::middleware(['token_staff'])->group(function () {
    Route::prefix('siswa')->group(function () {
        Route::get('welcome', [WelcomeController::class, 'indexWelcome']);

        Route::prefix('tracer-alumni')->group(function () {
            Route::get('/', [TracerAlumniSiswaController::class, 'viewTracerAlumni']);
            Route::get('datatables', [TracerAlumniSiswaController::class, 'datatablesTracerAlumni']);
            Route::get('add', [TracerAlumniSiswaController::class, 'addTracerAlumni']);
            Route::get('edit/{id}', [TracerAlumniSiswaController::class, 'editTracerAlumni']);
            //action arahkan ke humas
            Route::post('action/{mode}/{id}', [TracerAlumniController::class, 'actionTracerAlumni']);
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

        Route::prefix('e-learning')->group(function () {

            Route::prefix('materi-ajar')->group(function () {
                Route::get('/', [MateriAjarController::class, 'viewMateriAjar']);
                Route::get('datatables', [MateriAjarController::class, 'datatablesMateriAjar']);
                Route::get('detail/{id}', [MateriAjarController::class, 'viewDetailMateriAjar']);
                // Route::get('view/{id}', [MateriAjarController::class, 'addViewMateriAjar']);
            });
        });

        Route::prefix('e-learning-soal')->group(function () {

            Route::prefix('list-ujian')->group(function () {
                Route::get('/', [ListUjianController::class, 'indexList']);
                Route::post('test/end', [ListUjianController::class, 'actionEndTest']);
                //untuk bagian data table
                Route::post('table', [ListUjianController::class, 'commonList']);
                //untuk bagian test
                // Route::get('test', [TestController::class, 'index']);
                Route::get('cek/{id_paket_soal}', [ListUjianController::class, 'indexTest']);
                Route::get('test/{id_soal}/{no}', [ListUjianController::class, 'indexTest2']);
                Route::post('test/answer', [ListUjianController::class, 'actionSaveAnswer']);
                // Route::get('test/result', [TestController::class, 'indexReview']);
            });
            Route::prefix('nilai-ujian')->group(function () {
                Route::get('/', [NilaiUjianController::class, 'indexList']);
                Route::post('table', [NilaiUjianController::class, 'commonList']);
                Route::get('koreksi/{id_test}/', [NilaiUjianController::class, 'indexPenilaian']);
            });
        });

        Route::prefix('bursa-kerja')->group(function () {
            Route::prefix('bkk')->group(function () {
                Route::get('/', [BKKController::class, 'viewBkk']);
                Route::get('detail/{id}', [BKKController::class, 'viewDetailBkk']);
                Route::get('datatables', [BKKController::class, 'showDatatablesBkk']);
            });
        });

        Route::prefix('data-pribadi')->group(function () {
            Route::get('data-siswa', [DataSiswaController::class, 'viewDataSiswa']);
            Route::get('data-siswa/view-print-siswa/{nis_nama_siswa}', [DataSiswaController::class, 'viewPrintSiswa']);
            Route::post('data-siswa/{id}', [DataSiswaController::class, 'actionUpdateSiswa']);
        });

        Route::prefix('skpi')->group(function () {

            Route::prefix('data-kegiatan-siswa')->group(function () {
                Route::get('/', [DataKegiatanSiswaController::class, 'viewDataKegiatanSiswa']);
                Route::get('add', [DataKegiatanSiswaController::class, 'viewAddDataKegiatanSiswa']);
                Route::get('edit/{id}', [DataKegiatanSiswaController::class, 'viewEditDataKegiatanSiswa']);
                Route::post('action/{mode}/{id}', [DataKegiatanSiswaController::class, 'actionDataKegiatanSiswa']);

                Route::get('datatables', [DataKegiatanSiswaController::class, 'datatablesDataKegiatanSiswa']);
            });
            Route::prefix('data-prestasi-siswa')->group(function () {
                Route::get('/', [DataPrestasiSiswaController::class, 'viewDataPrestasiSiswa']);
                Route::get('add', [DataPrestasiSiswaController::class, 'viewAddDataPrestasiSiswa']);
                Route::get('edit/{id}', [DataPrestasiSiswaController::class, 'viewEditDataPrestasiSiswa']);
                Route::post('action/{mode}/{id}', [DataPrestasiSiswaController::class, 'actionDataPrestasiSiswa']);

                Route::get('datatables', [DataPrestasiSiswaController::class, 'datatablesDataPrestasiSiswa']);
            });
            Route::prefix('informasi_tambahan')->group(function () {
                Route::get('/', [InformasiTambahanController::class, 'viewInformasiTambahan']);
                Route::get('add', [InformasiTambahanController::class, 'viewAddInformasiTambahan']);
                Route::get('edit/{id}', [InformasiTambahanController::class, 'viewEditInformasiTambahan']);
                Route::post('action/{mode}/{id}', [InformasiTambahanController::class, 'actionInformasiTambahan']);

                Route::get('datatables', [InformasiTambahanController::class, 'datatablesInformasiTambahan']);
            });
        });

        Route::prefix('akademik')->group(function () {
            // MENU Kalender Akademik
            Route::get('kalender-akademik', [KalenderAkademikController::class, 'viewKalenderAkademik']);
            Route::get('kalender-akademik/datatables', [KalenderAkademikController::class, 'datatablesKalenderAkademik']);

            // MENU Jadwal KBM
            Route::get('jadwal-kbm', [JadwalKBMController::class, 'viewJadwalKBM']);
            Route::get('jadwal-kbm/datatables', [JadwalKBMController::class, 'datatablesJadwalKBM']);

            // MENU Jadwal Ujian
            Route::get('jadwal-ujian', [JadwalUjianController::class, 'viewJadwalUjian']);
            Route::get('jadwal-ujian/datatables-uts', [JadwalUjianController::class, 'datatablesJadwalUTS']);
            Route::get('jadwal-ujian/datatables-uas', [JadwalUjianController::class, 'datatablesJadwalUAS']);

            Route::prefix('jadwal-kelas-daring')->group(function () {
                Route::get('/', [JadwalKelasDaringController::class, 'viewJadwalKelasDaring']);
                Route::post('datatables', [JadwalKelasDaringController::class, 'datatablesJadwalKelasDaring']);
                Route::get('{id}', [JadwalKelasDaringController::class, 'viewDetailJadwalKelasDaring']);
                Route::get('download/{id}', [JadwalKelasDaringController::class, 'downloadMateri']);
                Route::post('upload-tugas/{id}', [JadwalKelasDaringController::class, 'uploadTugas']);
            });
            // MENU Magang
            Route::get('magang', [MagangController::class, 'viewMagang']);
            Route::get('magang/datatables', [MagangController::class, 'datatablesMagang']);

            Route::prefix('lihat-absensi')->group(function () {
                Route::get('/', [LihatAbsensiController::class, 'viewLihatAbsensi']);
                Route::get('/{id_bulan}/{tahun}', [LihatAbsensiController::class, 'viewLihatAbsensi']);
            });
        });

        Route::prefix('keuangan')->group(function () {
            // MENU Tagihan
            Route::get('tagihan', [TagihanController::class, 'viewTagihan']);
            Route::get('tagihan/datatables', [TagihanController::class, 'datatablesTagihan']);

            Route::post('tagihan/generate', [TagihanController::class, 'actionGenerate']);

            // MENU Riwayat Bayar
            Route::get('riwayat-bayar', [RiwayatBayarController::class, 'viewRiwayatBayar']);
            Route::get('riwayat-bayar/datatables', [RiwayatBayarController::class, 'datatablesRiwayatBayar']);
        });

        Route::prefix('pelanggaran')->group(function () {
            Route::get('riwayat-pelanggaran', [RiwayatPelanggaranController::class, 'viewRiwayatPelanggaran']);
            Route::get('riwayat-pelanggaran/datatables-non-kbm', [RiwayatPelanggaranController::class, 'datatablesPelanggaranNonKBM']);
            Route::get('riwayat-pelanggaran/datatables-kbm', [RiwayatPelanggaranController::class, 'datatablesPelanggaranKBM']);
        });

        Route::prefix('kesiswaan')->group(function () {
            //MENU Prestasi
            Route::get('prestasi', [PrestasiController::class, 'viewPrestasi']);
            Route::get('prestasi/datatables', [PrestasiController::class, 'datatablesPrestasi']);

            //MENU Prestasi
            Route::get('beasiswa', [BeasiswaController::class, 'viewBeasiswa']);
            Route::get('beasiswa/datatables', [BeasiswaController::class, 'datatablesBeasiswa']);

            Route::prefix('absensi-ekskul')->group(function () {
                Route::get('/', [AbsensiEkskulController::class, 'viewAbsensiEkskul']);
                Route::get('detail/{id_semester}/{id_ekskul}', [AbsensiEkskulController::class, 'viewDetailAbsensiEkskul']);
            });
            Route::prefix('nilai-ekskul')->group(function () {
                Route::get('/', [NilaiEkskulController::class, 'viewNilaiEkskul']);
                Route::get('detail/{id_semester}/{id_ekskul}', [NilaiEkskulController::class, 'viewDetailNilaiEkskul']);
            });
        });

        Route::prefix('sarpras')->group(function () {
            // MENU Komplain Inventaris/Sarpras
            Route::get('komplain-sarpras', [KomplainSarprasController::class, 'viewKomplainSarpras']);

            Route::post('post-view-ruangan-sarpras', [KomplainSarprasController::class, 'actionViewRuanganKomplainSarpras']);
            Route::get('komplain-sarpras/ruangan-sarpras/view-ruangan/{id_ruangan}', [KomplainSarprasController::class, 'viewRuanganKomplainSarpras']);
            Route::get('komplain-sarpras/ruangan-sarpras/datatables/{id_ruangan}', [KomplainSarprasController::class, 'datatablesRuanganKomplainSarpras']);
            Route::get('komplain-sarpras/ruangan-sarpras/add/{id_ruangan}', [KomplainSarprasController::class, 'addRuanganKomplainSarpras']);
            Route::get('komplain-sarpras/ruangan-sarpras/edit/{id_ruangan}/{id}', [KomplainSarprasController::class, 'editRuanganKomplainSarpras']);

            Route::post('post-view-bukualat-sarpras', [KomplainSarprasController::class, 'actionViewBukualatKomplainSarpras']);
            Route::get('komplain-sarpras/bukualat-sarpras/view-bukualat/{id_buku_alat}', [KomplainSarprasController::class, 'viewBukualatKomplainSarpras']);
            Route::get('komplain-sarpras/bukualat-sarpras/datatables/{id_buku_alat}', [KomplainSarprasController::class, 'datatablesBukualatKomplainSarpras']);
            Route::get('komplain-sarpras/bukualat-sarpras/add/{id_buku_alat}', [KomplainSarprasController::class, 'addBukualatKomplainSarpras']);
            Route::get('komplain-sarpras/bukualat-sarpras/edit/{id_buku_alat}/{id}', [KomplainSarprasController::class, 'editBukualatKomplainSarpras']);

            Route::post('action-komplain-sarpras/{mode}/{id}', [KomplainSarprasController::class, 'actionKomplainSarpras']);
        });

        Route::prefix('kegiatan-harian')->group(function () {

            Route::prefix('mengisi-form-kesehatan')->group(function () {
                // MENU Mengisi form kesehatan
                Route::get('/', [FormKesehatanController::class, 'viewFormKesehatan']);
                Route::get('add', [FormKesehatanController::class, 'viewAddFormKesehatan']);
                Route::get('detail/{id}', [FormKesehatanController::class, 'viewDetailFormKesehatan']);

                Route::post('action/{mode}', [FormKesehatanController::class, 'actionFormKesehatan']);
                Route::post('datatables', [FormKesehatanController::class, 'showDatatablesFormKesehatan']);
            });
        });

        Route::prefix('kesekretariatan')->group(function () {

            Route::prefix('dokumen')->group(function () {
                Route::get('/', [DokumenController::class, 'viewDokumen']);
                Route::get('detail/{id}', [DokumenController::class, 'viewDetailDokumen']);

                Route::post('datatables', [DokumenController::class, 'datatablesDokumen']);
            });
        });

        Route::prefix('absensi')->group(function () {
            Route::prefix('histori-absensi')->group(function () {
                Route::get('/', [HistoriAbsensiSiswaController::class, 'viewHistoriAbsensi']);
                Route::get('/{start_date}/{end_date}', [HistoriAbsensiSiswaController::class, 'viewHistoriAbsensi']);
            });
        });
    });
});
