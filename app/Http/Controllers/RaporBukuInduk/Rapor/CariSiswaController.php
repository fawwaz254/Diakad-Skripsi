<?php

namespace App\Http\Controllers\RaporBukuInduk\Rapor;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Models\LogKelasSiswa;
use App\Models\NilaiMp;
use App\Models\PengambilanEkskul;
use App\Models\PengambilanMagang;
use App\Models\PengambilanMp;
use App\Models\PresensiEkskulPeserta;
use App\Models\PresensiMpSiswa;
use App\Models\RaporDeskripsi;
use App\Models\RaporKategori;
use App\Models\RaporKelompok;
use App\Models\RaporKelompokMp;
use App\Models\RaporSiswa;
use App\Models\RaporSubkelompokMp;
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
        $auth_data = auth_data();

    	return view('rapor-buku-induk/rapor/cari-siswa/view-cari-siswa', compact('auth_data','nis_nama_siswa'));
    }

    public function actionViewCariSiswa(Request $request){
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
                'path' => 'rapor/cari-siswa/' . $input->nis_nama_siswa
            ];
        }
    }

    public function datatablesCariSiswa(Request $request, $nis_nama_siswa){
        $input = (object) $request->input();
        $auth_data = auth_data();

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
                $pengambilanMpSiswa = PengambilanMp::select('pengambilan_mp.id_semester', 
                                                            'pengambilan_mp.id_siswa',
                                                            'kelas_mp.id_kelas',
                                                            'kelas.nm_kelas',
                                                            'kelas.tingkat',
                                                            'semester.nm_semester')
                                                    ->join('kelas_mp', 'kelas_mp.id_kelas_mp', 'pengambilan_mp.id_kelas_mp')
                                                    ->join('kelas', 'kelas.id_kelas', 'kelas_mp.id_kelas')
                                                    ->join('semester', 'semester.id_semester', 'pengambilan_mp.id_semester')
                                                    ->where('id_siswa', $row->id_siswa)
                                                    ->groupBy('id_semester', 'id_siswa', 'id_kelas', 'nm_kelas', 'tingkat', 'nm_semester')
                                                    ->get();
                                                    
                $all_log_kelas = [];
                foreach($pengambilanMpSiswa as $kelas){
                    $all_log_kelas[] = $kelas->toArray();
                }

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
        $auth_data = auth_data();
        
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

        $validator = Validator::make(collect($input)->toArray(), [
            'id_siswa' =>'required|exists:siswa,id_siswa',
            'id_kelas' =>'required|exists:kelas,id_kelas',
            'id_semester' =>'required|exists:semester,id_semester',
        ]);

        if($validator->fails()){
            return [
				'status' => 300, // FAILED
				'message' => $validator->errors()->first()
			];
        }
        
        $print = new PrintRaporController($request);
        $pdf = $print->printRaporSiswa();

        if($pdf['status'] == 200){
            return $pdf['pdf']->stream();
        } else {
            return $pdf;
        }
    }
}