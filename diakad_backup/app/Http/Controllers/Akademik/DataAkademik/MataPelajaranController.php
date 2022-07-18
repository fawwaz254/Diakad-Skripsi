<?php

namespace App\Http\Controllers\Akademik\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\MataPelajaran as MataPelajaran;
use App\Models\JenisMataPelajaran as JenisMataPelajaran;
use App\Models\KurikulumMp as KurikulumMp;
use App\Models\KelasMp as KelasMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;

use Auth;
use DB;
use Session;
use Validator;

class MataPelajaranController extends BaseController{

    public function viewMataPelajaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/data-akademik/mata-pelajaran/view-mata-pelajaran',compact('auth_data'));

    }

    public function addMataPelajaran(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = LibDataAkademik::fetchDataJurusan($auth_data);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $jenis_mapel = JenisMataPelajaran::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

        $id_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('akademik/data-akademik/mata-pelajaran/add-mata-pelajaran',compact('auth_data','data_jurusan','id_mata_pelajaran','jenis_mapel'));

    }

    public function editMataPelajaran($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = LibDataAkademik::fetchDataJurusan($auth_data);
        $jenis_mapel = JenisMataPelajaran::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

        $data_mata_pelajaran = LibAkademik::fetchDataMataPelajaran($auth_data, $id);

        return view('akademik/data-akademik/mata-pelajaran/edit-mata-pelajaran',compact('auth_data','data_jurusan','data_mata_pelajaran','jenis_mapel'));


    }

    public function datatablesMataPelajaran(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibAkademik::fetchDataMataPelajaran($auth_data, null, "1");

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_mata_pelajaran
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionMataPelajaran(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jurusan'            => 'required',
            'kd_mata_pelajaran'     => 'required',
            'nm_mata_pelajaran'     => 'required',
            'id_jenis_mata_pelajaran'   => 'required',
            // 'nm_mata_pelajaran_en'  => 'required',
            //'nm_mata_pelajaran_en'  => 'required',
            // 'kredit_semester'       => 'required',
            // 'kredit_tatap_muka'     => 'required',
            // 'kredit_praktikum'      => 'required',
            // 'kredit_tutor'          => 'required',
            // 'kredit_prak_lapangan'  => 'required',
            // 'kredit_simulasi'       => 'required',
            // 'tingkat_semester'      => 'required',
            // 'nilai_kkm'             => 'required'
            // 'ada_sap'               => 'required',
            // 'ada_silabus'           => 'required',
            // 'ada_bahan_ajar'        => 'required',
            // 'ada_diktat'            => 'required'
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
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                
                $mataPelajaran                          = new MataPelajaran;
                $mataPelajaran->id_mata_pelajaran       = $id;
                if($input->id_jurusan==1) $mataPelajaran->id_jurusan = null;
                else $mataPelajaran->id_jurusan = $input->id_jurusan;
                $mataPelajaran->kd_mata_pelajaran       = $input->kd_mata_pelajaran;
                $mataPelajaran->id_jenis_mata_pelajaran = $input->id_jenis_mata_pelajaran;
                $mataPelajaran->nm_mata_pelajaran       = $input->nm_mata_pelajaran;
                if(isset($input->nm_mata_pelajaran_en)){
                    $mataPelajaran->nm_mata_pelajaran_en    = $input->nm_mata_pelajaran_en;
                }
                // $mataPelajaran->kredit_semester         = $input->kredit_semester;
                // $mataPelajaran->kredit_tatap_muka       = $input->kredit_tatap_muka;
                // $mataPelajaran->kredit_praktikum        = $input->kredit_praktikum;
                // $mataPelajaran->kredit_tutor            = $input->kredit_tutor;
                // $mataPelajaran->kredit_prak_lapangan    = $input->kredit_prak_lapangan;
                // $mataPelajaran->kredit_simulasi         = $input->kredit_simulasi;
                // $mataPelajaran->tingkat_semester        = $input->tingkat_semester;
                // $mataPelajaran->nilai_kkm               = $input->nilai_kkm;
                // $mataPelajaran->ada_sap                 = $input->ada_sap;
                // $mataPelajaran->ada_silabus             = $input->ada_silabus;
                // $mataPelajaran->ada_bahan_ajar          = $input->ada_bahan_ajar;
                // $mataPelajaran->ada_diktat              = $input->ada_diktat;
                $mataPelajaran->created_by              = $input->auth_data->pengguna->id_pengguna;
                $mataPelajaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/mata-pelajaran',
                    'message' => 'Save Mata Pelajaran successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $mataPelajaran                          = MataPelajaran::find($id);
                if($input->id_jurusan==1) $mataPelajaran->id_jurusan = null;
                else $mataPelajaran->id_jurusan = $input->id_jurusan;
                $mataPelajaran->kd_mata_pelajaran       = $input->kd_mata_pelajaran;
                $mataPelajaran->id_jenis_mata_pelajaran = $input->id_jenis_mata_pelajaran;
                $mataPelajaran->nm_mata_pelajaran       = $input->nm_mata_pelajaran;
                if(isset($input->nm_mata_pelajaran_en)){
                    $mataPelajaran->nm_mata_pelajaran_en    = $input->nm_mata_pelajaran_en;
                }
                // $mataPelajaran->kredit_semester         = $input->kredit_semester;
                // $mataPelajaran->kredit_tatap_muka       = $input->kredit_tatap_muka;
                // $mataPelajaran->kredit_praktikum        = $input->kredit_praktikum;
                // $mataPelajaran->kredit_tutor            = $input->kredit_tutor;
                // $mataPelajaran->kredit_prak_lapangan    = $input->kredit_prak_lapangan;
                // $mataPelajaran->kredit_simulasi         = $input->kredit_simulasi;
                // $mataPelajaran->tingkat_semester        = $input->tingkat_semester;
                // $mataPelajaran->nilai_kkm               = $input->nilai_kkm;
                // $mataPelajaran->ada_sap                 = $input->ada_sap;
                // $mataPelajaran->ada_silabus             = $input->ada_silabus;
                // $mataPelajaran->ada_bahan_ajar          = $input->ada_bahan_ajar;
                // $mataPelajaran->ada_diktat              = $input->ada_diktat;
                $mataPelajaran->updated_by              = $input->auth_data->pengguna->id_pengguna;
                $mataPelajaran->updated_at              = $now;
                $mataPelajaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/mata-pelajaran',
                    'message' => 'Update Mata Pelajaran successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($kurikulumMp = KurikulumMp::where('id_mata_pelajaran',$id)->first() or $kelasMp = KelasMp::where('id_mata_pelajaran',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Mata Pelajaran'
                    ]; 
                }
                else{
                    // make object to find id
                    $mataPelajaran               = MataPelajaran::find($id);
                    $mataPelajaran->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $mataPelajaran->save();

                    $mataPelajaran->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Mata Pelajaran successfully'
                    ];
                }
            }
        }
    }


}
