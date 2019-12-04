<?php

namespace App\Http\Controllers\Pendidikan\SettingKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kelas as Kelas;
use App\Models\Siswa as Siswa;
use App\Models\Semester as Semester;
use App\Models\SekretarisKelas as SekretarisKelas;
use App\Models\RuanganKelas as RuanganKelas;
use App\Models\WaliKelas as WaliKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class KelasController extends BaseController{

    public function viewKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/setting-kelas/kelas/view-kelas',compact('auth_data'));

    }

    public function addKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = LibDataAkademik::fetchDataJurusan($auth_data);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_kelas = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/setting-kelas/kelas/add-kelas',compact('auth_data','data_jurusan','id_kelas'));

    }

    public function editKelas($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = LibDataAkademik::fetchDataJurusan($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id);

        return view('pendidikan/setting-kelas/kelas/edit-kelas',compact('auth_data','data_jurusan','data_kelas'));

    }

    public function copyKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester   = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $now = (int) $semester->thn_akademik_semester + 1;

        $tahun_sebelum = (int) $semester->thn_akademik_semester - 2;

        $data_semester = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                            ->whereBetween('thn_akademik_semester', [$tahun_sebelum, $now])
                            ->orderBy('thn_akademik_semester', 'asc')
                            ->orderBy('nm_semester', 'asc')
                            ->get();

        return view('pendidikan/setting-kelas/kelas/copy-kelas',compact('auth_data','data_semester'));

    }

    public function datatablesKelas(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibKelas::fetchDataKelas($auth_data);

        return Datatables::of($list_data)
                ->addColumn('nm_sekretaris', function($item){
                    if(empty($item->nm_sekretaris)){
                        $data = array(
                            'nm_sekretaris' => 0,
                            'id' => $item->id_kelas
                        );
                    }
                    else{
                        $data = array(
                            'nm_sekretaris' => $item->nm_sekretaris
                        );
                    }
                    return $data;
                })
                ->addColumn('nm_ruangan', function($item){
                    if(empty($item->nm_ruangan)){
                        $data = array(
                            'nm_ruangan' => 0,
                            'id' => $item->id_kelas
                        );
                    }
                    else{
                        $data = array(
                            'nm_ruangan' => $item->nm_ruangan
                        );
                    }
                    return $data;
                })
                ->addColumn('nm_wali_kelas', function($item){
                    if(empty($item->nm_wali_kelas)){
                        $data = array(
                            'nm_wali_kelas' => 0,
                            'id' => $item->id_kelas
                        );
                    }
                    else{
                        $data = array(
                            'nm_wali_kelas' => $item->nm_wali_kelas
                        );
                    }
                    return $data;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kelas
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionKelas(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jurusan' => 'required',
            'nm_kelas' => 'required',
            'tingkat' => 'required',
            'keterangan_kelas' => 'required'
        ]);

        if($validator->fails() && $mode != 'delete' && $mode != 'copy') {
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

                    $kelas                     = new Kelas;
                    $kelas->id_kelas           = $id;
                    $kelas->id_jurusan         = $input->id_jurusan;
                    $kelas->nm_kelas           = $input->nm_kelas;
                    $kelas->tingkat            = $input->tingkat;
                    $kelas->keterangan_kelas   = $input->keterangan_kelas;
                    $kelas->created_by         = $input->auth_data->pengguna->id_pengguna;
                    $kelas->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'setting-kelas/kelas',
                        'message' => 'Save Kelas successfully'
                    ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $kelas                          = Kelas::find($id);
                $kelas->id_jurusan              = $input->id_jurusan;
                $kelas->nm_kelas                = $input->nm_kelas;
                $kelas->tingkat                 = $input->tingkat;
                $kelas->keterangan_kelas        = $input->keterangan_kelas;
                $kelas->updated_by              = $input->auth_data->pengguna->id_pengguna;
                $kelas->updated_at              = $now;
                $kelas->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'setting-kelas/kelas',
                    'message' => 'Update Kelas successfully'
                ];
            }
            elseif($mode == 'copy') {
                DB::beginTransaction();

                try {
                    if(! empty($input->sekretaris) && $input->sekretaris == 1) {
                        // proses tabel sekretaris_kelas
                        $sekretaris_kelas_set = SekretarisKelas::where('id_semester','=',$input->id_semester_copy)->get();

                        foreach($sekretaris_kelas_set as $sekretaris){
                            $id_sekretaris_kelas        = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $id_kelas                   = $sekretaris->id_kelas;
                            $id_siswa                   = $sekretaris->id_siswa;
                            $id_semester                = $input->id_semester_paste;

                            if(! empty($input->is_aktif_sekretaris) && $input->is_aktif_sekretaris == 11) {
                                $is_aktif   = 1;

                                // proses update is_aktif menjadi 0 All Record
                                $sekretaris_set                 = SekretarisKelas::join('semester', 'semester.id_semester', '=', 'sekretaris_kelas.id_semester')
                                                                ->where('sekretaris_kelas.id_kelas','=',$id_kelas)
                                                                ->where('sekretaris_kelas.is_aktif','=',1)
                                                                ->first();

                                $sekretaris_set->is_aktif       = 0;
                                $sekretaris_set->updated_by     = $input->auth_data->pengguna->id_pengguna;
                                $sekretaris_set->updated_at     = $now;
                                $sekretaris_set->save();

                                SekretarisKelas::insert(array(
                                    'id_sekretaris_kelas'       => $id_sekretaris_kelas,
                                    'id_kelas'                  => $id_kelas,
                                    'id_siswa'                  => $id_siswa,
                                    'id_semester'               => $id_semester,
                                    'is_aktif'                  => $is_aktif,
                                    'created_by'                => $input->auth_data->pengguna->id_pengguna,
                                    'created_at'                => $now
                                ));
                            } 
                            else {
                                $is_aktif = 0;

                                SekretarisKelas::insert(array(
                                    'id_sekretaris_kelas'       => $id_sekretaris_kelas,
                                    'id_kelas'                  => $id_kelas,
                                    'id_siswa'                  => $id_siswa,
                                    'id_semester'               => $id_semester,
                                    'is_aktif'                  => $is_aktif,
                                    'created_by'                => $input->auth_data->pengguna->id_pengguna,
                                    'created_at'                => $now
                                ));
                            }
                        }
                    }

                    if(! empty($input->ruangan) && $input->ruangan == 2) {
                        // proses tabel ruangan_kelas
                        $ruangan_kelas_set = RuanganKelas::where('id_semester','=',$input->id_semester_copy)->get();

                        foreach($ruangan_kelas_set as $ruangan){
                            $id_ruangan_kelas           = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $id_kelas                   = $ruangan->id_kelas;
                            $id_ruangan                 = $ruangan->id_ruangan;
                            $id_semester                = $input->id_semester_paste;

                            if(! empty($input->is_aktif_ruangan) && $input->is_aktif_ruangan == 22) {
                                $is_aktif   = 1;

                                // proses update is_aktif menjadi 0 All Record
                                $ruangan_set                 = RuanganKelas::join('semester', 'semester.id_semester', '=', 'ruangan_kelas.id_semester')
                                                                ->where('ruangan_kelas.id_kelas','=',$id_kelas)
                                                                ->where('ruangan_kelas.is_aktif','=',1)
                                                                ->first();

                                $ruangan_set->is_aktif       = 0;
                                $ruangan_set->updated_by     = $input->auth_data->pengguna->id_pengguna;
                                $ruangan_set->updated_at     = $now;
                                $ruangan_set->save();

                                RuanganKelas::insert(array(
                                    'id_ruangan_kelas'          => $id_ruangan_kelas,
                                    'id_kelas'                  => $id_kelas,
                                    'id_ruangan'                => $id_ruangan,
                                    'id_semester'               => $id_semester,
                                    'is_aktif'                  => $is_aktif,
                                    'created_by'                => $input->auth_data->pengguna->id_pengguna,
                                    'created_at'                => $now
                                ));
                            } 
                            else {
                                $is_aktif = 0;

                                RuanganKelas::insert(array(
                                    'id_ruangan_kelas'          => $id_ruangan_kelas,
                                    'id_kelas'                  => $id_kelas,
                                    'id_ruangan'                => $id_ruangan,
                                    'id_semester'               => $id_semester,
                                    'is_aktif'                  => $is_aktif,
                                    'created_by'                => $input->auth_data->pengguna->id_pengguna,
                                    'created_at'                => $now
                                ));
                            }
                        }
                    }

                    if(! empty($input->wali_kelas) && $input->wali_kelas == 3) {
                        // proses tabel wali_kelas
                        $wali_kelas_set = WaliKelas::where('id_semester','=',$input->id_semester_copy)->get();

                        foreach($wali_kelas_set as $wali_kelas){
                            $id_wali_kelas          = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $id_kelas               = $wali_kelas->id_kelas;
                            $id_guru                = $wali_kelas->id_guru;
                            $id_semester            = $input->id_semester_paste;

                            if(! empty($input->is_aktif_wali_kelas) && $input->is_aktif_wali_kelas == 33) {
                                $is_aktif   = 1;

                                // proses update is_aktif menjadi 0 All Record
                                $wali_kelas_set                 = WaliKelas::join('semester', 'semester.id_semester', '=', 'wali_kelas.id_semester')
                                                                ->where('wali_kelas.id_kelas','=',$id_kelas)
                                                                ->where('wali_kelas.is_aktif','=',1)
                                                                ->first();

                                $wali_kelas_set->is_aktif       = 0;
                                $wali_kelas_set->updated_by     = $input->auth_data->pengguna->id_pengguna;
                                $wali_kelas_set->updated_at     = $now;
                                $wali_kelas_set->save();

                                WaliKelas::insert(array(
                                    'id_wali_kelas'             => $id_wali_kelas,
                                    'id_kelas'                  => $id_kelas,
                                    'id_guru'                   => $id_guru,
                                    'id_semester'               => $id_semester,
                                    'is_aktif'                  => $is_aktif,
                                    'created_by'                => $input->auth_data->pengguna->id_pengguna,
                                    'created_at'                => $now
                                ));
                            } 
                            else {
                                $is_aktif = 0;

                                WaliKelas::insert(array(
                                    'id_wali_kelas'          => $id_wali_kelas,
                                    'id_kelas'               => $id_kelas,
                                    'id_guru'                => $id_guru,
                                    'id_semester'            => $id_semester,
                                    'is_aktif'               => $is_aktif,
                                    'created_by'             => $input->auth_data->pengguna->id_pengguna,
                                    'created_at'             => $now
                                ));
                            }
                        }
                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'setting-kelas/kelas',
                        'message' => 'Copy Kelas successfully'
                    ];

                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                                'status' => 300, // GAGAL
                                'message' => 'Copy Kelas Gagal! '.$e->getMessage()
                            ];
                }                
            }
            elseif($mode == 'delete'){
                if($siswa = Siswa::where('id_kelas',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Kelas'
                    ]; 
                }
                else{
                    // make object to find id
                    $kelas               = Kelas::find($id);
                    $kelas->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $kelas->save();

                    $kelas->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Kelas successfully'
                    ];
                }
            }
        }
    }

}