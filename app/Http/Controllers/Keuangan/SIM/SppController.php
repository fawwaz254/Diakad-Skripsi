<?php

namespace App\Http\Controllers\Keuangan\SIM;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;

use App\Models\Bulan;
use App\Models\DetailzBiaya;
use App\Models\PembayaranBiaya as PembayaranBiaya;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TagihanBiaya as TagihanBiaya;

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
}
