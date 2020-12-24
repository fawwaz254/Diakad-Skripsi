<?php

namespace App\Http\Controllers\RaporBukuInduk\Rapor;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Models\LogKelasSiswa;
use App\Models\NilaiMp;
use App\Models\PengambilanMp;
use App\Models\PresensiMpSiswa;
use App\Models\RaporSiswa;
use App\Models\Siswa as Siswa;

use Auth;
use PDF;
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

        $siswa = Siswa::select('siswa.id_siswa', 'siswa.nis_siswa','siswa.nisn_siswa','pengguna.nm_pengguna', 'kelas.id_kelas', 'kelas.nm_kelas', 'kelas.tingkat','status_pengguna.nm_status_pengguna','jalur.nm_jalur')
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
        
        $data = $siswa->map(function($row){
            $kelas_sekarang = [
                'id_siswa' => $row->id_siswa,
                'id_kelas' => $row->id_kelas,
                'nm_kelas' => $row->nm_kelas,
                'tingkat' => $row->tingkat,
            ];
            $log_kelas = LogKelasSiswa::select('log_kelas_siswa.id_siswa', 'kelas.id_kelas', 'kelas.nm_kelas', 'kelas.tingkat')
                                        ->join('kelas', 'kelas.id_kelas', 'log_kelas_siswa.id_kelas')
                                        ->where('id_siswa', $row->id_siswa)->get();
            $all_log_kelas = [];
            if(empty($log_kelas))
                $all_log_kelas[] = $log_kelas->toArray();
            $all_log_kelas[] = $kelas_sekarang;
            $row['log_kelas'] = $all_log_kelas;
            return $row;
        });
        
        return Datatables::of($data)
                ->addColumn('action', function($item) use ($nis_nama_siswa) {
                    $data = array(
                        'id' => $item->nis_siswa,
                    );
                    return $data;
                })
                ->make(true);
    }

    public function previewRaporSiswa(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $data = [
            'id_siswa' => $input->id_siswa,
            'id_kelas' => $input->id_kelas
        ];

        $validator = Validator::make($data, [
            'id_siswa' =>'required|exists:siswa,id_siswa',
            'id_kelas' =>'required|exists:kelas,id_kelas',
        ]);

        if($validator->fails()){
            return [
				'status' => 300, // FAILED
				'message' => $validator->errors()->first()
			];
        }

        // $pengambilanMp = PengambilanMp::where('id_siswa', $input->id_siswa)->get();
        $pengambilanMp = PengambilanMp::where('id_siswa', $input->id_siswa)
                                    ->where('kelas_mp.id_kelas', $input->id_kelas)
                                    ->join('kelas_mp', 'kelas_mp.id_kelas_mp', 'pengambilan_mp.id_kelas_mp')
                                    ->get();
        
        $data_nilai = [];
        foreach($pengambilanMp as $row){
            $data_nilai[] = [
                'id_kelas_mp' => $row->id_kelas_mp,
                'kelas_mp' => $row->kelas_mp->nm_kelas_mp,
                'nilai_angka' => $row->nilai_angka,
                'nilai_huruf' => $row->nilai_huruf,
            ];
        }
        return !empty($data_nilai) ? $data_nilai : null;
    }

    public function printRaporSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $id_siswa = $input->id_siswa;
        $id_kelas = $input->id_kelas;
        $catatan = $input->deskripsi_catatan_wali_kelas;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        
        $data = [
            'id_siswa' => $id_siswa,
            'id_kelas' => $id_kelas
        ];

        $validator = Validator::make($data, [
            'id_siswa' =>'required|exists:siswa,id_siswa',
            'id_kelas' =>'required|exists:kelas,id_kelas',
        ]);

        if($validator->fails()){
            return [
				'status' => 300, // FAILED
				'message' => $validator->errors()->first()
			];
        }

        // get data rapor
        $data_rapor = RaporSiswa::where([
            'id_siswa' => $id_siswa,
            'id_kelas' => $id_kelas
        ])->first();

        $pengambilanMpAll = PengambilanMp::where('id_siswa', $id_siswa)
                        ->where('kelas_mp.id_kelas', $id_kelas)
                        ->join('kelas_mp', 'kelas_mp.id_kelas_mp', 'pengambilan_mp.id_kelas_mp')
                        ->join('mata_pelajaran', 'kelas_mp.id_mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran')
                        ->join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', 'mata_pelajaran.id_jenis_mata_pelajaran')
                        ->get();

        $pengambilanMp = $pengambilanMpAll->first();

        $presensiMpSiswa = PresensiMpSiswa::where('id_siswa', $id_siswa)->get();
        $jumlahSakit = $presensiMpSiswa->where('kehadiran', 2)->count();
        $jumlahIzin = $presensiMpSiswa->where('kehadiran', 3)->count();
        $jumlahTanpaKeterangan = $presensiMpSiswa->where('kehadiran', 4)->count();

        // generate data rapor jika belum ada
        if(empty($data_rapor)){
            DB::beginTransaction();

            try{
            $data_rapor = new RaporSiswa();
            $data_rapor->id_rapor_siswa	    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
            $data_rapor->id_siswa           = $id_siswa;
            $data_rapor->id_kelas           = $id_kelas;
            $data_rapor->id_semester        = !empty($pengambilanMp) ? $pengambilanMp->id_semester : null;
            $data_rapor->id_rapor_deskripsi = null; // belum diisi
            $data_rapor->jumlah_sakit       = $jumlahSakit;
            $data_rapor->jumlah_izin        = $jumlahIzin;
            $data_rapor->jumlah_tanpa_keterangan = $jumlahTanpaKeterangan;
            $data_rapor->id_prestasi_siswa  = null; //belum diisi
            $data_rapor->deskripsi_catatan_wali_kelas = $catatan;
            $data_rapor->nilai_kkm          = !empty($pengambilanMp) ? $pengambilanMp->nilai_kkm : null;
            $data_rapor->nilai_angka        = !empty($pengambilanMp) ? $pengambilanMp->nilai_angka : null;
            $data_rapor->nilai_huruf        = !empty($pengambilanMp) ? $pengambilanMp->nilai_huruf : null;
            $data_rapor->created_by         = $input->auth_data->pengguna->id_pengguna;
            $data_rapor->save();
            DB::commit();
            }
            catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return [
                            'status' 	=> 200, // GAGAL
                            'message'	=> 'Insert Data Siswa Gagal'
                        ];
            } 
        }

        // get all data detail rapor
        $data_detail_rapor = [];
        if($pengambilanMpAll->isNotEmpty()){
            $data_detail_rapor = $pengambilanMpAll->groupBy('nm_jenis_mata_pelajaran');
        }
        
        $data_siswa = Siswa::find($id_siswa);
        
        // dd($data_rapor, $data_detail_rapor, $data_siswa);

        $pdf = PDF::loadView('rapor-buku-induk/rapor/cari-siswa/download-rapor-siswa', compact('data_rapor', 'auth_data', 'data_detail_rapor', 'data_siswa'))->setPaper('a4', 'potrait');
        return $pdf->stream();
    }
}