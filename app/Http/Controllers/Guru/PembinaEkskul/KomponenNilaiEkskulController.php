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
use App\Models\Semester;
use Auth;
use DB;
use Illuminate\Support\Facades\Validator;
use Session;

class KomponenNilaiEkskulController extends BaseController{

    public function viewKomponenNilaiEkskul(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $semester_aktif = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                    ->where('is_aktif_semester','=','1')
                                    ->first();

        // get all data ekskul by id_pengguna guru
        $data_ekskul = LibGuru::fetchDataEkskulGuru($auth_data, $auth_data->pengguna->id_pengguna);
        
    	return view('guru/pembina-ekskul/komponen-nilai-ekskul/view-komponen-nilai-ekskul', compact('auth_data','data_ekskul', 'data_semester', 'semester_aktif'));
    }

    public function postViewKomponenNilaiEkskul(Request $request)
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
                'path' => 'pembina-ekskul/komponen-nilai-ekskul/list/'.$input->id_semester.'/'.$input->id_ekskul
            ];   
        }
    }

    public function viewListKomponenNilaiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_ekskul = Ekskul::find($id_ekskul);
        $semester = Semester::find($id_semester);

        return view('guru/pembina-ekskul/komponen-nilai-ekskul/view-list-komponen-nilai-ekskul',compact('auth_data','data_ekskul', 'semester'));
    }

    public function datatablesKomponenNilaiEkskul(Request $request, $id_semester, $id_ekskul){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibGuru::fetchDataKomponenNilaiEkskul($auth_data, $id_semester, $id_ekskul);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_komponen_ekskul
                    );
                    return $data;
                })
                ->make(true);
    }

    public function addKomponenNilaiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $komponen_ekskul = null;
        $id_komponen_ekskul = null;
        $data_ekskul = Ekskul::find($id_ekskul);
        $semester = Semester::find($id_semester);

        return view('guru/pembina-ekskul/komponen-nilai-ekskul/view-detail-komponen-nilai-ekskul', compact('auth_data','data_ekskul', 'semester', 'komponen_ekskul', 'id_komponen_ekskul'));
    }
    
    public function editKomponenNilaiEkskul(Request $request, $id_semester, $id_ekskul, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $komponen_ekskul = KomponenEkskul::find($id);
        $id_komponen_ekskul = $id;
        $data_ekskul = Ekskul::find($id_ekskul);
        $semester = Semester::find($id_semester);

        return view('guru/pembina-ekskul/komponen-nilai-ekskul/view-detail-komponen-nilai-ekskul', compact('auth_data','data_ekskul', 'semester', 'komponen_ekskul', 'id_komponen_ekskul'));
    }

    public function actionKomponenNilaiEkskul(Request $request, $mode, $id = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'nm_komponen_ekskul' => 'required',
            'persentase_komponen_ekskul' => 'required',
            'urutan_komponen_ekskul' => 'required',
            // dari type hidden
            'id_ekskul' => 'required|exists:ekskul,id_ekskul',
            'id_semester' => 'required|exists:semester,id_semester'
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

            $pengambilan_ekskul = null;
            if($mode != 'delete'){
                $pengambilan_ekskul = PengambilanEkskul::with('nilai_ekskul')
                                                        ->where('id_ekskul', $input->id_ekskul)
                                                        ->where('id_semester', $input->id_semester)
                                                        ->first();
            }

            // if($pengambilan_ekskul){
            //     if($check_nilai_mp = $pengambilan_ekskul->nilai_ekskul->first()){
            //         return [
            //             'status' => 300, // FAILED
            //             'message' => 'Failed To Save Komponen Nilai Ekskul (Nilai ekskul sudah diinput)!'
            //         ];
            //     }
            // }

            // ADD action
            if($mode == 'add'){
                $komponenEkskul = KomponenEkskul::where('id_ekskul', $input->id_ekskul)
                                                    ->where('id_semester', $input->id_semester)
                                                    ->where('urutan_komponen_ekskul', $input->urutan_komponen_ekskul)
                                                    ->first();

                if($komponenEkskul){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Komponen Nilai Ekskul (Urutan Sudah Ada)!'
                    ];
                }
                else{
                    $jumlah_total_persentase_komponen = KomponenEkskul::where('id_ekskul','=',$input->id_ekskul)
                                                                        ->where('id_semester', $input->id_semester)
                                                                        ->sum('persentase_komponen_ekskul');
                    $jumlah_total_persentase_komponen += $input->persentase_komponen_ekskul;

                    if($jumlah_total_persentase_komponen > 100){
                        return [
                            'status' => 300, // FAILED
                            'message' => 'Failed To Save Komponen Nilai (Persentase lebih besar dari 100%)!'
                        ];
                    }

                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    
                    $komponenEkskul                                 = new KomponenEkskul;
                    $komponenEkskul->id_komponen_ekskul             = $id;
                    $komponenEkskul->id_ekskul                      = $input->id_ekskul;
                    $komponenEkskul->id_semester                    = $input->id_semester;
                    $komponenEkskul->nm_komponen_ekskul             = $input->nm_komponen_ekskul;
                    $komponenEkskul->persentase_komponen_ekskul     = $input->persentase_komponen_ekskul;
                    $komponenEkskul->urutan_komponen_ekskul         = $input->urutan_komponen_ekskul;
                    $komponenEkskul->created_by                     = $input->auth_data->pengguna->id_pengguna;
                    $komponenEkskul->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'pembina-ekskul/komponen-nilai-ekskul/list/'.$input->id_semester.'/'.$input->id_ekskul,
                        'message' => 'Save Komponen Nilai Ekskul Successfully'
                    ];
                }

            }
            elseif($mode == 'edit'){
                $validator = Validator::make(['id' => $id], [
                    'id' => 'required|exists:komponen_ekskul,id_komponen_ekskul'
                ]);
        
                if($validator->fails()) {
                    return [
                        'status' => 300, // FAILED
                        'message' => $validator->errors()->first()
                    ];
                }
                $urutan = KomponenEkskul::where('id_ekskul','=',$input->id_ekskul)
                                            ->where('id_semester','=', $input->id_semester)
                                            ->where('urutan_komponen_ekskul','=', $input->urutan_komponen_ekskul)
                                            ->where('id_komponen_ekskul', '<>', $id)
                                            ->first();

                if($urutan){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Komponen Nilai Ekskul (Urutan Sudah Ada)!'
                    ];
                }
                
                $jumlah_total_persentase_komponen = KomponenEkskul::where('id_ekskul','=',$input->id_ekskul)
                                                                    ->where('id_semester', '=', $input->id_semester)
                                                                    ->where('id_komponen_ekskul', '<>', $id)
                                                                    ->sum('persentase_komponen_ekskul');
                $jumlah_total_persentase_komponen += $input->persentase_komponen_ekskul;

                if($jumlah_total_persentase_komponen > 100){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Komponen Nilai Ekskul (Persentase lebih besar dari 100%)!'
                    ];
                }

                $komponenEkskul                                 = KomponenEkskul::find($id);
                $komponenEkskul->nm_komponen_ekskul             = $input->nm_komponen_ekskul;
                $komponenEkskul->persentase_komponen_ekskul     = $input->persentase_komponen_ekskul;
                $komponenEkskul->urutan_komponen_ekskul         = $input->urutan_komponen_ekskul;
                $komponenEkskul->updated_by                     = $auth_data->pengguna->id_pengguna;
                $komponenEkskul->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pembina-ekskul/komponen-nilai-ekskul/list/'.$input->id_semester.'/'.$input->id_ekskul,
                    'message' => 'Save Komponen Nilai Ekskul Successfully'
                ];
            }
            elseif($mode == 'delete'){
                $validator = Validator::make(['id' => $id], [
                    'id' => 'required|exists:komponen_ekskul,id_komponen_ekskul'
                ]);
        
                if($validator->fails()) {
                    return [
                        'status' => 300, // FAILED
                        'message' => $validator->errors()->first()
                    ];
                }

                if($nilaiEkskul = NilaiEkskul::where('id_komponen_ekskul',$id)->first()){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Delete Komponen Nilai Ekskul (Nilai sudah diinput)'
                    ]; 
                }

                $komponen_ekskul = KomponenEkskul::find($id);
                $komponen_ekskul->deleted_by = $auth_data->pengguna->id_pengguna;
                $komponen_ekskul->save();
                $komponen_ekskul->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Komponen Nilai Ekskul Successfully'
                ];
            }
        }
    }

}