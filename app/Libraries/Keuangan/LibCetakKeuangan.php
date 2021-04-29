<?php

namespace App\Libraries\Keuangan;

use App\Models\Bulan as Bulan;

use App\Models\Biaya;
use App\Models\Kelas;
use App\Models\PembayaranBiaya;
use App\Models\PembayaranTunggakan;
use App\Models\Rapb;
use App\Models\Realisasi;
use App\Models\Semester;
use App\Models\SubkategoriRapb;
use App\Models\TutupBukuBulananBiaya;
use App\Models\TutupBukuBulananKas;
use App\Models\TutupBukuTahunanBiaya;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use DB;

class LibCetakKeuangan{

    public static function fetchLaporanBulananFull($auth_data, $start_date, $end_date)
    {
        if(empty(session('setting_print_keuangan'))){
            $print_setting = 'all';
        }else{
            $print_setting = session('setting_print_keuangan');
        }

        $sc_bulan = Carbon::createFromFormat('Y-m-d', $start_date);
        $id_bulan       = $sc_bulan->month;
        $id_bulan_lalu  = $sc_bulan->subMonth()->month;

        $sc_tahun       = Carbon::createFromFormat('Y-m-d', $start_date);
        $tahun          = $sc_tahun->year;
        $tahun_lalu     = $sc_tahun->subYear()->year;
        
        if($id_bulan < 7){
            $tahun_semester = $tahun - 1;
        }else{
            $tahun_semester = $tahun;
        }

        $semester_mulai = Semester::where('kode_semester', $tahun_semester.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun_semester.'2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        if($id_bulan < 7){
            $id_semester = $id_semester_selesai;
        }else{
            $id_semester = $id_semester_mulai;
        }

        $kode_semester_mulai = $semester_mulai->kode_semester;

        if($print_setting == 'self'){
            $where_personal = " AND kelas.created_by = '".$auth_data->pengguna->id_pengguna."'";
        }else{
            $where_personal = "";
        }

        $list_data_jml_siswa = DB::select('SELECT kelas.tingkat, COUNT(siswa.id_siswa) AS jml_siswa
                            FROM siswa
                            JOIN kelas ON kelas.id_kelas = siswa.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN admisi ON admisi.id_siswa = siswa.id_siswa
                                AND admisi.id_semester = ?
                                AND admisi.deleted_at IS NULL
                            JOIN status_pengguna ON status_pengguna.id_status_pengguna = admisi.id_status_pengguna
                                AND status_pengguna.aktif_status_pengguna = 1
                                AND status_pengguna.deleted_at IS NULL
                            WHERE siswa.deleted_at IS NULL
                            '.$where_personal.'
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat', 
                        [$id_semester]);

        $list_data_tagihan = DB::select('SELECT kelas.tingkat, SUM(tagihan_biaya.besar_biaya) AS jml_tagihan_biaya
                            FROM tagihan_biaya
                            JOIN kelas ON kelas.id_kelas = tagihan_biaya.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN detail_biaya ON detail_biaya.id_detail_biaya = tagihan_biaya.id_detail_biaya
                                AND detail_biaya.id_jenis_detail_biaya = 4
                                AND detail_biaya.id_bulan = ?
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.id_semester = ?
                                AND biaya_sekolah.deleted_at IS NULL
                            WHERE tagihan_biaya.deleted_at IS NULL
                            '.$where_personal.'
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat', 
                        [$id_bulan, $id_semester]);

        $list_data_pembayaran = DB::select('SELECT kelas.tingkat, SUM(pembayaran_biaya.besar_pembayaran) AS jml_pembayaran_biaya
                            FROM pembayaran_biaya
                            JOIN tagihan_biaya ON tagihan_biaya.id_tagihan_biaya = pembayaran_biaya.id_tagihan_biaya
                                AND tagihan_biaya.deleted_at IS NULL
                            JOIN kelas ON kelas.id_kelas = tagihan_biaya.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN detail_biaya ON detail_biaya.id_detail_biaya = tagihan_biaya.id_detail_biaya
                                AND detail_biaya.id_jenis_detail_biaya = 4
                                AND detail_biaya.id_bulan = ?
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.id_semester = ?
                                AND biaya_sekolah.deleted_at IS NULL
                            WHERE YEAR(pembayaran_biaya.tgl_pembayaran) = ?
                                AND MONTH(pembayaran_biaya.tgl_pembayaran) = ?
                                AND pembayaran_biaya.deleted_at IS NULL 
                                '.$where_personal.'
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat', 
                        [$id_bulan, $id_semester, $tahun, $id_bulan]);

        $list_data_pembayaran_old_month = DB::select('SELECT kelas.tingkat, SUM(pembayaran_biaya.besar_pembayaran) AS jml_pembayaran_biaya_bulan_lalu
                            FROM pembayaran_biaya
                            JOIN tagihan_biaya ON tagihan_biaya.id_tagihan_biaya = pembayaran_biaya.id_tagihan_biaya
                                AND tagihan_biaya.deleted_at IS NULL
                            JOIN kelas ON kelas.id_kelas = tagihan_biaya.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN detail_biaya ON detail_biaya.id_detail_biaya = tagihan_biaya.id_detail_biaya
                                AND detail_biaya.id_jenis_detail_biaya = 4
                                AND detail_biaya.id_bulan = ?
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.id_semester = ?
                                AND biaya_sekolah.deleted_at IS NULL
                            WHERE YEAR(pembayaran_biaya.tgl_pembayaran) = ? 
                                AND MONTH(pembayaran_biaya.tgl_pembayaran) = ?
                                AND pembayaran_biaya.deleted_at IS NULL
                                '.$where_personal.'
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat', 
                        [$id_bulan_lalu, $id_semester, $tahun, $id_bulan]);

        // List data tunggakan
        $pembayaran_tunggakan_bulan_ini = PembayaranTunggakan::where('id_semester_mulai', $semester_mulai->id_semester)
                                                        ->where('id_semester_selesai', $semester_selesai->id_semester)
                                                        ->whereMonth('tgl_pembayaran', $id_bulan)
                                                        ->isInputByPengguna($auth_data->pengguna->id_pengguna)->sum('besar_pembayaran');

        // $list_data_pembayaran_old_years = DB::select('SELECT kelas.tingkat, SUM(pembayaran_biaya.besar_pembayaran) AS jml_pembayaran_biaya_tahun_lalu
        //                     FROM pembayaran_biaya
        //                     JOIN tagihan_biaya ON tagihan_biaya.id_tagihan_biaya = pembayaran_biaya.id_tagihan_biaya
        //                         AND tagihan_biaya.deleted_at IS NULL
        //                     JOIN kelas ON kelas.id_kelas = tagihan_biaya.id_kelas
        //                         AND kelas.deleted_at IS NULL
        //                     JOIN detail_biaya ON detail_biaya.id_detail_biaya = tagihan_biaya.id_detail_biaya
        //                         AND detail_biaya.id_jenis_detail_biaya = 4
        //                         AND detail_biaya.id_bulan = ?
        //                         AND detail_biaya.deleted_at IS NULL
        //                     JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
        //                         AND biaya_sekolah.deleted_at IS NULL
        //                     JOIN semester ON semester.id_semester = biaya_sekolah.id_semester
        //                         AND semester.kode_semester < ?
        //                         AND semester.deleted_at IS NULL
        //                     WHERE YEAR(pembayaran_biaya.tgl_pembayaran) = ? 
        //                         AND MONTH(pembayaran_biaya.tgl_pembayaran) = ?
        //                         AND pembayaran_biaya.deleted_at IS NULL
        //                         '.$where_personal.'
        //                     GROUP BY kelas.tingkat
        //                     ORDER BY kelas.tingkat', 
        //                 [$id_bulan_lalu, $kode_semester_mulai, $tahun, $id_bulan]);


        $now = Carbon::now(env('APP_TIMEZONE', 'Asia/Jakarta'));

        DB::beginTransaction();
        try {
            $tutup_buku_bulanan_kas_old = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan_lalu])->first();

            foreach($list_data_jml_siswa as $i => $data) {
                $tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where([
                    'id_semester_mulai' => $id_semester_mulai,
                    'id_semester_selesai' => $id_semester_selesai,
                    'id_bulan' => $id_bulan,
                    'tingkat' => $data->tingkat
                ])->first();
                
                if($tutup_buku_bulanan_biaya) {
                    $tutup_buku_bulanan_biaya->jml_siswa            = $data->jml_siswa;
                    
                    $tagihan = collect($list_data_tagihan)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_tagihan_biaya    = (!empty($tagihan)? $tagihan->jml_tagihan_biaya : 0);
                    
                    $pembayaran = collect($list_data_pembayaran)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya    = (!empty($pembayaran)? $pembayaran->jml_pembayaran_biaya : 0);

                    $tutup_buku_bulanan_biaya->jml_tunggakan_biaya    = $tutup_buku_bulanan_biaya->jml_tagihan_biaya - $tutup_buku_bulanan_biaya->jml_pembayaran_biaya;
                    
                    if($id_bulan == 7){
                        $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu    = 0;
                    }else{
                        $pembayaran_old_month = collect($list_data_pembayaran_old_month)->firstWhere('tingkat', $data->tingkat);
                        $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu    = (!empty($pembayaran_old_month)? $pembayaran_old_month->jml_pembayaran_biaya_bulan_lalu : 0);
                    }
                    
                    // $pembayaran_old_years = collect($list_data_pembayaran_old_years)->firstWhere('tingkat', $data->tingkat);
                    // $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu    = (!empty($pembayaran_old_years)? $pembayaran_old_years->jml_pembayaran_biaya_tahun_lalu : 0);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu    = $pembayaran_tunggakan_bulan_ini;
                    $pembayaran_tunggakan_bulan_ini = 0;
                    
                    $tutup_buku_bulanan_biaya->updated_by          = $auth_data->pengguna->id_pengguna;
                    $tutup_buku_bulanan_biaya->save();
                }else {
                    $id = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();
    
                    $tutup_buku_bulanan_biaya                               = new TutupBukuBulananBiaya;
                    $tutup_buku_bulanan_biaya->id_tutup_buku_bulanan_biaya  = $id;
                    $tutup_buku_bulanan_biaya->id_semester_mulai            = $id_semester_mulai;
                    $tutup_buku_bulanan_biaya->id_semester_selesai          = $id_semester_selesai;
                    $tutup_buku_bulanan_biaya->id_bulan                     = $id_bulan;
                    $tutup_buku_bulanan_biaya->tingkat                      = $data->tingkat;
                    $tutup_buku_bulanan_biaya->jml_siswa                    = $data->jml_siswa;

                    $tagihan = collect($list_data_tagihan)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_tagihan_biaya    = (!empty($tagihan)? $tagihan->jml_tagihan_biaya : 0);
                    
                    $pembayaran = collect($list_data_pembayaran)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya    = (!empty($pembayaran)? $pembayaran->jml_pembayaran_biaya : 0);

                    $tutup_buku_bulanan_biaya->jml_tunggakan_biaya    = $tutup_buku_bulanan_biaya->jml_tagihan_biaya - $tutup_buku_bulanan_biaya->jml_pembayaran_biaya;

                    if($id_bulan == 7){
                        $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu    = 0;
                    }else{
                        $pembayaran_old_month = collect($list_data_pembayaran_old_month)->firstWhere('tingkat', $data->tingkat);
                        $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu    = (!empty($pembayaran_old_month)? $pembayaran_old_month->jml_pembayaran_biaya_bulan_lalu : 0);
                    }

                    // $pembayaran_old_years = collect($list_data_pembayaran_old_years)->firstWhere('tingkat', $data->tingkat);
                    // $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu    = (!empty($pembayaran_old_years)? $pembayaran_old_years->jml_pembayaran_biaya_tahun_lalu : 0);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu    = $pembayaran_tunggakan_bulan_ini;
                    $pembayaran_tunggakan_bulan_ini = 0;

                    $tutup_buku_bulanan_biaya->created_by                   = $auth_data->pengguna->id_pengguna;
                    $tutup_buku_bulanan_biaya->save();
                }
            }

            $data_tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where([
                'id_semester_mulai' => $id_semester_mulai,
                'id_semester_selesai' => $id_semester_selesai,
                'id_bulan' => $id_bulan
            ])->get();
    
            /* INSERT TUTUP BUKU TAHUNAN BIAYA */
            $pembayaran_tunggakan_tahun_lalu = $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_tahun_lalu');
    
            $id_semester_mulai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu.'1')->first()->id_semester;
            $id_semester_selesai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu.'2')->first()->id_semester;
    
            $tutup_buku_tahunan_biaya_old = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $id_semester_mulai_tahun_lalu, 'id_semester_selesai' => $id_semester_selesai_tahun_lalu])->first();
    
            if($tutup_buku_tahunan_biaya_now = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai])->first()){
                $tutup_buku_tahunan_biaya_now->updated_by                   = $auth_data->pengguna->id_pengguna;
            }else{
                $tutup_buku_tahunan_biaya_now = new TutupBukuTahunanBiaya;
                $tutup_buku_tahunan_biaya_now->id_tutup_buku_tahunan_biaya  = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $tutup_buku_tahunan_biaya_now->id_semester_mulai            = $id_semester_mulai;
                $tutup_buku_tahunan_biaya_now->id_semester_selesai          = $id_semester_selesai;
                $tutup_buku_tahunan_biaya_now->created_by                   = $auth_data->pengguna->id_pengguna;
            }
            
            $tutup_buku_tahunan_biaya_now->jml_tunggakan_biaya          = $tutup_buku_tahunan_biaya_old->jml_tunggakan_biaya - $pembayaran_tunggakan_tahun_lalu;
            $tutup_buku_tahunan_biaya_now->save();
            /* END INSERT TUTUP BUKU TAHUNAN BIAYA */
    
            /* INSERT TUTUP BUKU BULANAN KAS */
            $pembayaran_tunggakan_bulan_ini = $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya');
            $pembayaran_tunggakan_bulan_lalu = $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_bulan_lalu');
    
            $data_realisasi = Realisasi::selectRaw('
                                        nm_kategori_rapb, 
                                        kode_subkategori_rapb,
                                        nm_subkategori_rapb,
                                        tipe_kategori_rapb,
                                        SUM(dana_realisasi) as total_realisasi,
                                        dana_perkiraan_rapb')
                                    ->join('rapb', function($q){
                                        $q->on('rapb.id_rapb', '=', 'realisasi.id_rapb')
                                            ->whereNull('rapb.deleted_at');
                                    })
                                    ->join('subkategori_rapb', function($q){
                                        $q->on('subkategori_rapb.id_subkategori_rapb', '=' ,'rapb.id_subkategori_rapb')
                                            ->whereNull('subkategori_rapb.deleted_at');
                                    })
                                    ->join('kategori_rapb', function($q){
                                        $q->on('kategori_rapb.id_kategori_rapb', '=' ,'subkategori_rapb.id_kategori_rapb')
                                            ->whereNull('kategori_rapb.deleted_at');
                                    })
                                    ->whereMonth('tgl_realisasi', $id_bulan)
                                    ->whereYear('tgl_realisasi', $tahun);

            if($print_setting == 'self'){
                $data_realisasi = $data_realisasi->isInputByPengguna($auth_data->pengguna->id_pengguna);
            }

            $data_realisasi = $data_realisasi->groupBy('realisasi.id_rapb', 'nm_kategori_rapb', 'kode_subkategori_rapb', 'nm_subkategori_rapb', 'tipe_kategori_rapb', 'dana_perkiraan_rapb')->get();

            if($tutup_buku_bulanan_kas_now = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan])->first()){
                $tutup_buku_bulanan_kas_now->updated_by                   = $auth_data->pengguna->id_pengguna;
            }else{
                $tutup_buku_bulanan_kas_now = new TutupBukuBulananKas;
                $tutup_buku_bulanan_kas_now->id_tutup_buku_bulanan_kas    = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $tutup_buku_bulanan_kas_now->id_semester_mulai            = $id_semester_mulai;
                $tutup_buku_bulanan_kas_now->id_semester_selesai          = $id_semester_selesai;
                $tutup_buku_bulanan_kas_now->id_bulan                     = $id_bulan;
                $tutup_buku_bulanan_kas_now->created_by                   = $auth_data->pengguna->id_pengguna;
            }
            $tutup_buku_bulanan_kas_now->kas_spp                = $pembayaran_tunggakan_bulan_ini + $pembayaran_tunggakan_bulan_lalu + $pembayaran_tunggakan_tahun_lalu;
            $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan    = $data_realisasi->where('tipe_kategori_rapb', 1)->sum('total_realisasi');
            $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran   = $data_realisasi->where('tipe_kategori_rapb', 2)->sum('total_realisasi');
            $tutup_buku_bulanan_kas_now->kas_akhir_bulan        = $tutup_buku_bulanan_kas_now->kas_spp + $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan - $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran + $tutup_buku_bulanan_kas_old->kas_akhir_bulan;
            $tutup_buku_bulanan_kas_now->save();
            /* END INSERT TUTUP BUKU BULANAN KAS */

            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollback();

            dd((env('APP_DEBUG', 'true') == 'true')? $e->getMessage(). '. In Line: ' . $e->getLine() : 'There is something wrong. Error Code '.$e->getLine());
        }

        $data_tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan ])->orderBy('tingkat')->get();

        $subkategori_rapb = SubkategoriRapb::whereHas('kategori', function($q){
            $q->where('tipe_kategori_rapb', 2);
        })->get()->pluck('id_subkategori_rapb');

        $data_realisasi = Rapb::selectRaw('
                                        nm_kategori_rapb, 
                                        kode_subkategori_rapb,
                                        nm_subkategori_rapb,
                                        tipe_kategori_rapb,
                                        SUM(dana_realisasi) as total_realisasi,
                                        dana_perkiraan_rapb')
                                    ->leftJoin('realisasi', function($q) use ($id_bulan, $tahun, $print_setting, $auth_data){
                                        $q->on('realisasi.id_rapb', '=', 'rapb.id_rapb')
                                            ->whereNull('realisasi.deleted_at')
                                            ->whereMonth('realisasi.tgl_realisasi', $id_bulan)
                                            ->whereYear('realisasi.tgl_realisasi', $tahun);

                                        if($print_setting == 'self'){
                                            $q->where('realisasi.created_by', $auth_data->pengguna->id_pengguna);
                                        }
                                    })
                                    ->join('subkategori_rapb', function($q){
                                        $q->on('subkategori_rapb.id_subkategori_rapb', '=' ,'rapb.id_subkategori_rapb')
                                            ->whereNull('subkategori_rapb.deleted_at');
                                    })
                                    ->join('kategori_rapb', function($q){
                                        $q->on('kategori_rapb.id_kategori_rapb', '=' ,'subkategori_rapb.id_kategori_rapb')
                                            ->whereNull('kategori_rapb.deleted_at');
                                    })
                                    ->where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai])
                                    ->orderBy('subkategori_rapb.kode_subkategori_rapb');

        if($print_setting == 'self'){
            $data_realisasi = $data_realisasi->isInputByPengguna($auth_data->pengguna->id_pengguna);
        }                            

        $data_realisasi = $data_realisasi->groupBy('realisasi.id_rapb', 'nm_kategori_rapb', 'kode_subkategori_rapb', 'nm_subkategori_rapb', 'tipe_kategori_rapb', 'dana_perkiraan_rapb')->get();
        $bulan = Bulan::find($id_bulan);
        $sekolah = $auth_data->sekolah_data;

        $tutup_buku_tahun_ini = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai])->firstOrFail();
        $tutup_buku_kas_bulan_ini = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan])->firstOrFail();
        $tutup_buku_kas_bulan_lalu = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan_lalu])->firstOrFail();
        
        // START SHOW Beban Non-KBM
        $subkategori_non_kbm = SubkategoriRapb::where('kode_subkategori_rapb', 'K.5.3')->where('nm_subkategori_rapb', 'Beban Pembelajaran Non KBM')->first();
            
        if($subkategori_non_kbm){
            $pembayaran_non_kbm = PembayaranBiaya::with('tagihan_biaya.kelas', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')
                                                ->whereMonth('tgl_pembayaran', $id_bulan)
                                                ->whereYear('tgl_pembayaran', $tahun)
                                                ->whereHas('tagihan_biaya.detail_biaya', function($q){
                                                    $q->where('id_jenis_detail_biaya', 4);
                                                });
            if($print_setting == 'self'){
                $pembayaran_non_kbm = $pembayaran_non_kbm->where('pembayaran_biaya.created_by', $auth_data->pengguna->id_pengguna)->get();   
            }else{
                $pembayaran_non_kbm = $pembayaran_non_kbm->get();   
            }
            $temp_data_bayar_non_kbm = [];
            $total_bayar_non_kbm = 0;

            foreach($pembayaran_non_kbm as $data){
                // Get Biaya Internal (kelompok_biaya_internal)
                $biayaInternal = $data->tagihan_biaya->detail_biaya->kelompok_biaya_internal;
                
                if(!empty($biayaInternal)){
                    $tingkat        = $data->tagihan_biaya->kelas->tingkat;
                    
                    $detailBiayaInternal = $biayaInternal->detail_biaya_internal;
                    
                    foreach($detailBiayaInternal as $x){
                        if($x->nm_detail_biaya_internal != 'SPP MURNI'){
                            if(!isset($temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat])){
                                $temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat] = $x->besar_biaya;
                            }else{
                                $temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat] += $x->besar_biaya;
                            }
    
                            $total_bayar_non_kbm += $x->besar_biaya;
                        }
                    }
                }
            }

            $subkategori_non_kbm = [
                'status' => true,
                'total_bayar' => $total_bayar_non_kbm,
                'data' => $temp_data_bayar_non_kbm
            ];
        }else{
            $subkategori_non_kbm = [
                'status' => false,
            ];
        }
        // END SHOW BEBAN NON-KBM

        $data = [
            'data_tutup_buku_bulanan_biaya' => $data_tutup_buku_bulanan_biaya,
            'data_realisasi' => $data_realisasi,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'sekolah' => $sekolah,
            'tutup_buku_tahun_ini' => $tutup_buku_tahun_ini,
            'tutup_buku_kas_bulan_ini' => $tutup_buku_kas_bulan_ini,
            'tutup_buku_kas_bulan_lalu' => $tutup_buku_kas_bulan_lalu,
            'subkategori_non_kbm' => $subkategori_non_kbm
        ];

        return $data;
    }
    /** Get Laporan Keuangan */
    public static function fetchLaporanKeuangan($auth_data, $start_date = null, $end_date = null)
    {
        $dataLaporan = []; // tgl, keterangan, tipe (debit/kredit), nominal
        $tempDataLaporan = [];

        $dataPembayaran = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.siswa', 'tagihan_biaya.siswa.pengguna', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')->with(['tagihan_biaya.detail_biaya' => function ($q) {
            $q->with('biaya');
        }]);

        if (!empty($start_date) && !empty($end_date)) {
            $dataPembayaran = $dataPembayaran->whereBetween('tgl_pembayaran', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }
        $allDataPembayaran = $dataPembayaran->get();

        foreach($allDataPembayaran as $x){
            $date = new DateTime($x->tgl_pembayaran);

            if($x->tagihan_biaya->detail_biaya->id_bulan === null){ // untuk non-SPP
                $keterangan = $x->tagihan_biaya->detail_biaya->biaya->nm_biaya . ' - ' . $x->tagihan_biaya->keterangan;
                
                $tempDataLaporan[] = [
                    'tanggal' => $date->format('Y-m-d'),
                    'keterangan' => $x->tagihan_biaya->siswa->pengguna->nm_pengguna .' - '. $keterangan,
                    'nominal' => $x->besar_pembayaran,
                    // 'tahun_ajaran' => ,
                    'nm_tipe' => 'debit',
                    'tipe' => 1, //penerimaan
                ];
            } else { // untuk SPP
                $keterangan = $x->tagihan_biaya->detail_biaya->biaya->nm_biaya . ' - ' . Carbon::createFromFormat('m', $x->tagihan_biaya->detail_biaya->id_bulan)->format('F');

                foreach($x->tagihan_biaya->detail_biaya->kelompok_biaya_internal->detail_biaya_internal as $spp){
                    $tempDataLaporan[] = [
                        'tanggal' => $date->format('Y-m-d'),
                        'keterangan' => $x->tagihan_biaya->siswa->pengguna->nm_pengguna .' - '. $keterangan . ' (' .$spp->nm_detail_biaya_internal . ')',
                        'nominal' => $spp->besar_biaya,
                        // 'tahun_ajaran' => ,
                        'nm_tipe' => 'debit',
                        'tipe' => 1, //penerimaan
                    ];
                }
            }
        }
        
        $dataRealisasi = Realisasi::with('rapb.subkategori.kategori');
        if (!empty($start_date) && !empty($end_date)) {
            $dataRealisasi = $dataRealisasi->whereBetween('tgl_realisasi', [$start_date, $end_date]);
        }
        $allDataRealisasi = $dataRealisasi->get();

        foreach($allDataRealisasi as $x){
            $keterangan = $x->rapb->subkategori->kategori->nm_kategori_rapb . ' - ' . $x->nm_realisasi;
            $tipe = $x->rapb->subkategori->kategori->tipe_kategori_rapb;
            $date = new DateTime($x->tgl_realisasi);
            $tempDataLaporan[] = [
                'tanggal' => $date->format('Y-m-d'),
                'keterangan' => $keterangan,
                'nominal' => $x->dana_realisasi,
                // 'tahun_ajaran' => ,
                'nm_tipe' => $tipe == 1 ? 'debit' : 'kredit',
                'tipe' => $tipe,
            ];
        }

        // reordering by date descending
        foreach(collect($tempDataLaporan)->sortByDesc('tanggal') as $data){
            $dataLaporan[] = $data;
        }

        $totalDebit = collect($dataLaporan)->where('tipe', 1)->sum('nominal');
        $totalKredit = collect($dataLaporan)->where('tipe', 2)->sum('nominal');
        
        $data = [
            'laporan' => collect($dataLaporan)->sortByDesc('tanggal'),
            'total_debit' => $totalDebit,
            'total_kredit' => $totalKredit
        ];

        return $data;
    }

    public static function fetchLaporanPengeluaranByKategori($auth_data, $start_date = null, $end_date = null)
    {
        if(empty(session('setting_print_keuangan'))){
            $print_setting = 'all';
        }else{
            $print_setting = session('setting_print_keuangan');
        }

        $dataLaporan = []; // tgl, keterangan, tipe (debit/kredit), nominal
        $tempDataLaporan = [];

        $allBiaya = Biaya::get();

        $dataRealisasi = Realisasi::query()
                                    ->with('rapb.subkategori.kategori')
                                    ->whereHas('rapb.subkategori.kategori', function($q){
                                        $q->where('tipe_kategori_rapb', 2);
                                    });

        if (!empty($start_date) && !empty($end_date)) {
            $dataRealisasi = $dataRealisasi->whereBetween('tgl_realisasi', [$start_date, $end_date]);
        }

        if($print_setting == 'self'){
            $allDataRealisasi = $dataRealisasi->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->sortBy('tgl_realisasi')->sortBy('rapb.subkategori.kode_subkategori_rapb');
        }else{
            $allDataRealisasi = $dataRealisasi->get()->sortBy('tgl_realisasi')->sortBy('rapb.subkategori.kode_subkategori_rapb');
        }

        $totalLaporan = $allDataRealisasi->sum('dana_realisasi');

        // START SHOW Beban Non-KBM
        $subkategori_non_kbm = SubkategoriRapb::where('kode_subkategori_rapb', 'K.5.3')->where('nm_subkategori_rapb', 'Beban Pembelajaran Non KBM')->first();
            
        if($subkategori_non_kbm){
            $pembayaran_non_kbm = PembayaranBiaya::with('tagihan_biaya.kelas', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')
                                                ->whereBetween('tgl_pembayaran', [$start_date, $end_date])
                                                ->whereHas('tagihan_biaya.detail_biaya', function($q){
                                                    $q->where('id_jenis_detail_biaya', 4);
                                                });
            if($print_setting == 'self'){
                $pembayaran_non_kbm = $pembayaran_non_kbm->where('pembayaran_biaya.created_by', $auth_data->pengguna->id_pengguna)->get();   
            }else{
                $pembayaran_non_kbm = $pembayaran_non_kbm->get();   
            }
            $temp_data_bayar_non_kbm = [];
            $total_bayar_non_kbm = 0;

            foreach($pembayaran_non_kbm as $data){
                // Get Biaya Internal (kelompok_biaya_internal)
                $biayaInternal = $data->tagihan_biaya->detail_biaya->kelompok_biaya_internal;
                
                if(!empty($biayaInternal)){
                    $tingkat        = $data->tagihan_biaya->kelas->tingkat;
                    
                    $detailBiayaInternal = $biayaInternal->detail_biaya_internal;
                    
                    foreach($detailBiayaInternal as $x){
                        if($x->nm_detail_biaya_internal != 'SPP MURNI'){
                            if(!isset($temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat])){
                                $temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat] = $x->besar_biaya;
                            }else{
                                $temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat] += $x->besar_biaya;
                            }
    
                            $total_bayar_non_kbm += $x->besar_biaya;
                        }
                    }
                }
            }

            $subkategori_non_kbm = [
                'status' => true,
                'total_bayar' => $total_bayar_non_kbm,
                'data' => $temp_data_bayar_non_kbm
            ];
        }else{
            $subkategori_non_kbm = [
                'status' => false,
            ];
        }

        // dd($temp_data_bayar_non_kbm);
        // END SHOW BEBAN NON-KBM
        
        $data = [
            'data' => $allDataRealisasi->groupBy(function ($item, $key){
                return $item->rapb->subkategori->kode_subkategori_rapb.' '.$item->rapb->subkategori->nm_subkategori_rapb;
            }),
            'total_data' => $totalLaporan,
            'subkategori_non_kbm' => $subkategori_non_kbm,
            'tingkat' => Kelas::select('tingkat')->distinct()->get()->pluck('tingkat')
        ];

        return $data;
    }
    
    public static function fetchLaporanKasInternal($auth_data, $start_date = null, $end_date = null)
    {
        if(empty(session('setting_print_keuangan'))){
            $print_setting = 'all';
        }else{
            $print_setting = session('setting_print_keuangan');
        }

        $dataLaporan = []; // tgl, keterangan, tipe (debit/kredit), nominal
        $tempDataLaporan = [];

        $allBiaya = Biaya::get();

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal');
        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }

        if($print_setting == 'self'){
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        }else{
            $allDataPembayaran = $pembayaran->get();
        }

        // find Pembayaran in each Biaya
        foreach($allBiaya as $kategori){
            // pembayaran for this Biaya per TA
            $pembayaran = $allDataPembayaran->where('tagihan_biaya.detail_biaya.biaya.nm_biaya', '=', $kategori->nm_biaya)->groupBy('tagihan_biaya.detail_biaya.biaya_sekolah.semester.thn_akademik_semester');
            
            foreach($pembayaran as $ta => $value){
                $stringNmBiaya = $value->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya;

                // loop each pembayaran
                $val = $value;
                foreach($value as $data){
                    // Get Biaya Internal (kelompok_biaya_internal)
                    $biayaInternal = $data->tagihan_biaya->detail_biaya->kelompok_biaya_internal;
                    // === var for $tempDataLaporan
                    $date           = new DateTime($data->tgl_pembayaran);
                    $ket_biaya      = $data->tagihan_biaya->detail_biaya->keterangan_biaya;
                    $count          = $val->where('tagihan_biaya.detail_biaya.keterangan_biaya', '=', $ket_biaya)
                                        ->filter(function($q) use ($date){
                                            $qDate = new DateTime($q->tgl_pembayaran);
                                            return $qDate->format('Y-m-d') == $date->format('Y-m-d'); 
                                        })->count();
                    $keyTempData    = $kategori->nm_biaya . '-' . $ket_biaya . '-' . $ta;
                    $tahun_ajaran   = $data->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran;
                    // ===

                    if(!empty($biayaInternal)){
                        $detailBiayaInternal = $biayaInternal->detail_biaya_internal;
                        
                        foreach($detailBiayaInternal as $x){
                            $tempDataLaporan[$keyTempData . $x->nm_detail_biaya_internal] = [
                                'tanggal' => $date->format('Y-m-d'),
                                'nominal' => $x->besar_biaya * $count,
                                'frekuensi' => $count,
                                'tipe' => 1,
                                'nm_tipe' => 'debit',
                                'kategori' => $stringNmBiaya,
                                'keterangan' => $x->nm_detail_biaya_internal . ' ' . $count . 'x '. number_format($x->besar_biaya) .' (' . $tahun_ajaran . ')',
                                'tahun_ajaran' => $tahun_ajaran
                            ];
                        }
                    } else {
                        $nominal = $val->where('tagihan_biaya.detail_biaya.keterangan_biaya', '=', $ket_biaya)->filter(function($q) use ($date){
                            $qDate = new DateTime($q->tgl_pembayaran);
                            return $qDate->format('Y-m-d') == $date->format('Y-m-d'); 
                        });

                        $tempDataLaporan[$keyTempData . $date->format('Y-m-d')] = [
                            'tanggal' => $date->format('Y-m-d'),
                            'nominal' => $nominal->sum('besar_pembayaran'),
                            'frekuensi' => $count,
                            'tipe' => 1,
                            'nm_tipe' => 'debit',
                            'kategori' => $stringNmBiaya,
                            'keterangan' => $ket_biaya . ' ' . $count . 'x (' . $tahun_ajaran . ')',
                            'tahun_ajaran' => $tahun_ajaran
                        ];
                    }
                }
            }
        }
        
        $dataRealisasi = Realisasi::with('rapb.subkategori.kategori');
        if (!empty($start_date) && !empty($end_date)) {
            $dataRealisasi = $dataRealisasi->whereBetween('tgl_realisasi', [$start_date, $end_date]);
        }
        
        if($print_setting == 'self'){
            $allDataRealisasi = $dataRealisasi->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        }else{
            $allDataRealisasi = $dataRealisasi->get();
        }

        foreach($allDataRealisasi as $x){
            $kategori = $x->rapb->subkategori->kategori->nm_kategori_rapb;
            $keterangan = $x->nm_realisasi;
            $tipe       = $x->rapb->subkategori->kategori->tipe_kategori_rapb;
            $date       = new DateTime($x->tgl_realisasi);
            $tempDataLaporan[] = [
                'tanggal' => $date->format('Y-m-d'),
                'nominal' => $x->dana_realisasi,
                'frekuensi' => null,
                'tipe' => $tipe,
                'nm_tipe' => $tipe == 1 ? 'debit' : 'kredit',
                'kategori' => $kategori,
                'keterangan' => ($keterangan == '-' || $keterangan == null) ? $kategori : $keterangan,
                'tahun_ajaran' => null,
            ];
        }
        
        // reordering by date descending
        foreach(collect($tempDataLaporan)->sortByDesc('tanggal') as $data){
            $dataLaporan[] = $data;
        }

        $totalDebit = collect($dataLaporan)->where('tipe', 1)->sum('nominal');
        $totalKredit = collect($dataLaporan)->where('tipe', 2)->sum('nominal');
        
        $data = [
            'laporan' => $dataLaporan,
            'total_debit' => $totalDebit,
            'total_kredit' => $totalKredit
        ];

        return $data;
    }

    public static function fetchLaporanKasReguler($auth_data, $start_date = null, $end_date = null)
    {
        if(empty(session('setting_print_keuangan'))){
            $print_setting = 'all';
        }else{
            $print_setting = session('setting_print_keuangan');
        }

        $dataLaporan = []; // tgl, keterangan, tipe (debit/kredit), nominal
        $tempDataLaporan = [];

        $allBiaya = Biaya::get();

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester');
        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }

        if($print_setting == 'self'){
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        }else{
            $allDataPembayaran = $pembayaran->get();
        }

        // find Pembayaran in each Biaya
        foreach($allBiaya as $kategori){
            // pembayaran for this Biaya per TA
            $pembayaran = $allDataPembayaran->where('tagihan_biaya.detail_biaya.biaya.nm_biaya', '=', $kategori->nm_biaya)->groupBy('tagihan_biaya.detail_biaya.biaya_sekolah.semester.thn_akademik_semester');
            
            foreach($pembayaran as $ta => $value){
                $stringNmBiaya = $value->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya;

                // loop each pembayaran
                $val = $value;
                foreach($value as $data){
                    // Get Biaya Internal (kelompok_biaya_internal)
                    $biayaInternal = $data->tagihan_biaya->detail_biaya->kelompok_biaya_internal;
                    // === var for $tempDataLaporan
                    $date           = new DateTime($data->tgl_pembayaran);
                    $ket_biaya      = $data->tagihan_biaya->detail_biaya->keterangan_biaya;
                    $count          = $val->where('tagihan_biaya.detail_biaya.keterangan_biaya', '=', $ket_biaya)
                                        ->filter(function($q) use ($date){
                                            $qDate = new DateTime($q->tgl_pembayaran);
                                            return $qDate->format('Y-m-d') == $date->format('Y-m-d'); 
                                        })->count();
                    $keyTempData    = $kategori->nm_biaya . '-' . $ket_biaya . '-' . $ta;
                    $tahun_ajaran   = $data->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran;
                    // ===

                    $nominal = $val->where('tagihan_biaya.detail_biaya.keterangan_biaya', '=', $ket_biaya)->filter(function($q) use ($date){
                        $qDate = new DateTime($q->tgl_pembayaran);
                        return $qDate->format('Y-m-d') == $date->format('Y-m-d'); 
                    });

                    $tempDataLaporan[$keyTempData . $date->format('Y-m-d')] = [
                        'tanggal' => $date->format('Y-m-d'),
                        'nominal' => $nominal->sum('besar_pembayaran'),
                        'frekuensi' => $count,
                        'tipe' => 1,
                        'nm_tipe' => 'debit',
                        'kategori' => $stringNmBiaya,
                        'keterangan' => $keyTempData . ' ' . $count . 'x (' . $tahun_ajaran . ')',
                        'tahun_ajaran' => $tahun_ajaran
                    ];
                }
            }
        }
        
        $dataRealisasi = Realisasi::with('rapb.subkategori.kategori');
        if (!empty($start_date) && !empty($end_date)) {
            $dataRealisasi = $dataRealisasi->whereBetween('tgl_realisasi', [$start_date, $end_date]);
        }

        if($print_setting == 'self'){
            $allDataRealisasi = $dataRealisasi->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        }else{
            $allDataRealisasi = $dataRealisasi->get();
        }

        foreach($allDataRealisasi as $x){
            $kategori = $x->rapb->subkategori->kategori->nm_kategori_rapb;
            $keterangan = $x->nm_realisasi;
            $tipe       = $x->rapb->subkategori->kategori->tipe_kategori_rapb;
            $date       = new DateTime($x->tgl_realisasi);
            $tempDataLaporan[] = [
                'tanggal' => $date->format('Y-m-d'),
                'nominal' => $x->dana_realisasi,
                'frekuensi' => null,
                'tipe' => $tipe,
                'nm_tipe' => $tipe == 1 ? 'debit' : 'kredit',
                'kategori' => $kategori,
                'keterangan' => ($keterangan == '-' || $keterangan == null) ? $kategori : $keterangan,
                'tahun_ajaran' => null,
            ];
        }
        
        // reordering by date descending
        foreach(collect($tempDataLaporan)->sortByDesc('tanggal') as $data){
            $dataLaporan[] = $data;
        }

        $totalDebit = collect($dataLaporan)->where('tipe', 1)->sum('nominal');
        $totalKredit = collect($dataLaporan)->where('tipe', 2)->sum('nominal');
        
        $data = [
            'laporan' => $dataLaporan,
            'total_debit' => $totalDebit,
            'total_kredit' => $totalKredit
        ];

        return $data;
    }
    
    public static function fetchLaporanPembayaranPerSiswa($auth_data, $start_date = null, $end_date = null)
    {
        if(empty(session('setting_print_keuangan'))){
            $print_setting = 'all';
        }else{
            $print_setting = session('setting_print_keuangan');
        }

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.siswa.pengguna', 'tagihan_biaya.siswa.kelas', 'tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal');
        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }

        if($print_setting == 'self'){
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->groupBy('tagihan_biaya.siswa.id_siswa');
        }else{
            $allDataPembayaran = $pembayaran->get()->groupBy('tagihan_biaya.siswa.id_siswa');
        }
        
        $listData = [];
        $ketTagihan = '';
        $idBiaya = null;
        foreach($allDataPembayaran as $idSiswa => $siswa){
            $tagihanBiayaSiswa = $siswa->first()->tagihan_biaya;

            $summ = [];
            foreach($siswa->groupBy('tagihan_biaya.detail_biaya.biaya.id_biaya') as $idBiaya => $rwytBayar){
                $item = $rwytBayar->first();

                if($idBiaya != $item->tagihan_biaya->detail_biaya->biaya->id_biaya){
                    $ketTagihan = ''; // reset
                }
                $idBiaya = $item->tagihan_biaya->detail_biaya->biaya->id_biaya;
                $ketTagihan = $ketTagihan . (empty($ketTagihan) ? '' : ', ') . $item->tagihan_biaya->keterangan;

                $summ[] = [
                    'id_siswa' => $idSiswa,
                    'id_biaya' => $idBiaya,
                    'nis_siswa' => $item->tagihan_biaya->siswa->nis_siswa,
                    'nm_siswa' => $item->tagihan_biaya->siswa->pengguna->nm_pengguna,
                    'kelas_siswa' => $item->tagihan_biaya->siswa->kelas->nm_kelas,
                    'total_nominal_pembayaran' => $rwytBayar->sum('besar_pembayaran'),
                    'frekuensi_pembayaran' => $rwytBayar->count(),
                    'kategori_biaya' => $item->tagihan_biaya->detail_biaya->biaya->nm_biaya,
                    'keterangan_tagihan' => $item->tagihan_biaya->detail_biaya->id_jenis_detail_biaya == 4 ? $item->tagihan_biaya->keterangan : $ketTagihan,
                    'keterangan_biaya' => $item->tagihan_biaya->detail_biaya->biaya->keterangan_biaya,
                    'tahun_ajaran_tagihan' => $item->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran
                ];
            }

            $listData[] = [
                'id_siswa' => $idSiswa,
                'nis_siswa' => $tagihanBiayaSiswa->siswa->nis_siswa,
                'nm_siswa' => $tagihanBiayaSiswa->siswa->pengguna->nm_pengguna,
                'kelas_siswa' => $tagihanBiayaSiswa->siswa->kelas->nm_kelas,
                'summary' => $summ
            ];
        }

        $allPembayaranBiaya = $pembayaran->get()->groupBy('tagihan_biaya.detail_biaya.biaya.id_biaya');
        $summaryData = [];
        foreach($allPembayaranBiaya as $idBiaya => $biaya){
            $summaryData[] = [
                'id_biaya' => $biaya->first()->tagihan_biaya->detail_biaya->biaya->id_biaya,
                'nm_biaya' => $biaya->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya,
                'frekuensi' => $biaya->count(),
                'id_jenis_detail_biaya' => $biaya->first()->tagihan_biaya->detail_biaya->id_jenis_detail_biaya,
                'total_pembayaran' => $biaya->sum('besar_pembayaran')
            ];
        }

        $result = [
            'data' => $listData,
            'summary' => $summaryData,
            'kategori_biaya' => collect($summaryData)->pluck('nm_biaya', 'id_biaya')
        ];

        return $result;
    }

    public static function fetchLaporanPembayaranPerKelas($auth_data, $start_date = null, $end_date = null)
    {
        if(empty(session('setting_print_keuangan'))){
            $print_setting = 'all';
        }else{
            $print_setting = session('setting_print_keuangan');
        }

        $allDataPembayaran = PembayaranBiaya::with('tagihan_biaya.kelas');

        if (!empty($start_date) && !empty($end_date)) {
            $allDataPembayaran = $allDataPembayaran->whereBetween('tgl_pembayaran', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }

        if($print_setting == 'self'){
            $allDataPembayaran = $allDataPembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        }else{
            $allDataPembayaran = $allDataPembayaran->get();
        }

        $result = [
            'data' => $allDataPembayaran
        ];

        return $result;
    }

    public static function fetchLaporanPembayaranPerTingkat($auth_data, $start_date = null, $end_date = null)
    {
        if(empty(session('setting_print_keuangan'))){
            $print_setting = 'all';
        }else{
            $print_setting = session('setting_print_keuangan');
        }

        $allDataPembayaran = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.detail_biaya', 'tagihan_biaya.kelas');

        if (!empty($start_date) && !empty($end_date)) {
            $allDataPembayaran = $allDataPembayaran->whereBetween('tgl_pembayaran', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }

        if($print_setting == 'self'){
            $allDataPembayaran = $allDataPembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        }else{
            $allDataPembayaran = $allDataPembayaran->get();
        }

        $result = [
            'data' => $allDataPembayaran,
            'dates' => CarbonPeriod::create($start_date, $end_date),
            'tingkat' => Kelas::select('tingkat')->distinct()->get()->pluck('tingkat')
        ];

        return $result;
    }
    
    public static function fetchLaporanPembayaranPerTanggal($auth_data, $start_date, $end_date)
    {
        if(empty(session('setting_print_keuangan'))){
            $print_setting = 'all';
        }else{
            $print_setting = session('setting_print_keuangan');
        }

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.siswa.pengguna', 'tagihan_biaya.siswa.kelas', 'tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal');
        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }
        if($print_setting == 'self'){
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->groupBy(function($pay){
                return Carbon::parse($pay->tgl_pembayaran)->format('Y-m-d');
            });
        }else{
            $allDataPembayaran = $pembayaran->get()->groupBy(function($pay){
                return Carbon::parse($pay->tgl_pembayaran)->format('Y-m-d');
            });
        }
        
        $listData = [];
        foreach($allDataPembayaran as $tanggal => $rwytBayar){
            $detail = [];
            foreach($rwytBayar->groupBy('tagihan_biaya.detail_biaya.biaya.id_biaya') as $x){
                $detail[] = [
                    'id_biaya' => collect($x)->first()->tagihan_biaya->detail_biaya->biaya->id_biaya,
                    'nama_biaya' => collect($x)->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya,
                    'frekuensi' => collect($x)->count(),
                    'nominal_pembayaran' => collect($x)->sum('besar_pembayaran')
                ];
            }

            $listData[] = [
               'tanggal_pembayaran' => $tanggal,
               'total_pembayaran' => collect($detail)->sum('nominal_pembayaran'),
               'detail' => $detail
            ];
        }

        $result = [
            'data' => $listData,
            'kategori_biaya' => $pembayaran->get()->pluck('tagihan_biaya.detail_biaya.biaya.nm_biaya', 'tagihan_biaya.detail_biaya.biaya.id_biaya')
        ];

        return $result;
    }
    
    public static function fetchLaporanPembayaranPerBulan($auth_data, $start_year, $end_year)
    {
        if(empty(session('setting_print_keuangan'))){
            $print_setting = 'all';
        }else{
            $print_setting = session('setting_print_keuangan');
        }

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.siswa.pengguna', 'tagihan_biaya.siswa.kelas', 'tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal');

        if (!empty($start_year) && !empty($end_year)) {
            $pembayaran = $pembayaran->where(function($month) use ($start_year, $end_year){
                $month->whereYear('tgl_pembayaran', '>=', $start_year);
                $month->whereYear('tgl_pembayaran', '<=', $end_year);
            });
        }

        if($print_setting == 'self'){
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->groupBy(function($pay){
                return Carbon::parse($pay->tgl_pembayaran)->format('Y-m');
            });
        }else{
            $allDataPembayaran = $pembayaran->get()->groupBy(function($pay){
                return Carbon::parse($pay->tgl_pembayaran)->format('Y-m');
            });
        }

        $listData = [];
        foreach($allDataPembayaran as $rwytBayarPerTgl){            
            $details = [];

            $rwytDetail = $rwytBayarPerTgl->groupBy('tagihan_biaya.detail_biaya.biaya.id_biaya');

            foreach($rwytDetail as $dtl){
                $details[] = [
                    'id_biaya' => $dtl->first()->tagihan_biaya->detail_biaya->biaya->id_biaya,
                    'nm_biaya' => $dtl->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya,
                    'frekuensi' => $dtl->count(),
                    'total_pembayaran' => $dtl->sum('besar_pembayaran')
                ];
            }
            
            $tglPembayaran = Carbon::parse($rwytBayarPerTgl->first()->tgl_pembayaran);
            $listData[] = [
                'kode_bulan' => $tglPembayaran->format('m'),
                'tahun' => $tglPembayaran->format('Y'),
                'frekuensi' => $rwytBayarPerTgl->count(),
                'total_pembayaran' => $rwytBayarPerTgl->sum('besar_pembayaran'),
                'details' => $details
            ];
        }
        $tempResult = [];
        $bulan = Bulan::orderBy('id_bulan')->get();
        $diffYear = $end_year - $start_year;
        for($i=0; $i <= $diffYear; $i++){
            foreach($bulan as $b){
                $dataPerMonth = collect($listData)->where('kode_bulan', $b->kode_bulan)->where('tahun', $i + $start_year)->first();
                $tempResult[] = [
                    'kode_bulan' => $b->kode_bulan,
                    'bulan' => $b->nm_bulan,
                    'tahun' => $dataPerMonth['tahun'] ?? $i + $start_year,
                    'frekuensi' => $dataPerMonth['frekuensi'] ?? 0,
                    'total_pembayaran' => $dataPerMonth['total_pembayaran'] ?? 0,
                    'details' => $dataPerMonth['details'] ?? []
                ];
            }
        }

        $result = [
            'data' => $tempResult,
            'kategori_biaya' => $pembayaran->get()->pluck('tagihan_biaya.detail_biaya.biaya.nm_biaya', 'tagihan_biaya.detail_biaya.biaya.id_biaya')
        ];

        return $result;
    }
    /** ========== */
}