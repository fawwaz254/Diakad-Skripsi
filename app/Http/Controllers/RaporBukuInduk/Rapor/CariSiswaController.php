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

        // get all data rapor by id_siswa, id_kelas
        $data_rapor = RaporSiswa::where([
            'id_siswa' => $id_siswa,
            'id_kelas' => $id_kelas
        ])->get();

        $pengambilanMpAll = PengambilanMp::where('id_siswa', $id_siswa)
                        ->where('kelas_mp.id_kelas', $id_kelas)
                        ->join('kelas_mp', 'kelas_mp.id_kelas_mp', 'pengambilan_mp.id_kelas_mp')
                        ->join('mata_pelajaran', 'kelas_mp.id_mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran')
                        ->join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', 'mata_pelajaran.id_jenis_mata_pelajaran')
                        ->get();

        $presensiMpSiswa = PresensiMpSiswa::join('presensi_mp', 'presensi_mp.id_presensi_mp', 'presensi_mp_siswa.id_presensi_mp')
                                            ->join('kelas_mp', 'kelas_mp.id_kelas_mp', 'presensi_mp.id_kelas_mp')
                                            ->where('id_siswa', $id_siswa)
                                            ->get();
        
        $pengambilanEkskulAll = PengambilanEkskul::where('id_siswa', $id_siswa)
                                                ->where('id_kelas', $id_kelas)
                                                ->join('nilai_ekskul', 'nilai_ekskul.id_pengambilan_ekskul', 'pengambilan_ekskul.id_pengambilan_ekskul')
                                                ->get();
                        
        $presensiEkskulSiswa = PresensiEkskulPeserta::select('presensi_ekskul_peserta.*', 'ekskul.id_ekskul', 'ekskul.nm_ekskul')
                                ->join('presensi_ekskul', 'presensi_ekskul.id_presensi_ekskul', 'presensi_ekskul_peserta.id_presensi_ekskul')
                                ->join('ekskul', 'presensi_ekskul.id_ekskul', 'ekskul.id_ekskul')
                                ->where('id_siswa', $id_siswa)
                                ->where('id_kelas', $id_kelas)
                                ->get();

        // dd($pengambilanEkskulAll, $presensiEkskulSiswa);

        DB::beginTransaction();
        try{
            // pengisian data rapor = Mapel
            foreach ($pengambilanMpAll as $mp){
                // insert rapor_deskripsi first, then rapor_siswa
                if($data_rapor->isNotEmpty())
                    $rapor_siswa = $data_rapor->shift();

                // start rapor_deskripsi
                if(isset($rapor_siswa->id_rapor_deskripsi) && !empty($rapor_siswa->id_rapor_deskripsi)){
                    $rapor_deskripsi = RaporDeskripsi::find($rapor_siswa->id_rapor_deskripsi);
                } else {
                    $rapor_deskripsi = new RaporDeskripsi();
                    $rapor_deskripsi->id_rapor_deskripsi = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                }

                $rapor_kelompok_mp = RaporKelompokMp::where('id_mata_pelajaran', $mp->id_mata_pelajaran)->first();
                $rapor_subkelompok_mp = RaporSubkelompokMp::where('id_mata_pelajaran', $mp->id_mata_pelajaran)->first();
                if(empty($rapor_kelompok_mp) && !empty($rapor_subkelompok_mp))
                    $rapor_kelompok_mp = RaporKelompokMp::find($rapor_subkelompok_mp->id_rapor_kelompok_mp);

                $rapor_deskripsi->id_rapor_subkategori = null;
                $rapor_deskripsi->id_rapor_kelompok_mp = !empty($rapor_kelompok_mp) ? $rapor_kelompok_mp->id_rapor_kelompok_mp : null;
                $rapor_deskripsi->id_rapor_subkelompok_mp = !empty($rapor_subkelompok_mp) ? $rapor_subkelompok_mp->id_rapor_subkelompok_mp : null;
                $rapor_deskripsi->id_ekstrakurikuler = null;
                $rapor_deskripsi->predikat_rapor_deskripsi = $mp->nilai_huruf;
                $rapor_deskripsi->deskripsi_rapor = 'Deskripsi.....';
                $rapor_deskripsi->created_by = $input->auth_data->pengguna->id_pengguna;
                $rapor_deskripsi->save();
                // end rapor_deskripsi

                // start rapor_siswa
                if(empty($rapor_siswa)){
                    $rapor_siswa = new RaporSiswa();
                    $rapor_siswa->id_rapor_siswa	 = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                }

                $sakit = $presensiMpSiswa->where('kehadiran', 2)->where('id_mata_pelajaran', $mp->id_mata_pelajaran)->count();
                $izin = $presensiMpSiswa->where('kehadiran', 3)->where('id_mata_pelajaran', $mp->id_mata_pelajaran)->count();
                $tanpa_keterangan = $presensiMpSiswa->where('kehadiran', 4)->where('id_mata_pelajaran', $mp->id_mata_pelajaran)->count();

                $rapor_siswa->id_siswa           = $id_siswa;
                $rapor_siswa->id_kelas           = $id_kelas;
                $rapor_siswa->id_semester        = $mp->id_semester;
                $rapor_siswa->id_rapor_deskripsi = $rapor_deskripsi->id_rapor_deskripsi;
                $rapor_siswa->jumlah_sakit       = $sakit;
                $rapor_siswa->jumlah_izin        = $izin;
                $rapor_siswa->jumlah_tanpa_keterangan = $tanpa_keterangan;
                $rapor_siswa->id_prestasi_siswa  = null; //belum diisi
                $rapor_siswa->deskripsi_catatan_wali_kelas = $catatan;
                $rapor_siswa->nilai_kkm          = $mp->nilai_kkm;
                $rapor_siswa->nilai_angka        = $mp->nilai_angka;
                $rapor_siswa->nilai_huruf        = $mp->nilai_huruf;
                $rapor_siswa->created_by         = $input->auth_data->pengguna->id_pengguna;
                $rapor_siswa->save();
                // end rapor_siswa
                // dd('rapor', $rapor_deskripsi, $rapor_siswa);
            }


            // pengisian data rapor = Ekskul
            foreach($pengambilanEkskulAll as $ekskul){
                // insert rapor_deskripsi first, then rapor_siswa
                if($data_rapor->isNotEmpty())
                    $rapor_siswa = $data_rapor->shift();

                // start rapor_deskripsi
                if(isset($rapor_siswa->id_rapor_deskripsi) && !empty($rapor_siswa->id_rapor_deskripsi)){
                    $rapor_deskripsi = RaporDeskripsi::find($rapor_siswa->id_rapor_deskripsi);
                } else {
                    $rapor_deskripsi = new RaporDeskripsi();
                    $rapor_deskripsi->id_rapor_deskripsi = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                }

                $rapor_deskripsi->id_rapor_subkategori = null;
                $rapor_deskripsi->id_rapor_kelompok_mp = null;
                $rapor_deskripsi->id_rapor_subkelompok_mp = null;
                $rapor_deskripsi->id_ekstrakurikuler = $ekskul->id_ekskul;
                $rapor_deskripsi->predikat_rapor_deskripsi = $ekskul->nilai_huruf;
                $rapor_deskripsi->deskripsi_rapor = 'Deskripsi.....';
                $rapor_deskripsi->created_by = $input->auth_data->pengguna->id_pengguna;
                $rapor_deskripsi->save();
                // end rapor_deskripsi

                // start rapor_siswa
                if(empty($rapor_siswa)){
                    $rapor_siswa = new RaporSiswa();
                    $rapor_siswa->id_rapor_siswa	 = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                }

                $sakit = $presensiEkskulSiswa->where('kehadiran', 2)
                                            ->where('id_ekskul', $ekskul->id_ekskul)
                                            ->count();
                $izin = $presensiEkskulSiswa->where('kehadiran', 3)
                                            ->where('id_ekskul', $ekskul->id_ekskul)
                                            ->count();
                $tanpa_keterangan = $presensiEkskulSiswa->where('kehadiran', 4)
                                                        ->where('id_ekskul', $ekskul->id_ekskul)
                                                        ->count();

                $rapor_siswa->id_siswa           = $id_siswa;
                $rapor_siswa->id_kelas           = $id_kelas;
                $rapor_siswa->id_semester        = $ekskul->id_semester;
                $rapor_siswa->id_rapor_deskripsi = $rapor_deskripsi->id_rapor_deskripsi;
                $rapor_siswa->jumlah_sakit       = $sakit;
                $rapor_siswa->jumlah_izin        = $izin;
                $rapor_siswa->jumlah_tanpa_keterangan = $tanpa_keterangan;
                $rapor_siswa->id_prestasi_siswa  = null; //belum diisi
                $rapor_siswa->deskripsi_catatan_wali_kelas = $catatan;
                $rapor_siswa->nilai_kkm          = null;
                $rapor_siswa->nilai_angka        = $ekskul->nilai_angka;
                $rapor_siswa->nilai_huruf        = $ekskul->nilai_huruf;
                $rapor_siswa->created_by         = $input->auth_data->pengguna->id_pengguna;
                $rapor_siswa->save();
                // end rapor_siswa
                // dd('rapor', $rapor_deskripsi, $rapor_siswa);
            }
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return [
                        'status' 	=> 200, // GAGAL
                        'message'	=> 'Insert Data Rapor Siswa Gagal'
                    ];
        } 
        
        $format_rapor_kategori = RaporKategori::orderBy('urutan_rapor_kategori')->get();

        $format_rapor_kelompok = RaporKelompok::orderBy('urutan_rapor_kelompok')->get();
        
        $format_rapor_kelompok_mp = RaporKelompokMp::leftJoin('rapor_subkelompok_mp', 'rapor_kelompok_mp.id_rapor_kelompok_mp', 'rapor_subkelompok_mp.id_rapor_kelompok_mp')->get();

        $new_data_rapor = RaporSiswa::select('rapor_siswa.*', 'rapor_deskripsi.*', 'rapor_kelompok_mp.id_rapor_kelompok', 'id_rapor_kategori', 'rapor_kelompok_mp.nm_rapor_kelompok_mp', 'mata_pelajaran.*', 'semester.nm_semester', 'semester.thn_akademik_semester', 'semester.tahun_ajaran')
            ->join('rapor_deskripsi', 'rapor_siswa.id_rapor_deskripsi', 'rapor_deskripsi.id_rapor_deskripsi')
            // ->leftJoin('rapor_subkategori', 'rapor_deskripsi.id_rapor_subkategori', 'rapor_subkategori.id_rapor_subkategori')
            ->leftJoin('rapor_kelompok_mp', 'rapor_deskripsi.id_rapor_kelompok_mp', 'rapor_kelompok_mp.id_rapor_kelompok_mp')
            ->leftJoin('mata_pelajaran', 'rapor_kelompok_mp.id_mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran')
            ->leftJoin('rapor_subkelompok_mp', 'rapor_deskripsi.id_rapor_subkelompok_mp', 'rapor_subkelompok_mp.id_rapor_subkelompok_mp')
            ->join('semester', 'rapor_siswa.id_semester', 'semester.id_semester')
            ->where('id_ekstrakurikuler', '=', null)
            ->where([
                'id_siswa' => $id_siswa,
                'id_kelas' => $id_kelas
            ])
            ->get();
        
        // get all data detail rapor based on new rapor_siswa
        $data_detail_rapor = [];
        foreach($new_data_rapor as $new_rapor){
            $data_detail_rapor[] = $new_rapor;
        }

        // $data_ekskul = RaporSiswa::join('rapor_deskripsi', 'rapor_siswa.id_rapor_deskripsi', 'rapor_deskripsi.id_rapor_deskripsi')
        //                         ->get();

        $presensi = [
            'sakit' => collect($data_detail_rapor)->sum('jumlah_sakit'),
            'izin' => collect($data_detail_rapor)->sum('jumlah_izin'),
            'tanpa_keterangan' => collect($data_detail_rapor)->sum('jumlah_tanpa_keterangan')
        ];
        
        $data_siswa = Siswa::find($id_siswa);
        
        // dd($format_rapor_kelompok, $data_detail_rapor, $data_siswa, $presensi, $auth_data->sekolah_data->nm_sekolah);

        $pdf = PDF::loadView('rapor-buku-induk/rapor/cari-siswa/download-rapor-siswa', compact('auth_data', 'data_detail_rapor', 'data_siswa', 'presensi', 'format_rapor_kategori', 'format_rapor_kelompok', 'format_rapor_kelompok_mp'))->setPaper('a4', 'potrait');
        return $pdf->stream();
    }
}