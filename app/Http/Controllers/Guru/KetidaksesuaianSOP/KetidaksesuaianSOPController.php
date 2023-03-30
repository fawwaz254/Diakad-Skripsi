<?php

namespace App\Http\Controllers\Guru\KetidaksesuaianSOP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\KetidaksesuaianSOP;

class KetidaksesuaianSOPController extends Controller
{
    public function viewKetidaksesuaianSOP(Request $request)
    {  # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/ketidaksesuaian-sop-pribadi/view-ketidaksesuaian-sop', compact('auth_data'));
    }

    public function datatablesKetidaksesuaianSOP(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = KetidaksesuaianSOP::where('id_pengguna', $auth_data->pengguna->id_pengguna);

        return Datatables::of($list_data)
            ->addColumn('tgl_pelanggaran', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_pelanggaran));
            })
            ->make(true);
    }
}
