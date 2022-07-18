<?php

namespace App\Http\Controllers\Api\v2;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use App\Models\Siswa;

class KeuanganController extends BaseController
{
    public function getTagihanBy(Request $request)
    {
        $pengguna = $request->auth_data->pengguna;

        if($pengguna->isWaliMurid){
            $wali_murid = $request->auth_data->actor;
    
            $siswa_wali_aktif = Siswa::penggunaSekolah($pengguna->id_sekolah)->isAktifWaliMurid($wali_murid->id_wali_murid)->first();
            
            $data_tagihan = $siswa_wali_aktif->all_tagihan();
    
            return api_response(200, null, ['tagihan' => $data_tagihan]);
        }else if($pengguna->isSiswa){
            $siswa = $request->auth_data->actor;
    
            $data_tagihan = $siswa->all_tagihan();
    
            return api_response(200, null, ['tagihan' => $data_tagihan]);
        }else{
            return error_response('Not found', 300);
        }
    }
}
