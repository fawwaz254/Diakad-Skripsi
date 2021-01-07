<?php

namespace App\Http\Controllers\Akademik\AktivitasSemester;

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

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $semester_aktif = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->where('is_aktif_semester','=','1')->first();
	   	$guru = Guru::join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
	   		->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
	   		->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
	   		->where('status_pengguna.aktif_status_pengguna','=','1')
	   		->get();

        return view('akademik/aktivitas-semester/input-nilai/view-input-nilai',compact('auth_data','data_semester','semester_aktif','guru'));
    }

    public function actionInputNilai(Request $request){
      # code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		$validator = Validator::make($request->all(), [
			'id_semester' =>'required',
			'id_pengguna' => 'required'
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
	                'path' => 'aktivitas-semester/input-nilai/view-guru-input-nilai/'.$input->id_pengguna.'/'.$input->id_semester
	            ];
        }
    }

    public function viewGuruInputNilai(Request $request, $id_pengguna, $id_semester){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($id_pengguna);

        $data_kelas = LibGuru::fetchDataKelasGuru($auth_data, $id_pengguna, $id_semester);

        /** groupping by tahun_ajaran and nm_semester */
        $grup_semester_kelas = $data_kelas->groupBy('tahun_ajaran')->transform(function($item, $k) {
            return $item->groupBy('nm_semester');
        });

        return view('akademik/aktivitas-semester/input-nilai/view-mapel-input-nilai',compact('auth_data','data_kelas','grup_semester_kelas','id_pengguna','id_semester'));
    }

    public function actionViewKelasKomponenNilai(Request $request){
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
                        'path' => 'aktivitas-semester/input-nilai/view-kelas/'.$input->id_pengguna.'/'.$input->id_semester.'/'.$input->id_kelas_mp
                    ];   
        }
    }

    public function viewKelasKomponenNilai(Request $request, $id_pengguna, $id_semester,$id_kelas_mp){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);

        return view('akademik/aktivitas-semester/input-nilai/view-kelas-input-nilai',compact('auth_data','data_kelas','id_semester','id_pengguna','id_kelas_mp'));

    }

    public function viewSiswaPerMapel(Request $request, $id_pengguna, $id_semester,$id_kelas_mp){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);
        $list_data = LibGuru::fetchDataKomponenNilai($auth_data, $id_kelas_mp);

        $jumlah_komponen = KomponenMp::select('komponen_mp.persentase_komponen_mp')->where('komponen_mp.id_kelas_mp','=',$id_kelas_mp)->sum('komponen_mp.persentase_komponen_mp');
        $list_siswa = PengambilanMp::select('siswa.nis_siswa','pengguna.nm_pengguna','pengambilan_mp.nilai_angka','pengambilan_mp.nilai_huruf','siswa.id_siswa','pengambilan_mp.id_pengambilan_mp', 'pengambilan_mp.id_kelas_mp')
            ->join('siswa','siswa.id_siswa','=','pengambilan_mp.id_siswa')
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
            ->where('pengambilan_mp.id_kelas_mp','=',$id_kelas_mp)->get();
        $pengambilan_mp = PengambilanMp::where('pengambilan_mp.id_kelas_mp','=',$id_kelas_mp)->first();

        return view('akademik/aktivitas-semester/input-nilai/view-siswa-input-nilai',compact('auth_data','data_kelas','id_semester','id_pengguna','list_data','jumlah_komponen','list_siswa','pengambilan_mp'));

    }

    public function datatablesMataPelajaran(Request $request, $id_pengguna, $id_semester){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibGuru::fetchDataKelasGuru($auth_data, $id_pengguna, $id_semester);

        return Datatables::of($list_data)
                ->addColumn('mata_pelajaran', function($item){
                    return $item->kd_mata_pelajaran." - ".$item->nm_mata_pelajaran;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kelas_mp
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesKomponenNilai(Request $request, $id_kelas_mp){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibGuru::fetchDataKomponenNilai($auth_data, $id_kelas_mp);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_komponen_mp
                    );
                    return $data;
                })
                ->make(true);
    }

    public function addKomponenNilai(Request $request, $id_pengguna, $id_semester, $id_kelas_mp){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_komponen_mp = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('akademik/aktivitas-semester/input-nilai/view-add-komponen-nilai',compact('auth_data','data_kelas','id_komponen_mp','id_pengguna','id_semester','id_kelas_mp'));

    }

    public function editKomponenNilai(Request $request, $id_pengguna, $id_semester, $id_kelas_mp, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);

        $data_komponen_mp = LibGuru::fetchDataKomponenNilai($auth_data, $id_kelas_mp, $id);

        return view('akademik/aktivitas-semester/input-nilai/view-edit-komponen-nilai',compact('auth_data','data_kelas','data_komponen_mp','id_pengguna','id_semester','id_kelas_mp'));

    }

    public function actionKomponenNilai(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_komponen_mp' => 'required',
            'persentase_komponen_mp' => 'required',
            'urutan_komponen_mp' => 'required',
            // dari type hidden
            'id_kelas_mp' => 'required'
        ]);

        if($validator->fails() && $mode != 'delete' && $mode != 'input-nilai' && $mode != 'tampil-nilai') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $pengambilan_mp = PengambilanMp::with('nilai_mp')->where('id_kelas_mp', $input->id_kelas_mp)->first();

            if(!empty($pengambilan_mp)){
                if($check_nilai_mp = $pengambilan_mp->nilai_mp->first()){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Komponen Nilai (Nilai mata pelajaran sudah diinput)!'
                    ];
                }
            }

            // ACTION ADD
            if($mode == 'add') {
                $komponenMp = KomponenMp::where('id_kelas_mp','=',$input->id_kelas_mp)->where('urutan_komponen_mp','=',$input->urutan_komponen_mp)->first();

                if($komponenMp){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Komponen Nilai (Urutan Sudah Ada)!'
                    ];
                }
                else{
                    $jumlah_total_persentase_komponen = KomponenMp::where('id_kelas_mp','=',$input->id_kelas_mp)->sum('persentase_komponen_mp');
                    $jumlah_total_persentase_komponen += $input->persentase_komponen_mp;

                    if($jumlah_total_persentase_komponen > 100){
                        return [
                            'status' => 300, // FAILED
                            'message' => 'Failed To Save Komponen Nilai (Persentase lebih besar dari 100%)!'
                        ];
                    }

                    $komponenMp                             = new KomponenMp;
                    $komponenMp->id_komponen_mp             = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $komponenMp->id_kelas_mp                = $input->id_kelas_mp;
                    $komponenMp->nm_komponen_mp             = $input->nm_komponen_mp;
                    $komponenMp->persentase_komponen_mp     = $input->persentase_komponen_mp;
                    $komponenMp->urutan_komponen_mp         = $input->urutan_komponen_mp;
                    $komponenMp->created_by                 = $input->auth_data->pengguna->id_pengguna;
                    $komponenMp->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'aktivitas-semester/input-nilai/view-kelas/'.$input->id_pengguna.'/'.$input->id_semester.'/'.$input->id_kelas_mp,
                        'message' => 'Save Komponen Nilai successfully'
                    ];
                }
            }
            elseif($mode == 'edit') {
                $komponenMp = KomponenMp::where('id_kelas_mp','=',$input->id_kelas_mp)->where('urutan_komponen_mp','=',$input->urutan_komponen_mp)->first();

                if($komponenMp){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Komponen Nilai (Urutan Sudah Ada)!'
                    ];
                }
                else{
                    $jumlah_total_persentase_komponen = KomponenMp::where('id_kelas_mp','=',$input->id_kelas_mp)->where('id_komponen_mp', '<>', $id)->sum('persentase_komponen_mp');
                    $jumlah_total_persentase_komponen += $input->persentase_komponen_mp;

                    if($jumlah_total_persentase_komponen > 100){
                        return [
                            'status' => 300, // FAILED
                            'message' => 'Failed To Save Komponen Nilai (Persentase lebih besar dari 100%)!'
                        ];
                    }

                    // make object to find id
                    $komponenMp                             = KomponenMp::find($id);
                    $komponenMp->nm_komponen_mp             = $input->nm_komponen_mp;
                    $komponenMp->persentase_komponen_mp     = $input->persentase_komponen_mp;
                    $komponenMp->urutan_komponen_mp         = $input->urutan_komponen_mp;
                    $komponenMp->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                    $komponenMp->updated_at                 = $now;
                    $komponenMp->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'aktivitas-semester/input-nilai/view-kelas/'.$input->id_pengguna.'/'.$input->id_semester.'/'.$input->id_kelas_mp,
                        'message' => 'Update Komponen Nilai successfully'
                    ];
                }
            }
            elseif($mode == 'delete') {
                if($nilaiMp = NilaiMp::where('id_komponen_mp',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Komponen Nilai'
                    ]; 
                }
                else {
                    // make object to find id
                    $komponenMp               = KomponenMp::find($id);
                    $komponenMp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $komponenMp->save();

                    $komponenMp->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Komponen Nilai successfully'
                    ];
                }
            }
            elseif($mode == 'tampil-nilai'){
                $pengambilanMp = PengambilanMp::find($id);
                $pengambilanMp->is_tampil = 1;
                $pengambilanMp->save();

                return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Nilai Berhasil Ditampilkan'
                    ];
            }
            elseif($mode == 'input-nilai'){
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
                        $nilai_akhir_final['nilai_angka'.$siswa->id_siswa][$data->id_komponen_mp]['raw']= $input->$nameInput;
                        $nilai_akhir_final['nilai_angka'.$siswa->id_siswa][$data->id_komponen_mp]['partial']= $nilaiCount;
                    }

                    $namePengambilan = 'id_pengambilan_mp_'.$siswa->id_siswa;
                    $idSiswa = substr($namePengambilan, 18);
                    if($idSiswa == $siswa->id_siswa){

                        $pengambilanMp = PengambilanMp::where('id_kelas_mp','=',$input->id_kelas_mp)
                            ->where('id_siswa','=',$idSiswa)
                            ->first();
                        if($pengambilanMp){
                            if($pengambilanMp->nilai_angka == null){
                                $nilai_angka = 0;
                                foreach ($nilai_akhir_final['nilai_angka'.$idSiswa] as $key => $item) {
                                    $nameInput                              = 'nilai'.$key.'-'.$idSiswa;                    
                                    $nilaiMp                                = new NilaiMp;
                                    $nilaiMp->id_nilai_mp                   = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                                    $nilaiMp->id_pengambilan_mp             = $siswa->id_pengambilan_mp;
                                    $nilaiMp->id_komponen_mp                = $key;
                                    if($input->$nameInput == null){
                                        $nilaiMp->besar_nilai_mp    = 0;
                                    }else{
                                        $nilaiMp->besar_nilai_mp            = $item['raw'];
                                        $nilai_angka                        = $nilai_angka + $item['partial'];
                                    }
                                    $nilaiMp->created_by                    = $input->auth_data->pengguna->id_pengguna;
                                    $nilaiMp->created_at                    = $now;
                                    $nilaiMp->save();
                                }
                                $nilai_huruf = PeraturanNilai::join('standar_nilai','standar_nilai.id_standar_nilai','=','peraturan_nilai.id_standar_nilai')
                                    ->where('peraturan_nilai.is_mata_pelajaran','=','1')
                                    ->where('peraturan_nilai.nilai_min_peraturan_nilai','<=',round($nilai_angka))
                                    ->where('peraturan_nilai.nilai_max_peraturan_nilai','>=',round($nilai_angka))
                                    ->first();
                                if($nilai_huruf){
                                    $nilai_huruf = $nilai_huruf['nm_standar_nilai'];
                                }else{
                                    $nilai_huruf = "-";
                                }
                                $nilai_pengambilanMp                        = PengambilanMp::find($siswa->id_pengambilan_mp);
                                $nilai_pengambilanMp->nilai_angka           = $nilai_angka;
                                $nilai_pengambilanMp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                                $nilai_pengambilanMp->updated_at            = $now;
                                $nilai_pengambilanMp->nilai_huruf           = $nilai_huruf;
                                $nilai_pengambilanMp->save();
                            }
                            else{
                                $nilai_angka = 0;
                                foreach ($nilai_akhir_final['nilai_angka'.$idSiswa] as $key => $item){
                                    $nameInput                              = 'nilai'.$key.'-'.$idSiswa;                    
                                    $nilaiMp                                = NilaiMp::where('id_pengambilan_mp','=',$pengambilanMp->id_pengambilan_mp)->where('id_komponen_mp','=',$key)->first();
                                    if($input->$nameInput == null){
                                        $nilaiMp->besar_nilai_mp    = 0;
                                    }else{
                                        $nilaiMp->besar_nilai_mp            = $item['raw'];
                                        $nilai_angka                        = $nilai_angka + $item['partial'];
                                    }
                                    $nilaiMp->updated_by                    = $input->auth_data->pengguna->id_pengguna;
                                    $nilaiMp->updated_at                    = $now;
                                    $nilaiMp->save();
                                }
                                $nilai_huruf = PeraturanNilai::join('standar_nilai','standar_nilai.id_standar_nilai','=','peraturan_nilai.id_standar_nilai')
                                        ->where('peraturan_nilai.is_mata_pelajaran','=','1')
                                        ->where('peraturan_nilai.nilai_min_peraturan_nilai','<=',round($nilai_angka))
                                        ->where('peraturan_nilai.nilai_max_peraturan_nilai','>=',round($nilai_angka))
                                        ->first();
                                if($nilai_huruf){
                                    $nilai_huruf = $nilai_huruf['nm_standar_nilai'];
                                }else{
                                    $nilai_huruf = "-";
                                }
                                $nilai_pengambilanMp                        = PengambilanMp::find($siswa->id_pengambilan_mp);
                                $nilai_pengambilanMp->nilai_angka           = $nilai_angka;
                                $nilai_pengambilanMp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                                $nilai_pengambilanMp->updated_at            = $now;
                                $nilai_pengambilanMp->nilai_huruf           = $nilai_huruf;
                                $nilai_pengambilanMp->save();
                            }
                        }
                    }
                }

                return redirect()->back();
            }
        }
    }
}
