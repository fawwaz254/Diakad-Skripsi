<?php

namespace App\Http\Controllers\Akademik\KPI;

use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Kelas;
use App\Models\PointKPI;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Validator;


class KelompokKPIController extends Controller
{
    public function viewKelompokKPI(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $tingkat_kelas = Kelas::groupBy('tingkat')->pluck('tingkat');
        return view('akademik/kpi/kelompok-kpi/view-kelompok-kpi', compact('auth_data', 'data_semester', 'tingkat_kelas'));
    }

    public function postKelompokKPI(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

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
        $auth_data = $input->auth_data;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $tingkat_kelas = Kelas::groupBy('tingkat')->pluck('tingkat');
        return view('akademik/kpi/kelompok-kpi/detail-kelompok-kpi', compact('auth_data', 'tingkat', 'tingkat_kelas', 'data_semester', 'id_semester'));
    }

    public function datatablesKelompokKPI(Request $request)
    {
        $input = (object) $request->input();

        $point_kpi = PointKPI::where('id_semester', $input->id_semester)->where('tingkat_kelas', $input->tingkat)->orWhereNull('tingkat_kelas')->with('kelompok_kpi');
        return Datatables::of($point_kpi)->editColumn('jenis', function ($item) {
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
}
