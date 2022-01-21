<?php

namespace App\Http\Controllers\Pendidikan\SettingKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Pengguna;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\BkKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class BkKelasController extends BaseController
{
    public function viewBkKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('pendidikan/setting-kelas/bk-kelas/view-bk-kelas', compact('auth_data', 'data_kelas'));
    }

    public function actionViewBkKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } 

        else {
            return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'setting-kelas/bk-kelas/view-kelas/'.$input->id_kelas
                ];
        }
    }

    public function viewKelasBkKelas(Request $request, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($id_kelas);
        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        return view('pendidikan/setting-kelas/bk-kelas/view-kelas-bk-kelas', compact('auth_data', 'data_kelas'));
    }

    public function addBkKelas(Request $request, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        // ambil data guru melalui role sumber daya
        $data_guru_tendik = Pengguna::whereIn('status_join_table',[1,2])->where('username','!=','admin')->get();

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_bk_kelas = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/setting-kelas/bk-kelas/add-bk-kelas', compact('auth_data', 'data_kelas', 'data_semester', 'data_guru_tendik', 'id_bk_kelas'));
    }

    public function editBkKelas(Request $request, $id_kelas, $id_semester, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $data_guru_tendik = Pengguna::whereIn('status_join_table',[1,2])->where('username','!=','admin')->get();

        $data_bk_kelas = LibGuru::fetchDataBkKelas($auth_data, $id_kelas, $id);

        return view('pendidikan/setting-kelas/bk-kelas/edit-bk-kelas', compact('auth_data', 'data_kelas', 'data_semester', 'data_guru_tendik', 'data_bk_kelas'));
    }

    public function datatablesBkKelas(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibGuru::fetchDataBkKelas($auth_data, $id_kelas);

        return Datatables::of($list_data)
                ->editColumn('nm_bk_kelas', function ($item) {
                    return $item->gelar_depan.' '.$item->nm_bk_kelas.' '.$item->gelar_belakang;
                    // return $item->guru->pengguna->fullname();
                })
                ->addColumn('semester', function ($item) {
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('status_aktif', function ($item) {
                    if ($item->is_aktif == 0) {
                        return "Non-Aktif";
                    } else {
                        return "Aktif";
                    }
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id'            => $item->id_bk_kelas,
                        'id_kelas'      => $item->id_kelas,
                        'id_semester'   => $item->id_semester
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionBkKelas(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'id_semester' => 'required',
            'id_pengguna' => 'required',
            'is_aktif' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if ($mode == 'add') {
               
                

                $kelas = Kelas::find($input->id_kelas);
                $semester = Semester::find($input->id_semester);
                $pengguna = Pengguna::find($input->id_pengguna);

              //  cek apabila ada record kelas dan semester yg sama
                $bkKelas = BkKelas::join('semester', 'semester.id_semester', '=', 'bk_kelas.id_semester')
                               
                                ->where('bk_kelas.id_kelas', '=', $input->id_kelas)
                                ->where('bk_kelas.id_semester', '=', $input->id_semester)
                                ->where('semester.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                                ->first();
           

                if ($bkKelas) {
                 
                


                    return [
                        'status' => 300, // FAILED
                        'message' => 'Mohon maaf kelas '.$kelas->nm_kelas.' pada semester '.$semester->tahun_ajaran.' sudah memiliki bk kelas yaitu '.$bkKelas->pengguna->nm_pengguna
                    ];
                 
                
                } 

                else {

                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $bkKelas                       = new BkKelas();
                    $bkKelas->id_bk_kelas          = $id;
                    $bkKelas->id_kelas             = $input->id_kelas;
                    $bkKelas->id_semester          = $input->id_semester;
                    
                    $bkKelas->id_pengguna          = $input->id_pengguna;
                    $bkKelas->is_aktif             = $input->is_aktif;
                    $bkKelas->created_by           = $input->auth_data->pengguna->id_pengguna;
                    $bkKelas->save();

                    // cek jika update status aktif = 1, maka yg lain status aktif = 0
                    if ($input->is_aktif == 1) {

                        $data_bk_kelas  = BkKelas::where('id_bk_kelas', "<>", $id)->where('id_kelas', $input->id_kelas)->get();

                        foreach ($data_bk_kelas as $bkKelas) {
                            $bkKelas->is_aktif    = 0;
                            $bkKelas->updated_by  = $input->auth_data->pengguna->id_pengguna;
                            $bkKelas->updated_at  = $now;
                            $bkKelas->save();
                        }
                    }

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'setting-kelas/bk-kelas/view-kelas/'.$input->id_kelas,
                        'message' => 'Save BK Kelas successfully'
                    ];
                }
            } 

            elseif ($mode == 'edit') {
                // make object to find id
                $bkKelas                   = BkKelas::find($id);
                $bkKelas->id_kelas         = $input->id_kelas;
                $bkKelas->id_semester      = $input->id_semester;
                $bkKelas->id_pengguna      = $input->id_pengguna;
                $bkKelas->is_aktif         = $input->is_aktif;
                $bkKelas->updated_by       = $input->auth_data->pengguna->id_pengguna;
                $bkKelas->updated_at       = $now;
                $bkKelas->save();

                // cek jika update status aktif = 1, maka yg lain status aktif = 0
                if ($input->is_aktif == 1) {

                    $data_bk_kelas  = BkKelas::where('id_bk_kelas', "<>", $id)->where('id_kelas', $input->id_kelas)->get();

                    foreach ($data_bk_kelas as $bkKelas) {
                        $bkKelas->is_aktif    = 0;
                        $bkKelas->updated_by  = $input->auth_data->pengguna->id_pengguna;
                        $bkKelas->updated_at  = $now;
                        $bkKelas->save();
                    }
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'setting-kelas/bk-kelas/view-kelas/'.$input->id_kelas,
                    'message' => 'Update BK Kelas successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $bkKelas               = BkKelas::find($id);
                $bkKelas->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $bkKelas->save();

                $bkKelas->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete BK Kelas successfully'
                ];
            }
        }
    }
}
