<?php

namespace App\Http\Controllers\Guru\Penilaian;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Siswa as Siswa;
use App\Models\Semester as Semester;
use App\Models\Guru as Guru;
use App\Models\KomponenMp as KomponenMp;
use App\Models\PengambilanMp as PengambilanMp;
use App\Models\PeraturanNilai as PeraturanNilai;
use App\Models\NilaiMp as NilaiMp;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class InputNilaiController extends BaseController
{
    public function viewInputNilai(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get all data kelas_mp by id_pengguna guru
        $data_kelas = LibGuru::fetchDataKelasGuru($auth_data, $auth_data->pengguna->id_pengguna);

        /** groupping by tahun_ajaran and nm_semester */
        $grup_semester_kelas = $data_kelas->groupBy('tahun_ajaran')->transform(function($item, $k) {
            return $item->groupBy('nm_semester');
        });

        return view('guru/penilaian//input-nilai/view-input-nilai',compact('auth_data','data_kelas','grup_semester_kelas'));
    }

    public function actionViewKelasInputNilai(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas_mp' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            return [
                        'status' => 204, // SUCCESS AND LOAD CONTENT
                        'path' => 'penilaian/input-nilai/view-kelas/'.$input->id_kelas_mp
                    ];   
        }
    }

    public function viewKelasInputNilai(Request $request, $id_kelas_mp){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);
        $list_data = LibGuru::fetchDataKomponenNilai($auth_data, $id_kelas_mp);

        $jumlah_komponen = KomponenMp::select('komponen_mp.persentase_komponen_mp')->where('komponen_mp.id_kelas_mp','=',$id_kelas_mp)->sum('komponen_mp.persentase_komponen_mp');
        $list_siswa = PengambilanMp::select('siswa.nis_siswa','pengguna.nm_pengguna','pengambilan_mp.nilai_angka','pengambilan_mp.nilai_huruf','siswa.id_siswa','pengambilan_mp.id_pengambilan_mp')
            ->join('siswa','siswa.id_siswa','=','pengambilan_mp.id_siswa')
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
            ->where('pengambilan_mp.id_kelas_mp','=',$id_kelas_mp)->get();

        return view('guru/penilaian/input-nilai/view-kelas-input-nilai',compact('auth_data','data_kelas','list_data','jumlah_komponen','list_siswa','id_kelas_mp'));

    }

    public function actionInputNilai(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas_mp' => 'required'
        ]);

        if($validator->fails() && $mode != 'tampil') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if($mode == 'tampil'){
                DB::beginTransaction();
        
                try {
                    $pengambilanMp = PengambilanMp::find($id);
                    $pengambilanMp->is_tampil = 1;
                    $pengambilanMp->save();

                    DB::commit();

                    return [
                        'status' => 200, // SUCCESS AND LOAD TABLE
                        'message' => 'Nilai Berhasil Ditampilkan'
                    ];

                } catch (\Exception $e) {
                    DB::rollback();

                    return [
                        'status' 	=> 300,
                        'message' => 'Aksi gagal'
                    ];
                }   
            }
            elseif($mode == 'kbm'){
                DB::beginTransaction();
        
                try {
                    $list_data = KomponenMp::where('id_kelas_mp','=',$input->id_kelas_mp)->get();
                    $list_siswa = PengambilanMp::select('siswa.nis_siswa','pengguna.nm_pengguna','pengambilan_mp.nilai_angka','pengambilan_mp.nilai_huruf','siswa.id_siswa','pengambilan_mp.id_pengambilan_mp')
                        ->join('siswa','siswa.id_siswa','=','pengambilan_mp.id_siswa')
                        ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                        ->where('pengambilan_mp.id_kelas_mp','=',$input->id_kelas_mp)->get();
                    $nilai_akhir_final = array();
                    foreach($list_siswa as $dataSiswa => $siswa){
                        foreach($list_data as $dataKomponen => $data){
                            $nameInput = 'nilai'.$data->id_komponen_mp.'-'.$siswa->id_siswa;                    
                            $nilaiCount = ($input->$nameInput*($data->persentase_komponen_mp/100));
                            $nilai_akhir_final['nilai_angka'.$siswa->id_siswa][$data->id_komponen_mp]=$nilaiCount;

                        }

                        $namePengambilan = 'id_pengambilan_mp_'.$siswa->id_siswa;
                        $idSiswa = substr($namePengambilan, 18);
                        if($idSiswa == $siswa->id_siswa){

                            $pengambilanMp = PengambilanMp::where('id_kelas_mp','=',$input->id_kelas_mp)
                                ->where('id_siswa','=',$idSiswa)
                                ->first();
                            if($pengambilanMp){
                                if($pengambilanMp->nilai_angka == null){
                                    
                                    foreach ($nilai_akhir_final['nilai_angka'.$idSiswa] as $key => $value) {
                                        $nameInput                              = 'nilai'.$key.'-'.$idSiswa;                    
                                        $nilaiMp                                = new NilaiMp;
                                        $nilaiMp->id_nilai_mp                   = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                                        $nilaiMp->id_pengambilan_mp             = $siswa->id_pengambilan_mp;
                                        $nilaiMp->id_komponen_mp                = $key;
                                        if($input->$nameInput == null){
                                            $nilaiMp->besar_nilai_mp    = 0;
                                        }else{
                                            $nilaiMp->besar_nilai_mp            = $value;
                                        }
                                        $nilaiMp->created_by                    = $input->auth_data->pengguna->id_pengguna;
                                        $nilaiMp->created_at                    = $now;
                                        $nilaiMp->save();
                                    }
                                    $nilaiMp                                    = NilaiMp::select('besar_nilai_mp')->where('id_pengambilan_mp','=',$pengambilanMp->id_pengambilan_mp)->sum('besar_nilai_mp');
                                    $nilai_huruf = PeraturanNilai::join('standar_nilai','standar_nilai.id_standar_nilai','=','peraturan_nilai.id_standar_nilai')
                                        ->where('peraturan_nilai.is_mata_pelajaran','=','1')
                                        ->where('peraturan_nilai.nilai_min_peraturan_nilai','<',$nilaiMp)
                                        ->where('peraturan_nilai.nilai_max_peraturan_nilai','>',$nilaiMp)
                                        ->first();
                                    if($nilai_huruf){
                                        $nilai_huruf = $nilai_huruf['nm_standar_nilai'];
                                    }else{
                                        $nilai_huruf = "-";
                                    }
                                    $nilai_pengambilanMp                        = PengambilanMp::find($siswa->id_pengambilan_mp);
                                    $nilai_pengambilanMp->nilai_angka           = $nilaiMp;
                                    $nilai_pengambilanMp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                                    $nilai_pengambilanMp->updated_at            = $now;
                                    $nilai_pengambilanMp->nilai_huruf           = $nilai_huruf;
                                    $nilai_pengambilanMp->save();
                                    
                                }
                                else{

                                    foreach ($nilai_akhir_final['nilai_angka'.$idSiswa] as $key => $value){
                                        $nameInput                              = 'nilai'.$key.'-'.$idSiswa;                    
                                        $nilaiMp                                = NilaiMp::where('id_pengambilan_mp','=',$pengambilanMp->id_pengambilan_mp)->where('id_komponen_mp','=',$key)->first();
                                        $nilaiMp->besar_nilai_mp                = $value;
                                        $nilaiMp->updated_by                    = $input->auth_data->pengguna->id_pengguna;
                                        $nilaiMp->updated_at                    = $now;
                                        $nilaiMp->save();
                                    }
                                    $nilaiMp                                    = NilaiMp::select('besar_nilai_mp')->where('id_pengambilan_mp','=',$pengambilanMp->id_pengambilan_mp)->sum('besar_nilai_mp');
                                    $nilai_pengambilanMp                        = PengambilanMp::find($siswa->id_pengambilan_mp);
                                    $nilai_pengambilanMp->nilai_angka           = $nilaiMp;
                                    $nilai_pengambilanMp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                                    $nilai_pengambilanMp->updated_at            = $now;
                                    $nilai_pengambilanMp->save();

                                }
                            }
                        }
                    }

                    DB::commit();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'message' => 'Sukses',
                        'path' => 'penilaian/input-nilai/view-kelas/'.$input->id_kelas_mp
                    ];

                } catch (\Exception $e) {
                    DB::rollback();

                    return [
                        'status' 	=> 300,
                        'message' => 'Penilaian gagal'
                    ];
                }   
                
            }
        }
    }
}
