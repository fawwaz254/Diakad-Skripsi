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

use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class KomponenNilaiController extends BaseController{

    public function viewKomponenNilai(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get all data kelas_mp by id_pengguna guru
        $data_kelas = LibGuru::fetchDataKelasGuru($auth_data, $auth_data->pengguna->id_pengguna);

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

            if($pengambilan_mp = PengambilanMp::with('nilai_mp')->where('id_kelas_mp', $input->id_kelas_mp)->first()){
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
        }
    }

}