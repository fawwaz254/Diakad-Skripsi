<?php
// ROLE SARANA PRASARANA
Route::group(array('middleware'=> ['token_staff']), function() {
    Route::group(array('prefix' => 'sarana-prasarana'), function() {
        Route::get('welcome', 'SaranaPrasarana\WelcomeController@indexWelcome');

        /** ==== MODUL DATA SARPRAS GEDUNG ==== **/
        Route::group(array('prefix' => 'data-sarpras-gedung'), function() {
            // MENU Data Jenis Gedung
            Route::get('jenis-gedung', 'SaranaPrasarana\DataSarprasGedung\JenisGedungController@viewJenisGedung');
            Route::get('jenis-gedung/datatables', 'SaranaPrasarana\DataSarprasGedung\JenisGedungController@datatablesJenisGedung');
            Route::get('jenis-gedung/add', 'SaranaPrasarana\DataSarprasGedung\JenisGedungController@addJenisGedung');
            Route::get('jenis-gedung/edit/{id}', 'SaranaPrasarana\DataSarprasGedung\JenisGedungController@editJenisGedung');

            Route::post('action-jenis-gedung/{mode}/{id}', 'SaranaPrasarana\DataSarprasGedung\JenisGedungController@actionJenisGedung');
            
            // MENU Data Gedung
            Route::get('gedung', 'SaranaPrasarana\DataSarprasGedung\GedungController@viewGedung');
            Route::get('gedung/datatables', 'SaranaPrasarana\DataSarprasGedung\GedungController@datatablesGedung');
            Route::get('gedung/add', 'SaranaPrasarana\DataSarprasGedung\GedungController@addGedung');
            Route::get('gedung/edit/{id}', 'SaranaPrasarana\DataSarprasGedung\GedungController@editGedung');

            Route::post('action-gedung/{mode}/{id}', 'SaranaPrasarana\DataSarprasGedung\GedungController@actionGedung');
        });
        
        /** ==== MODUL DATA SARPRAS RUANGAN ==== **/
        Route::group(array('prefix' => 'data-sarpras-ruangan'), function() {

            // MENU Data Pemilik Sarpras
            Route::get('pemilik-sarpras', 'SaranaPrasarana\DataSarprasRuangan\PemilikSarprasController@viewPemilikSarpras');
            Route::get('pemilik-sarpras/datatables', 'SaranaPrasarana\DataSarprasRuangan\PemilikSarprasController@datatablesPemilikSarpras');
            Route::get('pemilik-sarpras/add', 'SaranaPrasarana\DataSarprasRuangan\PemilikSarprasController@addPemilikSarpras');
            Route::get('pemilik-sarpras/edit/{id}', 'SaranaPrasarana\DataSarprasRuangan\PemilikSarprasController@editPemilikSarpras');

            Route::post('action-pemilik-sarpras/{mode}/{id}', 'SaranaPrasarana\DataSarprasRuangan\PemilikSarprasController@actionPemilikSarpras');

            // MENU Data Jenis Ruangan
            Route::get('jenis-ruangan', 'SaranaPrasarana\DataSarprasRuangan\JenisRuanganController@viewJenisRuangan');
            Route::get('jenis-ruangan/datatables', 'SaranaPrasarana\DataSarprasRuangan\JenisRuanganController@datatablesJenisRuangan');
            Route::get('jenis-ruangan/add', 'SaranaPrasarana\DataSarprasRuangan\JenisRuanganController@addJenisRuangan');
            Route::get('jenis-ruangan/edit/{id}', 'SaranaPrasarana\DataSarprasRuangan\JenisRuanganController@editJenisRuangan');

            Route::post('action-jenis-ruangan/{mode}/{id}', 'SaranaPrasarana\DataSarprasRuangan\JenisRuanganController@actionJenisRuangan');

            // MENU Data Ruangan
            Route::get('ruangan', 'SaranaPrasarana\DataSarprasRuangan\RuanganController@viewRuangan');
            Route::get('ruangan/datatables', 'SaranaPrasarana\DataSarprasRuangan\RuanganController@datatablesRuangan');
            Route::get('ruangan/add', 'SaranaPrasarana\DataSarprasRuangan\RuanganController@addRuangan');
            Route::get('ruangan/edit/{id}', 'SaranaPrasarana\DataSarprasRuangan\RuanganController@editRuangan');

            Route::post('action-ruangan/{mode}/{id}', 'SaranaPrasarana\DataSarprasRuangan\RuanganController@actionRuangan');

            // MENU Kondisi Ruangan
            Route::get('kondisi-ruangan', 'SaranaPrasarana\DataSarprasRuangan\KondisiRuanganController@viewKondisiRuangan');
            Route::get('kondisi-ruangan/datatables', 'SaranaPrasarana\DataSarprasRuangan\KondisiRuanganController@datatablesKondisiRuangan');
            Route::get('kondisi-ruangan/add', 'SaranaPrasarana\DataSarprasRuangan\KondisiRuanganController@addKondisiRuangan');
            Route::get('kondisi-ruangan/edit/{id}', 'SaranaPrasarana\DataSarprasRuangan\KondisiRuanganController@editKondisiRuangan');

            Route::post('action-kondisi-ruangan/{mode}/{id}', 'SaranaPrasarana\DataSarprasRuangan\KondisiRuanganController@actionKondisiRuangan');

            // MENU Data Inventaris
            Route::get('inventaris', 'SaranaPrasarana\DataSarprasRuangan\InventarisController@viewInventaris');
            Route::get('inventaris/datatables', 'SaranaPrasarana\DataSarprasRuangan\InventarisController@datatablesInventaris');
            Route::get('inventaris/add', 'SaranaPrasarana\DataSarprasRuangan\InventarisController@addInventaris');
            Route::get('inventaris/edit/{id}', 'SaranaPrasarana\DataSarprasRuangan\InventarisController@editInventaris');

            Route::post('action-inventaris/{mode}/{id}', 'SaranaPrasarana\DataSarprasRuangan\InventarisController@actionInventaris');
        });

        /** ==== MODUL DATA SARPRAS BUKU ALAT ==== **/
        Route::group(array('prefix' => 'data-sarpras-buku-alat'), function() {
            // MENU Data Jenis Buku/Alat
            Route::get('jenis-buku-alat', 'SaranaPrasarana\DataSarprasBukuAlat\JenisBukuAlatController@viewJenisBukuAlat');
            Route::get('jenis-buku-alat/datatables', 'SaranaPrasarana\DataSarprasBukuAlat\JenisBukuAlatController@datatablesJenisBukuAlat');
            Route::get('jenis-buku-alat/add', 'SaranaPrasarana\DataSarprasBukuAlat\JenisBukuAlatController@addJenisBukuAlat');
            Route::get('jenis-buku-alat/edit/{id}', 'SaranaPrasarana\DataSarprasBukuAlat\JenisBukuAlatController@editJenisBukuAlat');

            Route::post('action-jenis-buku-alat/{mode}/{id}', 'SaranaPrasarana\DataSarprasBukuAlat\JenisBukuAlatController@actionJenisBukuAlat');

            // MENU Data Buku/Alat
            Route::get('buku-alat', 'SaranaPrasarana\DataSarprasBukuAlat\BukuAlatController@viewBukuAlat');
            Route::get('buku-alat/datatables', 'SaranaPrasarana\DataSarprasBukuAlat\BukuAlatController@datatablesBukuAlat');
            Route::get('buku-alat/add', 'SaranaPrasarana\DataSarprasBukuAlat\BukuAlatController@addBukuAlat');
            Route::get('buku-alat/edit/{id}', 'SaranaPrasarana\DataSarprasBukuAlat\BukuAlatController@editBukuAlat');

            Route::post('action-buku-alat/{mode}/{id}', 'SaranaPrasarana\DataSarprasBukuAlat\BukuAlatController@actionBukuAlat');

        });

        /** ==== MODUL DATA SARPRAS BUKU ALAT ==== **/
        Route::group(array('prefix' => 'komplain-sarpras'), function() {

            // MENU Tanggapi Komplain
            // url: /sarana-prasarana/komplain-sarpras/tanggapi-komplain
            Route::get('tanggapi-komplain', 'SaranaPrasarana\KomplainSarpras\TanggapiKomplainController@viewTanggapiKomplain');
            Route::get('tanggapi-komplain/datatables-belum', 'SaranaPrasarana\KomplainSarpras\TanggapiKomplainController@datatablesTanggapiKomplainBelum');
            Route::get('tanggapi-komplain/datatables-sudah', 'SaranaPrasarana\KomplainSarpras\TanggapiKomplainController@datatablesTanggapiKomplainSudah');
            Route::get('tanggapi-komplain/edit/{id}', 'SaranaPrasarana\KomplainSarpras\TanggapiKomplainController@editTanggapiKomplain');

            Route::post('action-tanggapi-komplain/{mode}/{id}', 'SaranaPrasarana\KomplainSarpras\TanggapiKomplainController@actionTanggapiKomplain');

        });   

        /** ==== MODUL PERAWATAN SARPRAS ==== **/
        Route::group(array('prefix' => 'perawatan-sarpras'), function() {  
            // MENU Input Perawatan Rutin
            // url: /sarana-prasarana/perawatan-sarpras/input-perawatan-rutin
            Route::get('input-perawatan-rutin', 'SaranaPrasarana\PerawatanSarpras\InputPerawatanRutinController@viewInputPerawatanRutin');
            Route::get('input-perawatan-rutin/datatables-belum', 'SaranaPrasarana\PerawatanSarpras\InputPerawatanRutinController@datatablesInputPerawatanRutinBelum');
            Route::get('input-perawatan-rutin/datatables-sudah', 'SaranaPrasarana\PerawatanSarpras\InputPerawatanRutinController@datatablesInputPerawatanRutinSudah');
            Route::get('input-perawatan-rutin/add', 'SaranaPrasarana\PerawatanSarpras\InputPerawatanRutinController@addInputPerawatanRutin');
            Route::get('input-perawatan-rutin/edit/{id}', 'SaranaPrasarana\PerawatanSarpras\InputPerawatanRutinController@editInputPerawatanRutin');
            // AJAX GET INVENTARIS BY RUANGAN
            Route::post('inventaris-byruangan', 'SaranaPrasarana\PerawatanSarpras\InputPerawatanRutinController@ajaxGetInventarisByRuangan');

            Route::post('action-input-perawatan-rutin/{mode}/{id}', 'SaranaPrasarana\PerawatanSarpras\InputPerawatanRutinController@actionInputPerawatanRutin');

            // MENU Pengadaan Barang/Sarpras
            // url: /sarana-prasarana/perawatan-sarpras/pengadaan-sarpras
            Route::get('pengadaan-sarpras', 'SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController@viewPengadaanSarpras');
            Route::get('pengadaan-sarpras/datatables-tinggi', 'SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController@datatablesPengadaanSarprasTinggi');
            Route::get('pengadaan-sarpras/datatables-sedang', 'SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController@datatablesPengadaanSarprasSedang');
            Route::get('pengadaan-sarpras/datatables-rendah', 'SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController@datatablesPengadaanSarprasRendah');
            Route::get('pengadaan-sarpras/add', 'SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController@addPengadaanSarpras');
            Route::get('pengadaan-sarpras/edit/{id}', 'SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController@editInputPerawatanRutin');
            // AJAX GET INVENTARIS BY RUANGAN
            Route::post('inventaris-byruangan', 'SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController@ajaxGetInventarisByRuangan');

            Route::post('action-pengadaan-sarpras/{mode}/{id}', 'SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController@actionInputPerawatanRutin');

        }); 

    });
});