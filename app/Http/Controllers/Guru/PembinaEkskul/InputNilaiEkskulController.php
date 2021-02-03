<?php

namespace App\Http\Controllers\Guru\PembinaEkskul;

use App\Libraries\Pendidikan\LibDataAkademik;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;
use Carbon\Carbon;

use App\Libraries\SumberDaya\LibGuru;
use App\Models\Ekskul;
use App\Models\KomponenEkskul;
use App\Models\NilaiEkskul;
use App\Models\PengambilanEkskul;
use App\Models\PeraturanNilai;
use App\Models\Semester;
use Auth;
use DB;
use Illuminate\Support\Facades\Validator;
use Session;

class InputNilaiEkskulController extends BaseController{

    public function viewInputNilaiEkskul(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $semester_aktif = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                    ->where('is_aktif_semester','=','1')
                                    ->first();

        // get all data ekskul by id_pengguna guru
        $data_ekskul = LibGuru::fetchDataEkskulGuru($auth_data, $auth_data->pengguna->id_pengguna);
        
    	return view('guru/pembina-ekskul/input-nilai-ekskul/view-input-nilai-ekskul', compact('auth_data','data_ekskul', 'data_semester', 'semester_aktif'));
    }

    public function postViewInputNilaiEkskul(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_ekskul' => 'required',
            'id_semester' => 'required'
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
                'path' => 'pembina-ekskul/input-nilai-ekskul/detail/'.$input->id_semester.'/'.$input->id_ekskul
            ];   
        }
    }

    public function viewDetailInputNilaiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_ekskul_guru = LibGuru::fetchDataEkskulGuru($auth_data, $auth_data->pengguna->id_pengguna);
        $arr_id_ekskul = implode(',', $data_ekskul_guru->pluck('id_ekskul')->toArray());

        $data_validate = [
            'id_ekskul' => $id_ekskul,
            'id_semester' => $id_semester
        ];

        $validator = Validator::make($data_validate, [
            'id_ekskul' => 'required|exists:ekskul|in:' . $arr_id_ekskul,
            'id_semester' => 'required|exists:semester'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => 'Parameter yang dimasukkan tidak valid'
            ];
        }

        $data_ekskul = Ekskul::find($id_ekskul);
        $semester = Semester::find($id_semester);

        $list_komponen = LibGuru::fetchDataKomponenNilaiEkskul($auth_data, $id_semester, $id_ekskul);

        $jumlah_persentase_komponen = $list_komponen->sum('persentase_komponen_ekskul');

        $list_siswa = PengambilanEkskul::select('siswa.nis_siswa',
                                                'pengguna.nm_pengguna',
                                                'pengambilan_ekskul.id_pengambilan_ekskul',
                                                'pengambilan_ekskul.nilai_angka',
                                                'pengambilan_ekskul.nilai_huruf',
                                                'siswa.id_siswa',
                                                'kelas.nm_kelas'
                                                )
                        ->join('siswa','siswa.id_siswa','=','pengambilan_ekskul.id_siswa')
                        ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                        ->join('kelas', 'kelas.id_kelas', '=', 'pengambilan_ekskul.id_kelas')
                        ->where('pengambilan_ekskul.id_ekskul','=',$id_ekskul)
                        ->where('pengambilan_ekskul.id_semester','=',$id_semester)
                        ->get();
        
        $list_siswa = $list_siswa->map(function($row) use ($list_komponen){
            $list_nilai_ekskul = []; 
            foreach($list_komponen as $komponen){
                $nilai = NilaiEkskul::where('id_komponen_ekskul', $komponen->id_komponen_ekskul)
                                    ->where('id_pengambilan_ekskul', $row->id_pengambilan_ekskul)
                                    ->first();
                $list_nilai_ekskul[] = [
                    'id_komponen_ekskul' => $komponen->id_komponen_ekskul,
                    'nilai_komponen_ekskul' => !empty($nilai) ? $nilai->besar_nilai_ekskul : 0
                ];
            }
            $data = $row;
            $data->nilai_ekskul = $list_nilai_ekskul;
            return $data;
        });

        return view('guru/pembina-ekskul/input-nilai-ekskul/view-detail-input-nilai-ekskul',compact('auth_data','data_ekskul', 'semester', 'list_komponen', 'jumlah_persentase_komponen', 'list_siswa'));
    }

    public function saveInputNilaiEkskul(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->only('id_ekskul', 'id_semester'), [
            // dari type hidden
            'id_ekskul' => 'required',
            'id_semester' => 'required'
        ]);
        if($validator->fails()){
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ]; 
        }

        $data_validation = $request->except(['_token', 'id_ekskul', 'id_semester', 'primary_table_length', 'auth_data']); 
        $rule = [];
        foreach($data_validation as $keydv => $dv){
            $rule[$keydv] = 'numeric';
        }
        
        $validator = Validator::make($data_validation, $rule);

        if($validator->fails()){
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ]; 
        }
        
        $list_komponen = KomponenEkskul::where('id_ekskul','=', $input->id_ekskul)
                                        ->where('id_semester', $input->id_semester)
                                        ->get();

        $list_siswa = PengambilanEkskul::select('siswa.nis_siswa',
                                        'pengguna.nm_pengguna',
                                        'pengambilan_ekskul.id_pengambilan_ekskul',
                                        'pengambilan_ekskul.nilai_angka',
                                        'pengambilan_ekskul.nilai_huruf',
                                        'siswa.id_siswa',
                                        'kelas.nm_kelas'
                                        )
                ->join('siswa','siswa.id_siswa','=','pengambilan_ekskul.id_siswa')
                ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                ->join('kelas', 'kelas.id_kelas', '=', 'pengambilan_ekskul.id_kelas')
                ->where('pengambilan_ekskul.id_ekskul','=',$input->id_ekskul)
                ->where('pengambilan_ekskul.id_semester','=',$input->id_semester)
                ->get();

        $nilai_akhir_final = [];
        $nilai_per_komponen = [];

        $keys = collect($data_validation)->keys()->map(function($m){
            $pos = strpos($m, '-');
            $str = substr($m, $pos+1);
            return $str;
        })->toArray();

        foreach($list_siswa as $dataSiswa => $siswa){ //tiap siswa
            // check if input exist in $siswa
            if(in_array($siswa->id_siswa,$keys)){
                foreach($list_komponen as $dataKomponen => $komponen){ // tiap komponen
                    $nameInput = 'nilai'.$komponen->id_komponen_ekskul.'-'.$siswa->id_siswa;                    
                    $nilaiCount = ($input->$nameInput*($komponen->persentase_komponen_ekskul/100));
                    $nilai_akhir_final['nilai_angka'.$siswa->id_siswa][$komponen->id_komponen_ekskul]['raw']=$input->$nameInput;
                    $nilai_akhir_final['nilai_angka'.$siswa->id_siswa][$komponen->id_komponen_ekskul]['partial']=$nilaiCount;
                }

                $pengambilanEkskul = PengambilanEkskul::where('id_ekskul','=',$input->id_ekskul)
                                                ->where('id_semester','=',$input->id_semester)
                                                ->where('id_siswa','=',$siswa->id_siswa)
                                                ->first();

                // if siswa has pengambilanEkskul
                if($pengambilanEkskul){
                    $nilai_angka = 0;
                    foreach ($nilai_akhir_final['nilai_angka'.$siswa->id_siswa] as $id_komponen => $item) {
                        // save to nilai_mp
                        $is_new_komponen = 0;                
                        $nilaiEkskul    = NilaiEkskul::where('id_pengambilan_ekskul','=',$pengambilanEkskul->id_pengambilan_ekskul)
                                                ->where('id_komponen_ekskul','=',$id_komponen)
                                                ->first();

                        $nilaiKomponen      = $item['raw'];
                        $nilai_angka        = $nilai_angka + $item['partial'];

                        if(empty($nilaiEkskul)){ // if empty, then create new record
                            $nilaiEkskul                        = new NilaiEkskul;
                            $nilaiEkskul->id_nilai_ekskul       = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                            $is_new_komponen = 1;
                        }

                        $nilaiEkskul->id_pengambilan_ekskul     = $siswa->id_pengambilan_ekskul;
                        $nilaiEkskul->id_komponen_ekskul        = $id_komponen;
                        $nilaiEkskul->besar_nilai_ekskul        = !empty($nilaiKomponen) ? $nilaiKomponen : 0;
                        if($is_new_komponen == 1){
                            $nilaiEkskul->created_by        = $input->auth_data->pengguna->id_pengguna;
                            $nilaiEkskul->created_at        = $now;
                        } else {
                            $nilaiEkskul->updated_by        = $input->auth_data->pengguna->id_pengguna;
                            $nilaiEkskul->updated_at        = $now;
                        }
                        $nilaiEkskul->save();
                    }

                    // save to pengambilan MP as final result (result for rapor)
                    $nilai_huruf = PeraturanNilai::join('standar_nilai','standar_nilai.id_standar_nilai','=','peraturan_nilai.id_standar_nilai')
                        ->where('peraturan_nilai.is_mata_pelajaran','=','0')
                        ->where('peraturan_nilai.nilai_min_peraturan_nilai','<=',round($nilai_angka))
                        ->where('peraturan_nilai.nilai_max_peraturan_nilai','>=',round($nilai_angka))
                        ->first();
                    if($nilai_huruf){
                        $nilai_huruf = $nilai_huruf['nm_standar_nilai'];
                    }else{
                        $nilai_huruf = "-";
                    }
                    $nilaiPengambilanEkskul                        = PengambilanEkskul::find($siswa->id_pengambilan_ekskul);
                    $nilaiPengambilanEkskul->nilai_angka           = $nilai_angka;
                    $nilaiPengambilanEkskul->updated_by            = $input->auth_data->pengguna->id_pengguna;
                    $nilaiPengambilanEkskul->updated_at            = $now;
                    $nilaiPengambilanEkskul->nilai_huruf           = $nilai_huruf;
                    $nilaiPengambilanEkskul->save();
                }
            }
        }
        
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'pembina-ekskul/input-nilai-ekskul/detail/'.$input->id_semester.'/'.$input->id_ekskul,
            'message' => 'Save Nilai Ekskul successfully'
        ];
        
    }

}