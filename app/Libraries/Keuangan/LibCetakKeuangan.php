<?php

namespace App\Libraries\Keuangan;

use App\Models\Bulan as Bulan;

use App\Models\Biaya;
use App\Models\Kelas;
use App\Models\PembayaranBiaya;
use App\Models\Realisasi;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use DB;

class LibCetakKeuangan{

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