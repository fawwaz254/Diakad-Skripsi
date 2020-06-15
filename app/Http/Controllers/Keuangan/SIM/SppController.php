<?php

namespace App\Http\Controllers\Keuangan\SIM;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;

use App\Models\Bulan;
use App\Models\DetailBiaya;
use App\Models\PembayaranBiaya;
use App\Models\Kelas;
use App\Models\Realisasi;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\SubkategoriRapb;
use App\Models\TagihanBiaya;
use App\Models\TutupBukuBulananBiaya;
use App\Models\TutupBukuTahunanBiaya;

use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class SppController extends BaseController
{
    public function viewMenuSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        return view('keuangan/sim/spp/view-menu-spp', compact('auth_data'));
    }

    public function viewMenuCari(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('thn_akademik_semester', 'asc')->orderBy('nm_semester', 'asc')->get();
        
        return view('keuangan/sim/spp/view-menu-cari', compact('auth_data', 'data_semester'));
    }

    public function indexDownloadLapBulanan(Request $request, $tahun_akademik_semester = null, $id_bulan = null){
        $input = (object) $request->input();

        $tahun      = $tahun_akademik_semester;

        $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        if($id_bulan < 7){
            $id_semester = $id_semester_selesai;
        }else{
            $id_semester = $id_semester_mulai;
        }

        $data_tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where([
            'id_semester_mulai' => $id_semester_mulai,
            'id_semester_selesai' => $id_semester_selesai,
            'id_bulan' => $id_bulan
        ])->orderBy('tingkat')->get();

        $subkategori_rapb = SubkategoriRapb::whereHas('kategori', function($q){
            $q->where('tipe_kategori_rapb', 2);
        })->get()->pluck('id_subkategori_rapb');

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
                                    ->whereIn('id_semester_realisasi', [ $id_semester_mulai, $id_semester_selesai])
                                    ->groupBy('realisasi.id_rapb', 'nm_kategori_rapb', 'kode_subkategori_rapb', 'nm_subkategori_rapb', 'tipe_kategori_rapb', 'dana_perkiraan_rapb')
                                    ->get();
        
        $bulan = Bulan::find($id_bulan);
        $sekolah = $input->auth_data->sekolah_data;

        return view('keuangan/sim/spp/lap-bulanan-excel', compact('data_tutup_buku_bulanan_biaya', 'data_realisasi', 'bulan', 'tahun', 'sekolah'));
    }

    public function actionRefreshLapBulanan(Request $request, $tahun_akademik_semester = null, $id_bulan = null){
        $input = (object) $request->input();

        // $tahun      = $input->tahun;
        // $id_bulan   = $input->bulan;

        $tahun      = $tahun_akademik_semester;

        $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        if($id_bulan < 7){
            $id_semester = $id_semester_selesai;
        }else{
            $id_semester = $id_semester_mulai;
        }

        $kode_semester_mulai = $semester_mulai->kode_semester;

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
                                AND detail_biaya.id_bulan = ? - 1
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.id_semester = ?
                                AND biaya_sekolah.deleted_at IS NULL
                            WHERE YEAR(pembayaran_biaya.tgl_pembayaran) = ? 
                                AND MONTH(pembayaran_biaya.tgl_pembayaran) = ?
                                AND pembayaran_biaya.deleted_at IS NULL
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat', 
                        [$id_bulan, $id_semester, $tahun, $id_bulan]);

        $list_data_pembayaran_old_years = DB::select('SELECT kelas.tingkat, SUM(pembayaran_biaya.besar_pembayaran) AS jml_pembayaran_biaya_tahun_lalu
                            FROM pembayaran_biaya
                            JOIN tagihan_biaya ON tagihan_biaya.id_tagihan_biaya = pembayaran_biaya.id_tagihan_biaya
                                AND tagihan_biaya.deleted_at IS NULL
                            JOIN kelas ON kelas.id_kelas = tagihan_biaya.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN detail_biaya ON detail_biaya.id_detail_biaya = tagihan_biaya.id_detail_biaya
                                AND detail_biaya.id_jenis_detail_biaya = 4
                                AND detail_biaya.id_bulan = ? - 1
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.deleted_at IS NULL
                            JOIN semester ON semester.id_semester = biaya_sekolah.id_semester
                                AND semester.kode_semester < ?
                                AND semester.deleted_at IS NULL
                            WHERE YEAR(pembayaran_biaya.tgl_pembayaran) = ? 
                                AND MONTH(pembayaran_biaya.tgl_pembayaran) = ?
                                AND pembayaran_biaya.deleted_at IS NULL
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat', 
                        [$id_bulan, $kode_semester_mulai, $tahun, $id_bulan]);


        $now = Carbon::now(env('APP_TIMEZONE', 'Asia/Jakarta'));

        DB::beginTransaction();
        try {

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
                    
                    $pembayaran_old_years = collect($list_data_pembayaran_old_years)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu    = (!empty($pembayaran_old_years)? $pembayaran_old_years->jml_pembayaran_biaya_tahun_lalu : 0);
                    
                    $tutup_buku_bulanan_biaya->updated_by          = $input->auth_data->pengguna->id_pengguna;
                    $tutup_buku_bulanan_biaya->save();
                }else {
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
    
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

                    $pembayaran_old_years = collect($list_data_pembayaran_old_years)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu    = (!empty($pembayaran_old_years)? $pembayaran_old_years->jml_pembayaran_biaya_tahun_lalu : 0);

                    $tutup_buku_bulanan_biaya->created_by                   = $input->auth_data->pengguna->id_pengguna;
                    $tutup_buku_bulanan_biaya->save();
                }
            }

            DB::commit();

            $pembayaran_tunggakan_tahun_lalu = TutupBukuBulananBiaya::where([
                'id_semester_mulai' => $id_semester_mulai,
                'id_semester_selesai' => $id_semester_selesai,
                'id_bulan' => $id_bulan
            ])->sum('jml_pembayaran_biaya_tahun_lalu');

            $tahun_lalu      = $tahun - 1;
            $id_semester_mulai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu.'1')->first()->id_semester;
            $id_semester_selesai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu.'2')->first()->id_semester;

            $tutup_buku_tahunan_biaya = TutupBukuTahunanBiaya::where([
                'id_semester_mulai' => $id_semester_mulai_tahun_lalu,
                'id_semester_selesai' => $id_semester_selesai_tahun_lalu
            ])->first();

            $tutup_buku_tahunan_biaya->jml_tunggakan_biaya = $tutup_buku_tahunan_biaya->jml_tunggakan_biaya - $pembayaran_tunggakan_tahun_lalu;
            $tutup_buku_tahunan_biaya->save();

            return response()->json([
                'status_code' => 200,
                'status_text' => 'Success',
                'message' => 'Success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status_code' => 300,
                'status_text' => 'Failed',
                'message' => $e->getMessage(). ' in Line '.$e->getLine()
                // 'message' => json_encode($list_data_tagihan)
            ]);
        }

    }

    public function datatablesMenuCari(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        if (!empty($input->start_semester) && !empty($input->end_semester)) {
            $data_detail_biaya = DetailBiaya::with('bulan')->whereHas('biaya_sekolah', function($q) use ($input){
                $q->whereIn('id_semester', [$input->start_semester, $input->end_semester]);
            })->where('id_jenis_detail_biaya', 4)->get();

            $list_data = Siswa::with(['tagihan_biaya' => function($q) use ($data_detail_biaya){
                $q->where('is_tagih', 1)
                    ->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'));
            }])
            ->with('pengguna', 'kelas');
        }else{
            $list_data = array();
        }

        return Datatables::of($list_data)
                ->addColumn('total_tagihan_bulan', function ($item) {
                    return 'Rp'.number_format($item->tagihan_biaya->sum('besar_biaya'));
                })
                ->addColumn('tagihan_bulan', function ($item) use ($data_detail_biaya) {
                    $array_tagihan_bulan = array();
                    foreach($item->tagihan_biaya as $tagihan){
                        $tagihan_bulan['id_bulan'] = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->bulan->id_bulan;
                        $tagihan_bulan['nm_bulan'] = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->bulan->nm_bulan;

                        $array_tagihan_bulan[] = $tagihan_bulan;
                    }

                    $array_tagihan_bulan = collect($array_tagihan_bulan)->sortBy('id_bulan')->pluck('nm_bulan');

                    return $array_tagihan_bulan;
                })
                ->make(true);
    }

    public function viewMenuPembayaran(Request $request, $id_semester = null, $id_kelas = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        
        if(!empty($id_kelas) && !empty($id_semester)){
            $data_siswa = Siswa::with('pengguna')->where('siswa.id_kelas', $id_kelas)
                                ->get();
            
            $data_tagihan = TagihanBiaya::select('tagihan_biaya.id_siswa', 'siswa.nis_siswa', 'tagihan_biaya.id_tagihan_biaya', 'tagihan_biaya.is_tagih', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', DB::raw("(SELECT SUM(besar_pembayaran) FROM pembayaran_biaya WHERE pembayaran_biaya.id_tagihan_biaya = tagihan_biaya.id_tagihan_biaya AND pembayaran_biaya.deleted_at IS NULL) AS besar_pembayaran"), 'pembayaran_biaya.tgl_pembayaran', 'pembayaran_biaya.id_pembayaran_biaya')
                                ->join('siswa', function($q){
                                    $q->on('tagihan_biaya.id_siswa', '=', 'siswa.id_siswa')
                                        ->whereNull('siswa.deleted_at');
                                })
                                ->leftJoin('detail_biaya', function($q){
                                    $q->on('detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
                                        ->whereNull('detail_biaya.deleted_at');
                                })
                                ->leftJoin('biaya_sekolah', function($q){
                                    $q->on('biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
                                        ->whereNull('biaya_sekolah.deleted_at');
                                })
                                ->leftJoin('bulan', function($q){
                                    $q->on('detail_biaya.id_bulan', '=', 'bulan.id_bulan')
                                        ->whereNull('bulan.deleted_at');
                                })
                                ->leftJoin('pembayaran_biaya', function($q){
                                    $q->on('tagihan_biaya.id_tagihan_biaya', '=', 'pembayaran_biaya.id_tagihan_biaya')
                                        ->whereNull('pembayaran_biaya.deleted_at');
                                })
                                ->where('detail_biaya.validasi_biaya', 1)
                                ->where('detail_biaya.id_jenis_detail_biaya', 4)
                                ->where('biaya_sekolah.id_semester', $id_semester)
                                ->where('siswa.id_kelas', $id_kelas)
                                ->get();
            
            $data_bulan_tagihan = $data_tagihan->unique('nm_bulan')->sortBy('id_bulan')->values()->all();
        }else{
            $data_siswa = array();
            $data_tagihan = array();
            $data_bulan_tagihan = array();
        }

        return view('keuangan/sim/spp/view-menu-pembayaran', compact('auth_data', 'data_semester', 'data_kelas', 'id_semester', 'id_kelas', 'data_siswa', 'data_tagihan', 'data_bulan_tagihan'));
    }

    public function viewMenuPemasukan(Request $request, $tahun_akademik_semester = null, $id_bulan = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if(empty($tahun_akademik_semester)){
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }
        
        if(empty($id_bulan)){
            $now = Carbon::today();

            $id_bulan = $now->month;
        }

        $start_month = Carbon::create($tahun_akademik_semester, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun_akademik_semester, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();

        $dates = CarbonPeriod::create($start_month, $end_month);

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);
        
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_pemasukan_bulan_ini = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.detail_biaya', 'tagihan_biaya.kelas')->whereBetween('tgl_pembayaran', [$start_month, $end_month])->get();

        return view('keuangan/sim/spp/view-menu-pemasukan', compact('auth_data', 'data_semester', 'data_bulan', 'dates', 'tahun_akademik_semester', 'id_bulan', 'data_pemasukan_bulan_ini'));
    }

    public function viewMenuPenerimaan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if(empty($tahun_akademik_semester)){
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);
        
        return view('keuangan/sim/spp/view-menu-penerimaan', compact('auth_data', 'data_semester', 'tahun_akademik_semester'));
    }

    public function viewMenuTunggakan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if(empty($tahun_akademik_semester)){
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);
        
        return view('keuangan/sim/spp/view-menu-tunggakan', compact('auth_data', 'data_semester', 'tahun_akademik_semester'));
    }

    public function viewMenuSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('keuangan/sim/spp/view-menu-setting', compact('auth_data'));
    }

    public function datatablesMenuSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    
        $list_data = Kelas::orderBy('tingkat');

        return Datatables::of($list_data)
                ->addColumn('nominal_spp_juli', function ($item) {
                    return '';
                })
                ->addColumn('nominal_spp_non_juli', function ($item) {
                    return '';
                })
                ->addColumn('action', function ($item) {
                    return '';
                })
                ->make(true);
    }

    public function actionMenuSettingSaveSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'nominal_spp' => 'required',
            'setting_bulan' => 'required',
            'id_kelompok_biaya_internal' => 'required',
        ]);
        
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }else{
            DB::beginTransaction();
            try {
        

                DB::commit();
                return response()->json([
                    'status_code' => 200,
                    'status_text' => 'Success',
                    'message' => 'Success'
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status_code' => 300,
                    'status_text' => 'Failed',
                    'message' => 'Failed'
                ]);
            }
        }
    }
}
