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

        return view('akademik/rapor-semester/jenis-rapor/view-jenis-rapor', compact('auth_data'));
    }

    public  function datatablesJenisRaporSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Kelas::where('is_aktif', '1')->orderBy('tingkat')->with('jenis_rapor.komponen_jenis_rapor')->get();

        return Datatables::of($list_data)
            ->addColumn(
                'komponen_jenis_rapor',
                function ($item) {
                    $komponen = [];
                    foreach ($item->jenis_rapor->komponen_jenis_rapor as $key => $komponen_jenis_rapor) {
                        if ($komponen_jenis_rapor) {
                            $komponen[$key] = $komponen_jenis_rapor->nm_komponen_jenis_rapor;
                        } else {
                            $komponen[$key] = '';
                        }
                    }

                    return $komponen;
                }
            )->addColumn(
                'jenis_keterangan',
                function ($item) {
                    return $item->jenis_keterangan();
                }
            )

            ->make(true);
    }
}
