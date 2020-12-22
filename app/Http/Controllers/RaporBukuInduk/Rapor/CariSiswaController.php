<?php

namespace App\Http\Controllers\RaporBukuInduk\Rapor;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Models\RaporSiswa;
use App\Models\Siswa as Siswa;

use Auth;
use DB;
use Session;
use Validator;

class CariSiswaController extends BaseController
{
    public function viewCariSiswa(Request $request, $nis_nama_siswa = null){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('rapor-buku-induk/rapor/cari-siswa/view-cari-siswa', compact('auth_data','nis_nama_siswa'));
    }

    public function actionViewCariSiswa(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
  
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
                'path' => 'rapor/cari-siswa/' . $input->nis_nama_siswa
            ];
        }
    }

    public function datatablesCariSiswa(Request $request, $nis_nama_siswa){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::select('siswa.nis_siswa','siswa.nisn_siswa','pengguna.nm_pengguna','kelas.nm_kelas','status_pengguna.nm_status_pengguna','jalur.nm_jalur')
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
                ->addColumn('action', function($item) use ($nis_nama_siswa) {
                    $data = array(
                        'id' => $item->nis_siswa,
                    );
                    return $data;
                })
                ->make(true);
    }

    public function printRaporSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_rapor = RaporSiswa::get();

        /** rapor berdasarkan {rapor_siswa, 
         * rapor_deskripsi, 
         * rapor_kategori, 
         * rapor_subkategori, 
         * rapor_kelompok, 
         * rapor_kelompok_mp, 
         * rapor_subkelompok_mp} */

        $pdf = PDF::loadView('akademik/presensi/cetak-presensi-kbm/download-cetak-presensi-kbm', compact('data_siswa', 'auth_data', 'semester_aktif', 'data_kelas'))->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}