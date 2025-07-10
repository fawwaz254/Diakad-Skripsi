<?php

namespace App\Http\Controllers\Kesiswaan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibDataAkademik;

use App\Models\Siswa as Siswa;
use App\Models\Staff as Staff;
use App\Models\Bank as Bank;
use App\Models\BankVia as BankVia;
use App\Models\TagihanBiaya as TagihanBiaya;
use App\Models\PembayaranBiaya as PembayaranBiaya;

use Auth;
use DB;
use Session;
use Validator;

class PembayaranController extends BaseController
{
	public function viewPembayaran(Request $request, $nis_nama_siswa = null){
	    # code..
		$input = (object) $request->input();
		$auth_data = auth_data();

		return view('kesiswaan/siswa/pembayaran/view-pembayaran',compact('auth_data','nis_nama_siswa'));
	}

	public function actionViewPembayaran(Request $request){
      # code...
		$input = (object) $request->input();
		$auth_data = auth_data();

		$validator = Validator::make($request->all(), [
			'nis_nama_siswa' =>'required'
		]);

		if($validator->fails()) {
			return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
      }
      else {
      	return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'siswa/pembayaran/view-detail/'.$input->nis_nama_siswa
                ];
        }
    }

     public function datatablesPembayaran(Request $request, $nis_nama_siswa){
        $input = (object) $request->input();
        $auth_data = auth_data();

        $siswa = Siswa::select('siswa.nis_siswa','siswa.nisn_siswa','pengguna.nm_pengguna','kelas.nm_kelas','status_pengguna.nm_status_pengguna','jalur.nm_jalur', DB::raw("(SELECT SUM(besar_biaya) FROM tagihan_biaya WHERE tagihan_biaya.id_siswa = siswa.id_siswa AND tagihan_biaya.is_tagih = 1 AND tagihan_biaya.deleted_at IS NULL) AS total_tagihan"), DB::raw("(SELECT SUM(denda_biaya) FROM tagihan_biaya WHERE tagihan_biaya.id_siswa = siswa.id_siswa AND tagihan_biaya.is_tagih = 1 AND tagihan_biaya.deleted_at IS NULL) AS total_denda"), DB::raw("(SELECT SUM(besar_pembayaran) FROM tagihan_biaya WHERE tagihan_biaya.id_siswa = siswa.id_siswa AND tagihan_biaya.is_tagih = 1 AND tagihan_biaya.deleted_at IS NULL) AS total_pembayaran"))
          ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
          ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
          ->join('status_pengguna','pengguna.id_status_pengguna','=','status_pengguna.id_status_pengguna')
          ->join('jalur_siswa', function ($join) {
                            $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                                 ->where('jalur_siswa.is_jalur_aktif', '=', 1);
                        })
          ->join('jalur','jalur_siswa.id_jalur','=','jalur.id_jalur')
          ->where(function ($query) use ($nis_nama_siswa) {
                    $query->where('siswa.nis_siswa', 'like', '%'.$nis_nama_siswa.'%')
                    ->orWhere('pengguna.nm_pengguna', 'like', '%'.$nis_nama_siswa.'%')
                    ->orWhere('siswa.nisn_siswa', 'like', '%'.$nis_nama_siswa.'%');
             })
          ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
          ->get();

        return Datatables::of($siswa)
                ->addColumn('total_tagihan', function($item){
                    return "Rp".number_format($item->total_tagihan + $item->total_denda - $item->total_pembayaran);
                })
                ->addColumn('action', function($item) use($nis_nama_siswa) {
                    $data = array(
                        'id' => $item->nis_siswa,
                        'id_asli' => $nis_nama_siswa
                    );
                    return $data;
                })
                ->make(true);
    }
    public function viewDetailPembayaran(Request $request, $nis_nama_siswa){
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $siswa = LibSiswa::fetchCariSiswaDetail($auth_data, $nis_nama_siswa);
  
        return view('kesiswaan/siswa/pembayaran/view-pembayaran',compact('auth_data','nis_nama_siswa'));
    }
    public function viewDetailSiswaPembayaran(Request $request, $nis_siswa, $nis_nama_siswa_asli){
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $nis_siswa);

        return view('kesiswaan/siswa/pembayaran/view-detail-pembayaran',compact('auth_data','nis_siswa','nis_nama_siswa_asli','siswa'));
    }

    public function datatablesTagihanPembayaran(Request $request, $id_pengguna, $nis_nama_siswa){
        $input = (object) $request->input();
        $auth_data = auth_data();

        $list_data = LibSiswa::fetchTagihanSiswa($auth_data, $id_pengguna);

        return Datatables::of($list_data)
                ->editColumn('nm_biaya', function ($item) {
                    if ($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_biaya." (".$item->nm_bulan.")";
                    } else {
                        return $item->nm_biaya." ".$item->keterangan;
                    }
                })
                ->addColumn('biaya_sekolah', function($item){
                    return $item->nm_kelompok_biaya." (".$item->tahun_ajaran." ".$item->nm_semester.")";
                })
                ->addColumn('jenis_biaya', function($item){
                    if($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_jenis_detail_biaya." (".$item->nm_bulan.")";
                    }
                    else {
                        return $item->nm_jenis_detail_biaya;
                    }
                })
                ->addColumn('besar_biaya', function($item){
                    return "Rp".number_format($item->besar_biaya);
                })
                ->addColumn('denda_biaya', function($item){
                    return "Rp".number_format($item->denda_biaya);
                })
                ->addColumn('besar_pembayaran', function($item){
                    return "Rp".number_format($item->besar_pembayaran);
                })
                ->addColumn('sisa_tagihan', function($item){
                    return "Rp".number_format($item->besar_biaya + $item->denda_biaya - $item->besar_pembayaran);
                })
                ->addColumn('action', function($item) use($nis_nama_siswa) {
                    $data = array(
                        'id' => $item->id_tagihan_biaya,
                        'id_asli' => $nis_nama_siswa
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesRiwayatBayarSiswa(Request $request, $id_pengguna){
        $input = (object) $request->input();
        $auth_data = auth_data();

        $list_data = LibSiswa::fetchPembayaranSiswa($auth_data, $id_pengguna);

        return Datatables::of($list_data)
                ->editColumn('nm_biaya', function ($item) {
                    if ($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_biaya." (".$item->nm_bulan.")";
                    } else {
                        return $item->nm_biaya." ".$item->keterangan;
                    }
                })
                ->addColumn('biaya_sekolah', function($item){
                    return $item->nm_kelompok_biaya." (".$item->tahun_ajaran_biaya." ".$item->nm_semester_biaya.")";
                })
                ->addColumn('jenis_biaya', function($item){
                    if($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_jenis_detail_biaya." (".$item->nm_bulan.")";
                    }
                    else {
                        return $item->nm_jenis_detail_biaya;
                    }
                })
                ->addColumn('besar_biaya', function($item){
                    return "Rp".number_format($item->besar_biaya);
                })
                ->addColumn('denda_biaya', function($item){
                    return "Rp".number_format($item->denda_biaya);
                })
                ->addColumn('besar_pembayaran', function($item){
                    return "Rp".number_format($item->besar_pembayaran);
                })
                ->addColumn('nm_pengguna', function($item){
                    if( ! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    }
                    elseif( ! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;   
                    }
                    elseif( ! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;   
                    }
                    else {
                        return $item->nm_pengguna; 
                    }
                })
                ->addColumn('tgl_pembayaran', function ($item) {
                    return strftime("%d %B %Y", strtotime($item->tgl_pembayaran));
                })
                ->addColumn('semester_bayar', function($item){
                    return $item->tahun_ajaran_bayar." ".$item->nm_semester_bayar;
                })
                ->addColumn('nm_bank', function($item){
                    if(! empty($item->nm_bank)) {
                        return $item->nm_bank_via." ".$item->nm_bank;
                    }
                    else {
                        return "-";
                    }
                })
                ->addColumn('action', function($item) {
                    $data = array(
                        'id' => $item->id_pembayaran_biaya
                    );
                    return $data;
                })
                ->make(true);
    }
}
