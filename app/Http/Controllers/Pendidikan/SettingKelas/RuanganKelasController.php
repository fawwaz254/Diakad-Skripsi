<?php

namespace App\Http\Controllers\Pendidikan\SettingKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\RuanganKelas as RuanganKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;

class RuanganKelasController extends BaseController
{

    public function viewRuanganKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('pendidikan/setting-kelas/ruangan-kelas/view-ruangan-kelas', compact('auth_data', 'data_kelas'));
    }

    public function actionViewRuanganKelas(Request $request)
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
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'setting-kelas/ruangan-kelas/view-kelas/' . $input->id_kelas
            ];
        }
    }

    public function viewKelasRuanganKelas(Request $request, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        return view('pendidikan/setting-kelas/ruangan-kelas/view-kelas-ruangan-kelas', compact('auth_data', 'data_kelas'));
    }

    public function addRuanganKelas(Request $request, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        // ambil ruangan jenis kelas
        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data, 1);

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_ruangan_kelas = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('pendidikan/setting-kelas/ruangan-kelas/add-ruangan-kelas', compact('auth_data', 'data_kelas', 'data_semester', 'data_ruangan', 'id_ruangan_kelas'));
    }

    public function editRuanganKelas(Request $request, $id_kelas, $id_semester, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        // ambil ruangan jenis kelas
        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data, 1);

        $data_ruangan_kelas = LibKelas::fetchDataRuanganKelas($auth_data, $id_kelas, $id_semester, $id);

        return view('pendidikan/setting-kelas/ruangan-kelas/edit-ruangan-kelas', compact('auth_data', 'data_kelas', 'data_semester', 'data_ruangan', 'data_ruangan_kelas'));
    }

    public function datatablesRuanganKelas(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibKelas::fetchDataRuanganKelas($auth_data, $id_kelas);

        return Datatables::of($list_data)
            ->addColumn('semester', function ($item) {
                return $item->tahun_ajaran . " " . $item->nm_semester;
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
                    'id' => $item->id_ruangan_kelas,
                    'id_kelas' => $item->id_kelas,
                    'id_semester' => $item->id_semester
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionRuanganKelas(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'id_semester' => 'required',
            'id_ruangan' => 'required',
            'is_aktif' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            // ACTION ADD
            if ($mode == 'add') {
                // cek apabila ada record kelas dan semester yg sama
                $ruanganKelas = RuanganKelas::join('semester', 'semester.id_semester', '=', 'ruangan_kelas.id_semester')
                    ->where('ruangan_kelas.id_kelas', '=', $input->id_kelas)
                    ->where('ruangan_kelas.id_semester', '=', $input->id_semester)
                    ->where('semester.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                    ->first();

                if ($ruanganKelas) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Ruangan Kelas!'
                    ];
                } else {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    $ruanganKelas                       = new RuanganKelas;
                    $ruanganKelas->id_ruangan_kelas     = $id;
                    $ruanganKelas->id_kelas             = $input->id_kelas;
                    $ruanganKelas->id_semester          = $input->id_semester;
                    $ruanganKelas->id_ruangan           = $input->id_ruangan;
                    $ruanganKelas->is_aktif             = $input->is_aktif;
                    $ruanganKelas->created_by           = $input->auth_data->pengguna->id_pengguna;
                    $ruanganKelas->save();

                    // cek jika update status aktif = 1, maka yg lain status aktif = 0
                    if ($input->is_aktif == 1) {
                        $data_ruangan_kelas  = RuanganKelas::where('id_kelas', $input->id_kelas)->where('id_ruangan_kelas', "<>", $id)->get();

                        foreach ($data_ruangan_kelas as $ruanganKelas) {
                            $ruanganKelas->is_aktif    = 0;
                            $ruanganKelas->updated_by  = $input->auth_data->pengguna->id_pengguna;
                            $ruanganKelas->updated_at  = $now;
                            $ruanganKelas->save();
                        }
                    }

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'setting-kelas/ruangan-kelas/view-kelas/' . $input->id_kelas,
                        'message' => 'Save Ruangan Kelas Successfully'
                    ];
                }
            } elseif ($mode == 'edit') {
                // make object to find id
                $ruanganKelas                   = RuanganKelas::find($id);
                $ruanganKelas->id_kelas         = $input->id_kelas;
                $ruanganKelas->id_semester      = $input->id_semester;
                $ruanganKelas->id_ruangan       = $input->id_ruangan;
                $ruanganKelas->is_aktif         = $input->is_aktif;
                $ruanganKelas->updated_by       = $input->auth_data->pengguna->id_pengguna;
                $ruanganKelas->updated_at       = $now;
                $ruanganKelas->save();

                // cek jika update status aktif = 1, maka yg lain status aktif = 0
                if ($input->is_aktif == 1) {
                    $data_ruangan_kelas  = RuanganKelas::where('id_kelas', $input->id_kelas)->where('id_ruangan_kelas', "<>", $id)->get();

                    foreach ($data_ruangan_kelas as $ruanganKelas) {
                        $ruanganKelas->is_aktif    = 0;
                        $ruanganKelas->updated_by  = $input->auth_data->pengguna->id_pengguna;
                        $ruanganKelas->updated_at  = $now;
                        $ruanganKelas->save();
                    }
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'setting-kelas/ruangan-kelas/view-kelas/' . $input->id_kelas,
                    'message' => 'Update Ruangan Kelas Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $ruanganKelas               = RuanganKelas::find($id);
                $ruanganKelas->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $ruanganKelas->save();

                $ruanganKelas->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Ruangan Kelas Successfully'
                ];
            }
        }
    }
}
