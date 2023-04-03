<?php

namespace App\Http\Controllers\Guru\KetidaksesuaianSOP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\KetidaksesuaianSOP;
use Illuminate\Support\Facades\Storage;


class KetidaksesuaianSOPController extends Controller
{
    public function viewKetidaksesuaianSOP(Request $request)
    {  # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/ketidaksesuaian-sop/ketidaksesuaian-sop-pribadi/view-ketidaksesuaian-sop', compact('auth_data'));
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

    public function viewLaporanKetidaksesuaianSOP(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/ketidaksesuaian-sop/laporan-ketidaksesuaian-sop/view-laporan-ketidaksesuaian-sop', compact('auth_data'));
    }

    public function datataablesLaporanKetidaksesuaianSOP(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = KetidaksesuaianSOP::with('pengguna', 'pengguna_input')->get();

        return Datatables::of($list_data)
            ->addColumn('tgl_pelanggaran', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_pelanggaran));
            })
            ->addColumn('action', function ($item) {
                if ($item->path_file) {
                    $file =  Storage::disk('spaces')->url($item->path_file);
                    $ext = pathinfo($item->path_file, PATHINFO_EXTENSION);

                    $note = 'image';
                } else {
                    $file = null;
                    $note = null;
                }

                $data = array(
                    'id'        => $item->id_ketidaksesuaian_sop,
                    'file'      => $file,
                    'note'      => $item->mapel,
                );
                return $data;
            })
            ->make(true);
    }
}
