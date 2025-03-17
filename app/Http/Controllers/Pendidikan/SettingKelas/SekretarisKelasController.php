<?php

namespace App\Http\Controllers\Pendidikan\SettingKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\SekretarisKelas as SekretarisKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class SekretarisKelasController extends BaseController
{

    public function viewSekretarisKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('pendidikan/setting-kelas/sekretaris-kelas/view-sekretaris-kelas', compact('auth_data', 'data_kelas'));
    }

    public function actionViewSekretarisKelas(Request $request)
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
                'path' => 'setting-kelas/sekretaris-kelas/view-kelas/' . $input->id_kelas
            ];
        }
    }

    public function viewKelasSekretarisKelas(Request $request, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        return view('pendidikan/setting-kelas/sekretaris-kelas/view-kelas-sekretaris-kelas', compact('auth_data', 'data_kelas'));
    }

    public function addSekretarisKelas(Request $request, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        // ambil data siswa by kelas
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $id_kelas);

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_sekretaris_kelas = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('pendidikan/setting-kelas/sekretaris-kelas/add-sekretaris-kelas', compact('auth_data', 'data_kelas', 'data_semester', 'data_siswa', 'id_sekretaris_kelas'));
    }

    public function editSekretarisKelas(Request $request, $id_kelas, $id_semester, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        // ambil data siswa by kelas
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $id_kelas);

        $data_sekretaris_kelas = $this->fetchDataSekretarisKelas($auth_data, $id_kelas, $id);

        return view('pendidikan/setting-kelas/sekretaris-kelas/edit-sekretaris-kelas', compact('auth_data', 'data_kelas', 'data_semester', 'data_siswa', 'data_sekretaris_kelas'));
    }

    public function datatablesSekretarisKelas(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = $this->fetchDataSekretarisKelas($auth_data, $id_kelas);

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
                    'id' => $item->id_sekretaris_kelas,
                    'id_kelas' => $item->id_kelas,
                    'id_semester' => $item->id_semester
                );
                return $data;
            })
            ->make(true);
    }

    public function fetchDataSekretarisKelas($auth_data, $id_kelas, $id = null)
    {

        // get mode view
        if ($id == null) {
            $sekretarisKelas = SekretarisKelas::select('sekretaris_kelas.id_sekretaris_kelas', 'sekretaris_kelas.id_kelas', 'sekretaris_kelas.id_semester', 'kelas.nm_kelas', 'pengguna.nm_pengguna as nm_sekretaris', 'semester.tahun_ajaran', 'semester.nm_semester', 'sekretaris_kelas.is_aktif')
                ->join('kelas', 'kelas.id_kelas', '=', 'sekretaris_kelas.id_kelas')
                ->join('siswa', 'siswa.id_siswa', '=', 'sekretaris_kelas.id_siswa')
                ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                ->join('semester', 'semester.id_semester', '=', 'sekretaris_kelas.id_semester')
                ->where('sekretaris_kelas.id_kelas', '=', $id_kelas)
                ->orderBy('semester.thn_akademik_semester', 'asc')
                ->orderBy('semester.nm_semester', 'asc')
                ->get();
        }
        // get mode edit
        else {
            $sekretarisKelas = SekretarisKelas::where('sekretaris_kelas.id_sekretaris_kelas', '=', $id)->first();
        }

        return $sekretarisKelas;
    }


    // Action POST
    public function actionSekretarisKelas(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'id_semester' => 'required',
            'id_siswa' => 'required',
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
                $sekretarisKelas = SekretarisKelas::join('semester', 'semester.id_semester', '=', 'sekretaris_kelas.id_semester')
                    ->where('sekretaris_kelas.id_kelas', '=', $input->id_kelas)
                    ->where('sekretaris_kelas.id_semester', '=', $input->id_semester)
                    ->where('semester.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                    ->first();

                if ($sekretarisKelas) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Sekretaris Kelas!'
                    ];
                } else {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    $sekretarisKelas                       = new SekretarisKelas;
                    $sekretarisKelas->id_sekretaris_kelas  = $id;
                    $sekretarisKelas->id_kelas             = $input->id_kelas;
                    $sekretarisKelas->id_semester          = $input->id_semester;
                    $sekretarisKelas->id_siswa             = $input->id_siswa;
                    $sekretarisKelas->is_aktif             = $input->is_aktif;
                    $sekretarisKelas->created_by           = $input->auth_data->pengguna->id_pengguna;
                    $sekretarisKelas->save();

                    // cek jika update status aktif = 1, maka yg lain status aktif = 0
                    if ($input->is_aktif == 1) {
                        $data_sekretaris_kelas  = SekretarisKelas::where('id_sekretaris_kelas', "<>", $id)->where('id_kelas', $input->id_kelas)->get();

                        foreach ($data_sekretaris_kelas as $sekretarisKelas) {
                            $sekretarisKelas->is_aktif    = 0;
                            $sekretarisKelas->updated_by  = $input->auth_data->pengguna->id_pengguna;
                            $sekretarisKelas->updated_at  = $now;
                            $sekretarisKelas->save();
                        }
                    }

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'setting-kelas/sekretaris-kelas/view-kelas/' . $input->id_kelas,
                        'message' => 'Save Sekretaris Kelas Successfully'
                    ];
                }
            } elseif ($mode == 'edit') {
                // make object to find id
                $sekretarisKelas                   = SekretarisKelas::find($id);
                $sekretarisKelas->id_kelas         = $input->id_kelas;
                $sekretarisKelas->id_semester      = $input->id_semester;
                $sekretarisKelas->id_siswa         = $input->id_siswa;
                $sekretarisKelas->is_aktif         = $input->is_aktif;
                $sekretarisKelas->updated_by       = $input->auth_data->pengguna->id_pengguna;
                $sekretarisKelas->updated_at       = $now;
                $sekretarisKelas->save();

                // cek jika update status aktif = 1, maka yg lain status aktif = 0
                if ($input->is_aktif == 1) {
                    $data_sekretaris_kelas  = SekretarisKelas::where('id_sekretaris_kelas', "<>", $id)->where('id_kelas', $input->id_kelas)->get();

                    foreach ($data_sekretaris_kelas as $sekretarisKelas) {
                        $sekretarisKelas->is_aktif    = 0;
                        $sekretarisKelas->updated_by  = $input->auth_data->pengguna->id_pengguna;
                        $sekretarisKelas->updated_at  = $now;
                        $sekretarisKelas->save();
                    }
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'setting-kelas/sekretaris-kelas/view-kelas/' . $input->id_kelas,
                    'message' => 'Update Sekretaris Kelas Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $sekretarisKelas               = SekretarisKelas::find($id);
                $sekretarisKelas->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $sekretarisKelas->save();

                $sekretarisKelas->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Sekretaris Kelas Successfully'
                ];
            }
        }
    }
}
