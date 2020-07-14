<?php

namespace App\Http\Controllers\Keuangan\Utility;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibKelas;
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

class PembayaranByKelasController extends BaseController
{
    public function viewPembayaranByKelas(Request $request, $id_semester = null, $id_kelas = null){
	    # code..
	    $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

    	return view('keuangan/utility/pembayaran-by-kelas/view-pembayaran-by-kelas',compact('auth_data', 'data_semester', 'data_kelas', 'id_semester', 'id_kelas'));
    }
      
    public function actionViewPembayaranByKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester' =>'required',
            'id_kelas' =>'required'
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
                    'path' => 'utility/pembayaran-by-kelas/view-detail/'.$input->id_semester.'/'.$input->id_kelas
                ];
        }
    }

    public function viewDetailPembayaranByKelas(Request $request, $id_semester, $id_kelas){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        $data_siswa = Siswa::with('pengguna')->where('siswa.id_kelas', $id_kelas)
                            ->get();

        $data_tagihan = TagihanBiaya::select('tagihan_biaya.id_siswa', 'siswa.nis_siswa', 'tagihan_biaya.id_tagihan_biaya', 'tagihan_biaya.is_tagih', 'tagihan_biaya.is_request', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', DB::raw("(SELECT SUM(besar_pembayaran) FROM pembayaran_biaya WHERE pembayaran_biaya.id_tagihan_biaya = tagihan_biaya.id_tagihan_biaya AND pembayaran_biaya.deleted_at IS NULL) AS besar_pembayaran"), 'pembayaran_biaya.tgl_pembayaran', 'pembayaran_biaya.id_pembayaran_biaya')
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

        return view('keuangan/utility/pembayaran-by-kelas/view-pembayaran-by-kelas',compact('auth_data', 'data_semester', 'data_kelas', 'id_semester', 'id_kelas', 'data_siswa', 'data_tagihan', 'data_bulan_tagihan'));
    }

}