<?php

namespace App\Http\Controllers\RaporBukuInduk\Rapor;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Models\Kelas;
use App\Models\LogKelasSiswa;
use App\Models\NilaiMp;
use App\Models\PengambilanMp;
use App\Models\PresensiMpSiswa;
use App\Models\RaporSiswa;
use App\Models\Siswa as Siswa;

use PDF;
use DB;
use Validator;

class CetakByKelasController extends BaseController
{
    public function viewCetakByKelas(Request $request, $id_kelas = null){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kelas = Kelas::get();

    	return view('rapor-buku-induk/rapor/cetak-by-kelas/view-cetak-by-kelas', compact('auth_data','id_kelas', 'kelas'));
    }

    public function actionViewCetakByKelas(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
  
        $validator = Validator::make($request->all(), [
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
                'path' => 'rapor/cetak-by-kelas/' . $input->id_kelas
            ];
        }
    }

    public function datatablesCetakByKelas(Request $request, $id_kelas){
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
          ->where(function ($query) use ($id_kelas) {
                    $query->where('kelas.id_kelas', $id_kelas);
             })
          ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
          ->get();
        
        return Datatables::of($siswa)
                ->addColumn('action', function($item) use ($id_kelas) {
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
        if($pengambilanMpAll->isEmpty()){
            return [
				'status' => 300, // FAILED
				'message' => 'Data akademik / mata pelajaran tidak ditemukan'
			];
        }

        $pengambilanMp = $pengambilanMpAll->first();

        $presensiMpSiswa = PresensiMpSiswa::where('id_siswa', $id_siswa)->get();
        $jumlahSakit = $presensiMpSiswa->where('kehadiran', 2)->count();
        $jumlahIzin = $presensiMpSiswa->where('kehadiran', 3)->count();
        $jumlahTanpaKeterangan = $presensiMpSiswa->where('kehadiran', 4)->count();

        // generate data rapor jika belum ada
        if(empty($data_rapor)){
            $data_rapor = new RaporSiswa();
        }
        
        DB::beginTransaction();
        try{
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
                        'message'	=> 'Insert Data Siswa Gagal : ' . $e->getMessage()
                    ];
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