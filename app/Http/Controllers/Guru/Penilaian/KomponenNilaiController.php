<?php

namespace App\Http\Controllers\Guru\Penilaian;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\KomponenMp as KomponenMp;
use App\Models\NilaiMp as NilaiMp;
use App\Models\PengambilanMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\NilaiMpSubKomponen;
use App\Models\PeraturanNilai;
use App\Models\SubKomponenMp;
use Auth;
use DB;
use Session;
use Validator;

class KomponenNilaiController extends BaseController{

    public function viewKomponenNilai(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        // get all data kelas_mp by id_pengguna guru
        $data_kelas = LibGuru::fetchDataKelasGuru($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        /** groupping by tahun_ajaran and nm_semester */
        $grup_semester_kelas = $data_kelas->groupBy('tahun_ajaran')->transform(function($item, $k) {
            return $item->groupBy('nm_semester');
        });
        
    	return view('guru/penilaian/komponen-nilai/view-komponen-nilai',compact('auth_data','data_kelas','grup_semester_kelas'));

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
                        'path' => 'penilaian/komponen-nilai/view-kelas/'.$input->id_kelas_mp
                    ];   
        }
    }

    public function viewKelasKomponenNilai(Request $request, $id_kelas_mp){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);

        return view('guru/penilaian/komponen-nilai/view-kelas-komponen-nilai',compact('auth_data','data_kelas'));

    }

    public function viewKelasSubKomponenNilai(Request $request, $id_kelas_mp, $id_komponen_mp){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);
        $data_komponen = KomponenMp::find($id_komponen_mp);

        return view('guru/penilaian/komponen-nilai/view-subkomponen-nilai',compact('auth_data','data_kelas','id_kelas_mp', 'id_komponen_mp', 'data_komponen'));
    }

    public function addKomponenNilai(Request $request, $id_kelas_mp){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_komponen_mp = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('guru/penilaian/komponen-nilai/add-komponen-nilai',compact('auth_data','data_kelas','id_komponen_mp'));
    }

    public function editKomponenNilai(Request $request, $id_kelas_mp, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);

        $data_komponen_mp = LibGuru::fetchDataKomponenNilai($auth_data, $id_kelas_mp, $id);

        return view('guru/penilaian/komponen-nilai/edit-komponen-nilai',compact('auth_data','data_kelas','data_komponen_mp'));
    }
    
    public function addSubKomponenNilai(Request $request, $id_kelas_mp, $id_komponen_mp){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_komponen = KomponenMp::find($id_komponen_mp);
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_subkomponen_mp = null;

        return view('guru/penilaian/komponen-nilai/view-detail-subkomponen-nilai',compact('auth_data','data_komponen','id_subkomponen_mp', 'id_kelas_mp', 'id_komponen_mp'));
    }

    public function editSubKomponenNilai(Request $request, $id_kelas_mp, $id_komponen_mp, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_komponen = KomponenMp::find($id_komponen_mp);
        $data_subkomponen_mp = SubKomponenMp::find($id);

        $id_subkomponen_mp = $data_subkomponen_mp->id_subkomponen_mp;

        return view('guru/penilaian/komponen-nilai/view-detail-subkomponen-nilai',compact('auth_data','data_komponen','id_subkomponen_mp', 'data_subkomponen_mp', 'id_kelas_mp', 'id_komponen_mp'));
    }

    public function datatablesKomponenNilai(Request $request, $id_kelas_mp){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibGuru::fetchDataKomponenNilai($auth_data, $id_kelas_mp);

        return Datatables::of($list_data)
                ->addColumn('jumlah_sub_komponen_mp', function($item){
                    return count($item->sub_komponen_mp);
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_komponen_mp
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesSubKomponenNilai(Request $request, $id_komponen_mp){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibGuru::fetchDataSubKomponenNilai($auth_data, $id_komponen_mp);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_subkomponen_mp
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionKomponenNilai(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_komponen_mp' => 'required',
            'persentase_komponen_mp' => 'required',
            'urutan_komponen_mp' => 'required',
            // dari type hidden
            'id_kelas_mp' => 'required'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if($mode != 'delete'){
                if($pengambilan_mp = PengambilanMp::with('nilai_mp')->where('id_kelas_mp', $input->id_kelas_mp)->first()){
                    if($check_nilai_mp = $pengambilan_mp->nilai_mp->first()){
                        return [
                            'status' => 300, // FAILED
                            'message' => 'Failed To Save Komponen Nilai (Nilai mata pelajaran sudah diinput)!'
                        ];
                    }
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

                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    
                    $komponenMp                             = new KomponenMp;
                    $komponenMp->id_komponen_mp             = $id;
                    $komponenMp->id_kelas_mp                = $input->id_kelas_mp;
                    $komponenMp->nm_komponen_mp             = $input->nm_komponen_mp;
                    $komponenMp->persentase_komponen_mp     = $input->persentase_komponen_mp;
                    $komponenMp->urutan_komponen_mp         = $input->urutan_komponen_mp;
                    $komponenMp->created_by                 = $input->auth_data->pengguna->id_pengguna;
                    $komponenMp->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'penilaian/komponen-nilai/view-kelas/'.$input->id_kelas_mp,
                        'message' => 'Save Komponen Nilai successfully'
                    ];
                }
            }

            elseif($mode == 'edit') {
                $komponenMp = KomponenMp::where('id_kelas_mp','=',$input->id_kelas_mp)->where('urutan_komponen_mp','=',$input->urutan_komponen_mp)->where('id_komponen_mp', '<>', $id)->first();

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
                        'path' => 'penilaian/komponen-nilai/view-kelas/'.$input->id_kelas_mp,
                        'message' => 'Update Komponen Nilai successfully'
                    ];
                }
            }

            elseif($mode == 'delete') {
                if($nilaiMp = NilaiMp::where('id_komponen_mp',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Komponen Nilai Karena Sudah ada Nilai yang Dimasukkan'
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
        }
    }

    public function actionSubKomponenNilai(Request $request, $mode, $id = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'nm_subkomponen_mp' => 'required',
            'kd_subkomponen_mp' => 'required',
            // dari type hidden
            'id_komponen_mp' => 'required',
            'id_kelas_mp' => 'required'
        ]);

        if($validator->fails() && $mode != 'delete' && $mode != 'input-nilai') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{ // DO ACTION
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if($mode == 'add'){
                $nmSubKomponenMp = SubKomponenMp::where('id_komponen_mp','=',$input->id_komponen_mp)->where('nm_subkomponen_mp','=',$input->nm_subkomponen_mp)->first();
                $kdSubKomponenMp = SubKomponenMp::where('id_komponen_mp','=',$input->id_komponen_mp)->where('kd_subkomponen_mp','=',$input->kd_subkomponen_mp)->first();

                if($nmSubKomponenMp || $kdSubKomponenMp){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Komponen Nilai (KD / Nama Sub Komponen Sudah Ada)!'
                    ];
                }
                else{
                    $subKomponenMp                          = new SubKomponenMp;
                    $subKomponenMp->id_subkomponen_mp       = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $subKomponenMp->id_komponen_mp          = $input->id_komponen_mp;
                    $subKomponenMp->kd_subkomponen_mp       = $input->kd_subkomponen_mp;
                    $subKomponenMp->nm_subkomponen_mp       = $input->nm_subkomponen_mp;
                    $subKomponenMp->created_by              = $input->auth_data->pengguna->id_pengguna;
                    $subKomponenMp->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'penilaian/komponen-nilai/view-sub-komponen/'.$input->id_kelas_mp.'/'.$input->id_komponen_mp,
                        'message' => 'Save Sub Komponen Nilai successfully'
                    ];
                }
            }
            elseif($mode == 'edit'){
                $validator = Validator::make(['id_subkomponen_mp' => $id], [
                    'id_subkomponen_mp' => 'required|exists:subkomponen_mp'
                ]);
                if($validator->fails()){
                    return [
                        'status' => 300, // FAILED
                        'message' => $validator->errors()->first()
                    ];
                }

                $nmSubKomponenMp = SubKomponenMp::where('id_komponen_mp','=',$input->id_komponen_mp)
                                                ->where('nm_subkomponen_mp','=',$input->nm_subkomponen_mp)
                                                ->where('id_subkomponen_mp', '!=', $input->id_subkomponen_mp)
                                                ->first();
                $kdSubKomponenMp = SubKomponenMp::where('id_komponen_mp','=',$input->id_komponen_mp)
                                                ->where('kd_subkomponen_mp','=',$input->kd_subkomponen_mp)
                                                ->where('id_subkomponen_mp', '!=', $input->id_subkomponen_mp)
                                                ->first();

                if($nmSubKomponenMp || $kdSubKomponenMp){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Sub Komponen Nilai (KD / Nama Sub Komponen Sudah Ada)!'
                    ];
                }

                $subKomponenMp                          = SubKomponenMp::find($id);
                $subKomponenMp->id_komponen_mp          = $input->id_komponen_mp;
                $subKomponenMp->kd_subkomponen_mp       = $input->kd_subkomponen_mp;
                $subKomponenMp->nm_subkomponen_mp       = $input->nm_subkomponen_mp;
                $subKomponenMp->updated_by              = $input->auth_data->pengguna->id_pengguna;
                $subKomponenMp->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penilaian/komponen-nilai/view-sub-komponen/'.$input->id_kelas_mp.'/'.$input->id_komponen_mp,
                    'message' => 'Save Sub Komponen Nilai successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($nilaiMp = NilaiMpSubKomponen::where('id_subkomponen_mp',$id)->first()){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Delete Sub Komponen Nilai (Sudah ada nilai yang diinput)'
                    ]; 
                } else {
                    $validator = Validator::make(['id_subkomponen_mp' => $id], [
                        'id_subkomponen_mp' => 'required|exists:subkomponen_mp'
                    ]);
                    if($validator->fails()){
                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    }
                    // make object to find id
                    $subKomponenMp               = SubKomponenMp::find($id);
                    $subKomponenMp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $subKomponenMp->save();

                    $subKomponenMp->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Sub Komponen Nilai successfully'
                    ];
                }
            }
            elseif($mode == 'input-nilai'){
                $validate_input = $input;
                $validator = Validator::make($request->only('id_kelas_mp', 'id_pengguna', 'id_semester'), [
                    // dari type hidden
                    'id_kelas_mp' => 'required',
                    'id_pengguna' => 'required',
                    'id_semester' => 'required'
                ]);
                if($validator->fails()){
                    return [
                        'status' => 300, // FAILED
                        'message' => $validator->errors()->first()
                    ]; 
                }

                $data_validation = $request->except(['_token', 'id_kelas_mp', 'id_pengguna', 'id_semester', 'primary_table_length', 'auth_data']); 
                $key = [];
                foreach($data_validation as $keydv => $dv){
                    $key[$keydv] = 'numeric';
                }
                
                $validator = Validator::make($data_validation, $key);

                if($validator->fails()){
                    return [
                        'status' => 300, // FAILED
                        'message' => $validator->errors()->first()
                    ]; 
                }
                
                $list_data = KomponenMp::where('id_kelas_mp','=',$input->id_kelas_mp)->get();
                $list_siswa = PengambilanMp::select('siswa.nis_siswa','pengguna.nm_pengguna','pengambilan_mp.nilai_angka','pengambilan_mp.nilai_huruf','siswa.id_siswa','pengambilan_mp.id_pengambilan_mp')
                    ->join('siswa','siswa.id_siswa','=','pengambilan_mp.id_siswa')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->where('pengambilan_mp.id_kelas_mp','=',$input->id_kelas_mp)->get();

                $nilai_akhir_final = array();
                $nilai_per_komponen = [];

                $keys = collect($data_validation)->keys()->map(function($m){
                    $pos = strpos($m, '-');
                    $str = substr($m, $pos+1);
                    return $str;
                })->toArray();

                foreach($list_siswa as $dataSiswa => $siswa){ //tiap siswa
                    // check if input exist in $siswa
                    if(in_array($siswa->id_siswa,$keys)){
                        foreach($list_data as $dataKomponen => $komponen){ // tiap komponen
                            $list_subkomponen = SubKomponenMp::where('id_komponen_mp', $komponen->id_komponen_mp)->get();
    
                            foreach($list_subkomponen as $data){ // tiap subkomponen
                                $nameInput = 'nilai'.$data->id_subkomponen_mp.'-'.$siswa->id_siswa;
                                if(!isset($input->$nameInput)){
                                    dd($nameInput, $siswa);
                                }
                                $nilai_akhir_final['nilai_angka'.$siswa->id_siswa][$data->id_komponen_mp][$data->id_subkomponen_mp]['raw'] = $input->$nameInput;
                            }
                            
                            $c_nilai_akhir = collect($nilai_akhir_final['nilai_angka'.$siswa->id_siswa][$data->id_komponen_mp]);
    
                            $nilai_per_komponen[$siswa->id_siswa][$komponen->id_komponen_mp]['raw'] = round($c_nilai_akhir->sum('raw')/$c_nilai_akhir->count(), 2);
                            $nilai_per_komponen[$siswa->id_siswa][$komponen->id_komponen_mp]['persentase'] = $komponen->persentase_komponen_mp;
                        }
    
                        $namePengambilan = 'id_pengambilan_mp_'.$siswa->id_siswa;
                        $idSiswa = substr($namePengambilan, 18);
                        // if siswa found
                        if($idSiswa == $siswa->id_siswa){
                            $pengambilanMp = PengambilanMp::where('id_kelas_mp','=',$input->id_kelas_mp)
                                ->where('id_siswa','=',$idSiswa)
                                ->first();
    
                            // if siswa has pengambilanMp
                            if($pengambilanMp){
                                $nilai_angka = 0;
                                foreach ($nilai_akhir_final['nilai_angka'.$idSiswa] as $komponen => $item) {
                                    // save to nilai_mp_sub_komponen
                                    foreach($item as $subKomponen => $nilai){
                                        $is_new_subkomponen = 0;
                                        $nameInput  = 'nilai'.$subKomponen.'-'.$idSiswa;
    
                                        $nilaiMpSub = NilaiMpSubKomponen::where('id_pengambilan_mp', $siswa->id_pengambilan_mp)
                                                                        ->where('id_subkomponen_mp', $subKomponen)
                                                                        ->first();
    
                                        if(empty($nilaiMpSub)){ // if empty, then create new record
                                            $is_new_subkomponen = 1;
                                            $nilaiMpSub                            = new NilaiMpSubKomponen;
                                            $nilaiMpSub->id_nilai_mp_subkomponen   = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                                        }
    
                                        $nilaiMpSub->id_pengambilan_mp         = $siswa->id_pengambilan_mp;
                                        $nilaiMpSub->id_subkomponen_mp         = $subKomponen;
                                        if($input->$nameInput == null){
                                            $nilaiMpSub->besar_nilai_mp        = 0;
                                        }else{
                                            $nilaiMpSub->besar_nilai_mp        = $nilai['raw'];
                                        }
                                        if($is_new_subkomponen == 1){
                                            $nilaiMpSub->created_by            = $input->auth_data->pengguna->id_pengguna;
                                            $nilaiMpSub->created_at            = $now;
                                        } else {
                                            $nilaiMpSub->updated_by            = $input->auth_data->pengguna->id_pengguna;
                                            $nilaiMpSub->updated_at            = $now;
                                        }
                                        $nilaiMpSub->save();
                                    }
    
                                    // save to nilai_mp
                                    $is_new_komponen = 0;                
                                    $nilaiMp    = NilaiMp::where('id_pengambilan_mp','=',$pengambilanMp->id_pengambilan_mp)
                                                            ->where('id_komponen_mp','=',$komponen)
                                                            ->first();
    
                                    $nilaiKomponen      = $nilai_per_komponen[$siswa->id_siswa][$komponen]['raw'];
                                    $nilai_angka        = $nilai_angka + (round($nilaiKomponen * ($nilai_per_komponen[$siswa->id_siswa][$komponen]['persentase']/100),2));
    
                                    if(empty($nilaiMp)){ // if empty, then create new record
                                        $nilaiMp                = new NilaiMp;
                                        $nilaiMp->id_nilai_mp   = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                                        $is_new_komponen = 1;
                                    }
    
                                    $nilaiMp->id_pengambilan_mp     = $siswa->id_pengambilan_mp;
                                    $nilaiMp->id_komponen_mp        = $komponen;
                                    $nilaiMp->besar_nilai_mp        = !empty($nilaiKomponen) ? $nilaiKomponen : 0;
                                    if($is_new_komponen == 1){
                                        $nilaiMp->created_by        = $input->auth_data->pengguna->id_pengguna;
                                        $nilaiMp->created_at        = $now;
                                    } else {
                                        $nilaiMp->updated_by        = $input->auth_data->pengguna->id_pengguna;
                                        $nilaiMp->updated_at        = $now;
                                    }
                                    $nilaiMp->save();
                                }
    
                                // save to pengambilan MP as final result (result for rapor)
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
                
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penilaian/komponen-nilai/nilai-mapel/'.$input->id_kelas_mp.'/'.$input->id_pengguna.'/'.$input->id_semester,
                    'message' => 'Save Nilai successfully'
                ];
            }
        }
    }

}