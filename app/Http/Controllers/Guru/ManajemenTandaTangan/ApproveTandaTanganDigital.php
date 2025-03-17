<?php

namespace App\Http\Controllers\Guru\ManajemenTandaTangan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DokumenTandaTanganDigital;
use Yajra\Datatables\Datatables;
use Validator;
use Carbon\Carbon;

class ApproveTandaTanganDigital extends Controller
{
    public function viewApproveTandaTanganDigital(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/manajemen-tanda-tangan/approve-tanda-tangan-digital/view-approve-tanda-tangan-digital', compact('auth_data'));
    }

    public function datatablesApproveTandaTanganDigital(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = DokumenTandaTanganDigital::orderBy('is_approve')->get();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'is_approve' => $item->is_approve,
                    'id' => $item->id_tanda_tangan_digital
                );
                return $data;
            })
            ->make(true);
    }

    public function previewDocument(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $dokumen_tanda_tangan_digital = DokumenTandaTanganDigital::findOrFail($id);

        return view('guru/manajemen-tanda-tangan/approve-tanda-tangan-digital/preview-dokumen-tanda-tangan-digital', compact('auth_data', 'dokumen_tanda_tangan_digital'));
    }

    public function actionApproveTandaTanganDigital(Request $request, $id)
    {
        $now = Carbon::now();
        $input = (object) $request->input();

        $dokumen_tanda_tangan_digital = DokumenTandaTanganDigital::find($id);
        $dokumen_tanda_tangan_digital->approve_by = $input->auth_data->pengguna->id_pengguna;
        $dokumen_tanda_tangan_digital->approve_at = $now;
        $dokumen_tanda_tangan_digital->is_approve = 1;
        $dokumen_tanda_tangan_digital->save();

        return [
            'status' => 203, // SUCCESS AND LOAD TABLE
            'message' => 'Approve Dokumen Tanda Tangan Succesfully'
        ];
    }
}
