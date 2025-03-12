<?php

namespace App\Http\Controllers\Akademik\RaporAgama;

use App\Http\Controllers\Controller;
use App\Models\KomponenJenisRapor;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class KomponenRaporAgamaController extends Controller
{
    public function viewKomponenRapor(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('akademik/rapor-agama/komponen-rapor/view-komponen-rapor', compact('auth_data'));
    }

    public  function datatablesKomponenRapor(Request $request)
    {

        $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor',  'agama');
        })->get();

        return Datatables::of($list_data)


            ->make(true);
    }
}
