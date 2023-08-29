<?php

namespace App\Http\Controllers\Guru\Presensi;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PresensiQrCodeController extends BaseController
{
    public function viewPresensiQr(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/presensi/absensi-qr/view-absensi-qr-code', compact('auth_data'));
    }
    public function resultPresensiQr(Request $request)
    {
        $nis = $request->qr_code;
        dd($nis);
    }
}
