<?php

namespace App\Http\Controllers\Keuangan\SIM;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;

use App\Models\Biaya;
use App\Models\BiayaSekolah;
use App\Models\Bulan;
use App\Models\DetailBiaya;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelompokBiaya;
use App\Models\KelompokBiayaInternal;
use App\Models\JenisDetailBiaya;
use App\Models\Rapb;
use App\Models\PembayaranBiaya;
use App\Models\Realisasi;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Staff;
use App\Models\SubkategoriRapb;
use App\Models\TagihanBiaya;
use App\Models\TutupBukuBulananBiaya;
use App\Models\TutupBukuBulananKas;
use App\Models\TutupBukuTahunanBiaya;

use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Excel;
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

    public function viewMenuInput(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        if(empty($tahun_akademik_semester)){
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_subkategori = SubkategoriRapb::whereHas('kategori', function($q){
            $q->where('tipe_kategori_rapb', 1)->where('jenis_kategori_rapb', 0);
        })->get();

        return view('keuangan/sim/spp/view-menu-input-penerimaan', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_subkategori'));
    }

    public function viewMenuEditSetting(Request $request, $thn_akademik_semester, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);
        $tahun_akademik_semester = $thn_akademik_semester;

        $data_kelompok_biaya = KelompokBiaya::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                    ->orderBy('status_kelompok_biaya', 'asc')
                                    ->orderBy('nm_kelompok_biaya', 'asc')
                                    ->get();

        $data_kelompok_biaya_internal = KelompokBiayaInternal::orderBy('nm_kelompok_biaya_internal', 'asc')
                                    ->get();
        
        $kelas = Kelas::find($id);

        return view('keuangan/sim/spp/view-menu-edit-setting', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_kelompok_biaya', 'data_kelompok_biaya_internal', 'kelas'));
    }

    public function viewMenuEditSettingNonSpp(Request $request, $thn_akademik_semester, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);
        $tahun_akademik_semester = $thn_akademik_semester;

        $data_kelompok_biaya = KelompokBiaya::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                    ->orderBy('status_kelompok_biaya', 'asc')
                                    ->orderBy('nm_kelompok_biaya', 'asc')
                                    ->get();

        $data_kelompok_biaya_internal = KelompokBiayaInternal::orderBy('nm_kelompok_biaya_internal', 'asc')
                                    ->get();
        
        $kelas = Kelas::find($id);

        $data_biaya = Biaya::where('nm_biaya', '<>', 'SPP')->orderBy('nm_biaya', 'asc')->get();

        $data_jenis_detail_biaya = JenisDetailBiaya::get();

        return view('keuangan/sim/spp/view-menu-edit-setting-non-spp', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_kelompok_biaya', 'data_kelompok_biaya_internal', 'kelas', 'data_biaya', 'data_jenis_detail_biaya'));
    }

    public function viewMenuCari(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        
        return view('keuangan/sim/spp/view-menu-cari', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_kelas'));
    }

    public function viewMenuUpload(Request $request)
    {   
        return view('keuangan/sim/spp/view-menu-upload');
    }

    public function actionMenuUpload(Request $request){
    	$input = (object) $request->input();
	    $auth_data = $input->auth_data;
	    $now = Carbon::now(env('APP_TIMEZONE', ''));
        if($request->hasFile('file-excel')){
            $path = $request->file('file-excel')->getRealPath();
            $data = Excel::load($path)->get();

       		if($data->count()){
                DB::beginTransaction();
                try {
                    foreach ($data as $key => $item) {
                        if(!empty($item->nis)){
                            $siswa = Siswa::where('nis_siswa', $item->nis)->first();
                            $semester = Semester::where('kode_semester', $item->kode_semester)->first();
                            
                            $tanggal_bayar = $item->tanggal;
                            $id_bulan = $item->id_bulan;
    
                            $tagihan_siswa = TagihanBiaya::where('is_tagih', 1)->where('id_siswa', $siswa->id_siswa)
                                                            ->whereHas('detail_biaya', function($q) use ($id_bulan){
                                                                $q->where('id_bulan', $id_bulan)->where('id_jenis_detail_biaya', 4);
                                                            })
                                                            ->whereHas('detail_biaya.biaya_sekolah', function($q) use ($semester){
                                                                $q->where('id_semester', $semester->id_semester);
                                                            })->first();
    
                            if($tagihan_siswa){
                                $pembayaran_biaya                        = new PembayaranBiaya;
                                $pembayaran_biaya->id_pembayaran_biaya   = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                                $pembayaran_biaya->id_tagihan_biaya      = $tagihan_siswa->id_tagihan_biaya;
                                $pembayaran_biaya->id_staff_bayar        = $input->auth_data->pengguna->id_pengguna;
                                $pembayaran_biaya->id_semester_bayar     = $semester->id_semester;
                                $pembayaran_biaya->besar_pembayaran      = $tagihan_siswa->besar_biaya;
                                $pembayaran_biaya->tgl_pembayaran        = $tanggal_bayar;
                                $pembayaran_biaya->keterangan            = "Langsung Lunas";
                                $pembayaran_biaya->created_by            = $input->auth_data->pengguna->id_pengguna;
                                $pembayaran_biaya->save();
    
                                $tagihan_siswa->is_tagih     = 0;
                                $tagihan_siswa->updated_by   = $input->auth_data->pengguna->id_pengguna;
                                $tagihan_siswa->save();
                            }
                        }
                    }

                    DB::commit();
                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'sim/spp/upload-pembayaran',
                        'message' => 'Upload Pembayaran successfully'
                    ];
                }
                catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong
                    return [
                        'status' 	=> 203, // GAGAL
                        'message'	=> 'Upload Pembayaran Gagal '.$e->getMessage().' in line '.$e->getLine()
                    ];
                } 
            }else{
                return [
                    'status' 	=> 300, // FAILED
                    'message' 	=> "File Excel Anda Kosong"
                ];
            }
        }else{
			return [
				'status' 	=> 300, // FAILED
				'message' 	=> "File Excel tidak ditemukan"
			];
		}
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

        $data_realisasi = Rapb::selectRaw('
                                        nm_kategori_rapb, 
                                        kode_subkategori_rapb,
                                        nm_subkategori_rapb,
                                        tipe_kategori_rapb,
                                        SUM(dana_realisasi) as total_realisasi,
                                        dana_perkiraan_rapb')
                                    ->leftJoin('realisasi', function($q){
                                        $q->on('realisasi.id_rapb', '=', 'rapb.id_rapb')
                                            ->whereNull('realisasi.deleted_at');
                                    })
                                    ->join('subkategori_rapb', function($q){
                                        $q->on('subkategori_rapb.id_subkategori_rapb', '=' ,'rapb.id_subkategori_rapb')
                                            ->whereNull('subkategori_rapb.deleted_at');
                                    })
                                    ->join('kategori_rapb', function($q){
                                        $q->on('kategori_rapb.id_kategori_rapb', '=' ,'subkategori_rapb.id_kategori_rapb')
                                            ->whereNull('kategori_rapb.deleted_at');
                                    })
                                    ->where('id_semester_mulai', $id_semester_mulai)
                                    ->where('id_semester_selesai', $id_semester_selesai)
                                    ->groupBy('realisasi.id_rapb', 'nm_kategori_rapb', 'kode_subkategori_rapb', 'nm_subkategori_rapb', 'tipe_kategori_rapb', 'dana_perkiraan_rapb')
                                    ->get();
        
        $bulan = Bulan::find($id_bulan);
        $sekolah = $input->auth_data->sekolah_data;

        $tutup_buku_tahun_ini = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai])->first();
        $tutup_buku_kas_bulan_ini = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan])->first();
        if($tutup_buku_kas_bulan_lalu = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan - 1])->first()){
        }else{
            return response()->json([
                'status_code' => 300,
                'status_text' => 'Failed',
                'message' => 'Tagihan bulan lalu belum diproses'
            ]);
        }

        return view('keuangan/sim/spp/lap-bulanan-excel', compact('data_tutup_buku_bulanan_biaya', 'data_realisasi', 'bulan', 'tahun', 'sekolah', 'tutup_buku_tahun_ini', 'tutup_buku_kas_bulan_ini', 'tutup_buku_kas_bulan_lalu'));
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

        if($tutup_buku_bulanan_kas_old = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan - 1])->first()){
        }else{
            return response()->json([
                'status_code' => 300,
                'status_text' => 'Failed',
                'message' => 'Tagihan bulan lalu belum diproses'
            ]);
        }

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
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status_code' => 300,
                'status_text' => 'Failed',
                'message' => $e->getMessage(). ' in Line '.$e->getLine()
                // 'message' => json_encode($list_data_tagihan)
            ]);
        }

        DB::beginTransaction();
        try {
            $data_tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where([
                'id_semester_mulai' => $id_semester_mulai,
                'id_semester_selesai' => $id_semester_selesai,
                'id_bulan' => $id_bulan
            ])->get();
    
            /* INSERT TUTUP BUKU TAHUNAN BIAYA */
            $pembayaran_tunggakan_tahun_lalu = $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_tahun_lalu');
    
            $tahun_lalu      = $tahun - 1;
            $id_semester_mulai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu.'1')->first()->id_semester;
            $id_semester_selesai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu.'2')->first()->id_semester;
    
            $tutup_buku_tahunan_biaya_old = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $id_semester_mulai_tahun_lalu, 'id_semester_selesai' => $id_semester_selesai_tahun_lalu])->first();
    
            if($tutup_buku_tahunan_biaya_now = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai])->first()){
                $tutup_buku_tahunan_biaya_now->updated_by                   = $input->auth_data->pengguna->id_pengguna;
            }else{
                $tutup_buku_tahunan_biaya_now = new TutupBukuTahunanBiaya;
                $tutup_buku_tahunan_biaya_now->id_tutup_buku_tahunan_biaya  = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $tutup_buku_tahunan_biaya_now->id_semester_mulai            = $id_semester_mulai;
                $tutup_buku_tahunan_biaya_now->id_semester_selesai          = $id_semester_selesai;
                $tutup_buku_tahunan_biaya_now->created_by                   = $input->auth_data->pengguna->id_pengguna;
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
                                    ->whereIn('id_semester_realisasi', [ $id_semester_mulai, $id_semester_selesai])
                                    ->groupBy('realisasi.id_rapb', 'nm_kategori_rapb', 'kode_subkategori_rapb', 'nm_subkategori_rapb', 'tipe_kategori_rapb', 'dana_perkiraan_rapb')
                                    ->get();
    
            if($tutup_buku_bulanan_kas_now = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan])->first()){
                $tutup_buku_bulanan_kas_now->updated_by                   = $input->auth_data->pengguna->id_pengguna;
            }else{
                $tutup_buku_bulanan_kas_now = new TutupBukuBulananKas;
                $tutup_buku_bulanan_kas_now->id_tutup_buku_bulanan_kas    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $tutup_buku_bulanan_kas_now->id_semester_mulai            = $id_semester_mulai;
                $tutup_buku_bulanan_kas_now->id_semester_selesai          = $id_semester_selesai;
                $tutup_buku_bulanan_kas_now->id_bulan                     = $id_bulan;
                $tutup_buku_bulanan_kas_now->created_by                   = $input->auth_data->pengguna->id_pengguna;
            }
            $tutup_buku_bulanan_kas_now->kas_spp                = $pembayaran_tunggakan_bulan_ini + $pembayaran_tunggakan_bulan_lalu + $pembayaran_tunggakan_tahun_lalu;
            $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan    = $data_realisasi->where('tipe_kategori_rapb', 1)->sum('total_realisasi');
            $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran   = $data_realisasi->where('tipe_kategori_rapb', 2)->sum('total_realisasi');
            $tutup_buku_bulanan_kas_now->kas_akhir_bulan        = $tutup_buku_bulanan_kas_now->kas_spp + $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan + $tutup_buku_bulanan_kas_old->kas_akhir_bulan;
            $tutup_buku_bulanan_kas_now->save();
            /* END INSERT TUTUP BUKU BULANAN KAS */

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
                'message' => $e->getMessage(). ' in Line '.$e->getLine()
                // 'message' => json_encode($list_data_tagihan)
            ]);
        }
    }

    public function datatablesMenuCari(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tahun      = $input->tahun_akademik_semester;
        $kelas      = $input->kelas;

        $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;
        
        if (!empty($id_semester_mulai) && !empty($id_semester_selesai)) {
            $data_detail_biaya = DetailBiaya::with('bulan')->whereHas('biaya_sekolah', function($q) use ($id_semester_mulai, $id_semester_selesai){
                $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
            })->where('id_jenis_detail_biaya', 4)->get();

            $list_data = Siswa::with(['tagihan_biaya' => function($q) use ($data_detail_biaya){
                $q->where('is_tagih', 1)
                    ->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'));
            }])
            ->with('pengguna', 'kelas');

            if(!empty($kelas)){
                $list_data = $list_data->whereHas('kelas', function($q) use ($kelas){
                    $q->where('id_kelas', $kelas);
                });
            }
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

    public function viewMenuPembayaran(Request $request, $tahun_akademik_semester = null, $id_kelas = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if(empty($tahun_akademik_semester)){
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        
        if(!empty($id_kelas) && !empty($tahun_akademik_semester)){
            $semester_mulai = Semester::where('kode_semester', $tahun_akademik_semester.'1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun_akademik_semester.'2')->first();

            $data_siswa = Siswa::with('pengguna')->where('siswa.id_kelas', $id_kelas)
                                ->get();
            
            $data_tagihan = TagihanBiaya::select('tagihan_biaya.id_siswa', 'semester.kode_semester', 'siswa.nis_siswa', 'tagihan_biaya.id_tagihan_biaya', 'tagihan_biaya.is_tagih', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', DB::raw("(SELECT SUM(besar_pembayaran) FROM pembayaran_biaya WHERE pembayaran_biaya.id_tagihan_biaya = tagihan_biaya.id_tagihan_biaya AND pembayaran_biaya.deleted_at IS NULL) AS besar_pembayaran"), 'pembayaran_biaya.tgl_pembayaran', 'pembayaran_biaya.id_pembayaran_biaya')
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
                                ->leftJoin('semester', function($q){
                                    $q->on('semester.id_semester', '=', 'biaya_sekolah.id_semester')
                                        ->whereNull('semester.deleted_at');
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
                                ->whereIn('biaya_sekolah.id_semester', [$semester_mulai->id_semester, $semester_selesai->id_semester])
                                ->where('siswa.id_kelas', $id_kelas)
                                ->get();
            
            $data_bulan_tagihan = $data_tagihan->unique('nm_bulan')->sortBy('id_bulan')->sortBy('kode_semester')->values()->all();
        }else{
            $data_siswa = array();
            $data_tagihan = array();
            $data_bulan_tagihan = array();
        }

        return view('keuangan/sim/spp/view-menu-pembayaran', compact('auth_data', 'data_semester', 'data_kelas', 'tahun_akademik_semester', 'id_kelas', 'data_siswa', 'data_tagihan', 'data_bulan_tagihan'));
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

    public function viewMenuPenerimaan(Request $request, $tahun_akademik_semester = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if(empty($tahun_akademik_semester)){
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $tahun = $tahun_akademik_semester;
        $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_subkategori = SubkategoriRapb::whereHas('kategori', function($q){
            $q->where('tipe_kategori_rapb', 1)->where('jenis_kategori_rapb', 0);
        })->get();

        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_realisasi = Realisasi::select('*')->addSelect(DB::raw('MONTH(realisasi.created_at) month'))
                            ->with('rapb', 'rapb.subkategori')->whereHas('rapb.subkategori.kategori', function($q){
                                $q->where('tipe_kategori_rapb', 1)->where('jenis_kategori_rapb', 0);
                            })->whereIn('id_semester_realisasi', [$semester_mulai->id_semester, $semester_selesai->id_semester])->get();
        
        return view('keuangan/sim/spp/view-menu-penerimaan', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_subkategori', 'data_bulan', 'data_realisasi'));
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

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        return view('keuangan/sim/spp/view-menu-setting', compact('auth_data', 'data_semester', 'tahun_akademik_semester'));
    }

    public function datatablesMenuSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tahun      = $input->tahun_akademik_semester;

        $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;
        
        if (!empty($id_semester_mulai) && !empty($id_semester_selesai)) {
            $data_detail_biaya = DetailBiaya::whereHas('biaya_sekolah', function($q) use ($id_semester_mulai, $id_semester_selesai){
                                                    $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
                                                })
                                                ->where('id_jenis_detail_biaya', 4)
                                                ->where(function($q){
                                                    $q->where('id_bulan', 1)
                                                        ->orWhere('id_bulan', 7);
                                                })
                                                ->get();
        }else{
            $data_detail_biaya = null;
        }
    
        $list_data = Kelas::with(['tagihan' => function($q) use ($data_detail_biaya){
                                    $q->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'))
                                        ->with('detail_biaya', 'detail_biaya.bulan');
                                }])->orderBy('tingkat')->orderBy('nm_kelas');

        return Datatables::of($list_data)
                ->addColumn('nominal_spp_juli', function ($item) {
                    $biaya = 'Belum diset';
                    if(!empty($item->tagihan)){
                        if($tagihan = $item->tagihan->where('detail_biaya.bulan.id_bulan', 7)->first()){
                            $biaya = 'Rp'.number_format($tagihan->besar_biaya);
                        }
                    }

                    return $biaya;
                })
                ->addColumn('nominal_spp_non_juli', function ($item) {
                    $biaya = 'Belum diset';
                    if(!empty($item->tagihan)){
                        if($tagihan = $item->tagihan->where('detail_biaya.bulan.id_bulan', 1)->first()){
                            $biaya = 'Rp'.number_format($tagihan->besar_biaya);
                        }
                    }

                    return $biaya;
                })
                ->addColumn('action', function ($item) {
                    $status = 0;
                    if(!empty($item->tagihan)){
                        if($tagihan = $item->tagihan->where('detail_biaya.bulan.id_bulan', 7)->first()){
                            $status = 1;
                        }
                    }

                    $data = array(
                        'id' => $item->id_kelas,
                        'status' => $status
                    );
                    return $data;
                })
                ->make(true);
    }

    public function viewMenuSettingNonSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_biaya = Biaya::where('nm_biaya', '<>', 'SPP')->orderBy('nm_biaya', 'asc')->get();

        return view('keuangan/sim/spp/view-menu-setting-non-spp', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_biaya'));
    }

    public function datatablesMenuSettingNonSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tahun      = $input->tahun_akademik_semester;

        $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        $data_biaya = Biaya::where('nm_biaya', '<>', 'SPP')->get();
        
        if (!empty($id_semester_mulai) && !empty($id_semester_selesai)) {
            $data_detail_biaya = DetailBiaya::whereHas('biaya_sekolah', function($q) use ($id_semester_mulai, $id_semester_selesai){
                                                    $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
                                                })
                                                ->whereIn('id_biaya', $data_biaya->pluck('id_biaya'))
                                                ->get();
        }else{
            $data_detail_biaya = null;
        }
    
        $list_data = Kelas::with(['tagihan' => function($q) use ($data_detail_biaya){
                                    $q->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'))
                                        ->with('detail_biaya');
                                }])->orderBy('tingkat');
        
        $dt = Datatables::of($list_data);
        
        foreach($data_biaya as $biaya){
            $dt->addColumn($biaya->id_biaya, function($item) use ($biaya){
                $besar_biaya = 'Belum diset';
                if(!empty($item->tagihan)){
                    if($tagihan = $item->tagihan->firstWhere('detail_biaya.id_biaya', $biaya->id_biaya)){
                        $besar_biaya = 'Rp'.number_format($tagihan->besar_biaya);
                    }
                }

                return $besar_biaya;
            });
        }

        return $dt->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_kelas
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionMenuSettingSaveSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'tahun_akademik_semester'               => 'required',
            'id_kelas'                              => 'required',
            'nominal_spp_juli'                      => 'required',
            'nominal_spp_non_juli'                  => 'required',
            'id_kelompok_biaya'                     => 'required',
            'id_kelompok_biaya_internal_juli'       => 'required',
            'id_kelompok_biaya_internal_non_juli'   => 'required'
        ]);
        
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }else{
            $now = Carbon::now();

            $tahun      = $input->tahun_akademik_semester;
            $data_siswa = Siswa::where('id_kelas', $input->id_kelas)->get();
            $kelas      = Kelas::find($input->id_kelas);

            $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();
    
            $biaya = Biaya::where('nm_biaya', 'SPP')->first();
            $kelompok_biaya = KelompokBiaya::where('id_kelompok_biaya', $input->id_kelompok_biaya)->first();

            $data_bulan = Bulan::orderBy('id_bulan', 'asc')->get();

            DB::beginTransaction();
            try {
                Siswa::where('id_kelas', $kelas->id_kelas)->update(['id_kelompok_biaya' => $input->id_kelompok_biaya]);
                foreach($data_bulan as $bulan){
                    $detail_biaya = array();
                    $tagihan = array();
                
                    $id_detail_biaya = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    if($bulan->id_bulan < 7){
                        $id_semester = $semester_selesai->id_semester;
                    }else{
                        $id_semester = $semester_mulai->id_semester;
                    }

                    if($biaya_sekolah = BiayaSekolah::where(['id_semester' => $id_semester, 'id_kelompok_biaya' => $input->id_kelompok_biaya])->first()){

                    }else{
                        $biaya_sekolah = new BiayaSekolah;
                        $biaya_sekolah->id_biaya_sekolah                = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $biaya_sekolah->id_kelompok_biaya               = $input->id_kelompok_biaya;
                        $biaya_sekolah->id_semester                     = $id_semester;
                        $biaya_sekolah->besar_biaya_sekolah             = (1 * $input->nominal_spp_juli) + (11 * $input->nominal_spp_non_juli);
                        $biaya_sekolah->validasi_biaya_sekolah          = 1;
                        $biaya_sekolah->keterangan_biaya_sekolah        = 'SPP '.$kelompok_biaya->nm_kelompok_biaya;
                        $biaya_sekolah->save();
                    }

                    if($bulan->id_bulan == 7){
                        $besar_biaya = $input->nominal_spp_juli;
                        $id_kelompok_biaya_internal = $input->id_kelompok_biaya_internal_non_juli;
                    }else{
                        $besar_biaya = $input->nominal_spp_non_juli;
                        $id_kelompok_biaya_internal = $input->id_kelompok_biaya_internal_juli;
                    }

                    $detail_biaya[] = array(
                        'id_detail_biaya'               => $id_detail_biaya,
                        'id_biaya_sekolah'              => $biaya_sekolah->id_biaya_sekolah,
                        'id_biaya'                      => $biaya->id_biaya ,
                        'id_kelompok_biaya_internal'    => $id_kelompok_biaya_internal,
                        'validasi_biaya'                => 1,
                        'besar_biaya'                   => $besar_biaya,
                        'id_jenis_detail_biaya'         => 4,
                        'id_bulan'                      => $bulan->id_bulan,
                        'created_at'                    => $now,
                        'created_by'                    => $input->auth_data->pengguna->id_pengguna,
                        'updated_at'                    => $now,
                    );

                    foreach($data_siswa as $siswa){
                        $tagihan[] = array(
                            'id_tagihan_biaya'  => $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid(),
                            'id_siswa'          => $siswa->id_siswa,
                            'id_kelas'          => $siswa->id_kelas,
                            'id_detail_biaya'   => $id_detail_biaya,
                            'besar_biaya'       => $besar_biaya,
                            'denda_biaya'       => 0,
                            'is_tagih'          => 1,
                            'created_at'        => $now,
                            'created_by'        => $input->auth_data->pengguna->id_pengguna,
                            'updated_at'        => $now,
                        );
                    }

                    DetailBiaya::insert($detail_biaya);
                    TagihanBiaya::insert($tagihan);
                }
                

                DB::commit();

                return response()->json([
                    'status' => 202,
                    'status_text' => 'Success',
                    'path' => 'sim/spp/setting',
                    'message' => 'Success'
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status_code' => 300,
                    'status_text' => 'Failed',
                    'message' => 'Failed '.$e->getMessage().' in line '.$e->getLine()
                ]);
            }
        }
    }

    public function actionMenuSettingSaveNonSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'tahun_akademik_semester'       => 'required',
            'semester'                      => 'required',
            'id_jenis_detail_biaya'         => 'required',
            'id_kelas'                      => 'required',
            'id_biaya'                      => 'required',
            'besar_biaya'                   => 'required',
            'id_kelompok_biaya'             => 'required',
            'id_kelompok_biaya_internal'    => 'required'
        ]);
        
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }else{
            $now = Carbon::now();

            $tahun      = $input->tahun_akademik_semester;
            $data_siswa = Siswa::where('id_kelas', $input->id_kelas)->get();
            $kelas      = Kelas::find($input->id_kelas);

            $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();

            $data_bulan = Bulan::orderBy('id_bulan', 'asc')->get();

            if($input->semester == 1){
                $id_semester = $semester_mulai->id_semester;
            }else{
                $id_semester = $semester_selesai->id_semester;
            }

            if($biaya_sekolah = BiayaSekolah::where(['id_semester' => $id_semester, 'id_kelompok_biaya' => $input->id_kelompok_biaya])->first()){
                $biaya_sekolah->besar_biaya_sekolah             = $biaya_sekolah->besar_biaya_sekolah + $input->besar_biaya;
                $biaya_sekolah->save();
            }else{
                $biaya_sekolah = new BiayaSekolah;
                $biaya_sekolah->id_biaya_sekolah                = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $biaya_sekolah->id_kelompok_biaya               = $input->id_kelompok_biaya;
                $biaya_sekolah->id_semester                     = $id_semester;
                $biaya_sekolah->besar_biaya_sekolah             = $input->besar_biaya;
                $biaya_sekolah->validasi_biaya_sekolah          = 1;
                $biaya_sekolah->keterangan_biaya_sekolah        = 'SPP Kelas '.$kelas->nm_kelas;
                $biaya_sekolah->save();
            }

            DB::beginTransaction();
            try {
                $detail_biaya = array();
                $tagihan = array();

                foreach($data_siswa as $siswa){
                    $id_detail_biaya    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $besar_biaya        = $input->besar_biaya;

                    $detail_biaya[] = array(
                        'id_detail_biaya'               => $id_detail_biaya,
                        'id_biaya_sekolah'              => $biaya_sekolah->id_biaya_sekolah,
                        'id_biaya'                      => $input->id_biaya ,
                        'id_kelompok_biaya_internal'    => $input->id_kelompok_biaya_internal,
                        'validasi_biaya'                => 1,
                        'besar_biaya'                   => $besar_biaya,
                        'id_jenis_detail_biaya'         => $input->id_jenis_detail_biaya,
                        'created_at'                    => $now,
                        'created_by'                    => $input->auth_data->pengguna->id_pengguna,
                        'updated_at'                    => $now,
                    );

                    $tagihan[] = array(
                        'id_tagihan_biaya'  => $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid(),
                        'id_siswa'          => $siswa->id_siswa,
                        'id_kelas'          => $siswa->id_kelas,
                        'id_detail_biaya'   => $id_detail_biaya,
                        'besar_biaya'       => $besar_biaya,
                        'denda_biaya'       => 0,
                        'is_tagih'          => 1,
                        'created_at'        => $now,
                        'created_by'        => $input->auth_data->pengguna->id_pengguna,
                        'updated_at'        => $now,
                    );

                }

                DetailBiaya::insert($detail_biaya);
                TagihanBiaya::insert($tagihan);

                DB::commit();

                return response()->json([
                    'status' => 202,
                    'status_text' => 'Success',
                    'path' => 'sim/spp/setting-non-spp',
                    'message' => 'Success'
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status_code' => 300,
                    'status_text' => 'Failed',
                    'message' => 'Failed '.$e->getMessage().' in line '.$e->getLine()
                ]);
            }
        }
    }

    public function actionSaveInputPenerimaan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'tahun' =>'required',
            'tgl_realisasi' =>'required',
            'nm_realisasi' =>'required',
            'id_subkategori_rapb' =>'required',
            'dana_realisasi' =>'required',
        ]);
  
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }else{
            $now = Carbon::today();

            $tahun_akademik_semester = $input->tahun;
            $semester_mulai = Semester::where('kode_semester', $tahun_akademik_semester.'1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun_akademik_semester.'2')->first();
            
            $tgl_realisasi = Carbon::parse($input->tgl_realisasi);
            $id_bulan = $tgl_realisasi->month;
            
            if($id_bulan < 7){
                $id_semester = $semester_selesai->id_semester;
            }else{
                $id_semester = $semester_mulai->id_semester;
            }
            
            DB::beginTransaction();
            try {
                $rapb = Rapb::where(['id_subkategori_rapb' => $input->id_subkategori_rapb, 'id_semester_mulai' => $semester_mulai->id_semester, 'id_semester_selesai' => $semester_selesai->id_semester ])->first();

                if($rapb){

                }else{
                    $rapb = new Rapb;
                    $rapb->id_rapb = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $rapb->id_semester_mulai            = $semester_mulai->id_semester;
                    $rapb->id_semester_selesai          = $semester_selesai->id_semester;
                    $rapb->id_subkategori_rapb          = $input->id_subkategori_rapb;
                    $rapb->tgl_rapb                     = $now->format('Y-m-d');
                    $rapb->prioritas_rapb               = 3;
                    $rapb->dana_perkiraan_rapb          = 0;
                    $rapb->created_by                   = $input->auth_data->pengguna->id_pengguna;

                    if($actor = Staff::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first()){
                        $rapb->id_unit_kerja                = $actor->id_unit_kerja;
                    }else if($actor = Guru::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first()){
                        $rapb->id_unit_kerja                = $actor->id_unit_kerja;
                    }

                    if($kepala_unit_keuangan = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                            ->where('guru.jenis_jabatan', '=', 2)
                            ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                            ->first()){
                        $rapb->id_pengguna_kepala_unit      = $kepala_unit_keuangan->id_pengguna;
                    }else if($kepala_unit_keuangan = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                            ->where('staff.jenis_jabatan', '=', 2)
                            ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                            ->first()){
                        $rapb->id_pengguna_kepala_keuangan  = $kepala_unit_keuangan->id_pengguna;;
                    }
                    $rapb->save();
                }


                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $realisasi                               = new Realisasi;
                $realisasi->id_realisasi                 = $id;
                $realisasi->id_semester_realisasi        = $id_semester;
                $realisasi->id_rapb                      = $rapb->id_rapb;
                if($actor = Staff::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first()){
                    $realisasi->id_unit_kerja                = $actor->id_unit_kerja;
                }else if($actor = Guru::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first()){
                    $realisasi->id_unit_kerja                = $actor->id_unit_kerja;
                }
                $realisasi->nm_realisasi                 = $input->nm_realisasi;
                $realisasi->termin_dana_realisasi        = 1;
                $realisasi->is_hutang_realisasi          = 0;
                $realisasi->dana_realisasi               = $input->dana_realisasi;
                $realisasi->tgl_realisasi                = date_format(date_create($input->tgl_realisasi),"Y-m-d");
                $realisasi->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $realisasi->save();

                DB::commit();

                return response()->json([
                    'status' => 202,
                    'status_text' => 'Success',
                    'path' => 'sim/spp/penerimaan',
                    'message' => 'Success'
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status' => 300,
                    'status_text' => 'Failed',
                    'message' => 'Failed'
                ]);
            }
        }
    }
}
