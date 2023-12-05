<?php

namespace App\Http\Controllers\Akademik\RaporSemester;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class JenisRaporSemesterController extends Controller
{
    public function viewJenisRaporSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/rapor-sisipan/komponen-nilai/view-komponen-nilai', compact('auth_data'));
    }

    public  function datatablesviewJenisRaporSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Kelas::with('jenis_rapor.kompomen_jenis_rapor');

        return Datatables::of($list_data)
            ->editColumn('status', function ($item) {
                if ($item->status == '1') {
                    return 'Aktif';
                } else {
                    return 'Tidak Aktif';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id'     => $item->id_komponen_nilai
                );
                return $data;
            })
            ->make(true);
    }
}
