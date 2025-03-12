<?php

namespace App\Http\Controllers\Akademik\KPI;

use App\Models\Kelas;
use App\Models\PointKPI;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Semester;

class KelompokKPIController extends Controller
{
    public function viewKelompokKPI(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $tingkat_kelas = Kelas::groupBy('tingkat')->pluck('tingkat');
        return view('akademik/kpi/kelompok-kpi/view-kelompok-kpi', compact('auth_data', 'data_semester', 'tingkat_kelas'));
    }

    public function postKelompokKPI(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'id_semester' => 'required',
            'tingkat' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => 'Harap untuk memilih terlebih dahulu'
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'kpi/komponen-kpi/detail/' . $input->tingkat . '/' . $input->id_semester,
            ];
        }
    }

    public function detailKelompokKPI(Request $request, $tingkat, $id_semester)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $tingkat_kelas = Kelas::groupBy('tingkat')->pluck('tingkat');
        return view('akademik/kpi/kelompok-kpi/detail-kelompok-kpi', compact('auth_data', 'tingkat', 'tingkat_kelas', 'data_semester', 'id_semester'));
    }

    public function datatablesKelompokKPI(Request $request)
    {
        $input = (object) $request->input();

        $point_kpi = PointKPI::where('id_semester', $input->id_semester)
            ->where(function ($q) use ($input) {
                $q->where('tingkat_kelas', $input->tingkat)->orWhereNull('tingkat_kelas');
            })->with('kelompok_kpi');

        return Datatables::of($point_kpi)
            ->editColumn('jenis', function ($item) {
                return $item->jenis == '0' ? 'Header' : 'Point';
            })->editColumn('deskripsi', function ($item) {
                if (!empty($item->deskripsi)) {
                    $deskripsiArray = $item->deskripsi;
                    return 'A = ' . $deskripsiArray['A'];
                } else {
                    return '-';
                }
            })->make(true);
    }

    public function copyKomponenKPI(Request $request, $tingkat, $id_semester)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $tingkat_kelas = Kelas::groupBy('tingkat')->pluck('tingkat');
        $point_kpi = PointKPI::select('id_point_kpi', 'nm_point_kpi', 'id_semester')
            ->where('id_semester', $id_semester)
            ->where('tingkat_kelas', $tingkat)
            ->orWhereNull('tingkat_kelas')
            ->withCount('semester')->get();
        // $point_kpi = PointKPI::select('id_point_kpi', 'nm_point_kpi');
        return view('akademik/kpi/kelompok-kpi/copy-kelompok-kpi', compact('auth_data', 'tingkat', 'tingkat_kelas', 'data_semester', 'id_semester', 'point_kpi'));
    }

    // public function copyKomponenKPI(Request $request, $id_semester, $tingkat)
    // {
    //     $input = (object) $request->input();
    //     $auth_data = auth_data();
    //     // $kelas = Kelas::where('is_aktif', '1')->get();
    //     // $kelas_sisipan = KelasSisipan::get();
    //     $point_kpi = PointKPI::where('id_semester', $input->id_semester)
    //         ->where(function ($q) use ($input) {
    //             $q->where('tingkat_kelas', $input->tingkat)->orWhereNull('tingkat_kelas');
    //         })->with('kelompok_kpi');
    //     dd($input);

    //     return view('akademik/kpi/kelompok-kpi/copy-kelompok-kpi', compact('auth_data', 'point_kpi', 'id_semester', 'tingkat'));
    // }

    public function actionKomponenKPI(Request $request, $mode, $tingkat = null, $id_semester = null)
    {
        $input = (object) $request->input();
        $now = Carbon::now();
        $list_validator = [
            'tingkat',
            'id_semester',
        ];

        $validator = Validator::make($request->all(), $list_validator);

        if ($validator->fails() && $mode != 'copy') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'copy') {
                $kpi = PointKPI::where('tingkat_kelas', $input->select_id_kelas_tingkat)->where('id_semester', $input->select_id_semester)->with('kelompok_kpi')->get();
                $semester_aktif = Semester::where('is_aktif_semester', true)->first();
                if (count($kpi) == 0) {
                    return [
                        'status' => 404, // KPI NOT FOUND
                        'path' => 'kpi/komponen-kpi/copy/' . $tingkat . '/' . $id_semester,
                        'message' => 'KPI kosong'
                    ];
                } else {
                    foreach ($kpi as $k) {
                        PointKPI::create([
                            'id_point_kpi'      => auth_data()->sekolah_data->prefix . strtotime($now) . uniqid(),
                            'id_kelompok_kpi'   => $k->kelompok_kpi->id_kelompok_kpi,
                            'id_semester'       => $semester_aktif->id_semester,
                            'nm_point_kpi'      => $k->nm_point_kpi,
                            'tingkat_kelas'     => $tingkat,
                            'urutan'            => $k->urutan,
                            'deskripsi'         => $k->deskripsi,
                            'jenis'             => $k->jenis,
                            'created_by'        =>  auth_data()->pengguna->id_pengguna,
                        ]);
                    }

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'kpi/komponen-kpi/detail/' . $tingkat . '/' . $id_semester,
                        'message' => 'Copy Data Componen KPI Succesfully'
                    ];
                }
            }
        }
    }
}
