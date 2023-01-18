<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pendidikan\LibDataAkademik;
use Auth;
use DB;
use Session;
use Validator;

class RaporSisipanAkhirController extends Controller
{
    public function viewSemesterNilaiSAS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataSemester($auth_data);
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        return view('akademik/rapor-sisipan/daftar-nilai-sas/view-semester-nilai-sas', compact('auth_data', 'data_semester', 'semester_aktif'));
    }

    public function actionSemesterNilaiSAS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'thn_akademik_semester' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'rapor-sisipan/daftar-nilai-sas/' . $input->thn_akademik_semester
            ];
        }
    }

    public function viewDaftarNilaiSAS(Request $request, $thn_akademik_semester)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/rapor-sisipan/daftar-nilai-sas/view-daftar-nilai-sas', compact('auth_data', 'thn_akademik_semester'));
    }
}
