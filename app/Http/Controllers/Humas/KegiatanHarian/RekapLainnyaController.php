<?php

namespace App\Http\Controllers\Humas\KegiatanHarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\KegiatanHarian;
use App\Models\KegiatanHarianKategori;
use App\Models\Bulan;
use App\Models\KegiatanHarianPertanyaan;
use App\Models\KegiatanHarianJawaban;
use App\Models\Kelas;
use App\Models\PengisianKegiatanHarian;
use App\Models\Pengguna;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;


class RekapLainnyaController extends Controller
{
    public function viewListKegiatan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('humas/kegiatan-harian/rekap-lainnya/view-list-rekap-lainnya',compact('auth_data'));
    }

    public function datatablesListKegiatan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = KegiatanHarian::with('pengisisan_kegiatan_harian')->where('nm_kegiatan_harian', '!=', 'Monitoring Kesehatan COV-19');
        return Datatables::of($list_data)
                ->editColumn('is_aktif', function($item){
                    return $item->is_aktif_to_text();
                })
                ->addColumn('jumlah', function($item){
                    return $item->pengisisan_kegiatan_harian->count();
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kegiatan_harian
                    );
                    return $data;
                })
                ->make(true);
    }


    public function viewRekapKegiatanGuruTendik(Request $request, $id_kegiatan_harian){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::today();
        if (empty($id_bulan)) {
            $id_bulan = $now->month;
        }

        if (empty($tahun)) {
            $tahun = $now->year;
        }

        $start_month = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_pengguna = Pengguna::whereHas('status_pengguna', function ($q) {
            $q->where('aktif_status_pengguna', 1);
        })->whereIn('status_join_table', [1, 2])->orderBy('nm_pengguna')->get();
        $data_pengisian = PengisianKegiatanHarian::where('id_kegiatan_harian',$id_kegiatan_harian)->whereMonth('tgl_pengisian', $id_bulan)->whereYear('tgl_pengisian', $tahun)->whereIn('id_pengguna_pengisi', $data_pengguna->pluck('id_pengguna'))->get();
        return view('humas/kegiatan-harian/rekap-lainnya/view-rekap-lainnya-guru-tendik', compact('auth_data', 'dates', 'data_bulan', 'bulan', 'data_pengguna', 'data_pengisian', 'tahun'));
    }

    public function viewRekapKegiatanSiswa(Request $request, $id_kegiatan_harian){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::today();
        if (empty($id_bulan)) {
            $id_bulan = $now->month;
        }

        if (empty($tahun)) {
            $tahun = $now->year;
        }

        $start_month = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();
        $kelas = Kelas::first();
        $allKelas = Kelas::all();
        // dd($kelas);

        $data_pengguna = Pengguna::whereHas('status_pengguna', function ($q) {
            $q->where('aktif_status_pengguna', 1);
        })->
        whereHas('siswa', function ($q) use($kelas) {
            $q->where('id_kelas', $kelas->id_kelas);
        })->orderBy('nm_pengguna')->get();
        // dd($data_pengguna);
        $data_pengisian = PengisianKegiatanHarian::where('id_kegiatan_harian',$id_kegiatan_harian)->whereMonth('tgl_pengisian', $id_bulan)->whereYear('tgl_pengisian', $tahun)->whereIn('id_pengguna_pengisi', $data_pengguna->pluck('id_pengguna'))->get();
        return view('humas/kegiatan-harian/rekap-lainnya/view-rekap-lainnya-siswa', compact('auth_data', 'dates', 'data_bulan', 'bulan', 'data_pengguna', 'data_pengisian', 'tahun','allKelas','kelas'));
    }

}
