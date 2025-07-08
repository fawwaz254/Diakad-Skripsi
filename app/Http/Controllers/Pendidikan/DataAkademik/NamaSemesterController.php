<?php

namespace App\Http\Controllers\Pendidikan\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Semester as Semester;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class NamaSemesterController extends BaseController
{
    //Note
    // Kode semester menganut tahun dan semester(Ganjil/Genap)
    // Contoh: Tahun akademik semester 2025 dan tahun ajaran 2025/2026
    // Semester Ganjil: 20251
    // Semester Genap: 20262 (Karena sudah masuk tahun akademik baru)
    public function viewNamaSemester(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $now = Carbon::now();
        $this_month = $now->month;
        $this_year = $now->year;
        $next_year = $this_year + 1;
        
        $active_semester = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('is_aktif_semester', '=', 1)
            ->first();

        $availableTahunAjaranBaru =  Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where(function ($query) use ($this_year, $next_year) {
                $query->where('tahun_ajaran', '=', $this_year . '/' . $next_year);
            })
            ->first();
        
        $timeToChangeTahunAjaran =  ($this_month >= 6 && $this_month <= 8) && ($active_semester->tahun_ajaran == $this_year -1 . '/' . $this_year) && ($active_semester->nm_semester == 'Genap') && ($active_semester->thn_akademik_semester == $this_year);

        return view('pendidikan/data-akademik/nama-semester/view-nama-semester', compact('auth_data', 'active_semester', 'availableTahunAjaranBaru', 'timeToChangeTahunAjaran'));
    }

    public function addNamaSemester(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_semester = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('pendidikan/data-akademik/nama-semester/add-nama-semester', compact('auth_data', 'id_semester'));
    }

    public function editNamaSemester($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id);

        return view('pendidikan/data-akademik/nama-semester/edit-nama-semester', compact('auth_data', 'data_semester'));
    }

    public function datatablesNamaSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $list_data = $semester = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('tahun_ajaran', 'desc')->orderBy('nm_semester', 'desc')->get();

        return Datatables::of($list_data)
            ->addColumn('status_aktif', function ($item) {
                if ($item->is_aktif_semester == 0) {
                    return "Non-Aktif";
                } else {
                    return "Aktif";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_semester
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionNamaSemester(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'tahun_ajaran' => 'required',
            'nm_semester' => 'required',
            'thn_akademik_semester' => 'required',
            'kode_semester' => 'required',
            'is_aktif_semester' => 'required'
        ]);

        if ($validator->fails() && $mode == 'add') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $semester                           = new Semester;
                $semester->id_semester              = $id;
                $semester->tahun_ajaran             = $input->tahun_ajaran;
                $semester->nm_semester              = $input->nm_semester;
                $semester->thn_akademik_semester    = $input->thn_akademik_semester;
                $semester->kode_semester            = $input->kode_semester;
                $semester->is_aktif_semester        = $input->is_aktif_semester;
                $semester->id_sekolah               = auth_data()->pengguna->id_sekolah;
                $semester->created_by               = auth_data()->pengguna->id_pengguna;
                $semester->save();

                if ($input->is_aktif_semester == 1) {
                    $data_semester  = Semester::where('id_semester', "<>", $id)->get();

                    foreach ($data_semester as $semester) {
                        $semester->is_aktif_semester    = 0;
                        $semester->updated_by           = auth_data()->pengguna->id_pengguna;
                        $semester->updated_at           = $now;
                        $semester->save();
                    }
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/nama-semester',
                    'message' => 'Save Nama Semester Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $semester                           = Semester::find($id);
                // $semester->tahun_ajaran             = $input->tahun_ajaran;
                // $semester->nm_semester              = $input->nm_semester;
                // $semester->thn_akademik_semester    = $input->thn_akademik_semester;
                // $semester->kode_semester            = $input->kode_semester;
                $semester->is_aktif_semester        = $input->is_aktif_semester;
                $semester->updated_by               = auth_data()->pengguna->id_pengguna;
                $semester->updated_at               = $now;
                $semester->save();

                if ($input->is_aktif_semester == 1) {
                    $data_semester  = Semester::where('id_semester', "<>", $id)->get();

                    foreach ($data_semester as $semester) {
                        $semester->is_aktif_semester    = 0;
                        $semester->updated_by           = auth_data()->pengguna->id_pengguna;
                        $semester->updated_at           = $now;
                        $semester->save();
                    }
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/nama-semester',
                    'message' => 'Update Nama Semester Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $semester               = Semester::find($id);

                if ($semester->is_aktif_semester == 1) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Active Semester'
                    ];
                } else {
                    $semester->deleted_by   = auth_data()->pengguna->id_pengguna;
                    $semester->save();

                    $semester->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Nama Semester Successfully'
                    ];
                }
            }
        }
    }

    //Generate Tahun Ajaran Baru
    public function actionGenerateTahunAjaranBaru(Request $request)
    {
        $input = (object) $request->input();
        $now = Carbon::now();

        $commonData = [
            'tahun_ajaran' => $now->year . '/' . ($now->year + 1),
            'id_sekolah' => $input->auth_data->pengguna->id_sekolah,
            'created_by' => $input->auth_data->pengguna->id_pengguna,
            'is_aktif_semester' => 0
        ];

        Semester::create(array_merge($commonData, [
            'id_semester' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
            'nm_semester' => 'Ganjil',
            'thn_akademik_semester' => $now->year,
            'kode_semester' => $now->year . '1'
        ]));

        Semester::create(array_merge($commonData, [
            'id_semester' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
            'nm_semester' => 'Genap',
            'thn_akademik_semester' => $now->year + 1,
            'kode_semester' => $now->year . '2'
        ]));

        return [
            'status' => 202,
            'message' => 'Generate Tahun Ajaran Baru Successfully'
        ];
    }
}
