<?php

namespace App\Http\Controllers\Pendidikan\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PeraturanNilai as PeraturanNilai;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class RentangNilaiMutuController extends BaseController{

    public function viewRentangNilaiMutu(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/data-akademik/rentang-nilai-mutu/view-rentang-nilai-mutu',compact('auth_data'));

    }

    public function addRentangNilaiMutu(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_nilai_mutu = LibDataAkademik::fetchDataNilaiMutu($auth_data);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_peraturan_nilai = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/data-akademik/rentang-nilai-mutu/add-rentang-nilai-mutu',compact('auth_data','data_nilai_mutu','id_peraturan_nilai'));

    }

    public function editRentangNilaiMutu($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_nilai_mutu = LibDataAkademik::fetchDataNilaiMutu($auth_data);

        $data_rentang_nilai_mutu = $this->fetchDataRentangNilaiMutu($auth_data, $id);

        return view('pendidikan/data-akademik/rentang-nilai-mutu/edit-rentang-nilai-mutu',compact('auth_data','data_nilai_mutu','data_rentang_nilai_mutu'));

    }

    public function datatablesRentangNilaiMutu(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = $this->fetchDataRentangNilaiMutu($auth_data);

        return Datatables::of($list_data)
                ->addColumn('status', function($item){
                    if($item->is_mata_pelajaran == 0){
                        return "Umum (Ekskul, Magang, dll)";
                    }
                    else{
                        return "Mapel";
                    }
                })
                ->addColumn('nilai_kkm', function($item){
                    if(! empty($item->nilai_kkm)){
                        return $item->nilai_kkm;
                    }
                    else{
                        return "-";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_peraturan_nilai
                    );
                    return $data;
                })
                ->make(true);
    }

    public function fetchDataRentangNilaiMutu($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $rentangNilaiMutu = PeraturanNilai::select('peraturan_nilai.id_peraturan_nilai', 'standar_nilai.nm_standar_nilai', 'standar_nilai.mutu_standar_nilai', 'standar_nilai.keterangan_standar_nilai', 'peraturan_nilai.nilai_kkm','peraturan_nilai.nilai_min_peraturan_nilai', 'peraturan_nilai.nilai_max_peraturan_nilai', 'peraturan_nilai.is_mata_pelajaran')
                    ->join('standar_nilai','standar_nilai.id_standar_nilai','=','peraturan_nilai.id_standar_nilai')
                    ->where('standar_nilai.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('peraturan_nilai.is_mata_pelajaran', 'asc')
                    ->orderBy('peraturan_nilai.nilai_kkm', 'asc')
                    ->orderBy('standar_nilai.nm_standar_nilai', 'asc')
                    ->get();
        }
        // get mode edit
        else{
            $rentangNilaiMutu = PeraturanNilai::join('standar_nilai','standar_nilai.id_standar_nilai','=','peraturan_nilai.id_standar_nilai')->where('peraturan_nilai.id_peraturan_nilai','=',$id)->first();
        }

        return $rentangNilaiMutu;
    }


    // Action POST
    public function actionRentangNilaiMutu(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'is_mata_pelajaran' => 'required',
            //'nilai_kkm' => 'required',
            'id_standar_nilai' => 'required',
            'nilai_min_peraturan_nilai' => 'required',
            'nilai_max_peraturan_nilai' => 'required'
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

            // ACTION ADD
            if($mode == 'add') {
                if($input->is_mata_pelajaran == 0) {
                    $rentangNilaiMutu = PeraturanNilai::join('standar_nilai','standar_nilai.id_standar_nilai','=','peraturan_nilai.id_standar_nilai')
                        ->where('peraturan_nilai.id_standar_nilai','=',$input->id_standar_nilai)
                        ->where('standar_nilai.id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                        ->first();
                }
                else {
                    $rentangNilaiMutu = PeraturanNilai::join('standar_nilai','standar_nilai.id_standar_nilai','=','peraturan_nilai.id_standar_nilai')
                        ->where('peraturan_nilai.id_standar_nilai','=',$input->id_standar_nilai)
                        ->where('peraturan_nilai.nilai_kkm','=',$input->nilai_kkm)
                        ->where('standar_nilai.id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                        ->first();
                }

                if($rentangNilaiMutu){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Rentang Nilai Mutu!'
                    ];
                }
                else{
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $peraturanNilai                             = new PeraturanNilai;
                    $peraturanNilai->id_peraturan_nilai         = $id;
                    $peraturanNilai->is_mata_pelajaran          = $input->is_mata_pelajaran;
                    $peraturanNilai->nilai_kkm                  = $input->nilai_kkm;
                    $peraturanNilai->id_standar_nilai           = $input->id_standar_nilai;
                    $peraturanNilai->nilai_min_peraturan_nilai  = $input->nilai_min_peraturan_nilai;
                    $peraturanNilai->nilai_max_peraturan_nilai  = $input->nilai_max_peraturan_nilai;
                    $peraturanNilai->created_by                 = $input->auth_data->pengguna->id_pengguna;
                    $peraturanNilai->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'data-akademik/rentang-nilai-mutu',
                        'message' => 'Save Rentang Nilai Mutu Successfully'
                    ];
                }
            }
            elseif($mode == 'edit'){
                // make object to find id
                $peraturanNilai                             = PeraturanNilai::find($id);
                $peraturanNilai->is_mata_pelajaran          = $input->is_mata_pelajaran;
                $peraturanNilai->nilai_kkm                  = $input->nilai_kkm;
                $peraturanNilai->id_standar_nilai           = $input->id_standar_nilai;
                $peraturanNilai->nilai_min_peraturan_nilai  = $input->nilai_min_peraturan_nilai;
                $peraturanNilai->nilai_max_peraturan_nilai  = $input->nilai_max_peraturan_nilai;
                $peraturanNilai->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                $peraturanNilai->updated_at                 = $now;
                $peraturanNilai->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/rentang-nilai-mutu',
                    'message' => 'Update Rentang Nilai Mutu Successfully'
                ];
            }
            elseif($mode == 'delete'){
                // make object to find id
                $peraturanNilai               = PeraturanNilai::find($id);
                $peraturanNilai->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $peraturanNilai->save();

                $peraturanNilai->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Rentang Nilai Mutu Successfully'
                ];
            }
        }
    }

}